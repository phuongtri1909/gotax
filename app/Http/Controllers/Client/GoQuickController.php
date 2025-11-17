<?php
namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class GoQuickController extends Controller
{
    private function getApiUrl()
    {
        return config('services.go_quick.url', env('GO_QUICK_API_URL', 'http://127.0.0.1:5000/api/go-quick'));
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
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                
                $request->validate([
                    'file' => 'required|file|mimes:zip|max:102400'
                ]);
                
                $fileBytes = file_get_contents($file->getRealPath());
                $base64 = base64_encode($fileBytes);
                
                $response = Http::timeout(600)
                    ->post($this->getApiUrl() . '/process-cccd', [
                        'inp_path' => $base64
                    ]);
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
                return response()->json([
                    'status' => 'success',
                    'data' => $response->json()
                ]);
            }
            
            return response()->json([
                'status' => 'error',
                'message' => 'API trả về lỗi',
                'error' => $response->json()
            ], $response->status());
            
        } catch (\Exception $e) {
            Log::error('Go Quick Process CCCD Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi xử lý: ' . $e->getMessage()
            ], 500);
        }
    }

    public function processPDF(Request $request)
    {
        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                
                $request->validate([
                    'file' => 'required|file|mimes:pdf|max:102400' // PDF file, không phải zip
                ]);
                
                $fileBytes = file_get_contents($file->getRealPath());
                $base64 = base64_encode($fileBytes);
                
                $response = Http::timeout(600)
                    ->post($this->getApiUrl() . '/process-pdf', [
                        'inp_path' => $base64
                    ]);
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
                
                return response()->json([
                    'status' => 'success',
                    'data' => $result
                ]);
            }
            
            return response()->json([
                'status' => 'error',
                'message' => 'Xử lý PDF thất bại',
                'error' => $response->json()
            ], 500);
            
        } catch (\Exception $e) {
            Log::error('Go Quick PDF Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function processExcel(Request $request)
    {
        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                
                $request->validate([
                    'file' => 'required|file|mimes:xlsx,xls|max:10240'
                ]);
                
                $fileBytes = file_get_contents($file->getRealPath());
                $base64 = base64_encode($fileBytes);
                
                $response = Http::timeout(600)
                    ->post($this->getApiUrl() . '/process-excel', [
                        'inp_path' => $base64
                    ]);
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
                
                return response()->json([
                    'status' => 'success',
                    'data' => $result
                ]);
            }
            
            return response()->json([
                'status' => 'error',
                'message' => 'Xử lý Excel thất bại',
                'error' => $response->json()
            ], 500);
            
        } catch (\Exception $e) {
            Log::error('Go Quick Excel Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi: ' . $e->getMessage()
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

