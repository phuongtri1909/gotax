@extends('client.layouts.app')
@section('title', 'Home - ' . config('app.name'))
@section('description', config('app.name') . ' là dự án được đầu tư bởi Tập đoàn Hoàng Gia Việt Nam, với quy hoạch tổng thể như một
    tổ hợp khu công nghiệp và đô thị xanh, tuân thủ các tiêu chuẩn sinh thái, tích hợp sản xuất bền vững, logistics và không
    gian sống thân thiện với môi trường, với tổng quy mô hơn 2.300 ha.')
@section('keywords', config('app.name'))

@section('content')
<div class="home-page">
<section class="hero">
<div class="hero-inner">
<h1 class="hero-title">{{ config('app.name') }}</h1>
<p class="hero-subtitle">Khu công nghiệp & đô thị xanh, tích hợp logistics và sản xuất bền vững</p>
<div class="hero-cta">
<a href="#about" class="btn btn-primary">Tìm hiểu thêm</a>
<a href="#contact" class="btn btn-outline">Liên hệ</a>
</div>
</div>
</section>

<section class="features" id="features">
<div class="container">
<div class="section-head">
<h2>Định hướng phát triển</h2>
<p>Tạo dựng không gian sống thân thiện môi trường, chuẩn mực sinh thái hiện đại.</p>
</div>
<div class="grid">
<div class="card">
<div class="icon">🌿</div>
<h3>Sinh thái</h3>
<p>Quy hoạch tổng thể theo tiêu chuẩn xanh, tối ưu năng lượng và tài nguyên.</p>
</div>
<div class="card">
<div class="icon">🏭</div>
<h3>Sản xuất bền vững</h3>
<p>Hạ tầng đồng bộ, hỗ trợ chuỗi cung ứng và công nghệ sạch.</p>
</div>
<div class="card">
<div class="icon">🚚</div>
<h3>Logistics</h3>
<p>Kết nối giao thông chiến lược, tối ưu luồng vận tải đa phương thức.</p>
</div>
<div class="card">
<div class="icon">🏡</div>
<h3>Đô thị thông minh</h3>
<p>Dịch vụ tiện ích và số hoá trải nghiệm cư dân, doanh nghiệp.</p>
</div>
</div>
</div>
</section>

<section class="stats" aria-label="Thống kê">
<div class="container grid-4">
<div class="stat">
<div class="value">2.300<span class="unit">ha</span></div>
<div class="label">Quy mô tổng thể</div>
></div>
<div class="stat">
<div class="value">4</div>
<div class="label">Cụm chức năng trọng điểm</div>
></div>
<div class="stat">
<div class="value">24/7</div>
<div class="label">Vận hành hạ tầng</div>
></div>
<div class="stat">
<div class="value">A+</div>
<div class="label">Chuẩn tiện ích</div>
></div>
</div>
</section>

<section class="about" id="about">
<div class="container">
<div class="section-head">
<h2>Về dự án</h2>
<p>{{ config('app.name') }} là dự án được đầu tư bởi Tập đoàn Hoàng Gia Việt Nam.</p>
</div>
<div class="about-grid">
<div class="about-text">
<p>Với tầm nhìn dài hạn, dự án được phát triển như một tổ hợp khu công nghiệp và đô thị xanh, tuân thủ các tiêu chuẩn sinh thái, tích hợp sản xuất bền vững, logistics và không gian sống thân thiện với môi trường.</p>
<ul class="checklist">
<li>Quy hoạch bài bản, linh hoạt cho nhiều ngành</li>
<li>Kết nối giao thông liên vùng thuận tiện</li>
<li>Chính sách hỗ trợ nhà đầu tư hấp dẫn</li>
<li>Hệ sinh thái dịch vụ toàn diện</li>
}</ul>
</div>
<div class="about-media">
<img src="/images/dev/dev-banner.png" alt="Tổng quan dự án" />
</div>
</div>
</div>
</section>

