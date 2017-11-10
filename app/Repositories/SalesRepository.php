<?php

namespace App\Repositories;

use DB;
use App\Models\Sale;
use Illuminate\Config;
use Entrust;
use Carbon\Carbon;
use App\Models\Building;
use App\Models\Order;

class SalesRepository
{
    protected $saleModel;

    protected $orderModel;

    public function __construct(Sale $saleModel , Order $orderModel)
    {
        $this->saleModel = $saleModel;
        $this->orderModel =  $orderModel;
    }

    public function getChartData( $month = 0 )
    {
        $sales = Sale::with(['order'])
        ->where('status_id','invoiced')
        ->whereMonth('updated_at','=',$month)
        ->orderBy('updated_at','asc')
        ->get();
        $data['daily'] = $this->_arrangeSales( $sales ,false);
        $data['cumulative'] = $this->_arrangeSales( $sales, true );
        return $data;
        
    }

    protected function _getSaleDateRecords( $records, $date )
    {
        $response = [];
        if( isset( $records ) && $records->count()){
            foreach ($records as $value) {
                # code...
                if( !$value->created_at->diffInDays($date) ){
                    $response[] = $value;
                }
            }
            
        }
        return $response;

    }

    protected function _arrangeSales( $sales, $condition = false )
    {   
        if( $sales->count() ){
            $data= [];
            foreach ($sales as  $value) {
                    # code...
                $index = (int)date('d',strtotime($value->updated_at));
                $data[$index][] = $value->order->total_amount ? $value->order->total_amount : 0.00;

            }
            $data = collect($data)->map(function( $q ){
                return array_sum($q);
            });
            if( $condition ){
                $new = [];
                foreach ($data as $key => $value) {
                        # code...
                    $new[$key] = $this->_cumulativeAdd( $data, $key);
                }
                $data = collect($new);

            }
            return $data;
        }
        return false;

    }

    protected function _cumulativeAdd( $parent,$pKey ) 
    {
        $data = 00.00;
        foreach ($parent as $key => $value) {
            $data = $data + (float)$value;
            if( $key == $pKey ){
                break;
            }

        }
        return $data;
    }


    public function getProductionChartData($month)
    {
        $building = Building::with('last_status')
        ->join('building_history',function($join) use($month){
            $join->on('buildings.status_id','=','building_history.status_id')
            ->where('building_history.status_id','=',4)
            ->whereMonth('building_history.updated_at','=',$month);
        })
        ->get();
        
    }

    public function checkifSalesExists(int $building_id){
        return $this->orderModel->where('building_id',$building_id)->where('status_id','sale_generated')->count();
    }

    public function getSaleLocation(int $building_id){
        return $this->saleModel->with('location')->where('building_id',$building_id)->first();
    }

}