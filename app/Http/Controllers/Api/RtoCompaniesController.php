<?php

namespace App\Http\Controllers\Api;

use App\Models\RtoCompany;
use App\Http\Requests\RtoCompanies\IndexRtoCompanyRequest;
use App\Http\Controllers\Controller;
use App\Services\ArrayBuilder\ArrayBuilderAssistant;

class RtoCompaniesController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Get all rto companies
     * @param IndexRtoCompanyRequest $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return array
     */
    public function index(IndexRtoCompanyRequest $request, ArrayBuilderAssistant $abAssistant)
    {
        $model = new RtoCompany;
        $model->setVisible(['id', 'name']);
        $abAssistant->setModel($model);
        $abAssistant->setArrayQuery($request->all());
        $abAssistant->validate();
        if (!$abAssistant->isValid()) {
            return response()->json($abAssistant->getMessages(), 400);
        }

        $result = $abAssistant
            ->apply()
            ->paginate(
                request('page'),
                request('per_page') ? request('per_page') : $this->getPerPageSetting()
            );
        return response()->json($result);
    }
}
