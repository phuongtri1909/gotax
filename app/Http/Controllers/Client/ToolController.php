<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\GoInvoicePackage;
use App\Models\GoBotPackage;
use App\Models\GoSoftPackage;
use App\Models\GoQuickPackage;
use App\Models\SeoSetting;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\SEOMeta;

class ToolController extends Controller
{
    private function setSeoForTool($pageKey, $fallbackTitle, $fallbackDescription)
    {
        $seoSetting = SeoSetting::getByPageKey($pageKey);
        
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
            SEOTools::setTitle($fallbackTitle);
            SEOTools::setDescription($fallbackDescription);
            SEOTools::setCanonical(url()->current());

            OpenGraph::setTitle($fallbackTitle);
            OpenGraph::setDescription($fallbackDescription);
            OpenGraph::setUrl(url()->current());
            OpenGraph::setSiteName(config('app.name'));
            OpenGraph::addProperty('type', 'website');
            OpenGraph::addProperty('locale', 'vi_VN');

            TwitterCard::setTitle($fallbackTitle);
            TwitterCard::setType('summary_large_image');
        }
    }

    public function goInvoice()
    {
        $this->setSeoForTool(
            'go-invoice',
            'Go Invoice - Hóa đơn điện tử - ' . config('app.name'),
            'Go Invoice - Công cụ quản lý và tạo hóa đơn điện tử chuyên nghiệp.'
        );
        $packages = GoInvoicePackage::where('status', 'active')
            ->ordered()
            ->get()
            ->map(function ($package) {
                return (object) [
                    'id' => $package->id,
                    'name' => $package->name,
                    'price' => number_format($package->price, 0, ',', '.'),
                    'mst' => (string) $package->mst_limit,
                    'discount' => $package->discount_percent > 0 ? "Giảm {$package->discount_percent}%" : 'Ưu đãi',
                    'badge' => $package->badge,
                    'badge_type' => $package->badge === 'Most Popular' ? 'popular' : ($package->badge === 'Best Choice' ? 'choice' : null),
                    'features' => $package->features ?? [],
                    'button_text' => 'Đăng ký',
                    'button_link' => '#',
                ];
            });

        return view('client.pages.tools.go-invoice', compact('packages'));
    }

    public function goBot()
    {
        $this->setSeoForTool(
            'go-bot',
            'Go Bot - Tra cứu MST hàng loạt - ' . config('app.name'),
            'Go Bot - Công cụ tra cứu mã số thuế (MST) hàng loạt tự động.'
        );

        $botFeatures = [
            'Tra hàng loạt MST cá nhân cũ → MST cá nhân mới',
            'Tra hàng loạt CCCD → MST cá nhân và doanh nghiệp',
            'Tra cứu rủi ro nhà cung cấp hàng loạt',
            'Tra địa chỉ Doanh nghiệp sau sáp nhập chuẩn xác từ TCT',
            'Hỗ trợ tra cứu file Excel và TXT nhanh chóng',
            'Trích xuất dữ liệu hàng loạt ra file Excel',
        ];

        $botPackages = GoBotPackage::where('status', 'active')
            ->ordered()
            ->get()
            ->map(function ($package) {
                return (object) [
                    'id' => $package->id,
                    'name' => $package->name,
                    'title' => 'Số lượng MST',
                    'mst' => number_format($package->mst_limit),
                    'price' => number_format($package->price, 0, ',', '.'),
                    'discount' => $package->discount_percent > 0 ? "Giảm {$package->discount_percent}%" : 'Ưu đãi',
                ];
            });

        return view('client.pages.tools.go-bot', compact('botFeatures', 'botPackages'));
    }

    public function goSoft()
    {
        $this->setSeoForTool(
            'go-soft',
            'Go Soft - Tải tờ khai thuế - ' . config('app.name'),
            'Go Soft - Công cụ tải tờ khai, giấy nộp tiền và thông báo thuế hàng loạt.'
        );

        $commonFeatures = [
            'Tải tờ khai, giấy nộp tiền, thông báo hàng loạt.',
            'Thời gian tải: Tháng - Quý - Năm.',
            'Chuyển file XML thành file Excel.',
            'Tải về dữ liệu dưới dạng XML.',
            'Không giới hạn số lượng tra cứu.',
            'Không giới hạn thiết bị truy cập.',
        ];

        $packages = GoSoftPackage::where('status', 'active')
            ->ordered()
            ->get()
            ->map(function ($package) use ($commonFeatures) {
                return (object) [
                    'id' => $package->id,
                    'name' => $package->name,
                    'price' => number_format($package->price, 0, ',', '.'),
                    'mst' => (string) $package->mst_limit,
                    'discount' => $package->discount_percent > 0 ? "Giảm {$package->discount_percent}%" : 'Ưu đãi',
                    'badge' => $package->badge,
                    'badge_type' => $package->badge === 'Most Popular' ? 'popular' : ($package->badge === 'Best Choice' ? 'choice' : null),
                    'features' => !empty($package->features) ? $package->features : $commonFeatures,
                    'button_text' => 'Đăng ký',
                    'button_link' => '#',
                ];
            });

        return view('client.pages.tools.go-soft', compact('packages'));
    }

    public function goQuick()
    {
        $this->setSeoForTool(
            'go-quick',
            'Go Quick - Đọc CCCD tự động - ' . config('app.name'),
            'Go Quick - Công cụ đọc và trích xuất thông tin từ CCCD tự động.'
        );

        $botFeatures = [
            'Hỗ trợ tải ảnh CCCD lên để đọc dữ liệu tự động',
            'Quét và trích xuất thông tin CCCD hàng loạt ',
            'Hỗ trợ đọc đa dạng file: PDF, Excel, Folder chứa ảnh',
            'Xử lý linh hoạt phân loại mặt trước, mặt sau CCCD',
            'Tự động nhận diện thông tin khi nhập link ảnh',
            'Trích xuất dữ liệu hàng loạt ra file Excel ',
        ];

        $botPackages = GoQuickPackage::where('status', 'active')
            ->ordered()
            ->get()
            ->map(function ($package) {
                return (object) [
                    'id' => $package->id,
                    'name' => $package->name,
                    'title' => 'Số lượng CCCD',
                    'mst' => number_format($package->cccd_limit),
                    'price' => number_format($package->price, 0, ',', '.'),
                    'discount' => $package->discount_percent > 0 ? "Giảm {$package->discount_percent}%" : 'Ưu đãi',
                ];
            });

        return view('client.pages.tools.go-quick', compact('botFeatures', 'botPackages'));
    }
}
