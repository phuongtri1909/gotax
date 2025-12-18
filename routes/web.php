<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Client\PurchaseController;
use App\Http\Controllers\Client\FaqController;
use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\GoQuickController;
use App\Http\Controllers\Client\GoSoftController;
use App\Http\Controllers\Client\ToolController;
use App\Http\Controllers\Client\ContactController;
use App\Http\Controllers\Client\TestimonialController;
use App\Http\Controllers\Client\PaymentController;
use App\Http\Controllers\Client\TrialController;
use App\Http\Controllers\Client\SitemapController;
use App\Http\Controllers\Client\PricingController;
use App\Http\Controllers\Client\DocumentationController;
use App\Http\Controllers\Client\PolicyController;
use App\Http\Controllers\Client\FileUploadController;
use App\Http\Controllers\JobStreamController;

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

Route::get('pricing', [PricingController::class, 'index'])->name('pricing');

Route::get('documentation', [DocumentationController::class, 'index'])->name('documentation.index');
Route::get('documentation/{slug}', [DocumentationController::class, 'show'])->name('documentation.show');

Route::get('policy', [PolicyController::class, 'index'])->name('policy.index');
Route::get('policy/{slug}', [PolicyController::class, 'show'])->name('policy.show');

Route::post('upload/image', [FileUploadController::class, 'uploadImage'])->name('upload.image');

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
    
    // Upgrade routes
    Route::post('payment/calculate-upgrade/go-invoice', [PaymentController::class, 'calculateUpgradeGoInvoice'])->name('payment.calculate-upgrade.go-invoice');
    Route::post('payment/calculate-upgrade/go-soft', [PaymentController::class, 'calculateUpgradeGoSoft'])->name('payment.calculate-upgrade.go-soft');
    Route::post('payment/calculate-upgrade/go-bot', [PaymentController::class, 'calculateUpgradeGoBot'])->name('payment.calculate-upgrade.go-bot');
    Route::post('payment/calculate-upgrade/go-quick', [PaymentController::class, 'calculateUpgradeGoQuick'])->name('payment.calculate-upgrade.go-quick');
    Route::post('payment/check-referral-code', [PaymentController::class, 'checkReferralCode'])->name('payment.check-referral-code');
    Route::post('payment/upgrade/go-invoice', [PaymentController::class, 'storeUpgradeGoInvoice'])->name('payment.upgrade.go-invoice');
    Route::post('payment/upgrade/go-soft', [PaymentController::class, 'storeUpgradeGoSoft'])->name('payment.upgrade.go-soft');


    Route::prefix('go-quick')->group(function () {
        Route::get('/health', [GoQuickController::class, 'healthCheck'])->name('tools.go-quick.health');
        Route::post('/process-cccd', [GoQuickController::class, 'processCCCD'])->name('tools.go-quick.process-cccd');
        Route::post('/process-cccd-images', [GoQuickController::class, 'processCCCDImages'])->name('tools.go-quick.process-cccd-images');
        Route::post('/process-cccd-multiple-images', [GoQuickController::class, 'processCCCDMultipleImages'])->name('tools.go-quick.process-cccd-multiple-images');
        Route::post('/process-pdf', [GoQuickController::class, 'processPDF'])->name('tools.go-quick.process-pdf');
        Route::post('/process-excel', [GoQuickController::class, 'processExcel'])->name('tools.go-quick.process-excel');
        Route::post('/export-excel', [GoQuickController::class, 'exportExcel'])->name('tools.go-quick.export-excel');
        Route::post('/process', [GoQuickController::class, 'process'])->name('tools.go-quick.process');
        
        // SSE Streaming routes - tránh connection timeout khi xử lý nhiều CCCD
        Route::post('/process-cccd-stream', [GoQuickController::class, 'processCCCDStream'])->name('tools.go-quick.process-cccd-stream');
        Route::post('/process-pdf-stream', [GoQuickController::class, 'processPDFStream'])->name('tools.go-quick.process-pdf-stream');
        Route::post('/process-excel-stream', [GoQuickController::class, 'processExcelStream'])->name('tools.go-quick.process-excel-stream');
        Route::post('/process-images-stream', [GoQuickController::class, 'processImagesStream'])->name('tools.go-quick.process-images-stream');
        
        // Async Job routes - không cần giữ connection, polling để check progress
        Route::post('/process-cccd-async', [GoQuickController::class, 'processCCCDAsync'])->name('tools.go-quick.process-cccd-async');
        Route::post('/process-pdf-async', [GoQuickController::class, 'processPDFAsync'])->name('tools.go-quick.process-pdf-async');
        Route::post('/process-excel-async', [GoQuickController::class, 'processExcelAsync'])->name('tools.go-quick.process-excel-async');
        Route::get('/job-status/{jobId}', [GoQuickController::class, 'getJobStatus'])->name('tools.go-quick.job-status');
        Route::get('/job-result/{jobId}', [GoQuickController::class, 'getJobResult'])->name('tools.go-quick.job-result');
    });

    Route::prefix('go-soft')->group(function () {
        Route::post('/session/create', [GoSoftController::class, 'createSession'])->name('tools.go-soft.session.create');
        Route::get('/session/status', [GoSoftController::class, 'checkSessionStatus'])->name('tools.go-soft.session.status');
        Route::post('/session/close', [GoSoftController::class, 'closeSession'])->name('tools.go-soft.session.close');
        Route::post('/login/init', [GoSoftController::class, 'initLogin'])->name('tools.go-soft.login.init');
        Route::post('/login/submit', [GoSoftController::class, 'submitLogin'])->name('tools.go-soft.login.submit');
        Route::get('/tokhai/types', [GoSoftController::class, 'getTokhaiTypes'])->name('tools.go-soft.tokhai.types');
        Route::post('/crawl/tokhai', [GoSoftController::class, 'crawlTokhai'])->name('tools.go-soft.crawl.tokhai');
        Route::post('/crawl/tokhai/queue', [GoSoftController::class, 'crawlTokhaiQueue'])->name('tools.go-soft.crawl.tokhai.queue');
        Route::post('/crawl/tokhai/info', [GoSoftController::class, 'crawlTokhaiInfo'])->name('tools.go-soft.crawl.tokhai.info');
        Route::post('/crawl/tokhai/download', [GoSoftController::class, 'downloadTokhaiFiles'])->name('tools.go-soft.crawl.tokhai.download');
        Route::post('/crawl/thongbao', [GoSoftController::class, 'crawlThongbao'])->name('tools.go-soft.crawl.thongbao');
        Route::post('/crawl/thongbao/queue', [GoSoftController::class, 'crawlThongbaoQueue'])->name('tools.go-soft.crawl.thongbao.queue');
        Route::post('/crawl/giaynoptien', [GoSoftController::class, 'crawlGiayNopTien'])->name('tools.go-soft.crawl.giaynoptien');
        Route::post('/crawl/giaynoptien/queue', [GoSoftController::class, 'crawlGiayNopTienQueue'])->name('tools.go-soft.crawl.giaynoptien.queue');
        Route::post('/crawl/batch', [GoSoftController::class, 'crawlBatch'])->name('tools.go-soft.crawl.batch');
    });
    
    // Job Queue System Routes (for all tools)
    // Download endpoint không cần auth (public download link)
    Route::prefix('api/job')->group(function () {
        Route::get('/{jobId}/stream', [JobStreamController::class, 'stream'])->name('job.stream');
        Route::get('/{jobId}/status', [JobStreamController::class, 'status'])->name('job.status');
        Route::get('/{jobId}/result', [JobStreamController::class, 'result'])->name('job.result');
        Route::post('/{jobId}/cancel', [JobStreamController::class, 'cancel'])->name('job.cancel');
    });
    
    // Download endpoint - không cần auth (public download link)
    Route::get('/api/job/{jobId}/download', [JobStreamController::class, 'download'])->name('job.download');
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
