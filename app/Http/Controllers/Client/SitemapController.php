<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = [];

        // Home page
        $urls[] = [
            'loc' => url('/'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'daily',
            'priority' => '1.0'
        ];

        // Contact page
        $urls[] = [
            'loc' => route('contact'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'monthly',
            'priority' => '0.8'
        ];

        // FAQs page
        $urls[] = [
            'loc' => route('faqs'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'weekly',
            'priority' => '0.8'
        ];

        // Tool pages
        $toolPages = [
            ['route' => 'tools.go-invoice', 'priority' => '0.9'],
            ['route' => 'tools.go-bot', 'priority' => '0.9'],
            ['route' => 'tools.go-soft', 'priority' => '0.9'],
            ['route' => 'tools.go-quick', 'priority' => '0.9'],
        ];

        foreach ($toolPages as $page) {
            $urls[] = [
                'loc' => route($page['route']),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => $page['priority']
            ];
        }

        // Trial pages
        $trialPages = [
            ['route' => 'tools.trial.go-invoice', 'priority' => '0.8'],
            ['route' => 'tools.trial.go-bot', 'priority' => '0.8'],
            ['route' => 'tools.trial.go-soft', 'priority' => '0.8'],
            ['route' => 'tools.trial.go-quick', 'priority' => '0.8'],
        ];

        foreach ($trialPages as $page) {
            $urls[] = [
                'loc' => route($page['route']),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => $page['priority']
            ];
        }

        // Auth pages (public only)
        $authPages = [
            ['route' => 'login', 'priority' => '0.5'],
            ['route' => 'register', 'priority' => '0.5'],
            ['route' => 'forgot-password', 'priority' => '0.3'],
        ];

        foreach ($authPages as $page) {
            $urls[] = [
                'loc' => route($page['route']),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => $page['priority']
            ];
        }

        return response()->view('sitemap.index', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }

    public function robots()
    {
        $sitemapUrl = url('sitemap.xml');
        $content = "User-agent: *\n";
        $content .= "Allow: /\n\n";
        $content .= "# Sitemap\n";
        $content .= "Sitemap: {$sitemapUrl}\n\n";
        $content .= "# Disallow admin area\n";
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /admin\n\n";
        $content .= "# Disallow private/payment pages\n";
        $content .= "Disallow: /payment/\n";
        $content .= "Disallow: /purchase/\n";
        $content .= "Disallow: /profile\n";
        $content .= "Disallow: /account-settings\n";
        $content .= "Disallow: /change-password\n";
        $content .= "Disallow: /clear-cache\n\n";
        $content .= "# Allow public pages\n";
        $content .= "Allow: /login\n";
        $content .= "Allow: /register\n";
        $content .= "Allow: /forgot-password\n";
        $content .= "Allow: /\n";
        
        return response($content, 200)->header('Content-Type', 'text/plain');
    }
}

