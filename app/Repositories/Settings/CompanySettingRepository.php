<?php

namespace App\Repositories\Settings;

use App\Models\Company;
use App\Models\ManufacturerCompany;
use App\Models\RtoCompany;
use App\Models\SuperAdminCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class CompanySettingRepository
{
    /**
     * CompanyRepository constructor.
     */
    public function __construct()
    {
    }

    /**
     * Get settings scoped to company
     * @param Company $company
     * @return Model
     */
    public function getCompanyDetails(Company $company): Model {
        $companySettings = $company->company;
        if ($companySettings->logo) {
            $companySettings->append('company_logo_public_path');
        }

        return $companySettings;
    }

    /**
     * @param Company $company
     * @param array $params
     * @return bool
     */
    public function update(Company $company, array $params): bool {
        if ($company->role_id === 'manufacturer') {
            return ManufacturerCompany::where(COMPANY_ID, $company->id)->update($params);
        }

        if ($company->role_id === 'rto') {
            return RtoCompany::where(COMPANY_ID, $company->id)->update($params);
        }

        if ($company->role_id === 'super_admin') {
            return SuperAdminCompany::where(COMPANY_ID, $company->id)->update($params);
        }

        return false;
    }
}