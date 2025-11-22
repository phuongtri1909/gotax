<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\GoInvoicePurchase;
use App\Models\GoBotPurchase;
use App\Models\GoSoftPurchase;
use App\Models\GoQuickPurchase;
use App\Models\GoInvoiceUse;
use App\Models\GoBotUse;
use App\Models\GoSoftUse;
use App\Models\GoQuickUse;
use App\Http\Controllers\Client\PaymentController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    /**
     * Callback từ Casso Webhook v2 khi có giao dịch mới
     */
    public function cassoCallback(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('X-Casso-Signature');
        
        if (!$signature) {
            Log::warning('Thiếu chữ ký Casso trong header');
            return response()->json(['success' => false, 'message' => 'Thiếu chữ ký Casso'], 401);
        }
        
        if (!$this->verifyCassoSignature($payload, $signature)) {
            Log::warning('Chữ ký Casso không hợp lệ', [
                'signature' => $signature,
                'payload_preview' => substr($payload, 0, 100)
            ]);
            return response()->json(['success' => false, 'message' => 'Chữ ký Casso không hợp lệ'], 401);
        }
        
        // Parse JSON payload
        $data = json_decode($payload, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('JSON payload không hợp lệ', ['error' => json_last_error_msg()]);
            return response()->json(['success' => false, 'message' => 'JSON payload không hợp lệ'], 400);
        }
        
        // Casso Webhook v2 format
        $transactionId = $data['data']['id'] ?? null;
        $reference = $data['data']['reference'] ?? null;
        $description = $data['data']['description'] ?? '';
        $amount = $data['data']['amount'] ?? 0;
        $accountNumber = $data['data']['accountNumber'] ?? '';
        $bankName = $data['data']['bankName'] ?? '';
        $transactionDateTime = $data['data']['transactionDateTime'] ?? null;
        
        if (!$transactionId) {
            Log::warning('Thiếu ID giao dịch trong webhook Casso', ['data' => $data]);
            return response()->json(['success' => false, 'message' => 'Thiếu ID giao dịch'], 400);
        }
        
        DB::beginTransaction();
        try {
            // Extract transaction code từ description (GO-INVOICE, GO-BOT, GO-SOFT, GO-QUICK + 6 số)
            $transactionCode = null;
            $toolType = null;
            
            // Format: GO-INVOICE123456, GO-BOT123456, etc.
            if (preg_match('/(GO-INVOICE\d{6})/i', $description, $matches)) {
                $transactionCode = strtoupper($matches[1]);
                $toolType = 'go-invoice';
            } elseif (preg_match('/(GO-BOT\d{6})/i', $description, $matches)) {
                $transactionCode = strtoupper($matches[1]);
                $toolType = 'go-bot';
            } elseif (preg_match('/(GO-SOFT\d{6})/i', $description, $matches)) {
                $transactionCode = strtoupper($matches[1]);
                $toolType = 'go-soft';
            } elseif (preg_match('/(GO-QUICK\d{6})/i', $description, $matches)) {
                $transactionCode = strtoupper($matches[1]);
                $toolType = 'go-quick';
            }
            
            if (!$transactionCode || !$toolType) {
                Log::warning('Không tìm thấy transaction code hợp lệ', [
                    'description' => $description,
                    'transaction_id' => $transactionId
                ]);
                return response()->json(['success' => false, 'message' => 'Không tìm thấy mã giao dịch hợp lệ'], 404);
            }
            
            // Get purchase by transaction code
            $purchase = $this->getPurchaseByTransactionCode($transactionCode, $toolType);
            
            if (!$purchase) {
                Log::warning('Purchase not found', [
                    'transaction_code' => $transactionCode,
                    'tool_type' => $toolType,
                    'transaction_id' => $transactionId
                ]);
                return response()->json(['success' => false, 'message' => 'Giao dịch không tồn tại'], 404);
            }
            
            // Check if already processed
            if ($purchase->status === $this->getSuccessStatus($toolType) && $purchase->casso_transaction_id === $transactionId) {
                return response()->json(['success' => true, 'message' => 'Giao dịch đã được xử lý trước đó'], 200);
            }
            
            // Verify amount
            if ($amount != $purchase->amount) {
                Log::warning('Số tiền nhận được không khớp', [
                    'expected' => $purchase->amount,
                    'received' => $amount,
                    'transaction_code' => $transactionCode,
                    'description' => $description
                ]);
                
                $this->updatePurchaseStatus($purchase, $toolType, $this->getFailedStatus($toolType), 'Số tiền nhận được không khớp', $data);
                
                DB::commit();
                return response()->json(['success' => false, 'message' => 'Số tiền không khớp'], 400);
            }
            
            // Check if expired
            if ($purchase->expires_at && $purchase->expires_at->isPast()) {
                Log::warning('Purchase đã hết hạn', [
                    'transaction_code' => $transactionCode,
                    'expires_at' => $purchase->expires_at
                ]);
                
                $this->updatePurchaseStatus($purchase, $toolType, $this->getFailedStatus($toolType), 'Đơn hàng đã hết hạn', $data);
                
                DB::commit();
                return response()->json(['success' => false, 'message' => 'Đơn hàng đã hết hạn'], 400);
            }
            
            // Update purchase status
            $this->updatePurchaseStatus($purchase, $toolType, $this->getSuccessStatus($toolType), null, $data, $transactionId);
            
            // Process purchase based on tool type
            $this->processPurchase($purchase, $toolType);
            
            // Broadcast transaction update
            PaymentController::broadcastTransactionUpdate($transactionCode, 'success', $purchase);
            
            DB::commit();
            
            return response()->json(['success' => true], 200);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi xử lý callback purchase: ' . $e->getMessage(), [
                'transaction_id' => $transactionId ?? null,
                'reference' => $reference ?? null,
                'data' => $data ?? null,
                'trace' => $e->getTraceAsString(),
                'description' => $description ?? ''
            ]);
            return response()->json(['success' => false, 'message' => 'Có lỗi xảy ra khi xử lý'], 500);
        }
    }
    
    /**
     * Get purchase by transaction code
     */
    private function getPurchaseByTransactionCode($transactionCode, $toolType)
    {
        switch ($toolType) {
            case 'go-invoice':
                return GoInvoicePurchase::where('transaction_code', $transactionCode)
                    ->where('status', GoInvoicePurchase::STATUS_PENDING)
                    ->first();
            case 'go-bot':
                return GoBotPurchase::where('transaction_code', $transactionCode)
                    ->where('status', GoBotPurchase::STATUS_PENDING)
                    ->first();
            case 'go-soft':
                return GoSoftPurchase::where('transaction_code', $transactionCode)
                    ->where('status', GoSoftPurchase::STATUS_PENDING)
                    ->first();
            case 'go-quick':
                return GoQuickPurchase::where('transaction_code', $transactionCode)
                    ->where('payment_status', GoQuickPurchase::PAYMENT_STATUS_PENDING)
                    ->first();
            default:
                return null;
        }
    }
    
    /**
     * Get success status for tool type
     */
    private function getSuccessStatus($toolType)
    {
        switch ($toolType) {
            case 'go-invoice':
                return GoInvoicePurchase::STATUS_SUCCESS;
            case 'go-bot':
                return GoBotPurchase::STATUS_SUCCESS;
            case 'go-soft':
                return GoSoftPurchase::STATUS_SUCCESS;
            case 'go-quick':
                return GoQuickPurchase::PAYMENT_STATUS_PAID;
            default:
                return null;
        }
    }
    
    /**
     * Get failed status for tool type
     */
    private function getFailedStatus($toolType)
    {
        switch ($toolType) {
            case 'go-invoice':
                return GoInvoicePurchase::STATUS_FAILED;
            case 'go-bot':
                return GoBotPurchase::STATUS_FAILED;
            case 'go-soft':
                return GoSoftPurchase::STATUS_FAILED;
            case 'go-quick':
                return GoQuickPurchase::PAYMENT_STATUS_FAILED;
            default:
                return null;
        }
    }
    
    /**
     * Update purchase status
     */
    private function updatePurchaseStatus($purchase, $toolType, $status, $note = null, $cassoResponse = null, $cassoTransactionId = null)
    {
        $updateData = [
            'processed_at' => now(),
            'casso_response' => $cassoResponse,
        ];
        
        if ($note) {
            $updateData['note'] = $note;
        }
        
        if ($cassoTransactionId) {
            $updateData['casso_transaction_id'] = $cassoTransactionId;
        }
        
        if ($toolType === 'go-quick') {
            $updateData['payment_status'] = $status;
            if ($status === GoQuickPurchase::PAYMENT_STATUS_PAID) {
                $updateData['status'] = GoQuickPurchase::STATUS_ACTIVE;
            }
        } else {
            $updateData['status'] = $status;
        }
        
        $purchase->update($updateData);
    }
    
    /**
     * Process purchase - tạo hoặc cập nhật Use record
     */
    private function processPurchase($purchase, $toolType)
    {
        $user = $purchase->user;
        if (!$user) {
            Log::warning('Purchase không có user', [
                'purchase_id' => $purchase->id,
                'tool_type' => $toolType
            ]);
            return;
        }
        
        switch ($toolType) {
            case 'go-invoice':
                $this->processGoInvoicePurchase($purchase);
                break;
            case 'go-bot':
                $this->processGoBotPurchase($purchase);
                break;
            case 'go-soft':
                $this->processGoSoftPurchase($purchase);
                break;
            case 'go-quick':
                $this->processGoQuickPurchase($purchase);
                break;
        }
    }
    
    /**
     * Process GoInvoice purchase - tạo hoặc cập nhật Use (mỗi user 1 bản ghi)
     */
    private function processGoInvoicePurchase($purchase)
    {
        GoInvoiceUse::updateOrCreate(
            ['user_id' => $purchase->user_id],
            [
                'package_id' => $purchase->package_id,
                'mst_limit' => $purchase->mst_limit,
                'expires_at' => $purchase->expires_tool,
            ]
        );
    }
    
    /**
     * Process GoBot purchase - cộng dồn mst_limit (mỗi user 1 bản ghi)
     */
    private function processGoBotPurchase($purchase)
    {
        $use = GoBotUse::firstOrNew(['user_id' => $purchase->user_id]);
        
        if ($use->exists) {
            $use->mst_limit += $purchase->mst_limit;
        } else {
            $use->mst_limit = $purchase->mst_limit;
        }
        
        $use->package_id = $purchase->package_id;
        $use->save();
    }
    
    /**
     * Process GoSoft purchase - tạo hoặc cập nhật Use (mỗi user 1 bản ghi)
     */
    private function processGoSoftPurchase($purchase)
    {
        GoSoftUse::updateOrCreate(
            ['user_id' => $purchase->user_id],
            [
                'package_id' => $purchase->package_id,
                'mst_limit' => $purchase->mst_limit,
                'expires_at' => $purchase->expires_tool,
            ]
        );
    }
    
    /**
     * Process GoQuick purchase - cộng dồn cccd_limit (mỗi user 1 bản ghi)
     */
    private function processGoQuickPurchase($purchase)
    {
        $use = GoQuickUse::firstOrNew(['user_id' => $purchase->user_id]);
        
        if ($use->exists) {
            $use->cccd_limit += $purchase->cccd_limit;
        } else {
            $use->cccd_limit = $purchase->cccd_limit;
        }
        
        $use->package_id = $purchase->package_id;
        $use->save();
    }
    
    /**
     * Verify signature từ Casso Webhook v2
     */
    private function verifyCassoSignature($payload, $signature)
    {
        $secret = config('services.casso.webhook_secret');
        
        if (!$secret) {
            Log::error('Secret webhook Casso không được cấu hình');
            return false;
        }
        
        if (!preg_match('/t=(\d+),v1=(.+)/', $signature, $matches)) {
            Log::warning('Invalid signature format', ['signature' => $signature]);
            return false;
        }
        
        $timestamp = $matches[1];
        $receivedSignature = $matches[2];
        
        $currentTime = time() * 1000;
        $signatureTime = (int)$timestamp;
        $timeDiff = abs($currentTime - $signatureTime);
        
        if ($timeDiff > 300000) {
            Log::warning('Chữ ký timestamp quá cũ', [
                'current_time' => $currentTime,
                'signature_time' => $signatureTime,
                'time_diff' => $timeDiff
            ]);
            return false;
        }
        
        $data = json_decode($payload, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('JSON payload không hợp lệ cho việc xác thực chữ ký', ['error' => json_last_error_msg()]);
            return false;
        }
        
        $sortedData = $this->sortDataByKey($data);
        
        $messageToSign = $timestamp . '.' . json_encode($sortedData, JSON_UNESCAPED_SLASHES);
        
        $expectedSignature = hash_hmac('sha512', $messageToSign, $secret);
        
        return hash_equals($expectedSignature, $receivedSignature);
    }
    
    /**
     * Sắp xếp dữ liệu theo key
     */
    private function sortDataByKey($data)
    {
        if (!is_array($data)) {
            return $data;
        }
        
        $sortedData = [];
        $keys = array_keys($data);
        sort($keys);
        
        foreach ($keys as $key) {
            if (is_array($data[$key])) {
                $sortedData[$key] = $this->sortDataByKey($data[$key]);
            } else {
                $sortedData[$key] = $data[$key];
            }
        }
        
        return $sortedData;
    }
}

