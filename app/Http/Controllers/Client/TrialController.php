<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Trial;
use App\Models\TrialRegistration;
use App\Models\GoInvoiceUse;
use App\Models\GoBotUse;
use App\Models\GoSoftUse;
use App\Models\GoQuickUse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\TrialVerificationMail;
use Carbon\Carbon;

class TrialController extends Controller
{
    /**
     * Hiển thị trang đăng ký dùng thử
     */
    public function index(Request $request)
    {
        // Get toolType from route name
        $routeName = $request->route()->getName();
        $toolType = null;
        
        if (str_contains($routeName, 'go-invoice')) {
            $toolType = Trial::TOOL_INVOICE;
        } elseif (str_contains($routeName, 'go-bot')) {
            $toolType = Trial::TOOL_BOT;
        } elseif (str_contains($routeName, 'go-soft')) {
            $toolType = Trial::TOOL_SOFT;
        } elseif (str_contains($routeName, 'go-quick')) {
            $toolType = Trial::TOOL_QUICK;
        }
        
        // Validate tool type
        $validToolTypes = [Trial::TOOL_INVOICE, Trial::TOOL_BOT, Trial::TOOL_SOFT, Trial::TOOL_QUICK];
        if (!in_array($toolType, $validToolTypes)) {
            abort(404);
        }

        $trial = Trial::getByToolType($toolType);
        if (!$trial) {
            abort(404, 'Chương trình dùng thử chưa được cấu hình');
        }

        $user = Auth::user();
        $hasTrial = false;

        if ($user) {
            // Check if user already has trial
            $hasTrial = $this->checkUserHasTrial($user->id, $toolType);
        }

        $toolNames = [
            Trial::TOOL_INVOICE => 'Go Invoice',
            Trial::TOOL_BOT => 'Go Bot',
            Trial::TOOL_SOFT => 'Go Soft',
            Trial::TOOL_QUICK => 'Go Quick',
        ];

        return view('client.pages.tools.trial', [
            'toolType' => $toolType,
            'toolName' => $toolNames[$toolType] ?? 'Tool',
            'trial' => $trial,
            'user' => $user,
            'hasTrial' => $hasTrial,
        ]);
    }

