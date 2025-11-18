<?php

namespace App\Observers;

use App\Models\SMTPSetting;
use App\Providers\SMTPSettingsServiceProvider;

class SMTPSettingObserver
{
    public function created(SMTPSetting $smtpSetting): void
    {
        $this->reloadSMTPConfig();
    }

    public function updated(SMTPSetting $smtpSetting): void
    {
        $this->reloadSMTPConfig();
    }

    public function deleted(SMTPSetting $smtpSetting): void
    {
        $this->reloadSMTPConfig();
    }

    protected function reloadSMTPConfig(): void
    {
        try {
            SMTPSettingsServiceProvider::loadSMTPConfig();
        } catch (\Exception $e) {
            \Log::error('Failed to reload SMTP config after settings change', [
                'error' => $e->getMessage()
            ]);
        }
    }
}

