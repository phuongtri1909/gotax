@php
    $footerData = $footerData ?? [
        'description' => 'Đơn giản hoá công việc kế toán, kiểm tra rủi ro, tải tờ khai và đọc CCCD nhanh chóng.',
        'help_links' => [
            ['text' => 'Tư Vấn', 'url' => route('contact')],
            ['text' => 'Tài Liệu', 'url' => '#'],
            ['text' => 'Bảng Giá', 'url' => '#'],
            ['text' => 'Chính Sách', 'url' => '#'],
            ['text' => 'Câu Hỏi Thường Gặp', 'url' => route('faqs')],
        ],
        'tools_links' => [
            ['text' => 'Go Invoice', 'url' => route('tools.go-invoice')],
            ['text' => 'Go Bot', 'url' => route('tools.go-bot')],
            ['text' => 'Go Soft', 'url' => route('tools.go-soft')],
            ['text' => 'Go Quick', 'url' => route('tools.go-quick')],
        ],
        'copyright' => 'Copyright © 2025 ketoanmoclan. All Rights Reserved.',
    ];
    
    $contactPhone = $contactInfo->phone ?? '0989 466 992';
    $contactEmail = $contactInfo->email ?? 'supportgotax@gmail.com';
    $contactAddress = $contactInfo->address ?? '2321 New Design Str, Lorem Ipsum10';
@endphp

<footer class="footer-section">
    <div class="container">
        <div class="footer-content">
            <div class="footer-brand">
                <a href="{{ route('home') }}" class="footer-logo">
                    <img src="{{ $logoPath }}" alt="GoTax Logo" height="40px">
                </a>
                <p class="footer-description">{{ $footerData['description'] }}</p>
            </div>

            <div class="footer-column">
                <h5 class="footer-heading">Trợ Giúp</h5>
                <ul class="footer-links">
                    @foreach ($footerData['help_links'] as $link)
                        <li><a href="{{ $link['url'] }}">{{ $link['text'] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="footer-column">
                <h5 class="footer-heading">Công Cụ</h5>
                <ul class="footer-links">
                    @foreach ($footerData['tools_links'] as $link)
                        <li><a href="{{ $link['url'] }}">{{ $link['text'] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="footer-column">
                <h5 class="footer-heading">Liên Hệ</h5>
                <div class="footer-contact">
                    @if($contactAddress)
                    <p class="contact-item">
                        <span class="contact-label">Địa chỉ:</span>
                        @php
                            $mapUrl = null;
                            if (isset($contactInfo)) {
                                if ($contactInfo->map_url) {
                                    $mapUrl = $contactInfo->map_url;
                                } elseif ($contactInfo->latitude && $contactInfo->longitude) {
                                    $mapUrl = 'https://www.google.com/maps?q=' . $contactInfo->latitude . ',' . $contactInfo->longitude;
                                } elseif ($contactAddress) {
                                    $mapUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($contactAddress);
                                }
                            } elseif ($contactAddress) {
                                $mapUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($contactAddress);
                            }
                        @endphp
                        @if($mapUrl)
                            <a href="{{ $mapUrl }}" target="_blank" rel="noopener noreferrer" class="contact-value">{{ $contactAddress }}</a>
                        @else
                            <span class="contact-value">{{ $contactAddress }}</span>
                        @endif
                    </p>
                    @endif
                    @if($contactPhone)
                    <p class="contact-item">
                        <span class="contact-label">Số điện thoại:</span>
                        <a href="tel:{{ str_replace(' ', '', $contactPhone) }}"
                            class="contact-value">{{ $contactPhone }}</a>
                    </p>
                    @endif
                    @if($contactEmail)
                    <p class="contact-item">
                        <span class="contact-label">Email:</span>
                        <a href="mailto:{{ $contactEmail }}"
                            class="contact-value">{{ $contactEmail }}</a>
                    </p>
                    @endif
                </div>
                <div class="footer-social">
                    @if (isset($socials) && $socials->count() > 0)
                        @foreach ($socials as $social)
                            <a href="{{ $social->url }}" target="_blank" class="social-link text-decoration-none"
                                aria-label="{{ $social->name }}">
                                @if (strpos($social->icon, 'custom-') === 0)
                                    <span class="{{ $social->icon }}"></span>
                                @else
                                    <i class="{{ $social->icon }}"></i>
                                @endif
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="footer-divider"></div>

        <div class="footer-copyright">
            <p>{{ $footerData['copyright'] }}</p>
        </div>
    </div>
</footer>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
@stack('scripts')
</body>

</html>
