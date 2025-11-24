<?php

namespace App\Providers;

use App\Models\ContactInfo;
use App\Observers\ContactInfoObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class ContactInfoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        ContactInfo::observe(ContactInfoObserver::class);
        View::composer(
            ['client.layouts.partials.footer', 'client.pages.contact'],
            function ($view) {
                try {
                    $contactInfo = Cache::remember('contact_info', 3600, function () {
                        if (!Schema::hasTable('contact_infos')) {
                            return null;
                        }

                        return ContactInfo::first();
                    });

                    if (!$contactInfo) {
                        $contactInfo = (object) [
                            'phone' => '0989 466 992',
                            'email' => 'supportgotax@gmail.com',
                            'address' => '2321 New Design Str, Lorem Ipsum10',
                            'map_url' => null,
                            'latitude' => null,
                            'longitude' => null,
                        ];
                    }

                    $view->with('contactInfo', $contactInfo);
                } catch (\Exception $e) {
                    $contactInfo = (object) [
                        'phone' => '0989 466 992',
                        'email' => 'supportgotax@gmail.com',
                        'address' => '2321 New Design Str, Lorem Ipsum10',
                        'map_url' => null,
                        'latitude' => null,
                        'longitude' => null,
                    ];
                    $view->with('contactInfo', $contactInfo);
                }
            }
        );
    }
}

