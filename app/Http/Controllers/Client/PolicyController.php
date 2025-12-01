<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;

class PolicyController extends Controller
{
    public function index()
    {
        $policies = Policy::active()->ordered()->get();
        
        SEOTools::setTitle('Chính Sách - ' . config('app.name'));
        SEOTools::setDescription('Chính sách và quy định của GoTax');
        SEOTools::setCanonical(url()->current());
        
        return view('client.pages.policy.index', compact('policies'));
    }

    public function show($slug)
    {
        $policy = Policy::where('slug', $slug)->active()->firstOrFail();
        $policies = Policy::active()->ordered()->get();
        
        SEOTools::setTitle($policy->title . ' - Chính Sách - ' . config('app.name'));
        SEOTools::setDescription(strip_tags(substr($policy->content, 0, 160)));
        SEOTools::setCanonical(url()->current());
        
        return view('client.pages.policy.show', compact('policy', 'policies'));
    }
}
