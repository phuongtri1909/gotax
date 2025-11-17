@php
    $testimonials = $testimonials ?? [];
    $initialLimit = count($testimonials);
    $loadMoreLimit = 4;
@endphp

<section class="testimonials-section">
    <h2 class="testimonials-title">
        <span class="title-part-1">Khách Hàng Nói Gì Về</span>
        <span class="title-part-2"> Go Suite</span>
    </h2>

    <div class="testimonials-wrapper">
        <div class="testimonials-container-wrapper">
            <div class="testimonials-grid" id="testimonialsGrid">
                @foreach ($testimonials as $testimonial)
                    <div class="testimonial-card">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="quote-icon">
                                <img src="{{ asset('images/svg/testimonials/quote.svg') }}" alt="Quote" class="quote-icon-img">
                            </div>
                            <div class="testimonial-rating">
                                @for ($i = 0; $i < ($testimonial['rating'] ?? 5); $i++)
                                    <i class="fas fa-star"></i>
                                @endfor
                            </div>
                        </div>
                        <p class="testimonial-text mb-0">{{ $testimonial['text'] ?? '' }}</p>
                        <hr class="mb-4 mt-0 divider-testimonials">
                        <div class="testimonial-author">
                            <img src="{{ $testimonial['avatar'] ?? asset('images/default/avatar_default.jpg') }}" 
                                 alt="{{ $testimonial['name'] ?? '' }}" 
                                 class="author-avatar"
                                 onerror="this.src='{{ asset('images/default/avatar_default.jpg') }}'">
                            <span class="author-name">{{ $testimonial['name'] ?? '' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="text-center">
            <button type="button" class="view-more-button-right" id="loadMoreTestimonials" data-offset="{{ $initialLimit }}" data-limit="{{ $loadMoreLimit }}">
                Xem thêm <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    </div>
</section>

@push('styles')
    @vite('resources/assets/frontend/css/components/testimonials-section.css')
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loadMoreBtn = document.getElementById('loadMoreTestimonials');
            const testimonialsGrid = document.getElementById('testimonialsGrid');
            const containerWrapper = document.querySelector('.testimonials-container-wrapper');
            
            if (!loadMoreBtn || !testimonialsGrid) return;

            function adjustGridLayout() {
                const cards = testimonialsGrid.querySelectorAll('.testimonial-card');
                const totalItems = cards.length;
                const isMobile = window.innerWidth < 768;
                const containerWidth = containerWrapper ? containerWrapper.clientWidth : (isMobile ? window.innerWidth - 40 : window.innerWidth - 120);
                
                if (isMobile) {
                    if (totalItems <= 4) {
                        testimonialsGrid.style.gridTemplateColumns = '1fr';
                        testimonialsGrid.style.width = '100%';
                    } else {
                        testimonialsGrid.style.gridTemplateColumns = 'repeat(2, 1fr)';
                        const totalWidth = containerWidth * 2;
                        testimonialsGrid.style.width = totalWidth + 'px';
                    }
                } else {
                    if (totalItems <= 4) {
                        testimonialsGrid.style.gridTemplateColumns = 'repeat(2, 1fr)';
                        testimonialsGrid.style.width = '100%';
                    } else {
                        testimonialsGrid.style.gridTemplateColumns = 'repeat(4, 1fr)';
                        const totalWidth = containerWidth * 2;
                        testimonialsGrid.style.width = totalWidth + 'px';
                    }
                }
                
                void testimonialsGrid.offsetHeight;
            }
            
            let resizeTimeout;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(function() {
                    adjustGridLayout();
                }, 250);
            });

            adjustGridLayout();

            let isLoading = false;

            loadMoreBtn.addEventListener('click', function() {
                if (isLoading) return;

                isLoading = true;
                const offset = parseInt(this.getAttribute('data-offset'));
                const limit = parseInt(this.getAttribute('data-limit'));

                this.disabled = true;
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tải...';

                fetch(`{{ route('testimonials.load-more') }}?offset=${offset}&limit=${limit}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.testimonials.length > 0) {
                        data.testimonials.forEach((testimonial, index) => {
                            const card = document.createElement('div');
                            card.className = 'testimonial-card';
                            
                            let starsHtml = '';
                            for (let i = 0; i < (testimonial.rating || 5); i++) {
                                starsHtml += '<i class="fas fa-star"></i>';
                            }

                            card.innerHTML = `
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="quote-icon">
                                        <img src="{{ asset('images/svg/testimonials/quote.svg') }}" alt="Quote" class="quote-icon-img">
                                    </div>
                                    <div class="testimonial-rating">
                                        ${starsHtml}
                                    </div>
                                </div>
                                <p class="testimonial-text mb-0">${testimonial.text || ''}</p>
                                <hr class="mb-4 mt-0 divider-testimonials">
                                <div class="testimonial-author">
                                    <img src="${testimonial.avatar || '{{ asset('images/default/avatar_default.jpg') }}'}" 
                                         alt="${testimonial.name || ''}" 
                                         class="author-avatar"
                                         onerror="this.src='{{ asset('images/default/avatar_default.jpg') }}'">
                                    <span class="author-name">${testimonial.name || ''}</span>
                                </div>
                            `;

                            testimonialsGrid.appendChild(card);
                        });

                        adjustGridLayout();

                        const newOffset = offset + data.testimonials.length;
                        this.setAttribute('data-offset', newOffset);

                        if (containerWrapper) {
                            containerWrapper.scrollTo({
                                left: containerWrapper.scrollWidth,
                                behavior: 'smooth'
                            });
                        }

                        if (!data.hasMore) {
                            this.style.display = 'none';
                        } else {
                            this.innerHTML = originalText;
                            this.disabled = false;
                        }
                    } else {
                        this.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Error loading testimonials:', error);
                    this.innerHTML = originalText;
                    this.disabled = false;
                })
                .finally(() => {
                    isLoading = false;
                });
            });
        });
    </script>
@endpush

