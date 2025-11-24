<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin\SeoController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\SocialController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\AdminFaqController;
use App\Http\Controllers\Admin\LogoSiteController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminContactController;
use App\Http\Controllers\Admin\GoogleSettingController;
use App\Http\Controllers\Admin\AdminContactInfoController;
use App\Http\Controllers\Admin\AdminTestimonialController;
use App\Http\Controllers\Admin\TrialRegistrationController;
use App\Http\Controllers\Admin\TrialController;

Route::group(['as' => 'admin.'], function () {
    Route::get('/clear-cache', function () {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        return 'Cache cleared';
    })->name('clear.cache');

    Route::group(['middleware' => ['auth', 'check.role:admin']], function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('logo-site', [LogoSiteController::class, 'edit'])->name('logo-site.edit');
        Route::put('logo-site', [LogoSiteController::class, 'update'])->name('logo-site.update');
        Route::delete('logo-site/delete-logo', [LogoSiteController::class, 'deleteLogo'])->name('logo-site.delete-logo');
        Route::delete('logo-site/delete-favicon', [LogoSiteController::class, 'deleteFavicon'])->name('logo-site.delete-favicon');

        Route::resource('socials', SocialController::class)->except(['show']);

        Route::get('setting', [SettingController::class, 'index'])->name('setting.index');
        Route::put('setting/smtp', [SettingController::class, 'updateSMTP'])->name('setting.update.smtp');
        Route::put('setting/google', [GoogleSettingController::class, 'updateGoogle'])->name('setting.update.google');

        Route::resource('seo', SeoController::class)->except(['show', 'create', 'store', 'destroy']);

        // Contact Management
        Route::get('contacts', [AdminContactController::class, 'index'])->name('contacts.index');
        Route::get('contacts/{contact}', [AdminContactController::class, 'show'])->name('contacts.show');
        Route::post('contacts/{contact}/mark-read', [AdminContactController::class, 'markAsRead'])->name('contacts.mark-read');
        Route::delete('contacts/{contact}', [AdminContactController::class, 'destroy'])->name('contacts.destroy');

        // Contact Info Management
        Route::get('contact-info', [AdminContactInfoController::class, 'index'])->name('contact-info.index');
        Route::put('contact-info', [AdminContactInfoController::class, 'update'])->name('contact-info.update');

        // FAQ Management
        Route::resource('faqs', AdminFaqController::class)->except(['show']);

        // Testimonial Management
        Route::resource('testimonials', AdminTestimonialController::class)->except(['show']);

        Route::resource('banks', BankController::class)->except(['create', 'store', 'destroy']);

        // Package Management
        Route::resource('go-invoice-packages', \App\Http\Controllers\Admin\GoInvoicePackageController::class);
        Route::resource('go-bot-packages', \App\Http\Controllers\Admin\GoBotPackageController::class);
        Route::resource('go-soft-packages', \App\Http\Controllers\Admin\GoSoftPackageController::class);
        Route::resource('go-quick-packages', \App\Http\Controllers\Admin\GoQuickPackageController::class);

        // Trial Registration Management
        Route::get('trial-registrations', [TrialRegistrationController::class, 'index'])->name('trial-registrations.index');
        Route::get('trial-registrations/{trialRegistration}', [TrialRegistrationController::class, 'show'])->name('trial-registrations.show');
        Route::post('trial-registrations/{trialRegistration}/mark-read', [TrialRegistrationController::class, 'markAsRead'])->name('trial-registrations.mark-read');
        Route::delete('trial-registrations/{trialRegistration}', [TrialRegistrationController::class, 'destroy'])->name('trial-registrations.destroy');

        // Trial Configuration Management
        Route::get('trials', [TrialController::class, 'index'])->name('trials.index');
        Route::get('trials/{trial}', [TrialController::class, 'show'])->name('trials.show');
        Route::get('trials/{trial}/edit', [TrialController::class, 'edit'])->name('trials.edit');
        Route::put('trials/{trial}', [TrialController::class, 'update'])->name('trials.update');
    });

    Route::group(['middleware' => 'guest'], function () {
        Route::get('login', function () {
            return view('admin.pages.auth.login');
        })->name('login');
    });
});
