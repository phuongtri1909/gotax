<?php

namespace App\Observers;

use App\Models\ContactInfo;
use Illuminate\Support\Facades\Cache;

class ContactInfoObserver
{
    /**
     * Handle the ContactInfo "created" event.
     */
    public function created(ContactInfo $contactInfo): void
    {
        $this->clearCache();
    }

    /**
     * Handle the ContactInfo "updated" event.
     */
    public function updated(ContactInfo $contactInfo): void
    {
        $this->clearCache();
    }

    /**
     * Handle the ContactInfo "deleted" event.
     */
    public function deleted(ContactInfo $contactInfo): void
    {
        $this->clearCache();
    }

    /**
     * Handle the ContactInfo "restored" event.
     */
    public function restored(ContactInfo $contactInfo): void
    {
        $this->clearCache();
    }

    /**
     * Handle the ContactInfo "force deleted" event.
     */
    public function forceDeleted(ContactInfo $contactInfo): void
    {
        $this->clearCache();
    }

    /**
     * Clear contact info cache
     */
    private function clearCache(): void
    {
        Cache::forget('contact_info');
    }
}
