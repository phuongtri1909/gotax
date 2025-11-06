@extends('client.layouts.app')
@section('title', 'Home - ' . config('app.name'))
@section('description', config('app.name') . '')
@section('keywords', config('app.name'))

@section('content')
    <section class="home-page">
        @include('components.banner-home')
        <div class="container">
            @include('components.tools-section')
            @include('components.smart-support-section')
            @include('components.statistics-section')
            @include('components.get-more-section')
            @include('components.testimonials-section')

            @guest
                @include('components.cta-banner-section')
            @endguest
        </div>
    </section>
@endsection

@push('styles')
    @vite('resources/assets/frontend/css/pages/home.css')
@endpush
