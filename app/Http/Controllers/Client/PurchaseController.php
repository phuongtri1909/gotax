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
use App\Models\ReferralPurchase;
use App\Http\Controllers\Client\PaymentController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    
    public function cassoCallback(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('X-Casso-Signature');

        if (!$this->verifyCassoSignature($payload, $signature)) {
            Log::warning('Casso signature verification failed', [
                'signature' => $signature,
                'payload_length' => strlen($payload)
            ]);
            return response()->json(['success' => false, 'message' => 'Invalid signature'], 401);
        }

        $data = json_decode($payload, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('JSON payload không hợp lệ', ['error' => json_last_error_msg()]);
            return response()->json(['success' => false, 'message' => 'JSON payload không hợp lệ'], 400);
        }

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
            $transactionCode = null;
            $toolType = null;
            
            if (preg_match_all('/(GOINVOICE\d{6})/i', $description, $matches)) {
                $transactionCode = strtoupper($matches[1][0]);
                $toolType = 'go-invoice';
            } elseif (preg_match_all('/(GOBOT\d{6})/i', $description, $matches)) {
                $transactionCode = strtoupper($matches[1][0]);
                $toolType = 'go-bot';
            } elseif (preg_match_all('/(GOSOFT\d{6})/i', $description, $matches)) {
                $transactionCode = strtoupper($matches[1][0]);
                $toolType = 'go-soft';
            } elseif (preg_match_all('/(GOQUICK\d{6})/i', $description, $matches)) {
                $transactionCode = strtoupper($matches[1][0]);
                $toolType = 'go-quick';
            }
            
            if (!$transactionCode || !$toolType) {
                Log::warning('Không tìm thấy transaction code hợp lệ', [
                    'description' => $description,
                    'transaction_id' => $transactionId
                ]);
                return response()->json(['success' => false, 'message' => 'Không tìm thấy mã giao dịch hợp lệ'], 404);
            }
            
            $purchase = $this->getPurchaseByTransactionCode($transactionCode, $toolType);
            
            if (!$purchase) {
                Log::warning('Purchase not found', [
                    'transaction_code' => $transactionCode,
                    'tool_type' => $toolType,
                    'transaction_id' => $transactionId
                ]);
                return response()->json(['success' => false, 'message' => 'Giao dịch không tồn tại'], 404);
            }

            if ($purchase->status === $this->getSuccessStatus($toolType) && $purchase->casso_transaction_id === $transactionId) {
                return response()->json(['success' => true, 'message' => 'Giao dịch đã được xử lý trước đó'], 200);
            }

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

            if ($purchase->expires_at && $purchase->expires_at->isPast()) {
                Log::warning('Purchase đã hết hạn', [
                    'transaction_code' => $transactionCode,
                    'expires_at' => $purchase->expires_at
                ]);
                
                $this->updatePurchaseStatus($purchase, $toolType, $this->getFailedStatus($toolType), 'Đơn hàng đã hết hạn', $data);
                
                DB::commit();
                return response()->json(['success' => false, 'message' => 'Đơn hàng đã hết hạn'], 400);
            }

            $this->updatePurchaseStatus($purchase, $toolType, $this->getSuccessStatus($toolType), null, $data, $transactionId);

            $this->processPurchase($purchase, $toolType);

            PaymentController::broadcastTransactionUpdate($transactionCode, 'success', $purchase);
            
            DB::commit();
            
            return response()->json(['success' => true,'message' => 'Xử lý giao dịch thành công'], 200);
            
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
                    ->where('status', GoQuickPurchase::STATUS_PENDING)
                    ->first();
            default:
                return null;
        }
    }

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
                return GoQuickPurchase::STATUS_SUCCESS;
            default:
                return null;
        }
    }

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
                return GoQuickPurchase::STATUS_FAILED;
            default:
                return null;
        }
    }

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
        
        $updateData['status'] = $status;
        
        $purchase->update($updateData);

        // Đồng bộ status của ReferralPurchase nếu có
        ReferralPurchase::where('transaction_code', $purchase->transaction_code)
            ->where('tool_type', $toolType)
            ->update(['status' => $status]);
    }

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

    private function processGoInvoicePurchase($purchase)
    {
        $use = GoInvoiceUse::where('user_id', $purchase->user_id)->first();

        if ($purchase->is_upgrade && $purchase->upgradeHistory) {
            $expiresAt = Carbon::now()->addYear();
            $now = Carbon::now();

            if ($use) {
                $use->package_id = $purchase->package_id;
                $use->mst_limit = $purchase->mst_limit;
                $use->expires_at = $expiresAt;
                $use->purchase_count = 1;
                $use->first_purchase_date = $now;
                $use->save();
            } else {
                GoInvoiceUse::create([
                    'user_id' => $purchase->user_id,
                    'package_id' => $purchase->package_id,
                    'mst_limit' => $purchase->mst_limit,
                    'expires_at' => $expiresAt,
                    'purchase_count' => 1,
                    'first_purchase_date' => $now,
                ]);
            }
            } else {
                
            if ($use) {
                
                if ($use->package_id == $purchase->package_id) {
                    
                    $now = Carbon::now();
                    $firstPurchaseDate = $use->first_purchase_date ? Carbon::parse($use->first_purchase_date) : null;

                    $hasPassedFirstYear = false;
                    if ($firstPurchaseDate) {
                        $firstYearEnd = $firstPurchaseDate->copy()->addYear();
                        if ($now->greaterThanOrEqualTo($firstYearEnd)) {
                            $hasPassedFirstYear = true;
                        }
                    }
                    
                    if ($use->expires_at && $use->expires_at->isFuture()) {
                        
                        $newExpiresAt = $use->expires_at->copy()->addYear();
                        
                        if ($hasPassedFirstYear) {

                            $yearsPassed = floor($firstPurchaseDate->diffInDays($now) / 365);
                            $newFirstPurchaseDate = $firstPurchaseDate->copy()->addYears($yearsPassed);
                            $use->first_purchase_date = $newFirstPurchaseDate;
                            $use->purchase_count = 1; 
                        } else {
                            
                            if (!$use->first_purchase_date) {
                                
                                $use->first_purchase_date = $use->created_at ?? $now;
                            }
                            $use->purchase_count = ($use->purchase_count ?? 1) + 1;
                        }
                    } else {
                        
                        $newExpiresAt = $now->copy()->addYear();
                        $use->purchase_count = 1;
                        $use->first_purchase_date = $now;
                    }
                    
                    $use->expires_at = $newExpiresAt;
                    $use->save();
                } else {
                    
                    $now = Carbon::now();
                    $use->package_id = $purchase->package_id;
                    $use->mst_limit = $purchase->mst_limit;
                    $use->expires_at = $purchase->expires_tool;
                    $use->purchase_count = 1;
                    $use->first_purchase_date = $now;
                    $use->save();
                }
            } else {
                
                $now = Carbon::now();
                GoInvoiceUse::create([
                    'user_id' => $purchase->user_id,
                    'package_id' => $purchase->package_id,
                    'mst_limit' => $purchase->mst_limit,
                    'expires_at' => $purchase->expires_tool,
                    'purchase_count' => 1,
                    'first_purchase_date' => $now,
                ]);
            }
        }
    }

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

    private function processGoSoftPurchase($purchase)
    {
        $use = GoSoftUse::where('user_id', $purchase->user_id)->first();

        if ($purchase->is_upgrade && $purchase->upgradeHistory) {
            $expiresAt = Carbon::now()->addYear();
            $now = Carbon::now();

            if ($use) {
                $use->package_id = $purchase->package_id;
                $use->mst_limit = $purchase->mst_limit;
                $use->expires_at = $expiresAt;
                $use->purchase_count = 1;
                $use->first_purchase_date = $now;
                $use->save();
            } else {
                GoSoftUse::create([
                    'user_id' => $purchase->user_id,
                    'package_id' => $purchase->package_id,
                    'mst_limit' => $purchase->mst_limit,
                    'expires_at' => $expiresAt,
                    'purchase_count' => 1,
                    'first_purchase_date' => $now,
                ]);
            }
        } else {
            
            if ($use) {
                
                if ($use->package_id == $purchase->package_id) {
                    
                    $now = Carbon::now();
                    $firstPurchaseDate = $use->first_purchase_date ? Carbon::parse($use->first_purchase_date) : null;

                    $hasPassedFirstYear = false;
                    if ($firstPurchaseDate) {
                        $firstYearEnd = $firstPurchaseDate->copy()->addYear();
                        if ($now->greaterThanOrEqualTo($firstYearEnd)) {
                            $hasPassedFirstYear = true;
                        }
                    }
                    
                    if ($use->expires_at && $use->expires_at->isFuture()) {
                        
                        $newExpiresAt = $use->expires_at->copy()->addYear();
                        
                        if ($hasPassedFirstYear) {
                            
                            $yearsPassed = floor($firstPurchaseDate->diffInDays($now) / 365);
                            $newFirstPurchaseDate = $firstPurchaseDate->copy()->addYears($yearsPassed);
                            $use->first_purchase_date = $newFirstPurchaseDate;
                            $use->purchase_count = 1; 
                        } else {
                            
                            if (!$use->first_purchase_date) {
                                
                                $use->first_purchase_date = $use->created_at ?? $now;
                            }
                            $use->purchase_count = ($use->purchase_count ?? 1) + 1;
                        }
                    } else {
                        
                        $newExpiresAt = $now->copy()->addYear();
                        $use->purchase_count = 1;
                        $use->first_purchase_date = $now;
                    }
                    
                    $use->expires_at = $newExpiresAt;
                    $use->save();
                } else {
                    
                    $now = Carbon::now();
                    $use->package_id = $purchase->package_id;
                    $use->mst_limit = $purchase->mst_limit;
                    $use->expires_at = $purchase->expires_tool;
                    $use->purchase_count = 1;
                    $use->first_purchase_date = $now;
                    $use->save();
                }
            } else {
                
                $now = Carbon::now();
                GoSoftUse::create([
                    'user_id' => $purchase->user_id,
                    'package_id' => $purchase->package_id,
                    'mst_limit' => $purchase->mst_limit,
                    'expires_at' => $purchase->expires_tool,
                    'purchase_count' => 1,
                    'first_purchase_date' => $now,
                ]);
            }
        }
    }

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

