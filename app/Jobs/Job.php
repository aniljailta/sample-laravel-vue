<?php

namespace App\Jobs;

use App\Models\Company;
use App\Services\Tenancy\TenancyService;
use Illuminate\Bus\Queueable;

class Job
{
    /*
    |--------------------------------------------------------------------------
    | Queueable Jobs
    |--------------------------------------------------------------------------
    |
    | This job base class provides a central location to place any logic that
    | is shared across all of your jobs. The trait included with the class
    | provides access to the "onQueue" and "delay" queue helper methods.
    |
    */

    use Queueable;

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
