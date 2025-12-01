<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;

class DocumentationController extends Controller
{
    public function index()
    {
        $documents = Document::active()->ordered()->get();
        
        SEOTools::setTitle('Tài Liệu - ' . config('app.name'));
        SEOTools::setDescription('Tài liệu hướng dẫn sử dụng các công cụ GoTax');
        SEOTools::setCanonical(url()->current());
        
        return view('client.pages.documentation.index', compact('documents'));
    }

    public function show($slug)
    {
        $document = Document::where('slug', $slug)->active()->firstOrFail();
        $documents = Document::active()->ordered()->get();
        
        SEOTools::setTitle($document->title . ' - Tài Liệu - ' . config('app.name'));
        SEOTools::setDescription(strip_tags(substr($document->content, 0, 160)));
        SEOTools::setCanonical(url()->current());
        
        return view('client.pages.documentation.show', compact('document', 'documents'));
    }
}
