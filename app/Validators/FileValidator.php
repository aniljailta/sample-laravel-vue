<?php

namespace App\Validators;

use App\Models\BuildingPackageCategory;
use Store;
use Uuid;
use App\Models\File;
use App\Models\Order;
use App\Models\Option;
use App\Models\Building;
use App\Models\BuildingPackage;

use App\Validators\EnchantValidatorTrait;
use Fadion\ValidatorAssistant\ValidatorAssistant;

class FileValidator extends ValidatorAssistant {

    use EnchantValidatorTrait;
    
    protected $rules = array(
        
    );
    
    protected $rulesUpload = array(
        'storable_type' => 'required|string|in:option,building,building-package,building-package-category',
        'storable_id' => 'required|numeric|is_valid_storable_id',
        'upload_file' => 'required|file',
    );
    
    protected $attributes = array(
        'storable_type' => 'Storable type',
        'storable_id' => 'Storable id',
        'category_id' => 'Category id',
        'upload_file' => 'File',
    );
    
    protected $messages = array(
        "is_valid_storable_id" => "Storable ID is not valid.",
        "is_valid_category_id" => "Category ID is not valid.",
    );

    /**
     * Validate storable ID
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    protected function customIsValidStorableId($attribute, $value, $parameters)
    {
        $validator = $this->instance();

        if ( $this->inputs['storable_type'] && $this->inputs['storable_type'] === 'option' ) {
            $option = Option::find($value);
            if($option) {
                Store::set('storable_item', $option);
                return true;
            }
        }

        if ( $this->inputs['storable_type'] && $this->inputs['storable_type'] === 'building' ) {
            $building = Building::find($value);
            if($building) {
                Store::set('storable_item', $building);
                return true;
            }
        }

        if ( $this->inputs['storable_type'] && $this->inputs['storable_type'] === 'order' ) {
            $order = Order::where('uuid', '=', $value)->first();
            if($order) {
                Store::set('storable_item', $order);
                return true;
            }
        }

        if ( $this->inputs['storable_type'] && $this->inputs['storable_type'] === 'building-package' ) {
            $buildingPackage = BuildingPackage::find($value);
            if($buildingPackage) {
                Store::set('storable_item', $buildingPackage);
                return true;
            }
        }

        if ( $this->inputs['storable_type'] && $this->inputs['storable_type'] === 'building-package-category' ) {
            $buildingPackageCategory = BuildingPackageCategory::find($value);
            if($buildingPackageCategory) {
                Store::set('storable_item', $buildingPackageCategory);
                return true;
            }
        }

        return false;
    }

    /**
     * Validate file category ID
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    protected function customIsValidCategoryId($attribute, $value, $parameters)
    {
        $validator = $this->instance();

        if ( $this->inputs['storable_type'] && $this->inputs['storable_type'] === 'order' ) {
            $categories = File::$categories;
            if (array_key_exists($value, $categories)) {
                return true;
            }
        }

        return false;
    }
}