<?php

namespace App\Http\Controllers\Client;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Mail\AccountActivationMail;
use App\Mail\PasswordResetMail;
use App\Mail\PasswordChangeConfirmationMail;

class AuthController extends Controller
{
    /**
     * Tạo mã giới thiệu duy nhất
     * 
     * @return string
     */
    private function generateUniqueReferralCode()
    {
        $user = new User();
        $maxAttempts = 100;
        $attempts = 0;

        do {
            $referralCode = $user->generateReferralCode();
            $exists = User::where('referral_code', $referralCode)->exists();
            $attempts++;
        } while ($exists && $attempts < $maxAttempts);

        if ($attempts >= $maxAttempts) {
            $referralCode = $user->generateReferralCode() . substr(time(), -4);
        }

        return $referralCode;
    }

    /**
     * Kiểm tra rate limit cho việc gửi email
     * 
     * @param string $email
     * @param string $action (register, forgot-password, change-password)
     * @return array ['allowed' => bool, 'remaining_seconds' => int]
     */
    private function checkEmailRateLimit($email, $action)
    {
        $cacheKey = "email_rate_limit_{$action}_{$email}";
        $lastSentTimestamp = Cache::get($cacheKey);
        
        if ($lastSentTimestamp !== null) {
            $currentTimestamp = now()->timestamp;
            $elapsedSeconds = $currentTimestamp - $lastSentTimestamp;
            $remainingSeconds = max(0, 180 - $elapsedSeconds);
            
            if ($remainingSeconds > 0) {
                return [
                    'allowed' => false,
                    'remaining_seconds' => $remainingSeconds
                ];
            }
        }
        
        Cache::put($cacheKey, now()->timestamp, now()->addMinutes(3));
        
        return [
            'allowed' => true,
            'remaining_seconds' => 0
        ];
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không hợp lệ.',
            'password.required' => 'Mật khẩu không được để trống.',
        ]);

        try {

            $oldSessionId = session()->getId();

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return redirect()->back()->withInput()->withErrors([
                    'email' => 'Tài khoản không tồn tại.',
                ]);
            }

            if ($user->active == false) {
                return redirect()->back()->withInput()->withErrors([
                    'email' => 'Tài khoản chưa được kích hoạt.',
                ]);
            }

            if (!password_verify($request->password, $user->password)) {
                return redirect()->back()->withInput()->withErrors([
                    'email' => 'Mật khẩu không chính xác.',
                ]);
            }


            Auth::login($user);

            return redirect()->route('dashboard');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Đã xảy ra lỗi khi đăng nhập. Vui lòng thử lại sau.');
        }
    }

    public function logout(Request $request)
    {

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Họ và tên không được để trống.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email này đã được sử dụng.',
            'phone.required' => 'Số điện thoại không được để trống.',
            'password.required' => 'Mật khẩu không được để trống.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ]);

        try {
            $rateLimit = $this->checkEmailRateLimit($request->email, 'register');
            if (!$rateLimit['allowed']) {
                $remainingSeconds = $rateLimit['remaining_seconds'];
                $minutes = floor($remainingSeconds / 60);
                $seconds = $remainingSeconds % 60;
                
                if ($minutes > 0) {
                    $message = $seconds > 0 
                        ? "Vui lòng đợi {$minutes} phút {$seconds} giây trước khi thử lại."
                        : "Vui lòng đợi {$minutes} phút trước khi thử lại.";
                } else {
                    $message = "Vui lòng đợi {$seconds} giây trước khi thử lại.";
                }
                
                return redirect()->back()->withInput()->with('error', $message);
            }

            $activationKey = Str::random(60);

            $referralCode = $this->generateUniqueReferralCode();

            $user = User::create([
                'full_name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => User::ROLE_USER,
                'active' => User::ACTIVE_NO,
                'key_active' => $activationKey,
                'referral_code' => $referralCode,
            ]);

            $activationUrl = route('verify-account', ['key' => $activationKey, 'email' => $user->email]);

            Mail::to($user->email)->send(new AccountActivationMail($user, $activationUrl));

            return redirect()->route('login')->with('success', 'Đăng ký thành công! Vui lòng kiểm tra email để kích hoạt tài khoản.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Đã xảy ra lỗi khi đăng ký. Vui lòng thử lại sau.');
        }
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không hợp lệ.',
        ]);

        try {
            $rateLimit = $this->checkEmailRateLimit($request->email, 'forgot-password');
            if (!$rateLimit['allowed']) {
                $remainingSeconds = $rateLimit['remaining_seconds'];
                $minutes = floor($remainingSeconds / 60);
                $seconds = $remainingSeconds % 60;
                
                if ($minutes > 0) {
                    $message = $seconds > 0 
                        ? "Vui lòng đợi {$minutes} phút {$seconds} giây trước khi thử lại."
                        : "Vui lòng đợi {$minutes} phút trước khi thử lại.";
                } else {
                    $message = "Vui lòng đợi {$seconds} giây trước khi thử lại.";
                }
                
                return redirect()->back()->withInput()->with('error', $message);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return redirect()->back()->with('success', 'Nếu email tồn tại, chúng tôi đã gửi liên kết đặt lại mật khẩu đến email của bạn.');
            }

            if (!$user->isActive()) {
                return redirect()->back()->withInput()->with('error', 'Tài khoản của bạn chưa được kích hoạt. Vui lòng kiểm tra email để kích hoạt tài khoản trước khi đặt lại mật khẩu.');
            }

            $resetKey = Str::random(60);

            $user->key_reset_password = $resetKey;
            $user->reset_password_at = now();
            $user->save();

            $resetUrl = route('verify-reset-password', ['key' => $resetKey, 'email' => $user->email]);

            Mail::to($user->email)->send(new PasswordResetMail($user, $resetUrl));

            return redirect()->back()->with('success', 'Nếu email tồn tại, chúng tôi đã gửi liên kết đặt lại mật khẩu đến email của bạn.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Đã xảy ra lỗi. Vui lòng thử lại sau.');
        }
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required' => 'Mật khẩu không được để trống.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ]);

        try {
            if (!Auth::check()) {
                $key = $request->input('key');
                $email = $request->input('email');

                if (!$key || !$email) {
                    return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để đổi mật khẩu.');
                }

                $user = User::where('email', $email)
                    ->where('key_reset_password', $key)
                    ->first();

                if (!$user) {
                    return redirect()->route('login')->with('error', 'Liên kết không hợp lệ hoặc đã hết hạn.');
                }

                if (!$user->isActive()) {
                    return redirect()->route('login')->with('error', 'Tài khoản của bạn chưa được kích hoạt. Vui lòng kiểm tra email để kích hoạt tài khoản trước khi đổi mật khẩu.');
                }

                if ($user->reset_password_at && now()->diffInMinutes($user->reset_password_at) > 60) {
                    return redirect()->route('login')->with('error', 'Liên kết đã hết hạn. Vui lòng yêu cầu lại.');
                }

                $user->password = Hash::make($request->password);
                $user->key_reset_password = null;
                $user->reset_password_at = null;
                $user->save();

                return redirect()->route('login')->with('success', 'Đổi mật khẩu thành công! Vui lòng đăng nhập với mật khẩu mới.');
            }
            
            $user = Auth::user();

            if (!$user->isActive()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->with('error', 'Tài khoản của bạn chưa được kích hoạt. Vui lòng kiểm tra email để kích hoạt tài khoản.');
            }

            $rateLimit = $this->checkEmailRateLimit($user->email, 'change-password');
            if (!$rateLimit['allowed']) {
                $remainingSeconds = $rateLimit['remaining_seconds'];
                $minutes = floor($remainingSeconds / 60);
                $seconds = $remainingSeconds % 60;
                
                if ($minutes > 0) {
                    $message = $seconds > 0 
                        ? "Vui lòng đợi {$minutes} phút {$seconds} giây trước khi thử lại."
                        : "Vui lòng đợi {$minutes} phút trước khi thử lại.";
                } else {
                    $message = "Vui lòng đợi {$seconds} giây trước khi thử lại.";
                }
                
                return redirect()->back()->withInput()->with('error', $message);
            }

            $changeKey = Str::random(60);

            $hashedPassword = Hash::make($request->password);

            $user->key_change_password = $changeKey;
            $user->temp_password_hash = $hashedPassword;
            $user->save();

            $confirmationUrl = route('verify-change-password', ['key' => $changeKey, 'email' => $user->email]);

            Mail::to($user->email)->send(new PasswordChangeConfirmationMail($user, $confirmationUrl));

            return redirect()->back()->with('success', 'Vui lòng kiểm tra email để xác nhận đổi mật khẩu.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Đã xảy ra lỗi. Vui lòng thử lại sau.');
        }
    }

    public function resendActivationEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không hợp lệ.',
        ]);

        try {
            $rateLimit = $this->checkEmailRateLimit($request->email, 'resend-activation');
            if (!$rateLimit['allowed']) {
                $remainingSeconds = $rateLimit['remaining_seconds'];
                $minutes = floor($remainingSeconds / 60);
                $seconds = $remainingSeconds % 60;
                
                if ($minutes > 0) {
                    $message = $seconds > 0 
                        ? "Vui lòng đợi {$minutes} phút {$seconds} giây trước khi thử lại."
                        : "Vui lòng đợi {$minutes} phút trước khi thử lại.";
                } else {
                    $message = "Vui lòng đợi {$seconds} giây trước khi thử lại.";
                }
                
                return redirect()->back()->withInput()->with('error', $message);
            }

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return redirect()->back()->with('success', 'Nếu email tồn tại và chưa được kích hoạt, chúng tôi đã gửi lại email kích hoạt đến email của bạn.');
            }

            if ($user->isActive()) {
                return redirect()->back()->with('info', 'Tài khoản của bạn đã được kích hoạt. Vui lòng đăng nhập.');
            }

            $activationKey = Str::random(60);

            $user->key_active = $activationKey;
            $user->save();

            $activationUrl = route('verify-account', ['key' => $activationKey, 'email' => $user->email]);

            Mail::to($user->email)->send(new AccountActivationMail($user, $activationUrl));

            return redirect()->back()->with('success', 'Chúng tôi đã gửi lại email kích hoạt đến địa chỉ email của bạn. Vui lòng kiểm tra hộp thư.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Đã xảy ra lỗi. Vui lòng thử lại sau.');
        }
    }

    public function verifyAccount(Request $request, $key, $email)
    {
        try {
            $user = User::where('email', $email)
                ->where('key_active', $key)
                ->first();

            if (!$user) {
                return redirect()->route('login')->with('error', 'Liên kết kích hoạt không hợp lệ hoặc đã hết hạn.');
            }

            if ($user->active == User::ACTIVE_YES) {
                return redirect()->route('login')->with('info', 'Tài khoản của bạn đã được kích hoạt. Vui lòng đăng nhập.');
            }

            $user->active = User::ACTIVE_YES;
            $user->key_active = null;
            $user->email_verified_at = now();
            $user->save();

            return redirect()->route('login')->with('success', 'Kích hoạt tài khoản thành công! Vui lòng đăng nhập.');
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Đã xảy ra lỗi khi kích hoạt tài khoản. Vui lòng thử lại sau.');
        }
    }

    public function verifyResetPassword(Request $request, $key, $email)
    {
        try {
            $user = User::where('email', $email)
                ->where('key_reset_password', $key)
                ->first();

            if (!$user) {
                return redirect()->route('login')->with('error', 'Liên kết không hợp lệ hoặc đã hết hạn.');
            }

            if (!$user->reset_password_at || now()->diffInMinutes($user->reset_password_at) > 60) {
                $user->key_reset_password = null;
                $user->reset_password_at = null;
                $user->save();
                return redirect()->route('login')->with('error', 'Liên kết đã hết hạn. Vui lòng yêu cầu lại.');
            }

            return redirect()->route('change-password')->with([
                'reset_key' => $key,
                'reset_email' => $email,
            ]);
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Đã xảy ra lỗi. Vui lòng thử lại sau.');
        }
    }

    /**
     * Upload user avatar
     */
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ], [
            'avatar.required' => 'Vui lòng chọn ảnh đại diện.',
            'avatar.image' => 'File phải là hình ảnh.',
            'avatar.mimes' => 'Ảnh phải có định dạng: jpeg, png, jpg, gif.',
            'avatar.max' => 'Kích thước ảnh không được vượt quá 5MB.',
        ]);

        try {
            $user = Auth::user();

            if ($request->hasFile('avatar')) {
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }

                $avatarPath = $this->processAndSaveAvatar($request->file('avatar'));
                
                $user->avatar = $avatarPath;
                $user->save();

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Đã cập nhật ảnh đại diện thành công!',
                        'avatar_url' => Storage::url($avatarPath)
                    ]);
                }

                return redirect()->route('profile')
                    ->with('success', 'Đã cập nhật ảnh đại diện thành công!');
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy file ảnh.'
                ], 400);
            }

            return redirect()->back()
                ->with('error', 'Không tìm thấy file ảnh.');
        } catch (Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đã xảy ra lỗi khi upload ảnh. Vui lòng thử lại sau.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Đã xảy ra lỗi khi upload ảnh. Vui lòng thử lại sau.');
        }
    }

    /**
     * Process and save avatar
     */
    private function processAndSaveAvatar($imageFile)
    {
        $now = Carbon::now();
        $yearMonth = $now->format('Y/m');
        $timestamp = $now->format('YmdHis');
        $randomString = Str::random(8);
        $fileName = "{$timestamp}_{$randomString}";

        Storage::disk('public')->makeDirectory("avatars/{$yearMonth}");

        $avatarImage = Image::make($imageFile);
        $avatarImage->fit(300, 300);
        $avatarImage->encode('jpg', 85);
        
        $avatarPath = "avatars/{$yearMonth}/{$fileName}.jpg";
        Storage::disk('public')->put($avatarPath, $avatarImage->stream());

        return $avatarPath;
    }

    /**
     * Update user profile (full_name and phone)
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ], [
            'full_name.required' => 'Họ và tên không được để trống.',
            'phone.required' => 'Số điện thoại không được để trống.',
        ]);

        try {
            $user = Auth::user();
            
            $user->update([
                'full_name' => $request->full_name,
                'phone' => $request->phone,
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đã cập nhật thông tin thành công!'
                ]);
            }

            return redirect()->route('profile')
                ->with('success', 'Đã cập nhật thông tin thành công!');
        } catch (Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đã xảy ra lỗi khi cập nhật thông tin. Vui lòng thử lại sau.'
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Đã xảy ra lỗi khi cập nhật thông tin. Vui lòng thử lại sau.');
        }
    }

    public function verifyChangePassword(Request $request, $key, $email)
    {
        try {
            $user = User::where('email', $email)
                ->where('key_change_password', $key)
                ->first();

            if (!$user) {
                return redirect()->route('login')->with('error', 'Liên kết không hợp lệ hoặc đã hết hạn.');
            }

            if (!$user->temp_password_hash) {
                return redirect()->route('login')->with('error', 'Liên kết đã hết hạn. Vui lòng thử lại.');
            }

            $user->password = $user->temp_password_hash;
            $user->key_change_password = null;
            $user->temp_password_hash = null;
            $user->save();

            if (Auth::check() && Auth::id() == $user->id) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            return redirect()->route('login')->with('success', 'Đổi mật khẩu thành công! Vui lòng đăng nhập với mật khẩu mới.');
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Đã xảy ra lỗi khi đổi mật khẩu. Vui lòng thử lại sau.');
        }
    }

}
