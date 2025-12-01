@php
    $tools = $tools ?? [
        [
            'id' => 1,
            'title' => 'Go Invoice',
            'description' => 'Tải Hoá Đơn Điện Tử Hàng Loạt',
            'icon' => 'invoice',
            'link' => 'tools.go-invoice',
            'tool_link' => 'tools.trial.go-invoice',
        ],
        [
            'id' => 2,
            'title' => 'Go Bot',
            'description' => 'Tra Mã Số Thuế Hàng Loạt',
            'icon' => 'bot',
            'link' => 'tools.go-bot',
            'tool_link' => 'tools.trial.go-bot',
        ],
        [
            'id' => 3,
            'title' => 'Go Soft',
            'description' => 'Tải Tờ Khai Hàng Loạt',
            'icon' => 'soft',
            'link' => 'tools.go-soft',
            'tool_link' => 'tools.go-soft.overview',
        ],
        [
            'id' => 4,
            'title' => 'Go Quick',
            'description' => 'Đọc CCCD Hàng Loạt',
            'icon' => 'quick',
            'link' => 'tools.go-quick',
            'tool_link' => 'tools.go-quick.tools',
        ],
    ];
@endphp

<section class="tools-section">
    <h2 class="tools-title">Tất Cả Công Cụ</h2>
    <p class="tools-subtitle">Các công cụ tối ưu công việc kế toán để giúp cuộc sống của bạn dễ dàng hơn.</p>

    <div class="tools-grid">
        @foreach ($tools as $tool)
            @php
                $isActive = Route::currentRouteNamed($tool['link']);
                $href = $isActive ? route($tool['tool_link'] ?? $tool['link']) : route($tool['link']);
            @endphp
            <a href="{{ $href }}"
                class="tool-card {{ $isActive ? 'active' : '' }}"
                data-tool-link="{{ route($tool['link']) }}"
                data-trial-link="{{ route($tool['tool_link'] ?? $tool['link']) }}">
                <div class="tool-icon">
                    @if ($tool['icon'] === 'invoice')
                        <img src="{{ asset('images/svg/tools/go-invoice.svg') }}" alt="Invoice" width="64"
                            height="64">
                    @elseif($tool['icon'] === 'bot')
                        <img src="{{ asset('images/svg/tools/go-bot.svg') }}" alt="Bot" width="64"
                            height="64">
                    @elseif($tool['icon'] === 'soft')
                        <img src="{{ asset('images/svg/tools/go-soft.svg') }}" alt="Soft" width="64"
                            height="64">
                    @elseif($tool['icon'] === 'quick')
                        <img src="{{ asset('images/svg/tools/go-quick.svg') }}" alt="Quick" width="64"
                            height="64">
                    @endif
                </div>
                <h3 class="tool-title">
                    @php
                        $titleParts = explode(' ', $tool['title'], 2);
                    @endphp
                    <span class="tool-title-first">{{ $titleParts[0] }}</span>
                    @if (isset($titleParts[1]))
                        <span class="tool-title-second"> {{ $titleParts[1] }}</span>
                    @endif
                </h3>
                <p class="tool-description">{{ $tool['description'] }}</p>
            </a>
        @endforeach
    </div>
</section>

@push('styles')
    @vite('resources/assets/frontend/css/components/tools-section.css')
@endpush
