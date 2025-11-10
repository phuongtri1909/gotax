@php
    $banners = $banners ?? [];
@endphp

<section class="banner-home">
    <div class="banner-slider">
        @foreach ($banners as $index => $banner)
            <div class="banner-slide {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index }}">
                <div class="banner-background" 
                     style="background-image: url('{{ $banner['image'] }}');">
                    @if(isset($banner['overlay_opacity']) && $banner['overlay_opacity'] > 0)
                        <div class="banner-overlay"></div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="banner-content-wrapper">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="banner-content">
                        @foreach ($banners as $index => $banner)
                            <div class="content-item {{ $index === 0 ? 'active' : '' }}" data-content="{{ $index }}">

                                <h1 class="banner-title"><span class="banner-name">{{ empty($banner['name']) ? '' : $banner['name'] . ' ' }}</span> <span class="banner-title">{{ $banner['title'] ? $banner['title'] : '' }}</span></h1>
                                <p class="banner-subtitle">{{ $banner['subtitle'] }}</p>
                                <a href="{{ $banner['button_link'] }}" class="btn-banner btn-sm btn px-4 py-2">
                                    {{ $banner['button_text'] }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
    @vite('resources/assets/frontend/css/components/banner-home.css')
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.banner-slide');
            const contentItems = document.querySelectorAll('.content-item');
            let currentSlide = 0;
            const totalSlides = slides.length;
            const slideInterval = 5000; // 5 seconds

            function showSlide(index) {
                // Remove active class from all slides
                slides.forEach(slide => slide.classList.remove('active'));
                contentItems.forEach(item => item.classList.remove('active'));

                // Add active class to current slide
                if (slides[index]) slides[index].classList.add('active');
                if (contentItems[index]) contentItems[index].classList.add('active');

                currentSlide = index;
            }

            function nextSlide() {
                const next = (currentSlide + 1) % totalSlides;
                showSlide(next);
            }

            // Auto slide
            setInterval(nextSlide, slideInterval);

            // Optional: Pause on hover
            const bannerSection = document.querySelector('.banner-home');
            let autoSlideInterval = setInterval(nextSlide, slideInterval);

            bannerSection.addEventListener('mouseenter', function() {
                clearInterval(autoSlideInterval);
            });

            bannerSection.addEventListener('mouseleave', function() {
                autoSlideInterval = setInterval(nextSlide, slideInterval);
            });
        });
    </script>
@endpush
