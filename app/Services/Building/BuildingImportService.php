<?php

namespace App\Services\Building;

use Event;
use Auth;
use App\Events\BuildingWasAdded;
use App\Events\BuildingWasUpdated;

use App\Models\Building;
use App\Models\BuildingModel;
use App\Models\BuildingLocation;
use App\Models\BuildingHistory;
use App\Validators\BuildingValidator;
use Illuminate\Support\Facades\DB;

class BuildingImportService
{

    // id,name,group,description,unit_price
    private $fieldMap = [
        'options' => [],
        'building' => []
    ];

    private $fields = [

        'building' => [
            'serial_number' => "Serial Number",
            'model' => "Model",
            'shell_price' => "Shell Price",
            'body_color' => "Body Color",
            'trim_color' => "Trim Color",
            'roof_color'=> "Roof Color",
            'total_price' => "Total Price",
            'order_type' => "Order type (1 = Inventory, 2= Customer)",
            'build_status_id' => "Build Status",
            'sale_status_id' => "Sold Status",
            'location_id' => "Location",
        ],
        "options" => [
            "id" => 'Option $num ID',
            "quantity" => 'Option $num Quantity',
            "description" => 'Option $num Description',
            "total_price" => 'Option $num price',
        ]
    ];
    private $buildings = [];
    private $store = [];
    protected $validator;

    // should be validation here..
    private function buildFieldMap($headerRow)
    {
        // search building data
        foreach( $this->fields['building'] as $bField => $bFieldName )
        {
            $index = array_search($bFieldName, $headerRow);
            if ( $index !== FALSE )
            {
                $this->fieldMap['building'][$bField] = $index;
            }
        }

        // search options
        $totalOptions = 10;
        $i = 0;
        $optionFields = $this->fields['options'];

        for($i; $i<=$totalOptions; $i++)
        {
            foreach ($optionFields as $fieldId => $fieldName)
            {
                $search = str_replace('$num', $i, $fieldName);
                $foundIndex = array_search($search, $headerRow);
                if ( !$foundIndex && $fieldId == 'id' ) break;

                $this->fieldMap['options'][$i][] = $foundIndex;
            }
        }
    }

    public function importContent($fileContent)
    {
        $this->parseFile($fileContent);
        $this->validateValues();

        if ( $this->validator->fails() )
            return $this;

        $this->importBuildings();

        return $this;
    }

    public function buildings() {
        return $this->buildings;
    }

    public function validator() {
        return $this->validator;
    }

    private function importBuildings()
    {
        foreach ($this->store['buildings'] as $buildingParams)
        {
            DB::beginTransaction();

            $buildingData = $buildingParams['data'];
            $buildingModelKey = "{$buildingData['style_short_code']}-{$buildingData['width']}-{$buildingData['length']}-{$buildingData['height']}";
            $buildingModel = $this->store['models'][$buildingModelKey];

            $totalOptions = (!empty($buildingParams['options']))
                ? array_sum(array_column($buildingParams['options'], 'total_price'))
                : 0;

            $building = Building::create([
                'order_type' => $buildingData['order_type'],
                'plant_id' => $buildingData['plant_id'],
                //'last_location_id' => $buildingData['location_id'],
                'building_model_id' => $buildingModel->id,
                'serial_number' => $buildingData['serial_number'],
                'width' => $buildingData['width'],
                'height' => $buildingData['height'],
                'length' => $buildingData['length'],
                'body_color' => $buildingData['body_color'],
                'trim_color' => $buildingData['trim_color'],
                'roof_color' => $buildingData['roof_color'],
                'shell_price' => $buildingData['shell_price'],
                'total_options' => $totalOptions,
                'total_price' => $buildingData['total_price']
                ]);

            // OPTIONS
            if ( !empty($buildingParams['options']) )
                foreach($buildingParams['options'] as $optionParams)
                {
                    $building->building_options()->create([
                        'option_id' => $optionParams['id'],
                        'total_price' => $optionParams['total_price'],
                        'unit_price' => $optionParams['total_price'] / $optionParams['quantity'],
                        'quantity' =>  $optionParams['quantity']
                    ]);
                }

            // LOCATION
            if ( !empty($buildingData['location_id']) )
            {
                $building_location = new BuildingLocation([
                    'user_id' => Auth::user()->id,
                    'building_id' => $building->id,
                    'location_id' => (int) $buildingData['location_id']
                ]);

                $building->building_locations()->save($building_location);
                $building->update(['last_location_id' => $building_location->id]);
            }

            // STATUSES
            $statuses = ['build_status_id', 'sale_status_id'];
            $statusCollection = [];
            foreach ($statuses as $statusIdent)
                if ( !empty($buildingData[$statusIdent]) )
                {
                    $statusCollection[$statusIdent] = $buildingData[$statusIdent];
                }

            $buildingStatuses = collect($statusCollection);
            $buildingStatuses->each(function($buildingStatusId) use ($building) {
                $buildingHistory = new BuildingHistory([
                    'user_id' => Auth::user()->id,
                    'building_id' => $building->id,
                    'status_id' => $buildingStatusId
                ]);

                $buildingHistory = $building->building_history()->save($buildingHistory);
            });

            $this->buildings[] = $building;
            DB::commit();
        }
    }

