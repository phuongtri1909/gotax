<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Support\Facades\Cache;

class ContactController extends Controller
{
    public function index()
    {
        return view('client.pages.contact');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'service' => 'required|string|max:2000',
            'captcha' => 'required|integer',
        ], [
            'name.required' => 'Họ và tên không được để trống.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không hợp lệ.',
            'phone.required' => 'Số điện thoại không được để trống.',
            'service.required' => 'Dịch vụ cần tư vấn không được để trống.',
            'captcha.required' => 'Vui lòng nhập kết quả phép tính.',
            'captcha.integer' => 'Kết quả phép tính phải là số.',
        ]);

        $captchaKey = 'contact_captcha_' . session()->getId();
        $expectedAnswer = Cache::get($captchaKey);

        if (!$expectedAnswer || $request->captcha != $expectedAnswer) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['captcha' => 'Kết quả phép tính không chính xác. Vui lòng thử lại.']);
        }

        $rateLimitKey = 'contact_rate_limit_' . $request->ip();
        $lastSentTime = Cache::get($rateLimitKey);

        if ($lastSentTime !== null) {
            $currentTimestamp = now()->timestamp;
            $elapsedSeconds = $currentTimestamp - $lastSentTime;
            $remainingSeconds = max(0, 180 - $elapsedSeconds);

            if ($remainingSeconds > 0) {
                $minutes = floor($remainingSeconds / 60);
                $seconds = $remainingSeconds % 60;
                $message = $minutes > 0 
                    ? ($seconds > 0 ? "Vui lòng đợi {$minutes} phút {$seconds} giây trước khi gửi lại." : "Vui lòng đợi {$minutes} phút trước khi gửi lại.")
                    : "Vui lòng đợi {$seconds} giây trước khi gửi lại.";
                
                return redirect()->back()
                    ->withInput()
                    ->with('error', $message);
            }
        }

        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'service' => $request->service,
            'ip_address' => $request->ip(),
        ]);

        Cache::put($rateLimitKey, now()->timestamp, now()->addMinutes(3));
        Cache::forget($captchaKey);

        return redirect()->back()
            ->with('success', 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.');
    }

    public function generateCaptcha()
    {
        $num1 = rand(1, 10);
        $num2 = rand(1, 10);
        $answer = $num1 + $num2;

        $captchaKey = 'contact_captcha_' . session()->getId();
        Cache::put($captchaKey, $answer, now()->addMinutes(10));

        return response()->json([
            'question' => "{$num1} + {$num2} = ?",
            'num1' => $num1,
            'num2' => $num2,
        ]);
    }
}
