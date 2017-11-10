<?php

namespace App\Services\Tenancy;

use App\Exceptions\GeneralException;
use App\Models\Company;
use App\Models\Setting;
use App\Repositories\Companies\CompanyRepository;
use App\Repositories\Companies\TenancyRepository;
use App\Repositories\Settings\CompanySettingRepository;

use Landlord;
use DB;
use Store;
use URL;

class TenancyService
{
    /**
     * @var CompanyRepository
     * @var TenancyRepository
     */
    protected $companyRepository;
    protected $tenancyRepository;

    public function __construct()
    {
        $this->companyRepository = new CompanyRepository;
        $this->tenancyRepository = new TenancyRepository;
    }

    /**
     * Detect tenant company based on HTTP HOST
     * @return Company
     * @throws GeneralException
     */
    public function detectTenant(): Company {
        $company = $this->companyRepository->findByDomain($_SERVER['HTTP_HOST']);
        if (!$company) {
            throw new GeneralException(trans('exceptions.tenancy.domain_is_not_found'));
        }

        return $company;
    }

    /**
     * Apply tenant configs
     * @param Company $company
     * @return bool
     */
    public function applyTenant(Company $company) {
        // the same as
        // Landlord::addTenant(COMPANY_TENANT_ID, $company->id);
        // Landlord::addTenant($company);

        // Scoped to company
        Landlord::addTenant(COMPANY_ID, $company->id);

        // Scoped to role
        if ($company->role_id === 'manufacturer') Landlord::addTenant(MANUFACTURER_COMPANY_ID, $company->id);
        if ($company->role_id === 'rto') Landlord::addTenant(RTO_COMPANY_ID, $company->id);

        Landlord::applyTenantScopesToDeferredModels();

        $companyDetails = (new CompanySettingRepository)->getCompanyDetails($company);

        config([
            'app.name' => $companyDetails->name,
            'app.url' => $company->domain
        ]);

        if ($company->role_id === 'manufacturer') {
            config([
                'mail.host' => $companyDetails->mail_host,
                'mail.port' => $companyDetails->mail_port,
                'mail.username' => $companyDetails->mail_username,
                'mail.password' => $companyDetails->mail_password,
                'mail.encryption' => $companyDetails->mail_encryption,

                'mail.from.address' => $companyDetails->mail_from,
                'mail.from.name' => $companyDetails->name,
            ]);

            config([
                'hellosign.client_id' =>  $companyDetails->hellosign_client_id,
            ]);
        }
        // another company roles uses
        // default/fallback mail settings from .env

        // TODO: scope disk to domain prefixied?
            // config(['filesystems.disks.public.root' => '']);
            // config(['filesystems.disks.public.url' => '']);

        // force overwrite mail settings
        (new \Illuminate\Mail\MailServiceProvider(app()))->register();

        // share company settings globally to templates
        view()->share('companySettings', $companyDetails);

        // store tenant company globally
        Store::set('company', $company);
        Store::set('company_settings', $companyDetails);

        // specific console configuration
        // force overwrite url settings
        // (used in laravel url() helper)
        if (app()->runningInConsole()) {
            URL::forceRootUrl(config('app.url'));
        }

        return true;
    }

    /**
     * Add new tenant company
     * @param array $companyParams
     * @return Company
     */
    public function addTenant(array $companyParams): Company {
        $company = new Company($companyParams);
        $company = $this->tenancyRepository->add($company);

        return $company;
    }
}