<section class="cta" id="contact">
<div class="container">
<h2>Quan tâm hợp tác hoặc cần tư vấn?</h2>
<p>Liên hệ đội ngũ của chúng tôi để nhận tài liệu và lộ trình triển khai.</p>
<a href="mailto:info@example.com" class="btn btn-primary">Gửi email</a>
</div>
</section>
</div>
@endsection

@push('styles')
<style>
.home-page { font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, "Helvetica Neue", "Noto Sans", "Liberation Sans", sans-serif; color: #0f172a; }
.home-page .container { max-width: 1120px; margin: 0 auto; padding: 0 16px; }
.home-page .section-head { text-align: center; margin-bottom: 24px; }
.home-page h1, .home-page h2, .home-page h3 { line-height: 1.2; margin: 0 0 8px; }
.home-page p { margin: 0 0 12px; color: #334155; }

/* Hero */
.home-page .hero { position: relative; background: linear-gradient(135deg, #0ea5e9 0%, #22c55e 100%); color: #fff; padding: 72px 0; overflow: hidden; }
.home-page .hero-inner { max-width: 900px; margin: 0 auto; text-align: center; padding: 0 16px; }
.home-page .hero-title { font-size: 40px; font-weight: 800; letter-spacing: -0.02em; }
.home-page .hero-subtitle { font-size: 18px; opacity: 0.95; }
.home-page .hero-cta { display: inline-flex; gap: 12px; margin-top: 20px; flex-wrap: wrap; }

/* Buttons */
.home-page .btn { display: inline-block; padding: 10px 16px; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all .2s ease; border: 1px solid transparent; }
.home-page .btn-primary { background: #0f172a; color: #fff; }
.home-page .btn-primary:hover { background: #111827; transform: translateY(-1px); }
.home-page .btn-outline { background: transparent; color: #fff; border-color: rgba(255,255,255,.7); }
.home-page .btn-outline:hover { background: rgba(255,255,255,.12); transform: translateY(-1px); }

/* Features */
.home-page .features { padding: 56px 0; background: #f8fafc; }
.home-page .features .grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
.home-page .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; transition: box-shadow .2s ease, transform .2s ease; }
.home-page .card:hover { box-shadow: 0 10px 20px rgba(2,6,23,.06); transform: translateY(-2px); }
.home-page .card .icon { font-size: 24px; }

/* Stats */
.home-page .stats { padding: 40px 0; background: #0b1220; color: #e2e8f0; }
.home-page .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; align-items: stretch; }
.home-page .stat { text-align: center; background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08); border-radius: 12px; padding: 16px; }
.home-page .stat .value { font-size: 28px; font-weight: 800; color: #fff; }
.home-page .stat .unit { font-size: 14px; font-weight: 700; margin-left: 2px; opacity: .9; }
.home-page .stat .label { font-size: 13px; color: #cbd5e1; }

/* About */
.home-page .about { padding: 56px 0; background: #fff; }
.home-page .about-grid { display: grid; grid-template-columns: 1.2fr .8fr; gap: 24px; align-items: center; }
.home-page .about-text { font-size: 16px; }
.home-page .checklist { list-style: none; padding: 0; margin: 12px 0 0; }
.home-page .checklist li { position: relative; padding-left: 22px; margin: 6px 0; }
.home-page .checklist li:before { content: "✓"; position: absolute; left: 0; top: 0; color: #16a34a; font-weight: 800; }
.home-page .about-media img { width: 100%; height: auto; border-radius: 12px; border: 1px solid #e5e7eb; }

/* CTA */
.home-page .cta { padding: 56px 0; background: linear-gradient(180deg, #f0f9ff, #ecfdf5); text-align: center; }
.home-page .cta h2 { font-size: 28px; margin-bottom: 8px; }
.home-page .cta p { margin-bottom: 16px; }

/* Responsive */
@media (max-width: 1024px) {
.home-page .features .grid, .home-page .grid-4 { grid-template-columns: repeat(2, 1fr); }
.home-page .about-grid { grid-template-columns: 1fr; }
}
@media (max-width: 640px) {
.home-page .hero-title { font-size: 32px; }
.home-page .features .grid, .home-page .grid-4 { grid-template-columns: 1fr; }
}
</style>
@endpush
