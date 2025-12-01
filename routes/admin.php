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
use App\Http\Controllers\Admin\PackageUpgradeConfigController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReferralPurchaseController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\PolicyController;

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

        // Package Upgrade Config Management
        Route::get('package-upgrade-configs', [PackageUpgradeConfigController::class, 'index'])->name('package-upgrade-configs.index');
        Route::get('package-upgrade-configs/{packageUpgradeConfig}', [PackageUpgradeConfigController::class, 'show'])->name('package-upgrade-configs.show');
        Route::get('package-upgrade-configs/{packageUpgradeConfig}/edit', [PackageUpgradeConfigController::class, 'edit'])->name('package-upgrade-configs.edit');
        Route::put('package-upgrade-configs/{packageUpgradeConfig}', [PackageUpgradeConfigController::class, 'update'])->name('package-upgrade-configs.update');

        // Purchase Management
        Route::get('purchases', [PurchaseController::class, 'index'])->name('purchases.index');
        Route::get('purchases/{toolType}/{id}', [PurchaseController::class, 'show'])->name('purchases.show');

        // User Management
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');

        // Referral Purchase Management
        Route::get('referral-purchases', [ReferralPurchaseController::class, 'index'])->name('referral-purchases.index');
        Route::get('referral-purchases/{referralPurchase}', [ReferralPurchaseController::class, 'show'])->name('referral-purchases.show');

        // Document Management
        Route::resource('documents', DocumentController::class);

        // Policy Management
        Route::resource('policies', PolicyController::class);
    });

    Route::group(['middleware' => 'guest'], function () {
        Route::get('login', function () {
            return view('admin.pages.auth.login');
        })->name('login');
    });
});
