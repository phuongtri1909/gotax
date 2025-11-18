<?php

namespace App\Providers;

use App\Models\SMTPSetting;
use App\Observers\SMTPSettingObserver;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class SMTPSettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        SMTPSetting::observe(SMTPSettingObserver::class);
        $this->loadSMTPConfig();
    }

    /**
     * Load SMTP configuration from database
     */
    public static function loadSMTPConfig(): void
    {
        try {
            $smtpSettings = SMTPSetting::first();

            if ($smtpSettings) {
                $port = (int) $smtpSettings->port;
                $encryption = $smtpSettings->encryption ?: null;
                
                $config = [
                    'default' => 'smtp',
                    'mailers' => [
                        'smtp' => [
                            'transport' => 'smtp',
                            'host' => $smtpSettings->host,
                            'port' => $port,
                            'encryption' => $encryption,
                            'username' => $smtpSettings->username,
                            'password' => $smtpSettings->password,
                            'timeout' => null,
                            'local_domain' => null,
                        ],
                    ],
                    'from' => [
                        'address' => $smtpSettings->from_address,
                        'name' => $smtpSettings->from_name,
                    ],
                ];

                config(['mail' => array_merge(config('mail'), $config)]);
            } else {
                Log::warning('No SMTP settings found in database, using .env configuration');
            }
        } catch (\Exception $e) {
            Log::error('Failed to load SMTP settings from database', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}