<?php

namespace App\Notifications;

use App\Models\Company;
use App\Services\Tenancy\TenancyService;
use Illuminate\Notifications\Notification as VendorNotification;

class Notification extends VendorNotification
{
    /**
     * Apply tenant settings for queued jobs
     * @param Company $company
     * @return bool
     */
    public function applyTenantScope(Company $company) {
        $tenancyService = new TenancyService;
        return $tenancyService->applyTenant($company);
    }
}
