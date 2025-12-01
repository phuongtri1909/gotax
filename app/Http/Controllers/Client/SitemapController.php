<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Document;
use App\Models\Policy;

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

        // Pricing page
        $urls[] = [
            'loc' => route('pricing'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'weekly',
            'priority' => '0.9'
        ];

        // Documentation index page
        $urls[] = [
            'loc' => route('documentation.index'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'weekly',
            'priority' => '0.8'
        ];

        // Documentation detail pages
        $documents = Document::active()->get();
        foreach ($documents as $document) {
            $urls[] = [
                'loc' => route('documentation.show', $document->slug),
                'lastmod' => $document->updated_at->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.7'
            ];
        }

        // Policy index page
        $urls[] = [
            'loc' => route('policy.index'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'weekly',
            'priority' => '0.8'
        ];

        // Policy detail pages
        $policies = Policy::active()->get();
        foreach ($policies as $policy) {
            $urls[] = [
                'loc' => route('policy.show', $policy->slug),
                'lastmod' => $policy->updated_at->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.7'
            ];
        }

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

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= view('sitemap.index', compact('urls'))->render();
        
        return response($xml, 200)
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

