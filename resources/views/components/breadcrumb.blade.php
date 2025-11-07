@props(['items' => []])

<div class="breadcrumb-section">
    <nav class="breadcrumb-nav">
        @foreach($items as $index => $item)
            @if($index > 0)
                <span class="breadcrumb-separator">//</span>
            @endif
            @if(isset($item['url']) && !$loop->last)
                <a href="{{ $item['url'] }}" class="breadcrumb-link">{{ $item['label'] }}</a>
            @else
                <span class="breadcrumb-current">{{ $item['label'] }}</span>
            @endif
        @endforeach
    </nav>
</div>

@push('styles')
    @vite('resources/assets/frontend/css/components/breadcrumb.css')
@endpush


