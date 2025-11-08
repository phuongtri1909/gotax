@php
    $footerData = $footerData ?? [
        'description' => 'Đơn giản hoá công việc kế toán, kiểm tra rủi ro, tải tờ khai và đọc CCCD nhanh chóng.',
        'help_links' => [
            ['text' => 'Tư Vấn', 'url' => '#'],
            ['text' => 'Tài Liệu', 'url' => '#'],
            ['text' => 'Bảng Giá', 'url' => '#'],
            ['text' => 'Chính Sách', 'url' => '#'],
            ['text' => 'Câu Hỏi Thường Gặp', 'url' => route('faqs')],
        ],
        'tools_links' => [
            ['text' => 'Go Invoice', 'url' => '#'],
            ['text' => 'Go Bot', 'url' => '#'],
            ['text' => 'Go Soft', 'url' => '#'],
            ['text' => 'Go Quick', 'url' => '#'],
        ],
        'contact' => [
            'address' => '2321 New Design Str, Lorem Ipsum10',
            'phone' => '+ 0989 466 992',
            'email' => 'supportgotax@gmail.com',
        ],
        'copyright' => 'Copyright © 2025 ketoanmoclan. All Rights Reserved.',
    ];
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
                    <p class="contact-item">
                        <span class="contact-label">Địa chỉ:</span>
                        <span class="contact-value">{{ $footerData['contact']['address'] }}</span>
                    </p>
                    <p class="contact-item">
                        <span class="contact-label">Số điện thoại:</span>
                        <a href="tel:{{ str_replace(' ', '', $footerData['contact']['phone']) }}"
                            class="contact-value">{{ $footerData['contact']['phone'] }}</a>
                    </p>
                    <p class="contact-item">
                        <span class="contact-label">Email:</span>
                        <a href="mailto:{{ $footerData['contact']['email'] }}"
                            class="contact-value">{{ $footerData['contact']['email'] }}</a>
                    </p>
                </div>
                <div class="footer-social">
                    @if (isset($socials) && $socials->count() > 0)
                        @foreach ($socials as $social)
                            <a href="{{ $social->url }}" target="_blank" class="social-link"
                                aria-label="{{ $social->name }}">
                                @if (strpos($social->icon, 'custom-') === 0)
                                    <span class="{{ $social->icon }}"></span>
                                @else
                                    <i class="{{ $social->icon }}"></i>
                                @endif
                            </a>
                        @endforeach
                    @else
                        <a href="https://facebook.com" target="_blank" class="social-link" aria-label="Facebook">
                            <img src="{{ asset('images/svg/footers/facebook.svg') }}" alt="Facebook">
                        </a>
                        <a href="https://youtube.com" target="_blank" class="social-link" aria-label="YouTube">
                            <img src="{{ asset('images/svg/footers/youtube.svg') }}" alt="YouTube">
                        </a>
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
@stack('scripts')
</body>

</html>