    /**
     * Parse data per each line, save some common values to store for next checkings/getters
     * @param $raw_file
     * @return bool
     */
    private function parseFile($raw_file)
    {
        if ( empty($raw_file) ) return false;

        //$lines = str_getcsv($raw_file, "\n\r"); //parse the rows
        $lines = explode(PHP_EOL, $raw_file);
        // first line (headers)
        $this->buildFieldMap(str_getcsv(array_shift($lines)));

        foreach($lines as $lineNum => $line)
        {
            $line = str_getcsv($line);
            if ( !isset($line[0]) || $line[0] == '' ) continue;

            $building = [
                'options' => $this->getOptions($line),
                'data' => $this->getBuildingData($line),
            ];

            $this->store['buildings'][] = $building;
        }
    }

    /**
     * Collect building data (some of fields should be requested/validated against DB)
     * @param $dataLine
     * @return array
     */
    private function getBuildingData($dataLine)
    {
        $fieldMap = $this->fieldMap['building'];

        $serialNumber = $dataLine[$fieldMap['serial_number']];
        $parts = explode('-', $serialNumber);

        $width = (int) substr($parts[1], 0, 2);
        $length = (int) substr($parts[1], 2, 2);
        $height = (int) substr($parts[1], 4, 2);

        $styleShortCode = $parts[0];
        $modelKey = "{$parts[0]}-{$width}-{$length}-{$height}";

        $this->store['styles'][$styleShortCode] = null;
        $this->store['search_model_keys'][$modelKey] = [
            'style_short_code' => $parts[0],
            'width' => $width,
            'length' => $length,
            'height' => $height,
        ];

        return [
            'order_type' => $dataLine[$fieldMap['order_type']],
            'plant_id' => (int) substr($parts[2], 0, 3),
            'serial_number' => $dataLine[$fieldMap['serial_number']],
            'width' => $width,
            'length' => $length,
            'height' => $height,


            'body_color' => $dataLine[$fieldMap['body_color']],
            'trim_color' => $dataLine[$fieldMap['trim_color']],
            'roof_color' => $dataLine[$fieldMap['roof_color']],

            'shell_price' => (float) $dataLine[$fieldMap['shell_price']], // model price
            'total_price' => (float) $dataLine[$fieldMap['total_price']], // model + options prices

            'build_status_id' => $dataLine[$fieldMap['build_status_id']],
            'sale_status_id' => $dataLine[$fieldMap['sale_status_id']],

            'location_id' => $dataLine[$fieldMap['location_id']],

            'style_short_code' => $styleShortCode, // need to get model id by short code
            'building_count' => (int) substr($parts[2], 3, 4), // update building_plants
        ];
    }

    /**
     * Collect options from each line (for each building)
     * @param $dataLine
     * @return array
     */
    private function getOptions($dataLine)
    {
        $options = [];
        foreach( $this->fieldMap['options'] as $optionNum => $keyIDs )
        {
            if ( empty($dataLine[$keyIDs[1]]) ) continue; // if option ID is empty - don't collect another data

            list($id, $quantity, $description, $totalPrice) = array_values(array_intersect_key($dataLine, array_flip($keyIDs)));

            $options[$id] = [
                'id' => (int) $id,
                'quantity' => (int) $quantity,
                'description' => $description,
                'total_price' => (float) $totalPrice,
            ];

            $this->store['options'][$id] = null;
        }

        return $options;
    }

    private function validateValues()
    {
        $this->store['models'] = $this->searchModels($this->store['search_model_keys']);
        $this->validator = BuildingValidator::make([
            'options' => array_keys($this->store['options']),
            'styles' => array_keys($this->store['styles']),
            'undefined_models' => $this->store['search_model_keys']
        ]);
        $this->validator->addRule('options', 'is_valid_options');
        $this->validator->addRule('styles', 'exists:styles,short_code');
        $this->validator->addRule('undefined_models', 'is_valid_import_building_models');
        
        return $this;
    }

    /**
     * Search models by style_short_code (style table) and dimensions (building model table)
     * @param $rows
     * @return mixed
     */
    private function searchModels($rows)
    {
        foreach ($rows as $key => $rowParams)
        {
            $model = BuildingModel::select('building_models.*')
                ->join('styles', 'styles.short_code', '=', \DB::raw("'".$rowParams['style_short_code']."'") )
                ->where('width', $rowParams['width'])
                ->where('length', $rowParams['length'])
                ->where('wall_height', $rowParams['height'])
                ->get()->first();

            if (!is_null($model) )
            {
                unset($this->store['search_model_keys'][$key]);
                $models[$key] = $model;
            }
        }

        return $models;
    }
    
}
