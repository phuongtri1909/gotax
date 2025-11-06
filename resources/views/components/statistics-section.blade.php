@php
    // Sample data - sẽ truyền từ controller sau
    $statistics = $statistics ?? [
        [
            'value' => '3K+',
            'label' => 'Khách Hàng Sử Dụng',
        ],
        [
            'value' => '90%',
            'label' => 'Rút Ngắn Công Việc',
        ],
        [
            'value' => '10K+',
            'label' => 'Hoá Đơn Đã Tải',
        ],
        [
            'value' => '98%',
            'label' => 'Khách Hàng Hài Lòng',
        ],
    ];
@endphp

<section class="statistics-section">
    <div class="statistics-grid">
        @foreach ($statistics as $stat)
            <div class="statistic-card">
                <p class="statistic-value">{{ $stat['value'] }}</p>
                <p class="statistic-label">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>
</section>

@push('styles')
    @vite('resources/assets/frontend/css/components/statistics-section.css')
@endpush

