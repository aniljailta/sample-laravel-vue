<?php

namespace App\Http\Controllers\Api;

use Log;
use Auth;
use Exception;
use DB;
use Store;
use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\BuildingHistory;

use App\Http\Controllers\Controller;

class ChartsController extends Controller
{
    public $manufacturerCompanyId;
    public $currentResult;
    public $priorResult;
    public $rAxis;
    public $lAxis;
    public $xAxis;
    public $series;
    public $seriesNames;
    public $chartType;

    public function __construct(Request $request)
    {
        $company = Store::get('company');
        if ($company) {
            $this->manufacturerCompanyId = $company->id;
        }

        $this->rAxis = $request->r_axis ?? 0;
        $this->lAxis = $request->l_axis ?? null;
        $this->xAxis = $request->x_axis ?? 0;
    }

    /**
     * Data for chart1
     *
     * @return \Illuminate\Http\Response
     */
    public function chart1()
    {
        $this->chartType = 1;
        return $this->processChartData();
    }

    /**
     * Data for chart2
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function chart2(Request $request)
    {
        $this->chartType = 2;
        return $this->processChartData();
    }

    /**
     * Data for response
     *
     * @return \Illuminate\Http\Response
     */
    public function processChartData()
    {
        $this->currentResult = $this->getResult($this->rAxis, $this->xAxis);
        $this->priorResult = $this->getResult($this->rAxis, $this->xAxis, true);
        switch ($this->rAxis) {
            case 0:
                $this->seriesNames[1] = 'Daily $ (Current)';
                $this->seriesNames[3] = 'Daily $ (Prior)';
                $this->seriesNames[0] = 'Cumulative $ (Current)';
                $this->seriesNames[2] = 'Cumulative $ (Prior)';
                break;

            case 1:
                $this->seriesNames[1] = 'Daily LFt (Current)';
                $this->seriesNames[3] = 'Daily LFt (Prior)';
                $this->seriesNames[0] = 'Cumulative LFt (Current)';
                $this->seriesNames[2] = 'Cumulative LFt (Prior)';
                break;

            case 2:
                $this->seriesNames[1] = 'Daily SqFt (Current)';
                $this->seriesNames[3] = 'Daily SqFt (Prior)';
                $this->seriesNames[0] = 'Cumulative SqFt (Current)';
                $this->seriesNames[2] = 'Cumulative SqFt (Prior)';
                break;

            case 3:
                $this->seriesNames[1] = 'Count (Current)';
                $this->seriesNames[3] = 'Count (Prior)';
                $this->seriesNames[0] = 'Cumulative Count (Current)';
                $this->seriesNames[2] = 'Cumulative Count (Prior)';
                break;

            default:
                // code...
                break;
        }
        $currentResultCumulative = $this->getResultCumulative($this->currentResult);
        $priorResultCumulative = $this->getResultCumulative($this->priorResult);

        $this->series[0] = $currentResultCumulative['result']->pluck('chart_line');
        $this->series[1] = $this->currentResult['result']->pluck('chart_line');
        $this->series[2] = $priorResultCumulative['result']->pluck('chart_line');
        $this->series[3] = $this->priorResult['result']->pluck('chart_line');

        $xAxisData = $this->setPeriodCondition($this->xAxis, 1, $this->xAxis == 4 ? 'm' : 'd');

        // dd($xAxisData);
        $xAxisData = range(min($xAxisData), max($xAxisData));
        $seriesData = [];
        foreach ($xAxisData as $i => &$date) {
            // $date = str_pad($date, 2, 0, STR_PAD_LEFT);
            if ($i > 0) {

                $seriesData[0][] = in_array($date, $this->currentResult['xAxisData']->all()) ? $this->series[0]->shift() : $seriesData[0][count($seriesData[0]) - 1];
                $seriesData[1][] = in_array($date, $this->currentResult['xAxisData']->all()) ? $this->series[1]->shift() : 0;
                $seriesData[2][] = in_array($date, $this->priorResult['xAxisData']->all()) ? $this->series[2]->shift() : $seriesData[2][count($seriesData[2]) - 1];
                $seriesData[3][] = in_array($date, $this->priorResult['xAxisData']->all()) ? $this->series[3]->shift() : 0;
                if ($this->xAxis == 2) {
                    $date = date('D', strtotime(date('Y-m-') . $date));
                } elseif ($this->xAxis == 4) {
                    $dateObj = \DateTime::createFromFormat('!m', $date);
                    $date = $dateObj->format('M');
                }
            } else {
                $seriesData[0][] = in_array($date, $this->currentResult['xAxisData']->all()) ? $this->series[0]->shift() : 0;
                $seriesData[1][] = in_array($date, $this->currentResult['xAxisData']->all()) ? $this->series[1]->shift() : 0;
                $seriesData[2][] = in_array($date, $this->priorResult['xAxisData']->all()) ? $this->series[2]->shift() : 0;
                $seriesData[3][] = in_array($date, $this->priorResult['xAxisData']->all()) ? $this->series[3]->shift() : 0;
                if ($this->xAxis == 2) {
                    $date = date('D', strtotime(date('Y-m-') . $date));
                } elseif ($this->xAxis == 4) {
                    $dateObj = \DateTime::createFromFormat('!m', $date);
                    $date = $dateObj->format('M');
                }
            }
        }

        if (!in_array($this->xAxis, [2, 4]))
            sort($xAxisData);

        $series = [
            [
                'type' => 'spline',
                'name' => $this->seriesNames[0],
                'data' => $seriesData[0],
                'marker' => [
                    'lineWidth' => 2,
                ],
                'yAxis' => 1,
            ],
            [
                'type' => 'column',
                'name' => $this->seriesNames[1],
                'data' => $seriesData[1],
            ],
            [
                'type' => 'spline',
                'name' => $this->seriesNames[2],
                'data' => $seriesData[2],
                'marker' => [
                    'lineWidth' => 2,
                ],
                'yAxis' => 1,
            ],
            [
                'type' => 'column',
                'name' => $this->seriesNames[3],
                'data' => $seriesData[3],
            ],
        ];

        switch ($this->rAxis) {
            case 0:
                $rAxisText = 'Cumulative $';
                $lAxisText = 'Daily $';
                break;

            case 1:
                $rAxisText = 'Cumulative LFt';
                $lAxisText = 'Daily LFt';
                break;

            case 2:
                $rAxisText = 'Cumulative SqFt';
                $lAxisText = 'Daily SqFt';
                break;

            case 3:
                $rAxisText = 'Cumulative Count';
                $lAxisText = 'Daily Count';
                break;

            default:
                // code...
                break;
        }


        $responseData = [
            'options' => [
                'xAxis' => [
                    'gridLineColor' => "#F3F3F3",
                    'lineColor' => "#F3F3F3",
                    'minorGridLineColor' => "#F3F3F3",
                    'tickColor' => "#F3F3F3",
                    'tickWidth' => 1,
                    'categories' => $xAxisData,
                ],
                'yAxis' => [
                    [
                        'title' => [
                            'text' => $lAxisText,
                        ],
                    ],
                    [
                        'title' => [
                            'text' => $rAxisText,
                        ],
                    ],
                ],
            ],
            'series' => $series,
        ];

        return response()->json($responseData);
    }


