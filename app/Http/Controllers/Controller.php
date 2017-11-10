<?php

namespace App\Http\Controllers;

use Store;
use App\Models\Setting;
use App\Repositories\Settings\CompanySettingRepository;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getPerPageSetting()
    {
        $company = Store::get('company');
        return (new CompanySettingRepository)->getCompanyDetails($company)->per_page;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function returnJsonErrorResponse()
    {
        return response()->json(['Something went wrong'], 422);
    }
}
