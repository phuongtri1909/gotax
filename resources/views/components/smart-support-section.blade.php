@php
    $data = $data ?? [
        'subtitle' => 'HỖ TRỢ THÔNG MINH',
        'title' => 'Công Cụ Dành Cho Người Làm Việc Hiệu Quả',
        'description' =>
            'Nếu bạn làm kế toán mà vẫn thao tác thủ công, thì nên thử ngay các công cụ này. Nó sẽ thay đổi hoàn toàn cách bạn làm việc với hóa đơn và tờ khai.',
        'button_text' => 'Khám Phá Công Cụ',
        'button_link' => '#',
    ];
@endphp

<section class="smart-support-section">
    <div class="smart-support-content">
        <p class="support-subtitle">{{ $data['subtitle'] }}</p>
        <h2 class="support-title">{{ $data['title'] }}</h2>
        <p class="support-description">{{ $data['description'] }}</p>
        <a href="{{ $data['button_link'] }}" class="support-button">{{ $data['button_text'] }}</a>
    </div>
</section>

@push('styles')
    @vite('resources/assets/frontend/css/components/smart-support-section.css')
@endpush
