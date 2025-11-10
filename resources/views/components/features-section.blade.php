@props([
    'title' => 'Giải Pháp Tối Ưu Công Việc Của Bạn',
    'features' => [],
])

<section class="features-section">
    <div class="container">
        <h2 class="tools-title">{{ $title }}</h2>
        
        <div class="row g-4 mt-3">
            @foreach($features as $feature)
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="feature-card py-4 px-4">
                        <div class="feature-icon-wrapper">
                            <img src="{{ $feature['icon'] ?? '' }}" alt="{{ $feature['title'] ?? '' }}" class="feature-icon">
                        </div>
                        <h3 class="feature-title">{{ $feature['title'] ?? '' }}</h3>
                        <p class="feature-description">{{ $feature['description'] ?? '' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@push('styles')
    @vite('resources/assets/frontend/css/components/features-section.css')
@endpush

