<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\HomeController;

Route::get('clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return 'Cache cleared';
})->name('clear.cache');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('contact', function () {
    return view('client.pages.contact');
})->name('contact');

Route::post('contact', [HomeController::class, 'contact'])->name('contact.post');

Route::get('faqs', function () {
    return view('client.pages.faqs');
})->name('faqs');

Route::get('go-invoice', function () {
    return view('client.pages.tools.go-invoice');
})->name('tools.go-invoice');

Route::get('go-bot', function () {
    return view('client.pages.tools.go-bot');
})->name('tools.go-bot');

Route::get('go-soft', function () {
    return view('client.pages.tools.go-soft');
})->name('tools.go-soft');

Route::get('go-quick', function () {
    return view('client.pages.tools.go-quick');
})->name('tools.go-quick');

Route::group(['middleware' => 'auth'], function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('profile', function () {
        return view('client.pages.profile');
    })->name('profile');
    Route::get('account-settings', function () {
        return view('client.pages.account-settings');
    })->name('account-settings');
});


Route::group(['middleware' => 'guest'], function () {
    Route::get('login', function () {
        return view('client.pages.auth.login');
    })->name('login');

    Route::post('login', [AuthController::class, 'login'])->name('login.post');

    Route::get('register', function () {
        return view('client.pages.auth.register');
    })->name('register');

    Route::post('register', [AuthController::class, 'register'])->name('register.post');

    Route::get('forgot-password', function () {
        return view('client.pages.auth.forgot-password');
    })->name('forgot-password');

    Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password.post');

    Route::get('change-password', function () {
        return view('client.pages.auth.change-password');
    })->name('change-password');

    Route::post('change-password', [AuthController::class, 'changePassword'])->name('change-password.post');
});
