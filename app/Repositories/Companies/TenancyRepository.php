<?php

namespace App\Repositories\Companies;

use App\Exceptions\GeneralException;
use App\Models\BuildingStatus;
use App\Models\Company;
use App\Models\ManufacturerCompany;
use App\Models\RtoCompany;
use App\Models\SuperAdminCompany;
use App\Repositories\BaseRepository;
use App\Repositories\Settings\CompanySettingRepository;
use DB;
use Exception;
use Log;

class TenancyRepository extends BaseRepository
{
    /**
     * CompanyRepository constructor.
     */
    public function __construct()
    {

    }

    /**
     * Add tenancy with dependencies (transaction wrapped)
     * @param Company $company
     * @return Company
     * @throws GeneralException
     */
    public function add(Company $company): Company
    {
        try {
            DB::beginTransaction();
            $company->save();

            $this->populateDefaultCompanySettings($company);

            DB::commit();
            return $company;
        } catch (Exception $e) {
            Log::error($e);
            DB::rollback();
            throw new GeneralException(trans('exceptions.tenancy.unable_to_save_new_tenancy', [
                'message' => $e->getMessage(),
            ]));
        }
    }

    /**
     * Force Update or Create default system building statuses
     * @param Company $company
     * @return bool
     */
    public function populateDefaultCompanySettings(Company $company)
    {
        if ($company->role_id === 'manufacturer') $model = 'App\Models\ManufacturerCompany';
        if ($company->role_id === 'rto') $model = 'App\Models\RtoCompany';
        if ($company->role_id === 'super_admin') $model = '\App\Models\SuperAdminCompany';

        $company = $model::firstOrcreate([
            COMPANY_ID => $company->id,
            'time_zone' => 'America/Phoenix',
            'per_page' => 100,
        ]);
        Log::info("[Company #{$company->id}] Adding company type: {$company->toJson()} ");

        return true;
    }
}