    /**
     * Data for series
     *
     * @return array
     */
    private function getResult($rAxis, $xAxis, $prior = false)
    {
        if ($this->chartType == 1) {
            $groupBy = 'date_submitted';

            $result = BuildingHistory::where('building_history.created_at', '>=', Carbon::now()->firstOfMonth())
                ->whereHas('building_status', function ($query) {
                    $query->where('name', 'Ready to Deliver');
                })
              ->groupBy($groupBy)
              ->orderBy($groupBy);

            $byMonth = $xAxis == 4 ? ', MONTH(building_history.created_at) as date_submitted' : ', DATE(building_history.created_at) as date_submitted';
            $selectParams = 'SUM(buildings.total_price) as chart_line, COUNT(building_history.id) as chart_column, DATE(building_history.created_at) as date_submitted';

            switch ($rAxis) {
                case 0:
                    // dd($selectParams);
                    $result->selectRaw($selectParams . $byMonth)
                        ->leftJoin('buildings', function($join) {
                            $join->on('building_history.building_id', '=', 'buildings.id')
                                ->where(MANUFACTURER_COMPANY_ID, '=', $this->manufacturerCompanyId);
                        });
                    break;

                case 1:
                    $result->selectRaw('COUNT(building_history.id) as chart_column, CONVERT(SUM(buildings.length), unsigned) as chart_line' . $byMonth)
                        ->leftJoin('buildings', function($join) {
                            $join->on('building_history.building_id', '=', 'buildings.id')
                                ->where(MANUFACTURER_COMPANY_ID, '=', $this->manufacturerCompanyId);
                        });
                    break;

                case 2:
                    $result->selectRaw('COUNT(building_history.id) as chart_column, CONVERT(SUM(buildings.length * buildings.width), unsigned) as chart_line' . $byMonth)
                        ->leftJoin('buildings', function($join) {
                            $join->on('building_history.building_id', '=', 'buildings.id')
                                ->where(MANUFACTURER_COMPANY_ID, '=', $this->manufacturerCompanyId);
                        });
                    break;

                case 3:
                    $result->selectRaw('SUM(buildings.total_price) as chart_column, COUNT(building_history.id) as chart_line' . $byMonth)
                        ->leftJoin('buildings', function($join) {
                            $join->on('building_history.building_id', '=', 'buildings.id')
                                ->where(MANUFACTURER_COMPANY_ID, '=', $this->manufacturerCompanyId);
                        });
                    break;

                default:
                    // code...
                    break;
            }

            // $result = $this->setPeriodCondition($result, $xAxis, $prior);
            $result->whereBetween('building_history.created_at', $this->setPeriodCondition($xAxis, $prior));
        } else {
            $groupBy = $xAxis == 4 ? 'MONTH(orders.date_submitted)' : 'DATE(orders.date_submitted)';
            $result = Order::where('orders.status_id', 'sale_generated')
                ->groupBy(DB::raw($groupBy))
                ->orderBy(DB::raw($groupBy));
            $byMonth = $xAxis == 4 ? ', MONTH(orders.date_submitted) as date_submitted' : ', DATE(orders.date_submitted) as date_submitted';
            $selectParams = 'SUM(total_sales_price) as chart_line, COUNT(id) as chart_column';

            switch ($rAxis) {
                case 0:
                    $result->selectRaw($selectParams . $byMonth);
                    break;

                case 1:
                    $result->selectRaw('COUNT(orders.id) as chart_column, CONVERT(SUM(building.length), unsigned) as chart_line' . $byMonth)
                        ->leftJoinRelation('building');
                    break;

                case 2:
                    $result->selectRaw('COUNT(orders.id) as chart_column, CONVERT(SUM(building.length * building.width), unsigned) as chart_line' . $byMonth)
                        ->leftJoinRelation('building');
                    break;

                case 3:
                    $result->selectRaw('SUM(total_sales_price) as chart_column, COUNT(id) as chart_line' . $byMonth);
                    break;

                default:
                    // code...
                    break;
            }

            // $result = $this->setPeriodCondition($result, $xAxis, $prior);
            $result->whereBetween('date_submitted', $this->setPeriodCondition($xAxis, $prior));
        }
        $result = $result->get();

        switch ($xAxis) {
            case 0:
            case 1:
            case 3:
                $xAxisData = $result
                    ->pluck('date_submitted')
                    ->map(function ($item, $key) {
                        return date('d', strtotime($item));
                    })
                    ->values();
                break;

            case 2:
                $xAxisData = $result
                    ->pluck('date_submitted')
                    ->map(function ($item, $key) {
                        return date('d', strtotime($item));
                    })
                    ->values();
                break;

            case 4:
                $xAxisData = $result
                    ->pluck('date_submitted')
                    // ->map(function($item, $key){
                    //     return date('m', strtotime($item));
                    // })
                    ->values();
                break;

            default:
                // code...
                break;
        }
        // if($prior)
        // dd($result);

        return compact('result', 'xAxisData');
    }


