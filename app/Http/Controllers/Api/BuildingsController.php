<?php

namespace App\Http\Controllers\Api;

use App\Models\Setting;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use Event;
use Exception;
use DB;
use Store;
use Log;
use Auth;

use App\Models\File;
use App\Models\Dealer;
use App\Models\Building;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Buildings\AddBuildingRequest;
use App\Http\Requests\Buildings\UpdateBuildingRequest;
use App\Http\Requests\Buildings\DeleteBuildingRequest;

use App\Services\Building\BuildingImportService;
use App\Services\Building\BuildingService;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use App\Services\ArrayBuilder\ArrayBuilderAssistant;

class BuildingsController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new Building());
        $abAssistant->setArrayQuery($request->all());
        $abAssistant->validate();
        if (!$abAssistant->isValid()) {
            return response()->json($abAssistant->getMessages(), 400);
        }
        $query = $abAssistant->apply()->getQuery();

        if (isset($request->all()['include'])) {
            if (in_array('order.sale', $request->all()['include'])) {
                $query->where(function($q) {
                    $q->where('order_rel_sale.status_id', '<>', 'cancelled');
                    $q->orWhereNull('order_rel_sale.status_id');
                });
            }
            if (in_array('order', $request->all()['include'])) {
                $query->where(function($q) {
                    $q->where('order.status_id', '<>', 'cancelled');
                    $q->orWhereNull('order.status_id');
                });
            }
        }

        $result = $abAssistant
            ->setQuery($query)
            ->paginate(
                request('page'),
                request('per_page') ? request('per_page') : $this->getPerPageSetting()
            );

        return response()->json($result);
    }

    public function indexDealerInventory(Request $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new Building());
        $abAssistant->setArrayQuery($request->all());
        $abAssistant->validate();
        if (!$abAssistant->isValid()) {
            return response()->json($abAssistant->getMessages(), 400);
        }
        $query = $abAssistant->apply()->getQuery();

        $query->join('locations as location_tbl', function($join) {
            $join->on('last_location.location_id', '=', 'location_tbl.id')
                 ->where('location_tbl.category', '=', 'dealer')
                 ->where('location_tbl.is_active', '=', 'yes');
        });

        $result = $abAssistant->setQuery($query)->paginate(
            request('page'),
            request('per_page') ? request('per_page') : $this->getPerPageSetting()
        );

        return response()->json($result);
    }

    /**
     * Get resource
     * @param $id
     * @param Request $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return array
     */
    public function show($id, Request $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new Building());
        $abAssistant->setArrayQuery($request->all());
        $abAssistant->validate();
        if (!$abAssistant->isValid()) {
            return response()->json($abAssistant->getMessages(), 400);
        }

        $query = $abAssistant->apply()->getQuery();
        try {
            $item = $query->findOrFail($id);
            return response()->json($item);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['Building is not found.'], 422);
        }
    }

    /**
     * Store the specified resource in storage.
     *
     * @param AddBuildingRequest| $request
     * @param BuildingService $buildingService
     * @return \Illuminate\Http\Response
     */
    public function store(AddBuildingRequest $request, BuildingService $buildingService)
    {
        try {
            DB::transaction(function () use ($request, &$building, $buildingService) {
                $buildingData = $request->all();
                $buildingData['files'] = $request->file('upload_files') ?? null;

                $opts = [];
                if (Auth::user()->hasRole('administrator')) {
                    $opts['default_status'] = Building::DEFAULT_ROLE_STATUSES['administrator'];
                    $opts['update_serial_number'] = true;
                }

                $building = $buildingService->create($buildingData, $opts);
            });

            return response()->json([
                'payload' => $building,
                'msg' => 'Building successfully added.'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['msg' => 'Something went wrong.'], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateBuildingRequest| $request
     * @param  int $id
     * @param BuildingService $buildingService
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBuildingRequest $request, $id, BuildingService $buildingService)
    {
        $building = Store::get('building');

        try {
            DB::transaction(function () use ($request, &$building, $buildingService) {
                $buildingData = $request->all();
                $buildingData['files'] = $request->file('upload_files') ?? null;

                $opts = $request->input('opts', []);
                if (Auth::user()->hasRole('administrator')) {
                    $opts['update_serial_number'] = true;
                }

                $building = $buildingService->update($building, $buildingData, $opts);
            });

            return response()->json([
                'payload' => $building,
                'msg' => 'Building successfully updated.'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['msg' => 'Something went wrong.'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteBuildingRequest $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteBuildingRequest $request)
    {
        try
        {
            // get data which has got through validator
            $building = Store::get('building');
            $building->delete();
            return response()->json(['Building successfully deleted.']);
        } catch (Exception $e)
        {
            return response()->json(['Something went wrong.'], 422);
        }
    }
    
    /**
     * Display a listing of the resource.
     * TODO: now it used only in deliveries
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function perId(Request $request)
    {
        $buildings = Building::with([
            'sales',
            'sales.order_reference_name',
            'last_location',
            'last_location.location',
        ])->get()->keyBy('id');

        return response()->json($buildings);
    }
    
    /**
     * Get building by dealer id
     * @param Request $request
     * @return array
     */
    public function dealerInventory(Request $request)
    {
        $dealerID = $request->route('dealer_id');
        $buildingSerial = $request->route('building_serial');

        try
        {
            $dealer = Dealer::findOrFail($dealerID);
            $building = Building::with([
                'building_options',
                'building_options.option' => function($q) {
                    $q->withTrashed();
                },
                'building_options.option.category' => function($q) {
                    $q->withTrashed();
                },
                'building_options.colors' => function($q) {
                    $q->withTrashed();
                },
            ])
                ->select('buildings.*')
                ->join('building_locations', 'building_locations.id', '=', 'buildings.last_location_id')
                ->where('building_locations.location_id', $dealer->location_id)
                ->where('serial_number', $buildingSerial)
                ->firstOrFail();

            // $options = $building->options->pluck('name')->toArray();
            $options = [];
            $building->building_options->each(function($item, $key) use(&$options) {
                if ($item->category->group === 'discounts') {
                    $options[] = ['name' => "{$item->option->name}", 'quantity' => '$'.$item->quantity, 'unit_price' => $item->unit_price, 'total_price' => $item->total_price];
                } else {
                    $options[] = ['name' => "{$item->option->name}", 'quantity' => $item->quantity, 'unit_price' => $item->unit_price, 'total_price' => $item->total_price];
                }

                if ($item->category->group === 'siding' && $item->color) {
                    array_unshift($options, ['name' => "Siding color: {$item->color->name}", 'quantity' => false]);
                }

                if ($item->category->group === 'trim' && $item->color) {
                    array_unshift($options, ['name' =>"Trim color: {$item->color->name}", 'quantity' => false]);
                }

                if ($item->category->group === 'roof' && $item->color) {
                    array_unshift($options, ['name' => "Roof color: {$item->color->name}", 'quantity' => false]);
                }
            });

            return [
                'status' => 'success',
                'payload' => [
                    'serial' => $building->serial_number,
                    'shellPrice' => (double) $building->shell_price,
                    'totalOptions' => (double) $building->total_options,
                    'price' => (double) $building->total_price,
                    'securityDeposit' => (double) $building->security_deposit,
                    'options' => $options
                ]
            ];
        } catch (ModelNotFoundException $e)
        {
            return ['status' => 'error', 'message' => 'No building matches the provided Serial Number'];
        }
    }

    /**
     * Get building by similar style AND dimension
     * @param Request $request
     * @return array
     */
    public function similarInventory(Request $request)
    {
        $buildingModelId = $request->get('building_dimension');
        $validator = Validator::make(
            [ 'building_model_id' => $buildingModelId ],
            [ 'building_model_id' => 'required|numeric' ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }

        try
        {
            $buildings = Building::with(['options', 'last_location.dealer'])
                ->select('buildings.*')
                ->where('building_model_id', $buildingModelId)
                ->join('building_locations', 'building_locations.id', '=', 'buildings.last_location_id')
                ->rightJoin('dealers', 'dealers.location_id', '=', 'building_locations.location_id')
                ->get();

            return [
                'status' => 'success',
                'payload' => [
                    'buildings' => $buildings
                ]
            ];
        } catch (ModelNotFoundException $e)
        {
            return ['status' => 'error', 'message' => 'No building matches the provided Building Style & Dimension'];
        }
    }
    
    /**
     * Search dealer inventory by query (per dealer)
     * @param Request $request
     * @return array
     */
    public function dealerInventorySearch(Request $request)
    {
        $usedOnly = $request->get('used_rto_owner') === 'used' ? 1 : 0;
        $dealerID = $request->input('dealer');
        $query = $request->input('query');
        $condition = $request->input('condition', '%');
        $loc = $request->input('loc', 1);
        $validator = Validator::make(
            [
                'dealer' => $dealerID,
                'query' => $query,
                'condition' => $condition,
                'loc' => $loc,
            ],
            [
                'dealer' => 'required|exists:dealers,id,deleted_at,NULL',
                'query' => 'required|alpha_dash',
                'condition' => 'in:=,%',
                'loc' => 'in:0,1'
            ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->all(), 422);
        }

        try
        {
            $dealer = Dealer::findOrFail($dealerID);
            $buildings = Building::select('buildings.*');

            if ($loc) {
                $buildings->join('building_locations', 'building_locations.id', '=', 'buildings.last_location_id')
                    ->where('building_locations.location_id', $dealer->location_id);
            }

            if ($request->has('used_rto_owner') && $usedOnly) {
                $buildings->where('used_rto_owner', '<>', 0);
            } elseif ($request->has('used_rto_owner')) {
                $buildings->where('used_rto_owner', '=', 0);
            }

            // TODO: fix
            // dont use this filter for dealer order form
            // $buildings->whereNotNull('buildings.date_building_returned');

            if ($condition === '%') $buildings->where('serial_number', 'like', "%{$query}%");
            if ($condition === '=') $buildings->where('serial_number', '=', $query);

            $buildings = $buildings->get();
            if ( $buildings->isEmpty() ) {
                return response()->json(['message' => 'No building matches the provided Serial Number'], 422);
            }

            return $buildings;
        } catch (Exception $e)
        {
            Log::error($e);
            return response()->json(['message' => 'No building matches the provided Serial Number'], 422);
        }

    }

    /**
     * Importing buildings by data file
     * @param Request $request
     * @param BuildingImportService $buildingImportService
     * @return array
     */
    public function import(Request $request, BuildingImportService $buildingImportService)
    {
        if (!$request->hasFile('import_data_file'))
            return ['status' => 'error', 'error' => 'No file uploaded.'];
        
        try
        {
            $filePath = $request->file('import_data_file')->getPathName();
            $content = \File::get($filePath);
        } catch (FileNotFoundException $exception)
        {
            return ['status' => 'error', 'error' => 'The file doesn\'t exist.'];
        }

        $importService = $buildingImportService->importContent($content);
        if ( $importService->validator()->failed() )
        {
            $response['status'] = 'error';
            $response['error'] = implode( "\r\n", $importService->validator()->instance()->errors()->all() );
        } else
        {
            $response['status'] = 'success';
        }

        return response()->json($response);
    }

    /**
     * Get files specified building
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getFiles(Request $request)
    {
        $buildingId = $request->route('building_id');
        $files = File::where('storable_type', '=', 'building')
            ->where('storable_id', '=', $buildingId)->get();

        return response()->json($files);
    }

    public function exportCsv(Request $request, ArrayBuilderAssistant $abAssistant)
    {
        $queryParams = $request->all();
        $abAssistant->setModel(new Building());
        $abAssistant->setArrayQuery($queryParams);
        $abAssistant->validate();
        if (!$abAssistant->isValid()) {
            return response()->json($abAssistant->getMessages(), 400);
        }
        $result = $abAssistant->apply()->get()->toArray();

        $header = $this->getExportDataHeader($result[0]);

        Excel::create('export'.date("Ymd-H:i:s"), function($excel) use ($result, $header) {
            $excel->sheet('Buildings Export '. date("Y-m-d"), function($sheet) use ($result, $header){
                $sheet->row(1, $header);
                $rowNumber = 2;
                foreach ($result as $key => $item) {
                    $data = $this->getExportDataValues($header, $item);
                    $sheet->row($rowNumber, $data);
                    $rowNumber++;
                }
            });
        })->download('csv');
    }

    public function exportXls(Request $request, ArrayBuilderAssistant $abAssistant)
    {
        $queryParams = $request->all();
        $abAssistant->setModel(new Building());
        $abAssistant->setArrayQuery($queryParams);
        $abAssistant->validate();
        if (!$abAssistant->isValid()) {
            return response()->json($abAssistant->getMessages(), 400);
        }
        $result = $abAssistant->apply()->get()->toArray();

        $header = $this->getExportDataHeader($result[0]);

        Excel::create('export'.date("Ymd-H:i:s"), function($excel) use ($result, $header) {
            $excel->sheet('Buildings Export '. date("Y-m-d"), function($sheet) use ($result, $header){
                $sheet->row(1, $header);
                $rowNumber = 2;
                foreach ($result as $key => $item) {
                    $data = $this->getExportDataValues($header, $item);
                    $sheet->row($rowNumber, $data);
                    $rowNumber++;
                }
            });
        })->download('xls');
    }

    public function getExportDataHeader($item)
    {
        $header = [];
        if (isset($item['id'])) {
            $header['id'] = 'Building ID';
        }
        if (isset($item['created_at'])) {
            $header['date_created'] = 'Date Created';
        }
        if (isset($item['last_location'])) {
            $header['date_location'] = 'Location Date';
        }
        if (isset($item['serial_number'])) {
            $header['serial_number'] = 'Building SN';
        }
        if (array_key_exists('dealer', $item['last_location'])) {
            $header['dealer'] = 'Dealer';
        }
        if (isset($item['retail'])) {
            $header['retail'] = 'Retail';
        }
        return $header;
    }

    public function getExportDataValues($header, $item)
    {
        $data = [];
        if (isset($header['id'])) {
            $data[] = $item['id'];
        }
        if (isset($header['date_created'])) {
            $data[] = $item['created_at'];
        }
        if (isset($header['date_location'])) {
            $data[] = $item['last_location']['created_at'];
        }
        if (isset($header['serial_number'])) {
            $data[] = $item['serial_number'];
        }
        if (isset($header['dealer'])) {
            $data[] = $item['last_location']['dealer'] ? $item['last_location']['dealer']['business_name']: '';
        }
        if (isset($header['retail'])) {
            $data[] = $item['retail'];
        }
        return $data;
    }
}
