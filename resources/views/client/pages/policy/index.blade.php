@extends('client.layouts.app')

@section('title', 'Chính Sách - ' . config('app.name'))
@section('description', 'Chính sách và quy định của GoTax')

@section('content')
<section class="policy-page container-page">
    <div class="container">
        <div class="policy-header text-center mb-5">
            <h1 class="policy-title">Chính Sách</h1>
            <p class="policy-subtitle">Chính sách và quy định của GoTax</p>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="policy-list">
                    @forelse($policies as $policy)
                        <div class="policy-item">
                            <a href="{{ route('policy.show', $policy->slug) }}" class="policy-link">
                                <h3 class="policy-item-title">{{ $policy->title }}</h3>
                                @if($policy->content)
                                    <p class="policy-item-excerpt">{{ strip_tags(html_entity_decode(substr($policy->content, 0, 200), ENT_QUOTES, 'UTF-8')) }}...</p>
                                @endif
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <p class="text-muted">Chưa có chính sách nào.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
    @vite('resources/assets/frontend/css/pages/policy.css')
@endpush