    /**
     * Period condition for query
     *
     * @return \Illuminate\Database\Query\Builder
     */
    private function setPeriodCondition($period, $prior, $dateFormat = 'Y-m-d H:i:s')
    {
        switch ($period) {
            case 0:
                if ($prior) {
                    $now = Carbon::today()->subMonth()->format($dateFormat);
                    $priorPeriod = Carbon::today()->subDay()->subMonth()->startOfMonth()->format($dateFormat);
                } else {
                    $now = Carbon::today()->format($dateFormat);
                    $priorPeriod = Carbon::today()->subDay()->startOfMonth()->format($dateFormat);
                }
                break;

            case 1:
                if ($prior) {
                    $now = Carbon::today()->subWeek()->format($dateFormat);
                    $priorPeriod = Carbon::today()->subDay()->subWeek(2)->format($dateFormat);
                } else {
                    $now = Carbon::today()->format($dateFormat);
                    $priorPeriod = Carbon::today()->subDay()->subWeek()->format($dateFormat);
                }
                break;

            case 2:
                if ($prior) {
                    $now = Carbon::today()->subWeek()->format($dateFormat);
                    $priorPeriod = Carbon::today()->startOfWeek()->subDay()->subWeek()->format($dateFormat);
                } else {
                    $now = Carbon::today()->format($dateFormat);
                    $priorPeriod = Carbon::today()->startOfWeek()->subDay()->format($dateFormat);
                }
                break;

            case 3:
                if ($prior) {
                    $now = Carbon::today()->subMonth()->format($dateFormat);
                    $priorPeriod = Carbon::today()->subDay()->subMonth(2)->format($dateFormat);
                } else {
                    $now = Carbon::today()->format($dateFormat);
                    $priorPeriod = Carbon::today()->subDay()->subMonth()->format($dateFormat);
                }
                break;

            case 4:
                if ($prior) {
                    $now = Carbon::today()->subYear()->format($dateFormat);
                    $priorPeriod = Carbon::today()->subDay()->subYear()->startOfYear()->format($dateFormat);
                } else {
                    $now = Carbon::today()->format($dateFormat);
                    $priorPeriod = Carbon::today()->subDay()->startOfYear()->format($dateFormat);
                }
                break;

            default:
                // code...
                break;
        }

        // $query = $query->whereBetween('date_submitted', [$priorPeriod, $now]);

        return [$priorPeriod, $now];
    }

    /**
     * Data for series (Cumulative)
     *
     * @return \Illuminate\Http\Response
     */
    private function getResultCumulative($chartData)
    {
        $chartDataCumulative = [
            'result' => collect([]),
            'xAxis' => $chartData['xAxisData'],
        ];
        foreach ($chartData['result'] as $index => $item) {
            $chartDataCumulative['result']->push($item->replicate());
            if ($index === 0) {
                continue;
            } else {
                $chartDataCumulative['result'][$index]->chart_line += $chartDataCumulative['result'][$index - 1]->chart_line;
            }
        };

        return $chartDataCumulative;
    }

}
