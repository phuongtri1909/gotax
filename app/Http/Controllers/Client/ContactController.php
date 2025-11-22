<?php

namespace App\Http\Controllers\Client;

use App\Models\Contact;
use App\Models\SeoSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\SEOMeta;

class ContactController extends Controller
{
    public function index()
    {
        // Load SEO settings from database for contact page
        $seoSetting = SeoSetting::getByPageKey('contact');
        
        if ($seoSetting) {
            SEOTools::setTitle($seoSetting->title);
            SEOTools::setDescription($seoSetting->description);
            SEOMeta::setKeywords($seoSetting->keywords);
            SEOTools::setCanonical(url()->current());

            OpenGraph::setTitle($seoSetting->title);
            OpenGraph::setDescription($seoSetting->description);
            OpenGraph::setUrl(url()->current());
            OpenGraph::setSiteName(config('app.name'));
            OpenGraph::addProperty('type', 'website');
            OpenGraph::addProperty('locale', 'vi_VN');
            if ($seoSetting->thumbnail) {
                OpenGraph::addImage($seoSetting->thumbnail_url);
            }

            TwitterCard::setTitle($seoSetting->title);
            TwitterCard::setDescription($seoSetting->description);
            TwitterCard::setType('summary_large_image');
            if ($seoSetting->thumbnail) {
                TwitterCard::addImage($seoSetting->thumbnail_url);
            }
        } else {
            // Fallback SEO
            SEOTools::setTitle('Liên hệ - ' . config('app.name'));
            SEOTools::setDescription('Liên hệ với ' . config('app.name') . ' để được tư vấn và hỗ trợ.');
            SEOTools::setCanonical(url()->current());

            OpenGraph::setTitle('Liên hệ - ' . config('app.name'));
            OpenGraph::setDescription('Liên hệ với ' . config('app.name') . ' để được tư vấn và hỗ trợ.');
            OpenGraph::setUrl(url()->current());
            OpenGraph::setSiteName(config('app.name'));
            OpenGraph::addProperty('type', 'website');
            OpenGraph::addProperty('locale', 'vi_VN');

            TwitterCard::setTitle('Liên hệ - ' . config('app.name'));
            TwitterCard::setType('summary_large_image');
        }

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
