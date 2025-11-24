<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeoSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_key',
        'title',
        'description',
        'keywords',
        'thumbnail',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Scope for active SEO settings
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get SEO setting by page key
     */
    public static function getByPageKey($pageKey)
    {
        return static::where('page_key', $pageKey)->active()->first();
    }

    /**
     * Get thumbnail URL
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }
        return asset('images/d/Thumbnail.png'); 
    }

    /**
     * Get all page keys
     */
    public static function getPageKeys()
    {
        return [
            'home' => 'Trang chủ',
            'contact' => 'Trang liên hệ',
            'faqs' => 'Câu hỏi thường gặp',
            'go-invoice' => 'Go Invoice - Hóa đơn điện tử',
            'go-bot' => 'Go Bot - Tra cứu MST hàng loạt',
            'go-soft' => 'Go Soft - Tải tờ khai thuế',
            'go-quick' => 'Go Quick - Đọc CCCD tự động',
            'go-invoice-trial' => 'Đăng ký dùng thử Go Invoice',
            'go-bot-trial' => 'Đăng ký dùng thử Go Bot',
            'go-soft-trial' => 'Đăng ký dùng thử Go Soft',
            'go-quick-trial' => 'Đăng ký dùng thử Go Quick',
            'profile' => 'Hồ sơ cá nhân',
            'login' => 'Đăng nhập',
            'register' => 'Đăng ký tài khoản',
            'forgot-password' => 'Quên mật khẩu',
            'account-settings' => 'Thiết lập tài khoản',
        ];
    }

    /**
     * Get SEO data for blog post
     */
    public static function getBlogSeo($blog, $baseSeo = null)
    {
        $title = $blog->title;
        $description = strip_tags($blog->content);
        $description = strlen($description) > 160 ? substr($description, 0, 160) . '...' : $description;
        
        // Combine blog keywords with base keywords
        $keywords = $blog->title;
        if ($blog->category) {
            $keywords .= ', ' . $blog->category->name;
        }
        if ($baseSeo && $baseSeo->keywords) {
            $keywords .= ', ' . $baseSeo->keywords;
        }
        $keywords .= ', ' . config('app.name');

        return (object) [
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
            'thumbnail' => $blog->image ? asset('storage/' . $blog->image) : ($baseSeo ? $baseSeo->thumbnail_url : asset('images/d/Thumbnail.png'))
        ];
    }

    /**
     * Get SEO data for project
     */
    public static function getProjectSeo($project, $baseSeo = null)
    {
        $title = $project->title;
        $description = strip_tags($project->description);
        $description = strlen($description) > 160 ? substr($description, 0, 160) . '...' : $description;
        
        // Combine project keywords with base keywords
        $keywords = $project->title;
        if ($baseSeo && $baseSeo->keywords) {
            $keywords .= ', ' . $baseSeo->keywords;
        }
        $keywords .= ', ' . config('app.name') . ', dự án';

        return (object) [
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
            'thumbnail' => $project->hero_image ? asset('storage/' . $project->hero_image) : ($baseSeo ? $baseSeo->thumbnail_url : asset('images/d/Thumbnail.png'))
        ];
    }
}
