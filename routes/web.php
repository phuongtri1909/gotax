<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\GoQuickController;

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

Route::get('go-bot', function () {
    return view('client.pages.tools.go-bot');
})->name('tools.go-bot');

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

Route::get('go-soft', function () {
    return view('client.pages.tools.go-soft');
})->name('tools.go-soft');

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

Route::get('go-quick', function () {
    return view('client.pages.tools.go-quick');
})->name('tools.go-quick');

Route::get('go-quick/tools', function () {
    return view('client.pages.tools.go-quick.go-quick-tools');
})->name('tools.go-quick.tools');

Route::get('go-invoice/trial', function () {
    return view('client.pages.tools.go-invoice-trial');
})->name('tools.go-invoice.trial');

Route::group(['middleware' => 'auth'], function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('profile', function () {
        return view('client.pages.profile');
    })->name('profile');
    Route::get('account-settings', function () {
        return view('client.pages.account-settings');
    })->name('account-settings');


    Route::prefix('go-quick')->group(function () {
        Route::get('/health', [GoQuickController::class, 'healthCheck']);
        Route::post('/process-cccd', [GoQuickController::class, 'processCCCD']);
        Route::post('/process-pdf', [GoQuickController::class, 'processPDF']);
        Route::post('/process-excel', [GoQuickController::class, 'processExcel']);
        Route::post('/process', [GoQuickController::class, 'process']);
    });
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
