<?php

namespace App\Providers;

use App\Models\Testimonial;
use App\Observers\TestimonialObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class TestimonialServiceProvider extends ServiceProvider
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
        Testimonial::observe(TestimonialObserver::class);
        View::composer(
            ['components.testimonials-section'],
            function ($view) {
                try {
                    $testimonials = Cache::remember('testimonials_initial', 3600, function () {
                        if (!Schema::hasTable('testimonials')) {
                            return [];
                        }

                        return Testimonial::active()
                            ->ordered()
                            ->take(4)
                            ->get()
                            ->map(function ($testimonial) {
                                return [
                                    'id' => $testimonial->id,
                                    'text' => $testimonial->text,
                                    'rating' => $testimonial->rating,
                                    'name' => $testimonial->name,
                                    'avatar' => $testimonial->avatar ? asset('storage/' . $testimonial->avatar) : asset('images/default/avatar_default.jpg'),
                                ];
                            })
                            ->toArray();
                    });

                    $view->with('testimonials', $testimonials);
                } catch (\Exception $e) {
                    $view->with('testimonials', []);
                }
            }
        );
    }
}
