<?php

use App\Models\BuildingModel;
use App\Models\BuildingPlant;

if ( ! function_exists('building_get_serial_number')) {
    function building_get_serial_number($params, $currentSerialNumber = null) {

        if(!isset($params['building_model_id'])) return $currentSerialNumber;
        if(!isset($params['width'])) return $currentSerialNumber;
        if(!isset($params['length'])) return $currentSerialNumber;
        if(!isset($params['height'])) return $currentSerialNumber;
        if(!isset($params['plant'])) return $currentSerialNumber;

        $buildingModel = BuildingModel::with('style')->findOrFail($params['building_model_id']);
        
        $sWidth = str_pad($params['width'], 2, '0', STR_PAD_LEFT);
        $sLength = str_pad($params['length'], 2, '0', STR_PAD_LEFT);
        $sWallHeight = str_pad($params['height'], 2, '0', STR_PAD_LEFT);

        $serialShortCode = $buildingModel->style->short_code;
        $serialSizes = $sWidth.$sLength.$sWallHeight;
        
        if (!$currentSerialNumber) {
            $seriaPlant = str_pad($params['plant']->plant_id, 3, '0', STR_PAD_LEFT);
            $seriaCounter = BuildingPlant::getNextBuildingCount($params['plant']->id, date('Y'));
            $seriaYear = substr(date('Y'), -2);
            $serialIdent = $seriaPlant.$seriaCounter.$seriaYear;
        } else {
            $serialParts = explode('-', $currentSerialNumber);
            $serialIdent = $serialParts[2];
        }

        $newSerial = "{$serialShortCode}-{$serialSizes}-{$serialIdent}";
        return $newSerial;
    }
}