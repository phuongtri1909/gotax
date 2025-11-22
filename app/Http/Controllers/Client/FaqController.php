<?php

namespace App\Http\Controllers\Client;

use App\Models\Faq;
use App\Models\SeoSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\SEOMeta;

class FaqController extends Controller
{
    public function index()
    {
        // Load SEO settings from database for FAQs page
        $seoSetting = SeoSetting::getByPageKey('faqs');
        
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
            SEOTools::setTitle('Câu hỏi thường gặp - ' . config('app.name'));
            SEOTools::setDescription('Tìm hiểu các câu hỏi thường gặp về ' . config('app.name') . '.');
            SEOTools::setCanonical(url()->current());

            OpenGraph::setTitle('Câu hỏi thường gặp - ' . config('app.name'));
            OpenGraph::setDescription('Tìm hiểu các câu hỏi thường gặp về ' . config('app.name') . '.');
            OpenGraph::setUrl(url()->current());
            OpenGraph::setSiteName(config('app.name'));
            OpenGraph::addProperty('type', 'website');
            OpenGraph::addProperty('locale', 'vi_VN');

            TwitterCard::setTitle('Câu hỏi thường gặp - ' . config('app.name'));
            TwitterCard::setType('summary_large_image');
        }

        $faqs = Faq::active()->ordered()->paginate(30);
        return view('client.pages.faqs', compact('faqs'));
    }
}
