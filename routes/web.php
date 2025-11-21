<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\Client\FaqController;
use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ToolController;
use App\Http\Controllers\Client\ContactController;
use App\Http\Controllers\Client\TestimonialController;
use App\Http\Controllers\Client\PaymentController;

Route::get('clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('route:clear');
    return 'Cache cleared';
})->name('clear.cache');

Route::post('/purchase/casso/callback', [PurchaseController::class, 'cassoCallback'])->name('purchase.casso.callback');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('contact', [ContactController::class, 'index'])->name('contact');
Route::post('contact', [ContactController::class, 'store'])->name('contact.post');
Route::get('contact/captcha', [ContactController::class, 'generateCaptcha'])->name('contact.captcha');

Route::get('faqs', [FaqController::class, 'index'])->name('faqs');

Route::get('testimonials/load-more', [TestimonialController::class, 'loadMore'])->name('testimonials.load-more');

Route::get('go-invoice', [ToolController::class, 'goInvoice'])->name('tools.go-invoice');
Route::get('go-bot', [ToolController::class, 'goBot'])->name('tools.go-bot');
Route::get('go-soft', [ToolController::class, 'goSoft'])->name('tools.go-soft');
Route::get('go-quick', [ToolController::class, 'goQuick'])->name('tools.go-quick');

Route::get('go-invoice/trial', function () {
    return view('client.pages.tools.go-invoice-trial');
})->name('tools.go-invoice.trial');

Route::group(['middleware' => ['auth']], function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('change-password', function () {
        return view('client.pages.auth.change-password');
    })->name('change-password');

    Route::post('change-password', [AuthController::class, 'changePassword'])->name('change-password.post');

    Route::get('profile', function () {
        return view('client.pages.profile');
    })->name('profile');
    Route::post('profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('profile/avatar', [AuthController::class, 'uploadAvatar'])->name('profile.avatar.upload');

    Route::get('account-settings', function () {
        return view('client.pages.account-settings');
    })->name('account-settings');

    // Payment routes
    Route::post('payment/go-invoice', [PaymentController::class, 'storeGoInvoice'])->name('payment.go-invoice.store');
    Route::post('payment/go-bot', [PaymentController::class, 'storeGoBot'])->name('payment.go-bot.store');
    Route::post('payment/go-soft', [PaymentController::class, 'storeGoSoft'])->name('payment.go-soft.store');
    Route::post('payment/go-quick', [PaymentController::class, 'storeGoQuick'])->name('payment.go-quick.store');
    Route::get('payment/info', [PaymentController::class, 'getPaymentInfo'])->name('payment.info');
    Route::get('payment/sse', [PaymentController::class, 'sseTransactionUpdates'])->name('payment.sse');
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

    Route::get('resend-activation', function () {
        return view('client.pages.auth.resend-activation');
    })->name('resend-activation');

    Route::post('resend-activation', [AuthController::class, 'resendActivationEmail'])->name('resend-activation.post');
});
Route::get('verify-account/{key}/{email}', [AuthController::class, 'verifyAccount'])->name('verify-account');
Route::get('verify-reset-password/{key}/{email}', [AuthController::class, 'verifyResetPassword'])->name('verify-reset-password');
Route::get('verify-change-password/{key}/{email}', [AuthController::class, 'verifyChangePassword'])->name('verify-change-password');
