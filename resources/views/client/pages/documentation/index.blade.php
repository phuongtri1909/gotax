@extends('client.layouts.app')

@section('title', 'Tài Liệu - ' . config('app.name'))
@section('description', 'Tài liệu hướng dẫn sử dụng các công cụ GoTax')

@section('content')
<section class="documentation-page container-page">
    <div class="container">
        <div class="documentation-header text-center mb-5">
            <h1 class="documentation-title">Tài Liệu</h1>
            <p class="documentation-subtitle">Hướng dẫn sử dụng các công cụ GoTax</p>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="documentation-list">
                    @forelse($documents as $document)
                        <div class="documentation-item">
                            <a href="{{ route('documentation.show', $document->slug) }}" class="documentation-link">
                                <h3 class="documentation-item-title">{{ $document->title }}</h3>
                                @if($document->content)
                                    <p class="documentation-item-excerpt">{{ strip_tags(html_entity_decode(substr($document->content, 0, 200), ENT_QUOTES, 'UTF-8')) }}...</p>
                                @endif
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <p class="text-muted">Chưa có tài liệu nào.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
    @vite('resources/assets/frontend/css/pages/documentation.css')
@endpush

