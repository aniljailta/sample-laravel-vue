<?php

namespace App\Repositories;

use DB;
use App\Models\Bill;
use App\Models\BuildingHistory;
use App\Models\BuildingLocation;
use Illuminate\Config;

class BillRepository
{
    protected $billModel;

    public function __construct(Bill $billModel)
    {
        $this->billModel = $billModel;
    }

    /**
     * Get bills+sum amount based on criterias (dynamic Bill Report)
     * @param $conditions
     * @param $dimensions
     * @param $sorting
     * @return mixed
     */
    public function getByCriterias($conditions, $dimensions, $sorting)
    {
        $getBills = $this->billModel->select('bills.id', DB::raw("SUM(amount) as sum_amount"));
        $groupBy = [];
        $countTotals = [
            'select' => [
                'sum(sum_amount) as sum_amount',
                'count(distinct(id)) as count_bills'
            ]
        ];

        if ( isset($dimensions['date']) && $dimensions['date'] ) {
            $getBills->addSelect('bills.date');

            $groupBy[] = 'date';
        }

        if ( isset($dimensions['user_id']) && $dimensions['user_id'] ) {
            $getBills->addSelect('bills.user_id');
            $getBills->with(['user' => function ($query) use ($dimensions) {
                $query->select('id', 'first_name', 'last_name');
            }]);

            $groupBy[] = 'user_id';
            $countTotals['select'][] = 'count(distinct(user_id)) as count_users';
        }

        $getBills->with([
                'building_history' => function($query) {
                    $query->with('building');
                },
                'building_locations' => function($query) {
                    $query->with('building');
                }]);

        if (isset($conditions['date']['start'])) {
            $getBills->where('date', '>=', $conditions['date']['start']);
        }

        if (isset($conditions['date']['end'])) {
            $getBills->where('date', '<=', $conditions['date']['end']);
        }

        if ( isset($conditions['user_id']) ) {
            $getBills->where('user_id', $conditions['user_id']);
        }

        if ( isset($conditions['building_id']) ) {
            $getBills->whereHas('building_history', function($query) use($conditions) {
                $query->where('building_id', '=', $conditions['building_id']);
            });

            $getBills->whereHas('building_locations', function($query) use($conditions) {
                $query->where('building_id', '=', $conditions['building_id']);
            });
        }

        if ( $groupBy )
            $getBills->groupBy($groupBy);

        // queries for count/sum TOTAL items
        $getBillsTotals = DB::table( DB::raw("({$getBills->toSql()}) as sub") )->mergeBindings($getBills->getQuery());
        foreach($countTotals['select'] as $countTotalSelect)
            $getBillsTotals->selectRaw($countTotalSelect);
        $bills['totals'] = $getBillsTotals->get();


        $getBills->orderBy($sorting['field'], ($sorting['direction']) ? 'desc' : 'asc');

        //DB::enableQueryLog();
        //DB::statement("SET @@sql_mode = REPLACE( REPLACE( REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY,', ''), ',ONLY_FULL_GROUP_BY', ''), 'ONLY_FULL_GROUP_BY', '');");
        $bills['items'] = $getBills->paginate(25);
        //DB::statement("SET @@sql_mode = CONCAT(@@sql_mode, ',', 'ONLY_FULL_GROUP_BY');");
        //dd(DB::getQueryLog());

        return $bills;
    }
}