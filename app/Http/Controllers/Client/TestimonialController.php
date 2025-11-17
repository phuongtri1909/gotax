<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function loadMore(Request $request)
    {
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 4);

        $testimonials = Testimonial::active()
            ->ordered()
            ->skip($offset)
            ->take($limit)
            ->get()
            ->map(function ($testimonial) {
                return [
                    'id' => $testimonial->id,
                    'text' => $testimonial->text,
                    'rating' => $testimonial->rating,
                    'name' => $testimonial->name,
                    'avatar' => $testimonial->avatar ? asset('storage/' . $testimonial->avatar) : asset('images/default/avatar_default.jpg'),
                ];
            });

        $hasMore = Testimonial::active()->count() > ($offset + $limit);

        return response()->json([
            'success' => true,
            'testimonials' => $testimonials,
            'hasMore' => $hasMore,
        ]);
    }
}
