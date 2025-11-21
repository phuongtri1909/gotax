<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\GoInvoicePackage;
use App\Models\GoBotPackage;
use App\Models\GoSoftPackage;
use App\Models\GoQuickPackage;
use App\Models\GoInvoicePurchase;
use App\Models\GoBotPurchase;
use App\Models\GoSoftPurchase;
use App\Models\GoQuickPurchase;
use App\Services\QRCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PaymentController extends Controller
{
    protected $qrCodeService;

    public function __construct(QRCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    /**
     * Tạo thanh toán cho GoInvoice
     */
    public function storeGoInvoice(Request $request)
    {
        return $this->storePurchase($request, 'go-invoice', GoInvoicePackage::class, GoInvoicePurchase::class);
    }

    /**
     * Tạo thanh toán cho GoBot
     */
    public function storeGoBot(Request $request)
    {
        return $this->storePurchase($request, 'go-bot', GoBotPackage::class, GoBotPurchase::class);
    }

    /**
     * Tạo thanh toán cho GoSoft
     */
    public function storeGoSoft(Request $request)
    {
        return $this->storePurchase($request, 'go-soft', GoSoftPackage::class, GoSoftPurchase::class);
    }

    /**
     * Tạo thanh toán cho GoQuick
     */
    public function storeGoQuick(Request $request)
    {
        return $this->storePurchase($request, 'go-quick', GoQuickPackage::class, GoQuickPurchase::class);
    }

    /**
     * Store purchase - xử lý chung cho tất cả tools
     */
    private function storePurchase(Request $request, $toolType, $packageClass, $purchaseClass)
    {
        $packageModel = new $packageClass;
        $request->validate([
            'package_id' => 'required|exists:' . $packageModel->getTable() . ',id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'export_vat_invoice' => 'nullable|boolean',
            'vat_mst' => 'required_if:export_vat_invoice,1|nullable|string|max:255',
            'vat_company' => 'required_if:export_vat_invoice,1|nullable|string|max:255',
            'vat_address' => 'required_if:export_vat_invoice,1|nullable|string',
        ], [
            'package_id.required' => 'Vui lòng chọn gói.',
            'package_id.exists' => 'Gói không tồn tại.',
            'first_name.required' => 'Vui lòng nhập họ.',
            'last_name.required' => 'Vui lòng nhập tên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'vat_mst.required_if' => 'Vui lòng nhập mã số thuế.',
            'vat_company.required_if' => 'Vui lòng nhập tên đơn vị.',
            'vat_address.required_if' => 'Vui lòng nhập địa chỉ.',
        ]);

        DB::beginTransaction();
        try {
            $package = $packageClass::findOrFail($request->package_id);
            $user = Auth::user();

            // Generate transaction code
            $transactionCode = $this->generateTransactionCode($toolType);

            // Get bank (first active bank)
            $bank = Bank::first();
            if (!$bank) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chưa có ngân hàng được cấu hình.'
                ], 400);
            }

            // Prepare purchase data
            $purchaseData = [
                'user_id' => $user ? $user->id : null,
                'bank_id' => $bank->id,
                'package_id' => $package->id,
                'transaction_code' => $transactionCode,
                'amount' => $package->price,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'expires_at' => Carbon::now()->addMinutes(15), // Hạn thanh toán: 15 phút (hiển thị 10 phút cho user)
            ];

            // Add VAT info if needed
            if ($request->export_vat_invoice) {
                $purchaseData['vat_mst'] = $request->vat_mst;
                $purchaseData['vat_company'] = $request->vat_company;
                $purchaseData['vat_address'] = $request->vat_address;
            }

            // Add tool-specific fields and status
            if ($toolType === 'go-invoice') {
                $purchaseData['mst_limit'] = $package->mst_limit;
                $purchaseData['license_fee'] = $package->license_fee;
                $purchaseData['status'] = GoInvoicePurchase::STATUS_PENDING;
                // Thời hạn sử dụng tool: 1 năm
                $purchaseData['expires_tool'] = Carbon::now()->addYear();
            } elseif ($toolType === 'go-bot') {
                $purchaseData['mst_limit'] = $package->mst_limit;
                $purchaseData['status'] = GoBotPurchase::STATUS_PENDING;
            } elseif ($toolType === 'go-soft') {
                $purchaseData['mst_limit'] = $package->mst_limit;
                $purchaseData['status'] = GoSoftPurchase::STATUS_PENDING;
                // Thời hạn sử dụng tool: 1 năm
                $purchaseData['expires_tool'] = Carbon::now()->addYear();
            } elseif ($toolType === 'go-quick') {
                $purchaseData['cccd_limit'] = $package->cccd_limit;
                $purchaseData['payment_status'] = GoQuickPurchase::PAYMENT_STATUS_PENDING;
                $purchaseData['status'] = GoQuickPurchase::STATUS_CANCELLED;
            }

            // Create purchase
            $purchase = $purchaseClass::create($purchaseData);

            DB::commit();

            return response()->json([
                'success' => true,
                'transaction_code' => $transactionCode,
                'purchase_id' => $purchase->id,
                'message' => 'Tạo đơn hàng thành công.'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi tạo đơn hàng: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo đơn hàng'
            ], 500);
        }
    }

    /**
     * Generate transaction code theo tool type
     */
    private function generateTransactionCode($toolType)
    {
        $prefix = strtoupper(str_replace('go-', 'GO-', $toolType));
        $random = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        return $prefix . $random;
    }

    /**
     * Lấy thông tin bank và QR code cho payment
     */
    public function getPaymentInfo(Request $request)
    {
        $request->validate([
            'transaction_code' => 'required|string',
            'tool_type' => 'required|in:go-invoice,go-bot,go-soft,go-quick',
        ]);

        try {
            // Get purchase
            $purchase = $this->getPurchaseByTransactionCode($request->transaction_code, $request->tool_type);
            
            if (!$purchase) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy đơn hàng.'
                ], 404);
            }

            // Check if expired
            if ($purchase->expires_at && $purchase->expires_at->isPast()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn hàng đã hết hạn.',
                    'expired' => true
                ], 400);
            }

            $bank = $purchase->bank;
            if (!$bank) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy thông tin ngân hàng.'
                ], 404);
            }

            // Generate QR code
            $qrCode = $this->qrCodeService->generateBankQRCode(
                $bank,
                $purchase->transaction_code,
                $purchase->amount
            );

            return response()->json([
                'success' => true,
                'bank' => [
                    'name' => $bank->name,
                    'account_number' => $bank->account_number,
                    'account_name' => $bank->account_name,
                ],
                'transaction_code' => $purchase->transaction_code,
                'amount' => number_format($purchase->amount, 0, ',', '.') . 'đ',
                'content' => $purchase->transaction_code,
                'qr_code' => $qrCode,
                'expires_at' => $purchase->expires_at ? $purchase->expires_at->toIso8601String() : null,
            ]);

        } catch (\Exception $e) {
            Log::error('Lỗi lấy thông tin thanh toán: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy thông tin thanh toán.'
            ], 500);
        }
    }

    /**
     * Get purchase by transaction code
     */
    private function getPurchaseByTransactionCode($transactionCode, $toolType)
    {
        switch ($toolType) {
            case 'go-invoice':
                return GoInvoicePurchase::where('transaction_code', $transactionCode)->first();
            case 'go-bot':
                return GoBotPurchase::where('transaction_code', $transactionCode)->first();
            case 'go-soft':
                return GoSoftPurchase::where('transaction_code', $transactionCode)->first();
            case 'go-quick':
                return GoQuickPurchase::where('transaction_code', $transactionCode)->first();
            default:
                return null;
        }
    }

    /**
     * SSE endpoint để check transaction status
     */
    public function sseTransactionUpdates(Request $request)
    {
        $transactionCode = $request->get('transaction_code');
        $toolType = $request->get('tool_type', 'go-invoice');
        
        if (!$transactionCode) {
            return response('Missing transaction_code', 400);
        }
        
        return response()->stream(function () use ($transactionCode, $toolType) {
            $sseDir = storage_path('app/sse');
            if (!is_dir($sseDir)) {
                mkdir($sseDir, 0755, true);
            }
            $filename = $sseDir . '/transaction_' . $transactionCode . '.json';
            $lastModified = 0;
            
            while (true) {
                if (file_exists($filename)) {
                    $currentModified = filemtime($filename);
                    
                    if ($currentModified > $lastModified) {
                        $data = json_decode(file_get_contents($filename), true);
                        
                        echo "data: " . json_encode($data) . "\n\n";
                        
                        $lastModified = $currentModified;
                        
                        if (isset($data['status']) && $data['status'] === 'success') {
                            echo "data: " . json_encode(['type' => 'close']) . "\n\n";
                            break;
                        }
                    }
                }
                
                // Check if purchase expired
                $purchase = $this->getPurchaseByTransactionCode($transactionCode, $toolType);
                if ($purchase && $purchase->expires_at && $purchase->expires_at->isPast()) {
                    $expiredData = [
                        'transaction_code' => $transactionCode,
                        'status' => 'expired',
                        'message' => 'Đơn hàng đã hết hạn',
                        'timestamp' => now()->toISOString(),
                    ];
                    echo "data: " . json_encode($expiredData) . "\n\n";
                    echo "data: " . json_encode(['type' => 'close']) . "\n\n";
                    break;
                }
                
                sleep(5);
                
                if (connection_aborted()) {
                    break;
                }
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => 'Cache-Control',
        ]);
    }

    /**
     * Broadcast transaction update via file (for SSE)
     */
    public static function broadcastTransactionUpdate($transactionCode, $status, $purchase = null)
    {
        $sseData = [
            'transaction_code' => $transactionCode,
            'status' => $status,
            'purchase_id' => $purchase ? $purchase->id : null,
            'amount' => $purchase ? $purchase->amount : null,
            'timestamp' => now()->toISOString(),
        ];
        
        $sseDir = storage_path('app/sse');
        if (!is_dir($sseDir)) {
            mkdir($sseDir, 0755, true);
        }
        
        $filename = $sseDir . '/transaction_' . $transactionCode . '.json';
        file_put_contents($filename, json_encode($sseData));
    }
}
