<?php

namespace App\Services\Reports;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Building;
use App\Validators\ReportValidator;
use App\Services\Reports\ReportService;
use App\Repositories\BillRepository;

use Carbon\Carbon;

class BillReportService extends ReportService
{

    /**
     * Contains data table (report)
     */
    public $data;

    public $validator;

    public $rules = [];

    /**
     * Strict array of report rules (required for building UI/checks)
     * @var array
     */
    public $rulesScheme = [
        'default' => [
            'dimensions_fixed' => ['amount'],
            'dimensions' => [ 'date', 'user_id' ],
            'fields' => [ 'date', 'user_id' ],
        ],
        'admin' => [ ]
    ];

    /**
     * Strict array of report dimensions (dynamically specifed by user)
     * @var array
     */
    public $dimensionAttributes = [
        'bill_number' => ['title' => 'Bill Number', 'index' => 1],
        'date' => ['title' => 'Date', 'index' => 2],
        'user_id' => ['title' => 'User', 'index' => 3],
        'building_id' => ['title' => 'Building', 'index' => 4],
        'amount' => ['title' => 'Amount', 'index' => 5]
    ];

    /**
     * Strict array of report condtions (dynamically specifed by user)
     * @var array
     */
    public $conditionAttributes = [
        'date' => [ 'title' => 'Date', 'index' => 1 ],
        'user_id' => [  'title' => 'User', 'index' => 2 ],
        'building_id' => [ 'title' => 'Building', 'index' => 3 ]
    ];

    /**
     * Strict array of report totals
     * @var array
     */
    public $totalAttributes = [
        'sum_amount' => ['title' => 'Total Amount', 'index' => 1],
        'count_users' => ['title' => 'Total Users', 'index' => 2],
        'count_bills' => ['title' => 'Total Bills', 'index' => 3]
    ];

    protected function getConditions(array $inputConditions) {
        $conditions = [];

        // Check and set defaults conditions/dimensions
        if ( isset($inputConditions['date']) ) {
            $conditions['date']['start'] = date('Y-m-d', strtotime($inputConditions['date']['start']));
            $conditions['date']['end'] = date('Y-m-d', strtotime($inputConditions['date']['end']));
        }

        if ( isset($inputConditions['user_id']) ) $conditions['user_id'] = $inputConditions['user_id'];
        if ( isset($inputConditions['building_id']) ) $conditions['building_id'] = $inputConditions['building_id'];

        return $conditions;
    }

    protected function getDimensions(array $inputDimensions) {
        $dimensions = [];

        if ( isset($inputDimensions['building_id']) ) $dimensions['building_id'] = 'building_id';
        if ( isset($inputDimensions['user_id']) ) $dimensions['user_id'] = 'user_id';
        if ( isset($inputDimensions['date']) ) $dimensions['date'] = 'date';

        if ((isset($dimensions['date']) && $dimensions['date']) &&
            (isset($dimensions['user_id']) && $dimensions['user_id']) ) {
            $dimensions['bill_number'] = 'bill_number';
        }

        return $dimensions;
    }

    /**
     * Get rules for build UI
     * @return array
     */
    public function getRules()
    {
        $dimensions = $this->getDimensionAttributes($this->rulesScheme['default']['dimensions']);
        $conditions = $this->getConditionAttributes($this->rulesScheme['default']['fields']);

        if ( isset($conditions['user_id']) )
        {
            $users = User::all()->pluck('full_name', 'id');
            $conditions['user_id']['data'] = $users;
            $conditions['user_id']['disabled'] = (count($users) > 0) ? false : true;
        }

        if ( isset($conditions['building_id']) )
        {
            $buildings = Building::all()->pluck('serial_number', 'id');
            $conditions['building_id']['data'] = $buildings;
            $conditions['building_id']['disabled'] = (count($buildings) > 0) ? false : true;
        }

        $conditions['date']['start'] = Carbon::now()->startOfMonth()->toDateString();
        $conditions['date']['start_formatted'] = date('F j, Y', strtotime($conditions['date']['start']));
        $conditions['date']['end'] = Carbon::now()->toDateString();
        $conditions['date']['end_formatted'] = date('F j, Y', strtotime($conditions['date']['end']));

        $this->rules['tz_offset'] = 0;
        $this->rules['report_type'] = 'bills';
        $this->rules['dimensions'] = $dimensions;
        $this->rules['conditions'] = $conditions;
        $this->rules['defaults']['dimensions'] = array('date', 'user_id');
        $this->rules['defaults']['conditions'] = array('date');

        return $this->rules;
    }

    /**
     * Request + parse data, return mixed (data + html)
     * @param Request $request
     * @param BillRepository $billRepository
     * @return $this
     */
    public function getReport(Request $request, BillRepository $billRepository)
    {
        $inputAll = $request->all();
        $this->validator = ReportValidator::make($inputAll)->scope('bills');

        if ( $this->validator->fails() ) {
            return $this;
        }

        $conditions = $this->getConditions($request->input('conditions', []));
        $dimensions = $this->getDimensions($request->input('dimensions', []));
        $sorting = $this->getSorting($request->input('sort', []));

        // Build Data + Pagination
        $stats = $billRepository->getByCriterias($conditions, $dimensions, $sorting);
        $statsHead = $this->getDimensionAttributes($dimensions, $this->rulesScheme['default']['dimensions_fixed'], $sorting);
        $statsData = $stats['items'];
        $statsTotals = $stats['totals'];

        if ( $statsData->count() ) {
            $this->parseStatsbyDimensions($dimensions, $statsData);

            $this->data['stats_table']['data'] = $statsData->toArray()['data'];
            $this->data['stats_table_pagination'] = $statsData->render();
        }

        if ( !is_null($statsTotals) )
        {
            $totalAtts = [];
            foreach($statsTotals as &$totals) {
                $totals->sum_amount = '$'.number_format($totals->sum_amount, 2, '.', ',');

                if( isset($totals->count_users)) $totalAtts[] = 'count_users';
                if( isset($totals->count_bills)) $totalAtts[] = 'count_bills';
                if( isset($totals->sum_amount)) $totalAtts[] = 'sum_amount';
            }

            $this->data['stats_table']['totals']['params'] = $this->getTotalAttributes($totalAtts);
            $this->data['stats_table']['totals']['items'] = $statsTotals[0];
        }

        $this->data['stats_table']['head'] = $statsHead;

        return $this;
    }

    private function parseStatsbyDimensions(array $dimensions, &$statsData) {

        foreach ($statsData as &$bill) {

            if ( isset($dimensions['bill_number']) ) {
                $bill['bill_number'] = $bill->number;
            }

            if ( isset($bill->date) ) {
                $bill['date_formatted'] = date('j F, Y', strtotime($bill->date));
            }
            if ( isset($bill->sum_amount) ) {
                $bill['date_formatted'] = date('j F, Y', strtotime($bill->date));
                $bill['amount_formatted'] = number_format($bill['sum_amount'], 2, '.', ',');
            }
        }
    }

}