    /**
     * Đăng ký dùng thử
     */
    public function register(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để đăng ký dùng thử.'
            ], 401);
        }

        // Get toolType from route name
        $routeName = $request->route()->getName();
        $toolType = null;
        
        if (str_contains($routeName, 'go-invoice')) {
            $toolType = Trial::TOOL_INVOICE;
        } elseif (str_contains($routeName, 'go-bot')) {
            $toolType = Trial::TOOL_BOT;
        } elseif (str_contains($routeName, 'go-soft')) {
            $toolType = Trial::TOOL_SOFT;
        } elseif (str_contains($routeName, 'go-quick')) {
            $toolType = Trial::TOOL_QUICK;
        }
        
        // Validate tool type
        $validToolTypes = [Trial::TOOL_INVOICE, Trial::TOOL_BOT, Trial::TOOL_SOFT, Trial::TOOL_QUICK];
        if (!in_array($toolType, $validToolTypes)) {
            return response()->json([
                'success' => false,
                'message' => 'Tool không hợp lệ.'
            ], 404);
        }

        // Check if user already has verified trial
        if ($this->checkUserHasTrial($user->id, $toolType)) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã đăng ký dùng thử tool này rồi.'
            ], 400);
        }

        // Check if user has pending verification
        $pendingRegistration = TrialRegistration::where('user_id', $user->id)
            ->where('tool_type', $toolType)
            ->whereNull('verified_at')
            ->first();
            
        if ($pendingRegistration) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã có đăng ký dùng thử đang chờ xác thực. Vui lòng kiểm tra email để xác thực.'
            ], 400);
        }

        $trial = Trial::getByToolType($toolType);
        if (!$trial) {
            return response()->json([
                'success' => false,
                'message' => 'Chương trình dùng thử chưa được cấu hình.'
            ], 404);
        }

        DB::beginTransaction();
        try {
            // Tạo verification token
            $verificationToken = Str::random(60);
            
            // Lưu vào TrialRegistration với verification_token (chưa verified)
            $trialRegistration = TrialRegistration::create([
                'user_id' => $user->id,
                'tool_type' => $toolType,
                'is_read' => false,
                'verification_token' => $verificationToken,
                'verified_at' => null,
            ]);

            // Gửi email xác thực
            $toolNames = [
                Trial::TOOL_INVOICE => 'Go Invoice',
                Trial::TOOL_BOT => 'Go Bot',
                Trial::TOOL_SOFT => 'Go Soft',
                Trial::TOOL_QUICK => 'Go Quick',
            ];
            
            $verificationUrl = route('tools.trial.verify', [
                'token' => $verificationToken,
                'email' => $user->email
            ]);
            
            Mail::to($user->email)->send(new TrialVerificationMail(
                $user,
                $toolNames[$toolType] ?? $toolType,
                $verificationUrl
            ));

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Chúng tôi đã gửi email xác thực đến địa chỉ email của bạn. Vui lòng kiểm tra hộp thư và nhấp vào liên kết để hoàn tất đăng ký.',
                'email' => $user->email
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi đăng ký dùng thử: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'tool_type' => $toolType,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đăng ký dùng thử.'
            ], 500);
        }
    }

    /**
     * Check if user already has trial (verified)
     */
    private function checkUserHasTrial($userId, $toolType)
    {
        // Check if user has verified trial registration
        $hasVerifiedRegistration = TrialRegistration::where('user_id', $userId)
            ->where('tool_type', $toolType)
            ->whereNotNull('verified_at')
            ->exists();
            
        if ($hasVerifiedRegistration) {
            return true;
        }
        
        // Also check if user has Use record (for backward compatibility)
        switch ($toolType) {
            case Trial::TOOL_INVOICE:
                return GoInvoiceUse::where('user_id', $userId)->exists();
            case Trial::TOOL_BOT:
                return GoBotUse::where('user_id', $userId)->exists();
            case Trial::TOOL_SOFT:
                return GoSoftUse::where('user_id', $userId)->exists();
            case Trial::TOOL_QUICK:
                return GoQuickUse::where('user_id', $userId)->exists();
            default:
                return false;
        }
    }

    /**
     * Create GoInvoice trial
     */
    private function createGoInvoiceTrial($userId, $trial)
    {
        $expiresAt = $trial->expires_days 
            ? Carbon::now()->addDays($trial->expires_days) 
            : null;

        GoInvoiceUse::create([
            'user_id' => $userId,
            'package_id' => null,
            'mst_limit' => $trial->mst_limit ?? 1,
            'expires_at' => $expiresAt,
        ]);
    }

    /**
     * Create GoBot trial
     */
    private function createGoBotTrial($userId, $trial)
    {
        GoBotUse::create([
            'user_id' => $userId,
            'package_id' => null,
            'mst_limit' => $trial->mst_limit ?? 10,
        ]);
    }

    /**
     * Create GoSoft trial
     */
    private function createGoSoftTrial($userId, $trial)
    {
        $expiresAt = $trial->expires_days 
            ? Carbon::now()->addDays($trial->expires_days) 
            : null;

        GoSoftUse::create([
            'user_id' => $userId,
            'package_id' => null,
            'mst_limit' => $trial->mst_limit ?? 1,
            'expires_at' => $expiresAt,
        ]);
    }

    /**
     * Create GoQuick trial
     */
    private function createGoQuickTrial($userId, $trial)
    {
        GoQuickUse::create([
            'user_id' => $userId,
            'package_id' => null,
            'cccd_limit' => $trial->cccd_limit ?? 10,
        ]);
    }

    /**
     * Xác thực đăng ký dùng thử
     */
    public function verify(Request $request, $token, $email)
    {
        try {
            $trialRegistration = TrialRegistration::where('verification_token', $token)
                ->whereHas('user', function($query) use ($email) {
                    $query->where('email', $email);
                })
                ->first();

            if (!$trialRegistration) {
                return redirect()->route('tools.go-invoice')
                    ->with('error', 'Liên kết xác thực không hợp lệ hoặc đã hết hạn.');
            }

            if ($trialRegistration->verified_at) {
                return redirect()->route('tools.go-invoice')
                    ->with('info', 'Đăng ký dùng thử đã được xác thực trước đó.');
            }

            // Kiểm tra xem user đã có trial chưa (có thể đã tạo từ lần verify khác)
            $hasTrial = $this->checkUserHasTrial($trialRegistration->user_id, $trialRegistration->tool_type);
            
            if (!$hasTrial) {
                // Lấy thông tin trial config
                $trial = Trial::getByToolType($trialRegistration->tool_type);
                
                if ($trial) {
                    DB::beginTransaction();
                    try {
                        // Tạo Use record
                        switch ($trialRegistration->tool_type) {
                            case Trial::TOOL_INVOICE:
                                $this->createGoInvoiceTrial($trialRegistration->user_id, $trial);
                                break;
                            case Trial::TOOL_BOT:
                                $this->createGoBotTrial($trialRegistration->user_id, $trial);
                                break;
                            case Trial::TOOL_SOFT:
                                $this->createGoSoftTrial($trialRegistration->user_id, $trial);
                                break;
                            case Trial::TOOL_QUICK:
                                $this->createGoQuickTrial($trialRegistration->user_id, $trial);
                                break;
                        }
                        
                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollBack();
                        Log::error('Lỗi tạo trial use: ' . $e->getMessage(), [
                            'trial_registration_id' => $trialRegistration->id,
                            'trace' => $e->getTraceAsString()
                        ]);
                        
                        return redirect()->route('tools.go-invoice')
                            ->with('error', 'Có lỗi xảy ra khi kích hoạt dùng thử. Vui lòng thử lại sau.');
                    }
                }
            }

            // Đánh dấu đã xác thực
            $trialRegistration->verified_at = Carbon::now();
            $trialRegistration->verification_token = null;
            $trialRegistration->save();

            $toolNames = [
                Trial::TOOL_INVOICE => 'Go Invoice',
                Trial::TOOL_BOT => 'Go Bot',
                Trial::TOOL_SOFT => 'Go Soft',
                Trial::TOOL_QUICK => 'Go Quick',
            ];

            $toolName = $toolNames[$trialRegistration->tool_type] ?? $trialRegistration->tool_type;

            return redirect()->route('tools.go-invoice')
                ->with('success', "Xác thực đăng ký dùng thử {$toolName} thành công! Bạn có thể bắt đầu sử dụng ngay.");
                
        } catch (\Exception $e) {
            Log::error('Lỗi xác thực trial: ' . $e->getMessage(), [
                'token' => $token,
                'email' => $email,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('tools.go-invoice')
                ->with('error', 'Có lỗi xảy ra khi xác thực. Vui lòng thử lại sau.');
        }
    }
}
