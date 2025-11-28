<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Client\PurchaseController;
use App\Http\Controllers\Client\FaqController;
use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\GoQuickController;
use App\Http\Controllers\Client\ToolController;
use App\Http\Controllers\Client\ContactController;
use App\Http\Controllers\Client\TestimonialController;
use App\Http\Controllers\Client\PaymentController;
use App\Http\Controllers\Client\TrialController;
use App\Http\Controllers\Client\SitemapController;

Route::get('sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('robots.txt', [SitemapController::class, 'robots'])->name('robots');


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

Route::get('go-invoice/overview', function () {
    return view('client.pages.tools.go-invoice.overview');
})->name('tools.go-invoice.overview');

Route::get('go-invoice/process', function () {
    return view('client.pages.tools.go-invoice.process');
})->name('tools.go-invoice.process');

Route::get('go-invoice/storage', function () {
    return view('client.pages.tools.go-invoice.storage');
})->name('tools.go-invoice.storage');

Route::get('go-invoice/support', function () {
    return view('client.pages.tools.go-invoice.support');
})->name('tools.go-invoice.support');

Route::get('go-invoice/download', function () {
    return view('client.pages.tools.go-invoice.download');
})->name('tools.go-invoice.download');


Route::get('go-bot/overview', function () {
    return view('client.pages.tools.go-bot.overview');
})->name('tools.go-bot.overview');

Route::get('go-bot/result', function () {
    return view('client.pages.tools.go-bot.result');
})->name('tools.go-bot.result');

Route::get('go-bot/batch', function () {
    return view('client.pages.tools.go-bot.batch');
})->name('tools.go-bot.batch');

Route::get('go-bot/download', function () {
    return view('client.pages.tools.go-bot.download');
})->name('tools.go-bot.download');

Route::get('go-soft/overview', function () {
    return view('client.pages.tools.go-soft.overview');
})->name('tools.go-soft.overview');

Route::get('go-soft/xml-to-excel', function () {
    return view('client.pages.tools.go-soft.xml-to-excel');
})->name('tools.go-soft.xml-to-excel');

Route::get('go-soft/storage', function () {
    return view('client.pages.tools.go-soft.storage');
})->name('tools.go-soft.storage');

Route::get('go-soft/support', function () {
    return view('client.pages.tools.go-soft.support');
})->name('tools.go-soft.support');

Route::get('go-soft/download', function () {
    return view('client.pages.tools.go-soft.download');
})->name('tools.go-soft.download');

Route::get('go-quick/tools', function () {
    return view('client.pages.tools.go-quick.go-quick-tools');
})->name('tools.go-quick.tools');

Route::get('go-invoice/trial', [TrialController::class, 'index'])->defaults('toolType', 'go-invoice')->name('tools.trial.go-invoice');
Route::get('go-bot/trial', [TrialController::class, 'index'])->defaults('toolType', 'go-bot')->name('tools.trial.go-bot');
Route::get('go-soft/trial', [TrialController::class, 'index'])->defaults('toolType', 'go-soft')->name('tools.trial.go-soft');
Route::get('go-quick/trial', [TrialController::class, 'index'])->defaults('toolType', 'go-quick')->name('tools.trial.go-quick');

// Trial verification route
Route::get('trial/verify/{token}/{email}', [TrialController::class, 'verify'])->name('tools.trial.verify');


Route::group(['middleware' => ['auth']], function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('change-password', function () {
        return view('client.pages.auth.change-password');
    })->name('change-password');
    
    Route::post('change-password', [AuthController::class, 'changePassword'])->name('change-password.post');
    
    Route::get('profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::post('profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::post('profile/avatar', [AuthController::class, 'uploadAvatar'])->name('profile.avatar.upload');
    
    Route::get('account-settings', [AuthController::class, 'showAccountSettings'])->name('account-settings');
    
    Route::post('go-invoice/trial/register', [TrialController::class, 'register'])->defaults('toolType', 'go-invoice')->name('tools.trial.register.go-invoice');
    Route::post('go-bot/trial/register', [TrialController::class, 'register'])->defaults('toolType', 'go-bot')->name('tools.trial.register.go-bot');
    Route::post('go-soft/trial/register', [TrialController::class, 'register'])->defaults('toolType', 'go-soft')->name('tools.trial.register.go-soft');
    Route::post('go-quick/trial/register', [TrialController::class, 'register'])->defaults('toolType', 'go-quick')->name('tools.trial.register.go-quick');
    
    // Payment routes
    Route::post('payment/go-invoice', [PaymentController::class, 'storeGoInvoice'])->name('payment.go-invoice.store');
    Route::post('payment/go-bot', [PaymentController::class, 'storeGoBot'])->name('payment.go-bot.store');
    Route::post('payment/go-soft', [PaymentController::class, 'storeGoSoft'])->name('payment.go-soft.store');
    Route::post('payment/go-quick', [PaymentController::class, 'storeGoQuick'])->name('payment.go-quick.store');
    Route::get('payment/info', [PaymentController::class, 'getPaymentInfo'])->name('payment.info');
    Route::get('payment/sse', [PaymentController::class, 'sseTransactionUpdates'])->name('payment.sse');


    Route::prefix('go-quick')->group(function () {
        Route::get('/health', [GoQuickController::class, 'healthCheck'])->name('tools.go-quick.health');
        Route::post('/process-cccd', [GoQuickController::class, 'processCCCD'])->name('tools.go-quick.process-cccd');
        Route::post('/process-cccd-images', [GoQuickController::class, 'processCCCDImages'])->name('tools.go-quick.process-cccd-images');
        Route::post('/process-cccd-multiple-images', [GoQuickController::class, 'processCCCDMultipleImages'])->name('tools.go-quick.process-cccd-multiple-images');
        Route::post('/process-pdf', [GoQuickController::class, 'processPDF'])->name('tools.go-quick.process-pdf');
        Route::post('/process-excel', [GoQuickController::class, 'processExcel'])->name('tools.go-quick.process-excel');
        Route::post('/export-excel', [GoQuickController::class, 'exportExcel'])->name('tools.go-quick.export-excel');
        Route::post('/process', [GoQuickController::class, 'process'])->name('tools.go-quick.process');
    });
});


Route::group(['middleware' => 'guest'], function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');

    Route::post('login', [AuthController::class, 'login'])->name('login.post');

    Route::get('register', [AuthController::class, 'showRegister'])->name('register');

    Route::post('register', [AuthController::class, 'register'])->name('register.post');

    Route::get('forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');

    Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password.post');

    Route::get('resend-activation', function () {
        return view('client.pages.auth.resend-activation');
    })->name('resend-activation');

    Route::post('resend-activation', [AuthController::class, 'resendActivationEmail'])->name('resend-activation.post');
});
Route::get('verify-account/{key}/{email}', [AuthController::class, 'verifyAccount'])->name('verify-account');
Route::get('verify-reset-password/{key}/{email}', [AuthController::class, 'verifyResetPassword'])->name('verify-reset-password');
Route::get('verify-change-password/{key}/{email}', [AuthController::class, 'verifyChangePassword'])->name('verify-change-password');
