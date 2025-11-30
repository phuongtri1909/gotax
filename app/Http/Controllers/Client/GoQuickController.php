<?php
namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use ZipArchive;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Validation\ValidationException;
use App\Models\GoQuickUse;
use Illuminate\Support\Facades\Auth;

class GoQuickController extends Controller
{
    private function getApiUrl()
    {
        return config('services.go_quick.url', env('GO_QUICK_API_URL', 'http://127.0.0.1:5000/api/go-quick'));
    }

    private function checkUsage($requiredCount = 1)
    {
        $user = Auth::user();
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Bạn cần đăng nhập để sử dụng tính năng này.'
            ];
        }

        $use = GoQuickUse::where('user_id', $user->id)->first();
        
        if (!$use) {
            return [
                'success' => false,
                'message' => 'Bạn chưa có gói sử dụng. Vui lòng mua gói để tiếp tục.'
            ];
        }

        if ($use->cccd_limit < $requiredCount) {
            return [
                'success' => false,
                'message' => 'Bạn đã hết lượt sử dụng. Bạn còn ' . $use->cccd_limit . ' lượt nhưng cần ' . $requiredCount . ' lượt. Vui lòng mua thêm gói để tiếp tục.'
            ];
        }

        return [
            'success' => true,
            'use' => $use
        ];
    }

    private function updateUsage($customerCount)
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }

        $use = GoQuickUse::where('user_id', $user->id)->first();
        if (!$use) {
            return;
        }

        $use->cccd_limit = max(0, $use->cccd_limit - $customerCount);
        $use->total_used += 1;
        $use->total_cccd_extracted += $customerCount;
        $use->save();
    }

    private function getCustomerCount($apiResponse)
    {
        if (!is_array($apiResponse)) {
            return 0;
        }

        if (isset($apiResponse['customer']) && is_array($apiResponse['customer'])) {
            return count($apiResponse['customer']);
        }

        return 0;
    }

    public function healthCheck()
    {
        try {
            $response = Http::timeout(5)->get($this->getApiUrl() . '/health');
            
            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Go Quick API is running',
                    'data' => $response->json()
                ]);
            }
            
            return response()->json([
                'status' => 'error',
                'message' => 'API không phản hồi'
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không thể kết nối API: ' . $e->getMessage()
            ], 500);
        }
    }

    public function processCCCD(Request $request)
    {
        try {
            $usageCheck = $this->checkUsage(1);
            if (!$usageCheck['success']) {
                return response()->json([
                    'status' => 'error',
                    'message' => $usageCheck['message']
                ], 403);
            }

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                
                $request->validate([
                    'file' => 'required|file|mimes:zip|max:102400'
                ], [
                    'file.required' => 'Vui lòng chọn file ZIP',
                    'file.file' => 'File không hợp lệ',
                    'file.mimes' => 'Chỉ chấp nhận file ZIP. File của bạn có định dạng: ' . ($file->getClientOriginalExtension() ?? 'unknown'),
                    'file.max' => 'File ZIP không được vượt quá 100MB',
                ]);
                
                // Gửi file trực tiếp qua multipart/form-data (theo README)
                $response = Http::timeout(600)
                    ->attach('file', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
                    ->post($this->getApiUrl() . '/process-cccd');
            }
            elseif ($request->has('inp_path')) {
                $request->validate([
                    'inp_path' => 'required|string'
                ]);
                
                $response = Http::timeout(600)
                    ->post($this->getApiUrl() . '/process-cccd', [
                        'inp_path' => $request->inp_path
                    ]);
            }
            else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Vui lòng cung cấp file hoặc base64 string'
                ], 400);
            }
            
            if ($response->successful()) {
                $apiResult = $response->json();
                $customerCount = $this->getCustomerCount($apiResult);
                
                if ($customerCount > 0) {
                    $usageCheck = $this->checkUsage($customerCount);
                    if (!$usageCheck['success']) {
                        return response()->json([
                            'status' => 'error',
                            'message' => $usageCheck['message']
                        ], 403);
                    }
                    
                    $this->updateUsage($customerCount);
                }
                
                return response()->json([
                    'status' => 'success',
                    'data' => $apiResult
                ]);
            }
            
            // Log chi tiết lỗi từ API
            $statusCode = $response->status();
            $errorBody = $response->body();
            $errorJson = null;
            
            try {
                $errorJson = $response->json();
            } catch (\Exception $e) {
                Log::warning('Go Quick Process CCCD: API response không phải JSON. Status: ' . $statusCode . ', Body: ' . substr($errorBody, 0, 500));
            }
            
            Log::error('Go Quick Process CCCD API Error', [
                'status' => $statusCode,
                'body' => substr($errorBody, 0, 1000),
                'json' => $errorJson
            ]);
            
            $errorMessage = 'API trả về lỗi';
            if ($errorJson && isset($errorJson['message'])) {
                $apiMessage = $errorJson['message'];
                if (strpos($apiMessage, 'No such file or directory') !== false) {
                    $errorMessage = 'API gặp lỗi khi xử lý file. Có thể do xử lý nhiều request cùng lúc. Vui lòng thử lại sau vài giây.';
                } else {
                    $errorMessage = $apiMessage;
                }
            } elseif ($statusCode === 500) {
                $errorMessage = 'API server đang bận hoặc gặp lỗi. Vui lòng thử lại sau vài giây.';
            } elseif ($statusCode === 503) {
                $errorMessage = 'API server đang quá tải. Vui lòng thử lại sau vài giây.';
            }
            
            return response()->json([
                'status' => 'error',
                'message' => $errorMessage,
                'error' => $errorJson ?: ['raw_body' => substr($errorBody, 0, 500)]
            ], $statusCode >= 400 && $statusCode < 600 ? $statusCode : 500);
            
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Go Quick Process CCCD Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi xử lý: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xử lý upload 2 ảnh (mặt trước và mặt sau)
     * Đổi tên thành 1mt, 1ms và nén thành ZIP rồi gửi qua API process-cccd
     */
    public function processCCCDImages(Request $request)
    {
        try {
            $usageCheck = $this->checkUsage(1);
            if (!$usageCheck['success']) {
                return response()->json([
                    'status' => 'error',
                    'message' => $usageCheck['message']
                ], 403);
            }

            $request->validate([
                'mt' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
                'ms' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            ], [
                'mt.required' => 'Vui lòng chọn ảnh mặt trước CCCD',
                'mt.image' => 'File mặt trước phải là ảnh (JPG, PNG, GIF, WEBP)',
                'mt.mimes' => 'File mặt trước chỉ chấp nhận định dạng: JPG, PNG, GIF, WEBP',
                'mt.max' => 'File mặt trước không được vượt quá 5MB',
                'ms.required' => 'Vui lòng chọn ảnh mặt sau CCCD',
                'ms.image' => 'File mặt sau phải là ảnh (JPG, PNG, GIF, WEBP)',
                'ms.mimes' => 'File mặt sau chỉ chấp nhận định dạng: JPG, PNG, GIF, WEBP',
                'ms.max' => 'File mặt sau không được vượt quá 5MB',
            ]);

            $frontImage = $request->file('mt');
            $backImage = $request->file('ms');

            // Tạo thư mục temp để lưu file
            $tempDir = storage_path('app/temp/go_quick_' . uniqid());
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            try {
                // Đổi tên và lưu file
                $frontPath = $tempDir . '/1mt.' . $frontImage->getClientOriginalExtension();
                $backPath = $tempDir . '/1ms.' . $backImage->getClientOriginalExtension();

                copy($frontImage->getRealPath(), $frontPath);
                copy($backImage->getRealPath(), $backPath);

                // Tạo file ZIP
                $zipPath = $tempDir . '/images.zip';
                $zip = new ZipArchive();
                
                if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
                    throw new \Exception('Không thể tạo file ZIP');
                }

                $zip->addFile($frontPath, '1mt.' . $frontImage->getClientOriginalExtension());
                $zip->addFile($backPath, '1ms.' . $backImage->getClientOriginalExtension());
                $zip->close();

                // Gửi file ZIP qua API process-cccd
                try {
                    $apiUrl = $this->getApiUrl() . '/process-cccd';
                    
                    $response = Http::timeout(600)
                        ->attach('file', file_get_contents($zipPath), 'images.zip')
                        ->post($apiUrl);

                    // Xóa thư mục temp
                    $this->deleteDirectory($tempDir);

                    if ($response->successful()) {
                        $apiResult = $response->json();
                        $customerCount = $this->getCustomerCount($apiResult);
                        
                        if ($customerCount > 0) {
                            $usageCheck = $this->checkUsage($customerCount);
                            if (!$usageCheck['success']) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => $usageCheck['message']
                                ], 403);
                            }
                            
                            $this->updateUsage($customerCount);
                        }
                        
                        return response()->json([
                            'status' => 'success',
                            'data' => $apiResult
                        ]);
                    }

                    // Log chi tiết lỗi từ API
                    $statusCode = $response->status();
                    $errorBody = $response->body();
                    $errorJson = null;
                    
                    try {
                        $errorJson = $response->json();
                    } catch (\Exception $e) {
                        Log::warning('Go Quick CCCD Images: API response không phải JSON. Status: ' . $statusCode . ', Body: ' . substr($errorBody, 0, 500));
                    }
                    
                    Log::error('Go Quick CCCD Images API Error', [
                        'status' => $statusCode,
                        'body' => substr($errorBody, 0, 1000),
                        'json' => $errorJson
                    ]);
                    
                    $errorMessage = 'API trả về lỗi';
                    if ($errorJson && isset($errorJson['message'])) {
                        $apiMessage = $errorJson['message'];
                        // Kiểm tra nếu là lỗi file không tồn tại
                        if (strpos($apiMessage, 'No such file or directory') !== false) {
                            $errorMessage = 'API gặp lỗi khi xử lý file. Có thể do xử lý nhiều request cùng lúc. Vui lòng thử lại sau vài giây.';
                        } else {
                            $errorMessage = $apiMessage;
                        }
                    } elseif ($statusCode === 500) {
                        $errorMessage = 'API server đang bận hoặc gặp lỗi. Vui lòng thử lại sau vài giây.';
                    } elseif ($statusCode === 503) {
                        $errorMessage = 'API server đang quá tải. Vui lòng thử lại sau vài giây.';
                    }

                    return response()->json([
                        'status' => 'error',
                        'message' => $errorMessage,
                        'error' => $errorJson ?: ['raw_body' => substr($errorBody, 0, 500)]
                    ], $statusCode >= 400 && $statusCode < 600 ? $statusCode : 500);
                } catch (\Illuminate\Http\Client\ConnectionException $e) {
                    // Xóa thư mục temp
                    $this->deleteDirectory($tempDir);
                    Log::error('API Connection Error: ' . $e->getMessage());
                    throw new \Exception('Không thể kết nối đến API server. Vui lòng kiểm tra API server có đang chạy không.');
                } catch (\Exception $e) {
                    // Xóa thư mục temp
                    $this->deleteDirectory($tempDir);
                    throw $e;
                }

            } catch (\Exception $e) {
                // Xóa thư mục temp nếu có lỗi
                if (file_exists($tempDir)) {
                    $this->deleteDirectory($tempDir);
                }
                throw $e;
            }
            
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Go Quick Process CCCD Images Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi xử lý: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xử lý upload nhiều ảnh (folder)
     * Nén các ảnh thành ZIP và gửi qua API process-cccd
     * Yêu cầu: Tên file phải đúng format (1mt, 1ms, 2mt, 2ms...)
     */
    public function processCCCDMultipleImages(Request $request)
    {
        try {
            $usageCheck = $this->checkUsage(1);
            if (!$usageCheck['success']) {
                return response()->json([
                    'status' => 'error',
                    'message' => $usageCheck['message']
                ], 403);
            }

            $request->validate([
                'images' => 'required|array',
                'images.*' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            ], [
                'images.required' => 'Vui lòng chọn ít nhất một ảnh',
                'images.array' => 'Dữ liệu ảnh không hợp lệ',
                'images.*.required' => 'Vui lòng chọn ảnh',
                'images.*.image' => 'Tất cả file phải là ảnh (JPG, PNG, GIF, WEBP)',
                'images.*.mimes' => 'Chỉ chấp nhận file ảnh định dạng: JPG, PNG, GIF, WEBP',
                'images.*.max' => 'Mỗi file ảnh không được vượt quá 5MB',
            ]);

            $images = $request->file('images');

            // Tạo thư mục temp để lưu file
            $tempDir = storage_path('app/temp/go_quick_' . uniqid());
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            try {
                // Kiểm tra và đổi tên file nếu cần
                $imagePairs = [];
                foreach ($images as $image) {
                    $originalName = $image->getClientOriginalName();
                    $extension = $image->getClientOriginalExtension();
                    
                    // Kiểm tra tên file có đúng format không (1mt, 1ms, 2mt, 2ms...)
                    if (preg_match('/^(\d+)(mt|ms)\./i', $originalName, $matches)) {
                        // Tên file đã đúng format
                        $newName = $matches[1] . $matches[2] . '.' . $extension;
                    } else {
                        // Tự động đặt tên theo thứ tự (không khuyến nghị)
                        // Tạm thời báo lỗi yêu cầu đổi tên
                        throw new \Exception('Tên file không đúng format. Vui lòng đổi tên file theo format: 1mt, 1ms, 2mt, 2ms...');
                    }

                    $newPath = $tempDir . '/' . $newName;
                    copy($image->getRealPath(), $newPath);
                    $imagePairs[] = ['path' => $newPath, 'name' => $newName];
                }

                // Tạo file ZIP
                $zipPath = $tempDir . '/images.zip';
                $zip = new ZipArchive();
                
                if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
                    throw new \Exception('Không thể tạo file ZIP');
                }

                foreach ($imagePairs as $pair) {
                    $zip->addFile($pair['path'], $pair['name']);
                }
                $zip->close();

                // Gửi file ZIP qua API process-cccd
                $response = Http::timeout(600)
                    ->attach('file', file_get_contents($zipPath), 'images.zip')
                    ->post($this->getApiUrl() . '/process-cccd');

                // Xóa thư mục temp
                $this->deleteDirectory($tempDir);

                if ($response->successful()) {
                    $apiResult = $response->json();
                    $customerCount = $this->getCustomerCount($apiResult);
                    
                    if ($customerCount > 0) {
                        $usageCheck = $this->checkUsage($customerCount);
                        if (!$usageCheck['success']) {
                            return response()->json([
                                'status' => 'error',
                                'message' => $usageCheck['message']
                            ], 403);
                        }
                        
                        $this->updateUsage($customerCount);
                    }
                    
                    return response()->json([
                        'status' => 'success',
                        'data' => $apiResult
                    ]);
                }

                // Log chi tiết lỗi từ API
                $statusCode = $response->status();
                $errorBody = $response->body();
                $errorJson = null;
                
                try {
                    $errorJson = $response->json();
                } catch (\Exception $e) {
                    Log::warning('Go Quick CCCD Multiple Images: API response không phải JSON. Status: ' . $statusCode . ', Body: ' . substr($errorBody, 0, 500));
                }
                
                Log::error('Go Quick CCCD Multiple Images API Error', [
                    'status' => $statusCode,
                    'body' => substr($errorBody, 0, 1000),
                    'json' => $errorJson
                ]);
                
                $errorMessage = 'API trả về lỗi';
                if ($errorJson && isset($errorJson['message'])) {
                    $errorMessage = $errorJson['message'];
                } elseif ($statusCode === 500) {
                    $errorMessage = 'API server đang bận hoặc gặp lỗi. Vui lòng thử lại sau vài giây.';
                } elseif ($statusCode === 503) {
                    $errorMessage = 'API server đang quá tải. Vui lòng thử lại sau vài giây.';
                }
                
                return response()->json([
                    'status' => 'error',
                    'message' => $errorMessage,
                    'error' => $errorJson ?: ['raw_body' => substr($errorBody, 0, 500)]
                ], $statusCode >= 400 && $statusCode < 600 ? $statusCode : 500);

            } catch (\Exception $e) {
                // Xóa thư mục temp nếu có lỗi
                if (file_exists($tempDir)) {
                    $this->deleteDirectory($tempDir);
                }
                throw $e;
            }
            
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Go Quick Process CCCD Multiple Images Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi xử lý: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa thư mục và tất cả file bên trong
     */
    private function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return;
        }

        try {
            $files = array_diff(scandir($dir), ['.', '..']);
            foreach ($files as $file) {
                $path = $dir . '/' . $file;
                try {
                    if (is_dir($path)) {
                        $this->deleteDirectory($path);
                    } else {
                        if (file_exists($path) && is_writable($path)) {
                            @unlink($path);
                        }
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
            
            if (is_dir($dir) && is_writable($dir)) {
                @rmdir($dir);
            }
        } catch (\Exception $e) {
        }
    }

    /**
     * Export Excel từ dữ liệu CCCD
     */
    public function exportExcel(Request $request)
    {
        try {
            $request->validate([
                'customers' => 'required|array',
                'customers.*.id_card' => 'nullable|string',
                'customers.*.name' => 'nullable|string',
                'customers.*.birth_date' => 'nullable|string',
                'customers.*.gender' => 'nullable|string',
                'customers.*.nationality' => 'nullable|string',
                'customers.*.hometown' => 'nullable|string',
                'customers.*.address' => 'nullable|string',
                'customers.*.created_date' => 'nullable|string',
                'customers.*.place_created' => 'nullable|string',
            ]);

            $customers = $request->input('customers');

            // Tạo Export class
            $export = new class($customers) implements FromArray, WithHeadings, WithColumnWidths, WithStyles {
                protected $customers;

                public function __construct($customers)
                {
                    $this->customers = $customers;
                }

                public function array(): array
                {
                    $data = [];
                    foreach ($this->customers as $customer) {
                        $data[] = [
                            $customer['id_card'] ?? '',
                            $customer['name'] ?? '',
                            $customer['birth_date'] ?? '',
                            $customer['gender'] ?? '',
                            $customer['nationality'] ?? 'Việt Nam',
                            $customer['hometown'] ?? '',
                            $customer['address'] ?? '',
                            $customer['created_date'] ?? '',
                            $customer['place_created'] ?? '',
                        ];
                    }
                    return $data;
                }

                public function headings(): array
                {
                    return [
                        'Số',
                        'Họ và tên',
                        'Ngày sinh',
                        'Giới tính',
                        'Quốc tịch',
                        'Quê quán',
                        'Nơi thường trú',
                        'Ngày cấp',
                        'Nơi cấp'
                    ];
                }

                public function columnWidths(): array
                {
                    return [
                        'A' => 15,  // Số
                        'B' => 25,  // Họ và tên
                        'C' => 12,  // Ngày sinh
                        'D' => 10,  // Giới tính
                        'E' => 12,  // Quốc tịch
                        'F' => 30,  // Quê quán
                        'G' => 40,  // Nơi thường trú
                        'H' => 12,  // Ngày cấp
                        'I' => 50,  // Nơi cấp
                    ];
                }

                public function styles(Worksheet $sheet)
                {
                    return [
                        1 => ['font' => ['bold' => true]], // Header row
                    ];
                }
            };

            $fileName = 'cccd_data_' . date('Y-m-d_His') . '.xlsx';

            return Excel::download($export, $fileName);

        } catch (\Exception $e) {
            Log::error('Go Quick Export Excel Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi xuất file Excel: ' . $e->getMessage()
            ], 500);
        }
    }

    public function processPDF(Request $request)
    {
        try {
            $usageCheck = $this->checkUsage(1);
            if (!$usageCheck['success']) {
                return response()->json([
                    'status' => 'error',
                    'message' => $usageCheck['message']
                ], 403);
            }

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                
                $request->validate([
                    'file' => 'required|file|mimes:pdf|max:102400' // PDF file, không phải zip
                ], [
                    'file.required' => 'Vui lòng chọn file PDF',
                    'file.file' => 'File không hợp lệ',
                    'file.mimes' => 'Chỉ chấp nhận file PDF. File của bạn có định dạng: ' . ($file->getClientOriginalExtension() ?? 'unknown'),
                    'file.max' => 'File PDF không được vượt quá 100MB',
                ]);
                
                // Gửi file trực tiếp qua multipart/form-data
                $response = Http::timeout(600)
                    ->attach('file', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
                    ->post($this->getApiUrl() . '/process-pdf');
            }
            elseif ($request->has('inp_path')) {
                $request->validate([
                    'inp_path' => 'required|string'
                ]);
                
                $response = Http::timeout(600)
                    ->post($this->getApiUrl() . '/process-pdf', [
                        'inp_path' => $request->inp_path
                    ]);
            }
            else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Vui lòng cung cấp file PDF hoặc base64 string'
                ], 400);
            }
            
            if ($response->successful()) {
                $result = $response->json();
                
                if (isset($result['zip_base64'])) {
                    $zipData = base64_decode($result['zip_base64']);
                    $zipName = $result['zip_name'] ?? 'pdf_images_' . uniqid() . '.zip';
                    
                    Storage::disk('public')->makeDirectory('go_quick');
                    Storage::disk('public')->put('go_quick/' . $zipName, $zipData);
                    
                    $result['zip_url'] = Storage::url('go_quick/' . $zipName);
                }
                
                $customerCount = $this->getCustomerCount($result);
                if ($customerCount > 0) {
                    $usageCheck = $this->checkUsage($customerCount);
                    if (!$usageCheck['success']) {
                        return response()->json([
                            'status' => 'error',
                            'message' => $usageCheck['message']
                        ], 403);
                    }
                    
                    $this->updateUsage($customerCount);
                }
                
                return response()->json([
                    'status' => 'success',
                    'data' => $result
                ]);
            }
            
            // Log chi tiết lỗi từ API
            $statusCode = $response->status();
            $errorBody = $response->body();
            $errorJson = null;
            
            try {
                $errorJson = $response->json();
            } catch (\Exception $e) {
                Log::warning('Go Quick PDF: API response không phải JSON. Status: ' . $statusCode . ', Body: ' . substr($errorBody, 0, 500));
            }
            
            Log::error('Go Quick PDF API Error', [
                'status' => $statusCode,
                'body' => substr($errorBody, 0, 1000),
                'json' => $errorJson
            ]);
            
            $errorMessage = 'Xử lý PDF thất bại';
            if ($errorJson && isset($errorJson['message'])) {
                $apiMessage = $errorJson['message'];
                // Kiểm tra nếu là lỗi file không tồn tại
                if (strpos($apiMessage, 'No such file or directory') !== false) {
                    $errorMessage = 'API gặp lỗi khi xử lý file. Có thể do xử lý nhiều request cùng lúc. Vui lòng thử lại sau vài giây.';
                } else {
                    $errorMessage = $apiMessage;
                }
            } elseif ($statusCode === 500) {
                $errorMessage = 'API server đang bận hoặc gặp lỗi. Vui lòng thử lại sau vài giây.';
            } elseif ($statusCode === 503) {
                $errorMessage = 'API server đang quá tải. Vui lòng thử lại sau vài giây.';
            }
            
            return response()->json([
                'status' => 'error',
                'message' => $errorMessage,
                'error' => $errorJson ?: ['raw_body' => substr($errorBody, 0, 500)]
            ], $statusCode >= 400 && $statusCode < 600 ? $statusCode : 500);
            
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Go Quick PDF Connection Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Không thể kết nối đến API server. Vui lòng kiểm tra API server có đang chạy không.'
            ], 503);
        } catch (\Exception $e) {
            Log::error('Go Quick PDF Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi xử lý: ' . $e->getMessage()
            ], 500);
        }
    }

    public function processExcel(Request $request)
    {
        try {
            $usageCheck = $this->checkUsage(1);
            if (!$usageCheck['success']) {
                return response()->json([
                    'status' => 'error',
                    'message' => $usageCheck['message']
                ], 403);
            }

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                
                $request->validate([
                    'file' => 'required|file|mimes:xlsx,xls|max:10240'
                ], [
                    'file.required' => 'Vui lòng chọn file Excel',
                    'file.file' => 'File không hợp lệ',
                    'file.mimes' => 'Chỉ chấp nhận file Excel (XLSX, XLS). File của bạn có định dạng: ' . ($file->getClientOriginalExtension() ?? 'unknown'),
                    'file.max' => 'File Excel không được vượt quá 10MB',
                ]);
                
                // Gửi file trực tiếp qua multipart/form-data
                $response = Http::timeout(600)
                    ->attach('file', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
                    ->post($this->getApiUrl() . '/process-excel');
            }
            elseif ($request->has('inp_path')) {
                $request->validate([
                    'inp_path' => 'required|string'
                ]);
                
                $response = Http::timeout(600)
                    ->post($this->getApiUrl() . '/process-excel', [
                        'inp_path' => $request->inp_path
                    ]);
            }
            else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Vui lòng cung cấp file Excel hoặc base64 string'
                ], 400);
            }
            
            if ($response->successful()) {
                $result = $response->json();
                
                if (isset($result['zip_base64'])) {
                    $zipData = base64_decode($result['zip_base64']);
                    $zipName = $result['zip_name'] ?? 'excel_images_' . uniqid() . '.zip';
                    
                    Storage::disk('public')->makeDirectory('go_quick');
                    Storage::disk('public')->put('go_quick/' . $zipName, $zipData);
                    
                    $result['zip_url'] = Storage::url('go_quick/' . $zipName);
                }
                
                $customerCount = $this->getCustomerCount($result);
                if ($customerCount > 0) {
                    $usageCheck = $this->checkUsage($customerCount);
                    if (!$usageCheck['success']) {
                        return response()->json([
                            'status' => 'error',
                            'message' => $usageCheck['message']
                        ], 403);
                    }
                    
                    $this->updateUsage($customerCount);
                }
                
                return response()->json([
                    'status' => 'success',
                    'data' => $result
                ]);
            }
            
            // Log chi tiết lỗi từ API
            $statusCode = $response->status();
            $errorBody = $response->body();
            $errorJson = null;
            
            try {
                $errorJson = $response->json();
            } catch (\Exception $e) {
                Log::warning('Go Quick Excel: API response không phải JSON. Status: ' . $statusCode . ', Body: ' . substr($errorBody, 0, 500));
            }
            
            Log::error('Go Quick Excel API Error', [
                'status' => $statusCode,
                'body' => substr($errorBody, 0, 1000),
                'json' => $errorJson
            ]);
            
            $errorMessage = 'Xử lý Excel thất bại';
            if ($errorJson && isset($errorJson['message'])) {
                $apiMessage = $errorJson['message'];
                // Kiểm tra nếu là lỗi file không tồn tại
                if (strpos($apiMessage, 'No such file or directory') !== false) {
                    $errorMessage = 'API gặp lỗi khi xử lý file. Có thể do xử lý nhiều request cùng lúc. Vui lòng thử lại sau vài giây.';
                } else {
                    $errorMessage = $apiMessage;
                }
            } elseif ($statusCode === 500) {
                $errorMessage = 'API server đang bận hoặc gặp lỗi. Vui lòng thử lại sau vài giây.';
            } elseif ($statusCode === 503) {
                $errorMessage = 'API server đang quá tải. Vui lòng thử lại sau vài giây.';
            }
            
            return response()->json([
                'status' => 'error',
                'message' => $errorMessage,
                'error' => $errorJson ?: ['raw_body' => substr($errorBody, 0, 500)]
            ], $statusCode >= 400 && $statusCode < 600 ? $statusCode : 500);
            
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Go Quick Excel Connection Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Không thể kết nối đến API server. Vui lòng kiểm tra API server có đang chạy không.'
            ], 503);
        } catch (\Exception $e) {
            Log::error('Go Quick Excel Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi xử lý: ' . $e->getMessage()
            ], 500);
        }
    }

    public function process(Request $request)
    {
        try {
            $func_type = $request->input('func_type');
            
            if (!$func_type) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Missing func_type'
                ], 400);
            }
            
            $inp_data = null;
            
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileBytes = file_get_contents($file->getRealPath());
                $base64 = base64_encode($fileBytes);
                $inp_data = $base64;
            }
            elseif ($request->has('inp_path')) {
                $inp_data = $request->input('inp_path');
            }
            else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Vui lòng cung cấp file hoặc inp_path'
                ], 400);
            }
            
            $response = Http::timeout(600)
                ->post($this->getApiUrl() . '/process', [
                    'func_type' => $func_type,
                    'inp_path' => $inp_data
                ]);
            
            if ($response->successful()) {
                $result = $response->json();
                
                if (isset($result['zip_base64'])) {
                    $zipData = base64_decode($result['zip_base64']);
                    $zipName = $result['zip_name'] ?? 'result_' . uniqid() . '.zip';
                    
                    Storage::disk('public')->makeDirectory('go_quick');
                    Storage::disk('public')->put('go_quick/' . $zipName, $zipData);
                    
                    $result['zip_url'] = Storage::url('go_quick/' . $zipName);
                }
                
                return response()->json([
                    'status' => 'success',
                    'data' => $result
                ]);
            }
            
            return response()->json([
                'status' => 'error',
                'message' => 'Xử lý thất bại',
                'error' => $response->json()
            ], $response->status());
            
        } catch (\Exception $e) {
            Log::error('Go Quick Process Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }
}

