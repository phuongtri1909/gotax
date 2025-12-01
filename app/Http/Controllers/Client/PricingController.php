<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\GoInvoicePackage;
use App\Models\GoBotPackage;
use App\Models\GoSoftPackage;
use App\Models\GoQuickPackage;
use App\Models\SeoSetting;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\SEOMeta;

class PricingController extends Controller
{
    public function index()
    {
        $seoSetting = SeoSetting::getByPageKey('pricing');
        
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
            $title = 'Bảng Giá - ' . config('app.name');
            $description = 'Xem bảng giá chi tiết cho tất cả các công cụ: Go Invoice, Go Bot, Go Soft, Go Quick.';
            
            SEOTools::setTitle($title);
            SEOTools::setDescription($description);
            SEOTools::setCanonical(url()->current());

            OpenGraph::setTitle($title);
            OpenGraph::setDescription($description);
            OpenGraph::setUrl(url()->current());
            OpenGraph::setSiteName(config('app.name'));
            OpenGraph::addProperty('type', 'website');
            OpenGraph::addProperty('locale', 'vi_VN');

            TwitterCard::setTitle($title);
            TwitterCard::setType('summary_large_image');
        }

        // Go Invoice Packages
        $goInvoicePackages = GoInvoicePackage::where('status', 'active')
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

        // Go Bot Packages
        $botFeatures = [
            'Tra hàng loạt MST cá nhân cũ → MST cá nhân mới',
            'Tra hàng loạt CCCD → MST cá nhân và doanh nghiệp',
            'Tra cứu rủi ro nhà cung cấp hàng loạt',
            'Tra địa chỉ Doanh nghiệp sau sáp nhập chuẩn xác từ TCT',
            'Hỗ trợ tra cứu file Excel và TXT nhanh chóng',
            'Trích xuất dữ liệu hàng loạt ra file Excel',
        ];

        $goBotPackages = GoBotPackage::where('status', 'active')
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

        // Go Soft Packages
        $commonFeatures = [
            'Tải tờ khai, giấy nộp tiền, thông báo hàng loạt.',
            'Thời gian tải: Tháng - Quý - Năm.',
            'Chuyển file XML thành file Excel.',
            'Tải về dữ liệu dưới dạng XML.',
            'Không giới hạn số lượng tra cứu.',
            'Không giới hạn thiết bị truy cập.',
        ];

        $goSoftPackages = GoSoftPackage::where('status', 'active')
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

        // Go Quick Packages
        $quickFeatures = [
            'Hỗ trợ tải ảnh CCCD lên để đọc dữ liệu tự động',
            'Quét và trích xuất thông tin CCCD hàng loạt ',
            'Hỗ trợ đọc đa dạng file: PDF, Excel, Folder chứa ảnh',
            'Xử lý linh hoạt phân loại mặt trước, mặt sau CCCD',
            'Tự động nhận diện thông tin khi nhập link ảnh',
            'Trích xuất dữ liệu hàng loạt ra file Excel ',
        ];

        $goQuickPackages = GoQuickPackage::where('status', 'active')
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

        return view('client.pages.pricing', compact(
            'goInvoicePackages',
            'goBotPackages',
            'botFeatures',
            'goSoftPackages',
            'goQuickPackages',
            'quickFeatures'
        ));
    }
}

