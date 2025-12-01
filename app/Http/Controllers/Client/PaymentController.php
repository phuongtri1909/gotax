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
use App\Models\ReferralPurchase;
use App\Models\User;
use App\Services\QRCodeService;
use App\Services\PackageUpgradeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PaymentController extends Controller
{
    protected $qrCodeService;
    protected $upgradeService;

    public function __construct(QRCodeService $qrCodeService, PackageUpgradeService $upgradeService)
    {
        $this->qrCodeService = $qrCodeService;
        $this->upgradeService = $upgradeService;
    }

    public function storeGoInvoice(Request $request)
    {
        return $this->storePurchase($request, 'go-invoice', GoInvoicePackage::class, GoInvoicePurchase::class);
    }

    public function storeGoBot(Request $request)
    {
        return $this->storePurchase($request, 'go-bot', GoBotPackage::class, GoBotPurchase::class);
    }

    public function storeGoSoft(Request $request)
    {
        return $this->storePurchase($request, 'go-soft', GoSoftPackage::class, GoSoftPurchase::class);
    }

    public function storeGoQuick(Request $request)
    {
        return $this->storePurchase($request, 'go-quick', GoQuickPackage::class, GoQuickPurchase::class);
    }

    public function calculateUpgradeGoInvoice(Request $request)
    {
        return $this->calculateUpgradePrice($request, 'go-invoice');
    }

    public function calculateUpgradeGoSoft(Request $request)
    {
        return $this->calculateUpgradePrice($request, 'go-soft');
    }

    public function calculateUpgradeGoBot(Request $request)
    {
        return $this->calculateUpgradePrice($request, 'go-bot');
    }

    public function calculateUpgradeGoQuick(Request $request)
    {
        return $this->calculateUpgradePrice($request, 'go-quick');
    }

    public function checkReferralCode(Request $request)
    {
        try {
            $referralCode = $request->input('referral_code');
            
            if (!$referralCode) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng nhập mã giới thiệu.'
                ], 400);
            }

            $referrer = User::where('referral_code', $referralCode)
                ->where('active', User::ACTIVE_YES)
                ->first();

            if (!$referrer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã giới thiệu không hợp lệ hoặc không tồn tại.'
                ], 404);
            }

            // Không cho phép tự giới thiệu chính mình
            $currentUser = Auth::user();
            if ($currentUser && $currentUser->id === $referrer->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không thể sử dụng mã giới thiệu của chính mình.'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $referrer->id,
                    'full_name' => $referrer->full_name,
                    'referral_code' => $referrer->referral_code,
                ],
                'message' => 'Mã giới thiệu hợp lệ.'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Lỗi kiểm tra mã giới thiệu: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi kiểm tra mã giới thiệu.'
            ], 500);
        }
    }

    private function calculateUpgradePrice(Request $request, $toolType)
    {
        $request->validate([
            'package_id' => 'required|integer',
        ]);

        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập để nâng cấp gói.'
                ], 401);
            }

            $renewalInfo = null;
            if (in_array($toolType, ['go-invoice', 'go-soft'])) {
                $renewalInfo = $this->upgradeService->checkRenewalPackage(
                    $user->id,
                    $toolType,
                    $request->package_id
                );
            }

            if ($renewalInfo && $renewalInfo['is_renewal']) {
                return response()->json([
                    'success' => true,
                    'data' => null,
                    'is_renewal' => true,
                    'renewal_info' => $renewalInfo,
                    'message' => 'Gia hạn gói hiện tại thêm 1 năm với ưu đãi ' . $renewalInfo['discount_percent'] . '%.'
                ], 200);
            }

            $downgradeResult = null;
            if (in_array($toolType, ['go-invoice', 'go-soft'])) {
                $downgradeResult = $this->upgradeService->calculateDowngradePrice(
                    $user->id,
                    $toolType,
                    $request->package_id,
                    false 
                );
            }

            if ($downgradeResult && $downgradeResult['is_downgrade']) {
                $message = 'Giảm cấp gói với ưu đãi ' . $downgradeResult['discount_percent'] . '%';
                if ($downgradeResult['days_remaining'] !== null) {
                    $hoursRemaining = $downgradeResult['hours_remaining'] ?? 0;
                    $timeText = '';
                    if ($downgradeResult['days_remaining'] == 0 && $hoursRemaining == 0) {
                        $timeText = 'đã hết hạn';
                    } elseif ($downgradeResult['days_remaining'] == 0) {
                        $timeText = $hoursRemaining . ' giờ';
                    } elseif ($hoursRemaining == 0) {
                        $timeText = $downgradeResult['days_remaining'] . ' ngày';
                    } else {
                        $timeText = $downgradeResult['days_remaining'] . ' ngày ' . $hoursRemaining . ' giờ';
                    }
                    
                    if ($downgradeResult['days_remaining'] < 30) {
                        $message .= ' (thời hạn còn ' . $timeText . ')';
                    } else {
                        $message .= ' (thời hạn còn ' . $timeText . ' - chỉ cho phép khi còn dưới 1 tháng)';
                    }
                }
                
                return response()->json([
                    'success' => true,
                    'data' => null,
                    'is_downgrade' => true,
                    'downgrade_info' => $downgradeResult,
                    'message' => $message
                ], 200);
            }

            $result = $this->upgradeService->calculateUpgradePrice(
                $user->id,
                $toolType,
                $request->package_id
            );

            if (!$result) {
                
                $package = $this->upgradeService->getPackage($toolType, $request->package_id);
                if (!$package) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gói không tồn tại.'
                    ], 400);
                }

                $discountInfo = null;
                $finalAmount = $package->price;
                $licenseFee = null;

                $currentUse = $this->upgradeService->getCurrentUse($user->id, $toolType);
                $isFirstPurchase = !$currentUse || !$currentUse->package_id;
                
                // Kiểm tra license_fee cho go-invoice lần đầu đăng ký
                if ($toolType === 'go-invoice' && $isFirstPurchase && $package->license_fee) {
                    $licenseFee = $package->license_fee;
                }
                
                if ($isFirstPurchase) {
                    
                    $discountPercent = $this->upgradeService->getFirstPurchaseDiscount($toolType);
                    if ($discountPercent > 0) {
                        $discountAmount = $package->price * ($discountPercent / 100);
                        $finalAmount = $package->price - $discountAmount;
                        $discountInfo = [
                            'type' => 'first_purchase',
                            'discount_percent' => $discountPercent,
                            'discount_amount' => $discountAmount,
                            'original_price' => $package->price,
                            'final_amount' => $finalAmount,
                        ];
                    }
                } else {

                    if (in_array($toolType, ['go-bot', 'go-quick'])) {
                        
                        $discountPercent = $this->upgradeService->getCrossProductDiscount($toolType);
                        if ($discountPercent > 0) {
                            $discountAmount = round($package->price * ($discountPercent / 100));
                            $finalAmount = round($package->price - $discountAmount);
                            $discountInfo = [
                                'type' => 'cross_product',
                                'discount_percent' => $discountPercent,
                                'discount_amount' => $discountAmount,
                                'original_price' => $package->price,
                                'final_amount' => $finalAmount,
                            ];
                        }
                    } else {
                        
                        $hasOtherProducts = $this->upgradeService->hasAnyActiveProduct($user->id, $toolType);
                        if ($hasOtherProducts) {
                            $discountPercent = $this->upgradeService->getCrossProductDiscount($toolType);
                            if ($discountPercent > 0) {
                                $discountAmount = round($package->price * ($discountPercent / 100));
                                $finalAmount = round($package->price - $discountAmount);
                                $discountInfo = [
                                    'type' => 'cross_product',
                                    'discount_percent' => $discountPercent,
                                    'discount_amount' => $discountAmount,
                                    'original_price' => $package->price,
                                    'final_amount' => $finalAmount,
                                ];
                            }
                        }
                    }
                }

                $responseData = $discountInfo ? [
                    'is_new_purchase' => true,
                    'discount_info' => $discountInfo,
                    'package' => [
                        'id' => $package->id,
                        'name' => $package->name,
                        'price' => (float) $package->price,
                    ],
                ] : [
                    'is_new_purchase' => true,
                    'package' => [
                        'id' => $package->id,
                        'name' => $package->name,
                        'price' => (float) $package->price,
                    ],
                ];
                
                // Thêm license_fee vào response nếu có
                if ($licenseFee) {
                    $responseData['license_fee'] = (float) $licenseFee;
                    $responseData['final_amount'] = (float) ($discountInfo ? $discountInfo['final_amount'] : $package->price) + $licenseFee;
                }
                
                return response()->json([
                    'success' => true,
                    'data' => $responseData,
                    'message' => $discountInfo ? 'Áp dụng ưu đãi ' . $discountInfo['discount_percent'] . '% cho lần mua này.' : 'Bạn chưa có gói hiện tại. Đây là gói mua mới.'
                ], 200);
            }

            if (isset($result['can_upgrade']) && !$result['can_upgrade']) {
                
                $message = $result['message'] ?? 'Không thể nâng cấp gói này.';
                $isError = strpos($message, 'giảm cấp') !== false || 
                          strpos($message, 'thấp hơn') !== false ||
                          strpos($message, 'Không thể mua') !== false ||
                          strpos($message, 'Không thể giảm') !== false;
                
                if ($isError) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'can_purchase' => $result['can_purchase'] ?? false
                    ], 400);
                } else {

                    if (in_array($toolType, ['go-bot', 'go-quick'])) {
                        $package = $this->upgradeService->getPackage($toolType, $request->package_id);
                        if ($package) {
                            
                            $currentUse = $this->upgradeService->getCurrentUse($user->id, $toolType);
                            $currentLimit = 0;
                            $newLimit = 0;
                            
                            if ($toolType === 'go-bot') {
                                $currentLimit = $currentUse ? ($currentUse->mst_limit ?? 0) : 0;
                                $newLimit = $package->mst_limit ?? 0;
                            } elseif ($toolType === 'go-quick') {
                                $currentLimit = $currentUse ? ($currentUse->cccd_limit ?? 0) : 0;
                                $newLimit = $package->cccd_limit ?? 0;
                            }
                            
                            $totalLimitAfter = $currentLimit + $newLimit;
                            
                            $discountPercent = $this->upgradeService->getCrossProductDiscount($toolType);
                            if ($discountPercent > 0) {
                                $discountAmount = round($package->price * ($discountPercent / 100));
                                $finalAmount = round($package->price - $discountAmount);
                                $discountInfo = [
                                    'type' => 'cross_product',
                                    'discount_percent' => $discountPercent,
                                    'discount_amount' => $discountAmount,
                                    'original_price' => $package->price,
                                    'final_amount' => $finalAmount,
                                ];
                                
                                return response()->json([
                                    'success' => true,
                                    'data' => [
                                        'is_new_purchase' => false,
                                        'discount_info' => $discountInfo,
                                        'package' => [
                                            'id' => $package->id,
                                            'name' => $package->name,
                                            'price' => (float) $package->price,
                                        ],
                                        'current_limit' => $currentLimit,
                                        'new_limit' => $newLimit,
                                        'total_limit_after' => $totalLimitAfter,
                                        'limit_type' => $toolType === 'go-bot' ? 'mst' : 'cccd',
                                    ],
                                    'message' => 'Áp dụng ưu đãi ' . $discountPercent . '% cho lần mua này.'
                                ], 200);
                            }
                        }
                    }

                    return response()->json([
                        'success' => true,
                        'data' => $result,
                        'message' => $message
                    ], 200);
                }
            }

            return response()->json([
                'success' => true,
                'data' => $result
            ], 200);

        } catch (\Exception $e) {
            Log::error('Lỗi tính toán giá nâng cấp: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tính toán giá nâng cấp.'
            ], 500);
        }
    }

    public function storeUpgradeGoInvoice(Request $request)
    {
        return $this->storeUpgrade($request, 'go-invoice', GoInvoicePackage::class, GoInvoicePurchase::class);
    }

    public function storeUpgradeGoSoft(Request $request)
    {
        return $this->storeUpgrade($request, 'go-soft', GoSoftPackage::class, GoSoftPurchase::class);
    }

    private function storeUpgrade(Request $request, $toolType, $packageClass, $purchaseClass)
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
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập để nâng cấp gói.'
                ], 401);
            }

            $calculationResult = $this->upgradeService->calculateUpgradePrice(
                $user->id,
                $toolType,
                $request->package_id
            );

            if (!$calculationResult || (isset($calculationResult['can_upgrade']) && !$calculationResult['can_upgrade'])) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => $calculationResult['message'] ?? 'Không thể nâng cấp gói này.'
                ], 400);
            }

            $newPackage = $packageClass::findOrFail($request->package_id);

            $transactionCode = $this->generateTransactionCode($toolType);

            $bank = Bank::first();
            if (!$bank) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Chưa có ngân hàng được cấu hình.'
                ], 400);
            }

            $purchaseData = [
                'user_id' => $user->id,
                'bank_id' => $bank->id,
                'package_id' => $newPackage->id,
                'transaction_code' => $transactionCode,
                'amount' => $calculationResult['final_amount'], 
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'expires_at' => Carbon::now()->addMinutes(15),
                'is_upgrade' => true, 
                'old_package_id' => $calculationResult['old_package']['id'],
            ];

            if ($request->export_vat_invoice) {
                $purchaseData['vat_mst'] = $request->vat_mst;
                $purchaseData['vat_company'] = $request->vat_company;
                $purchaseData['vat_address'] = $request->vat_address;
            }

            if ($toolType === 'go-invoice') {
                $purchaseData['mst_limit'] = $newPackage->mst_limit;
                $purchaseData['license_fee'] = $newPackage->license_fee;
                $purchaseData['status'] = GoInvoicePurchase::STATUS_PENDING;
                $purchaseData['expires_tool'] = $calculationResult['new_expires_at'];
            } elseif ($toolType === 'go-soft') {
                $purchaseData['mst_limit'] = $newPackage->mst_limit;
                $purchaseData['status'] = GoSoftPurchase::STATUS_PENDING;
                $purchaseData['expires_tool'] = $calculationResult['new_expires_at'];
            }

            $purchase = $purchaseClass::create($purchaseData);

            $upgradeHistory = $this->upgradeService->createUpgradeHistory(
                $user->id,
                $toolType,
                $calculationResult,
                $purchase->id
            );

            if ($upgradeHistory) {
                $purchase->update(['upgrade_history_id' => $upgradeHistory->id]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'transaction_code' => $transactionCode,
                'purchase_id' => $purchase->id,
                'upgrade_history_id' => $upgradeHistory ? $upgradeHistory->id : null,
                'message' => 'Tạo đơn hàng nâng cấp thành công.'
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
            Log::error('Lỗi tạo đơn hàng nâng cấp: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo đơn hàng nâng cấp'
            ], 500);
        }
    }

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

            $isUpgrade = false;
            $upgradeCalculation = null;
            $upgradeHistory = null;

            if ($user && in_array($toolType, ['go-invoice', 'go-soft'])) {
                
                $upgradeCalculation = $this->upgradeService->calculateUpgradePrice(
                    $user->id,
                    $toolType,
                    $request->package_id
                );

                if ($upgradeCalculation && isset($upgradeCalculation['can_upgrade']) && $upgradeCalculation['can_upgrade']) {
                    $isUpgrade = true;
                } else {

                    $downgradeCalculation = $this->upgradeService->calculateDowngradePrice(
                        $user->id,
                        $toolType,
                        $request->package_id,
                        true 
                    );
                    
                    if ($downgradeCalculation && $downgradeCalculation['is_downgrade']) {

                    } else {
                        
                        $canPurchase = $this->upgradeService->canPurchaseLowerPackage(
                            $user->id,
                            $toolType,
                            $request->package_id
                        );
                        
                        if (!$canPurchase && $upgradeCalculation && isset($upgradeCalculation['can_upgrade']) && !$upgradeCalculation['can_upgrade']) {
                            DB::rollBack();
                            return response()->json([
                                'success' => false,
                                'message' => $upgradeCalculation['message'] ?? 'Không thể mua gói này.'
                            ], 400);
                        }
                    }
                }
            }

            $transactionCode = $this->generateTransactionCode($toolType);

            $bank = Bank::first();
            if (!$bank) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Chưa có ngân hàng được cấu hình.'
                ], 400);
            }

            $finalAmount = $package->price;
            $discountInfo = null;

            if ($isUpgrade) {
                
                $finalAmount = $upgradeCalculation['final_amount'];
                $discountInfo = [
                    'type' => 'upgrade',
                    'discount_percent' => $upgradeCalculation['discount_percent'],
                    'discount_amount' => $upgradeCalculation['discount_amount'],
                    'price_difference' => $upgradeCalculation['price_difference'],
                    'remaining_value' => $upgradeCalculation['remaining_value'] ?? null,
                ];
            } else {

                if (in_array($toolType, ['go-invoice', 'go-soft']) && $user) {
                    $downgradeCalculation = $this->upgradeService->calculateDowngradePrice(
                        $user->id,
                        $toolType,
                        $request->package_id,
                        true 
                    );
                    
                    if ($downgradeCalculation && $downgradeCalculation['is_downgrade']) {
                        
                        $finalAmount = $downgradeCalculation['final_amount'];
                        $discountInfo = [
                            'type' => 'downgrade',
                            'discount_percent' => $downgradeCalculation['discount_percent'],
                            'discount_amount' => $downgradeCalculation['discount_amount'],
                            'original_price' => $downgradeCalculation['original_price'],
                            'old_package' => $downgradeCalculation['old_package'],
                            'new_package' => $downgradeCalculation['new_package'],
                            'days_remaining' => $downgradeCalculation['days_remaining'],
                        ];
                    }
                }

                if (!$discountInfo && in_array($toolType, ['go-invoice', 'go-soft']) && $user) {
                    $renewalInfo = $this->upgradeService->checkRenewalPackage(
                        $user->id,
                        $toolType,
                        $request->package_id
                    );
                    
                    if ($renewalInfo && $renewalInfo['is_renewal']) {
                        
                        $finalAmount = $renewalInfo['final_amount'];
                        $discountInfo = [
                            'type' => 'renewal',
                            'discount_percent' => $renewalInfo['discount_percent'],
                            'discount_amount' => $renewalInfo['discount_amount'],
                            'original_price' => $renewalInfo['package']['price'],
                        ];
                    }
                }

                if (!$discountInfo && $user) {
                    
                    $currentUse = $this->upgradeService->getCurrentUse($user->id, $toolType);
                    $isFirstPurchase = !$currentUse || !$currentUse->package_id;
                    
                    if ($isFirstPurchase) {
                        
                        $discountPercent = $this->upgradeService->getFirstPurchaseDiscount($toolType);
                        if ($discountPercent > 0) {
                            $discountAmount = round($package->price * ($discountPercent / 100));
                            $finalAmount = round($package->price - $discountAmount);
                            $discountInfo = [
                                'type' => 'first_purchase',
                                'discount_percent' => $discountPercent,
                                'discount_amount' => $discountAmount,
                                'original_price' => $package->price,
                            ];
                        }
                    } else {

                        if (in_array($toolType, ['go-bot', 'go-quick'])) {

                            $discountPercent = $this->upgradeService->getCrossProductDiscount($toolType);
                            if ($discountPercent > 0) {
                                $discountAmount = round($package->price * ($discountPercent / 100));
                                $finalAmount = round($package->price - $discountAmount);
                                $discountInfo = [
                                    'type' => 'cross_product',
                                    'discount_percent' => $discountPercent,
                                    'discount_amount' => $discountAmount,
                                    'original_price' => $package->price,
                                ];
                            }
                        } else {

                            $hasOtherProducts = $this->upgradeService->hasAnyActiveProduct($user->id, $toolType);
                            if ($hasOtherProducts) {
                                $discountPercent = $this->upgradeService->getCrossProductDiscount($toolType);
                                if ($discountPercent > 0) {
                                    $discountAmount = round($package->price * ($discountPercent / 100));
                                    $finalAmount = round($package->price - $discountAmount);
                                    $discountInfo = [
                                        'type' => 'cross_product',
                                        'discount_percent' => $discountPercent,
                                        'discount_amount' => $discountAmount,
                                        'original_price' => $package->price,
                                    ];
                                }
                            }
                        }
                    }
                }
            }
            
            // Kiểm tra và cộng license_fee cho go-invoice lần đầu đăng ký
            $licenseFeeToAdd = 0;
            if ($toolType === 'go-invoice' && $user && !$isUpgrade) {
                $currentUse = $this->upgradeService->getCurrentUse($user->id, 'go-invoice');
                $isFirstRegistration = !$currentUse || !$currentUse->package_id;
                
                if ($isFirstRegistration && $package->license_fee) {
                    // Lần đầu đăng ký: cộng thêm license_fee vào tổng tiền (không áp dụng giảm giá)
                    $licenseFeeToAdd = $package->license_fee;
                }
            }
            
            $purchaseData = [
                'user_id' => $user ? $user->id : null,
                'bank_id' => $bank->id,
                'package_id' => $package->id,
                'transaction_code' => $transactionCode,
                'amount' => round($finalAmount + $licenseFeeToAdd),
                'discount_percent' => $discountInfo ? $discountInfo['discount_percent'] : null,
                'discount_amount' => $discountInfo ? round($discountInfo['discount_amount']) : null,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'expires_at' => Carbon::now()->addMinutes(15), 
                'is_upgrade' => $isUpgrade,
            ];

            if ($isUpgrade) {
                $purchaseData['old_package_id'] = $upgradeCalculation['old_package']['id'];
            }

            if ($request->export_vat_invoice) {
                $purchaseData['vat_mst'] = $request->vat_mst;
                $purchaseData['vat_company'] = $request->vat_company;
                $purchaseData['vat_address'] = $request->vat_address;
            }

            if ($toolType === 'go-invoice') {
                $purchaseData['mst_limit'] = $package->mst_limit;
                $purchaseData['license_fee'] = $licenseFeeToAdd > 0 ? $licenseFeeToAdd : null;
                $purchaseData['status'] = GoInvoicePurchase::STATUS_PENDING;
                
                $purchaseData['expires_tool'] = $isUpgrade ? $upgradeCalculation['new_expires_at'] : Carbon::now()->addYear();
            } elseif ($toolType === 'go-bot') {
                $purchaseData['mst_limit'] = $package->mst_limit;
                $purchaseData['status'] = GoBotPurchase::STATUS_PENDING;
            } elseif ($toolType === 'go-soft') {
                $purchaseData['mst_limit'] = $package->mst_limit;
                $purchaseData['status'] = GoSoftPurchase::STATUS_PENDING;
                
                $purchaseData['expires_tool'] = $isUpgrade ? $upgradeCalculation['new_expires_at'] : Carbon::now()->addYear();
            } elseif ($toolType === 'go-quick') {
                $purchaseData['cccd_limit'] = $package->cccd_limit;
                $purchaseData['status'] = GoQuickPurchase::STATUS_PENDING;
            }

            $purchase = $purchaseClass::create($purchaseData);

            if ($isUpgrade) {
                $upgradeHistory = $this->upgradeService->createUpgradeHistory(
                    $user->id,
                    $toolType,
                    $upgradeCalculation,
                    $purchase->id
                );

                if ($upgradeHistory) {
                    $purchase->update(['upgrade_history_id' => $upgradeHistory->id]);
                }
            }

            // Lưu thông tin mã giới thiệu nếu có
            if ($request->has('referral_code') && $request->referral_code) {
                $referrer = User::where('referral_code', $request->referral_code)
                    ->where('active', User::ACTIVE_YES)
                    ->first();

                if ($referrer) {
                    // Không cho phép tự giới thiệu chính mình
                    if (!$user || $user->id !== $referrer->id) {
                        ReferralPurchase::create([
                            'referrer_id' => $referrer->id,
                            'referred_user_id' => $user ? $user->id : null,
                            'referral_code' => $request->referral_code,
                            'tool_type' => $toolType,
                            'purchase_id' => $purchase->id,
                            'transaction_code' => $transactionCode,
                            'amount' => round($finalAmount + $licenseFeeToAdd),
                            'status' => ReferralPurchase::STATUS_PENDING,
                            'purchase_date' => Carbon::now(),
                        ]);
                    }
                }
            }

            DB::commit();

            $responseData = [
                'success' => true,
                'transaction_code' => $transactionCode,
                'purchase_id' => $purchase->id,
                'message' => $isUpgrade ? 'Tạo đơn hàng nâng cấp thành công.' : 'Tạo đơn hàng thành công.',
                'is_upgrade' => $isUpgrade,
            ];

            if ($isUpgrade && $upgradeCalculation) {
                $responseData['upgrade_info'] = [
                    'old_package_price' => $upgradeCalculation['old_package']['price'],
                    'new_package_price' => $upgradeCalculation['new_package']['price'],
                    'price_difference' => $upgradeCalculation['price_difference'],
                    'discount_percent' => $upgradeCalculation['discount_percent'],
                    'discount_amount' => $upgradeCalculation['discount_amount'],
                    'final_amount' => $upgradeCalculation['final_amount'],
                ];
            }

            return response()->json($responseData);

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

    private function generateTransactionCode($toolType)
    {
        $prefix = strtoupper(str_replace('-', '', $toolType));
        $random = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        return $prefix . $random;
    }

    public function getPaymentInfo(Request $request)
    {
        $request->validate([
            'transaction_code' => 'required|string',
            'tool_type' => 'required|in:go-invoice,go-bot,go-soft,go-quick',
        ]);

        try {
            
            $purchase = $this->getPurchaseByTransactionCode($request->transaction_code, $request->tool_type);
            
            if (!$purchase) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy đơn hàng.'
                ], 404);
            }

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
