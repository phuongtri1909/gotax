@props(['title', 'breadcrumb' => null])

<div class="title-page-section">
    <div class="title-page-content">
        <h1 class="title-page-text">{{ $title }}</h1>
        @if($breadcrumb)
            @include('components.breadcrumb', ['items' => $breadcrumb])
        @endif
    </div>
</div>

@push('styles')
    @vite('resources/assets/frontend/css/components/title-page.css')
@endpush



