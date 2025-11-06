@extends('client.layouts.app')
@section('title', 'Home - ' . config('app.name'))
@section('description', config('app.name') . '')
@section('keywords', config('app.name'))

@section('content')
    <section class="home-page">
        @include('components.banner-home')
    </section>
@endsection

@push('styles')
    @vite('resources/assets/frontend/css/pages/home.css')
@endpush
