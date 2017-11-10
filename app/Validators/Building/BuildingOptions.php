<?php
namespace App\Validators\Building;

use App\Models\Building;
use App\Models\BuildingModel;
use App\Models\BuildingOption;
use App\Models\BuildingOptionColor;
use App\Models\Option;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BuildingOptions
{

    public $validator;
    public $is_valid = true;

    public $buildingModel;
    public $building;
    public $buildingOptions;

    /**
     * CustomBuildOptions constructor.
     * @param $validator
     * @param BuildingModel $buildingModel
     * @param Building|null $building
     */
    public function __construct($validator, BuildingModel $buildingModel, Building $building)
    {
        $this->validator = $validator;
        $this->buildingModel = $buildingModel;
        $this->building = $building;
    }

    /**
     * @param $value
     * @return bool
     */
    public function passes($value): bool
    {
        $categoryRequirements = $this->getCategoryRequirements($this->buildingModel);
        $requiredOptions = [];
        $buildingOptions = collect();

        // load building options for existed building
        if ($this->building && $this->building->exists) {
            $this->building->load([
                'building_options.option.allowable_colors.option',
                'building_options.option_color.color'
            ]);
        }

        foreach ($value as $key => &$option) {
            // validate input option and get building option
            $buildingOption = $this->validateBuildingOption($option, $requiredOptions);
            if (!$buildingOption) continue;

            $buildingOptions->push($buildingOption);
            $this->validateQty($buildingOption, $categoryRequirements['qty_limit']);

            // if building option is specified - remove it from requirements
            if (isset($categoryRequirements['required_categories'][$buildingOption->option->category_id])) {
                unset($categoryRequirements['required_categories'][$buildingOption->option->category_id]);
            }
        }

        $this->validateRequiredCategories($categoryRequirements['required_categories']);
        $this->validateRequiredOptions($value, $requiredOptions);

        $this->buildingOptions = $buildingOptions;
        return $this->is_valid;
    }

    /**
     * @param array $option
     * @param array $requiredOptions
     * @return BuildingOption
     */
    private function validateBuildingOption(array &$option, array &$requiredOptions): ?BuildingOption {
        $option['is_valid'] = true;

        if (!isset($option['quantity']) || !is_numeric($option['quantity']) || !($option['quantity'] >= 0)) {
            $this->validator->getMessageBag()->add('is_valid_build_options', 'Quantity of specified custom build option is not valid');
            $this->is_valid = $option['is_valid'] = false;
        }

        // check options as is
        if (!isset($option['option_id'])) {
            $this->validator->getMessageBag()->add('is_valid_build_options', 'Specified Custom build option is not found');
            $this->is_valid = $option['is_valid'] = false;
            return null; // not found in db =(
        }

        $buildingOption = $this->getBuildingOption($option);
        // check options as is
        if (!$buildingOption->option) {
            $this->validator->getMessageBag()->add('is_valid_build_options', 'Specified Custom build option is not allowed for this model');
            $this->is_valid = $option['is_valid'] = false;
            return null; // not found in db =(
        }

        if ($buildingOption->option->force_quantity && $option['is_valid']) {
            if ($buildingOption->option->force_quantity === 'building_length' && $option['quantity'] < $this->buildingModel->length) {
                $this->validator->getMessageBag()->add('is_valid_build_options', 'Quantity of specified custom build option is not valid (should be equal or more than building length)');
                $this->is_valid = $option['is_valid'] = false;
            }
        }

        /*
        * Validate option color
        * Check if current option have allowable colors (no building) OR current option already associated with color (building)
        */
        if (count($buildingOption->option->allowable_colors) > 0 || $buildingOption->color) {
            $option['is_valid'] = $this->validateColor($option, $buildingOption, $requiredOptions);
            if (!$option['is_valid']) {
                $this->is_valid = false;
            }
        }

        $buildingOption->quantity = $option['quantity'];
        $buildingOption->total_price = $buildingOption->unit_price * $buildingOption->quantity;
        return $buildingOption;
    }

    /**
     * @param $option
     * @param BuildingOption $buildingOption
     * @param array $requiredOptions
     * @return bool
     */
    private function validateColor($option, BuildingOption &$buildingOption, array &$requiredOptions) {
        $validator = $this->validator;

        /*
         * Validate option color
         * Check if current option have allowable colors (no building) OR current option already associated with color (building)
         */

        if (!isset($option['color']) || !isset($option['color']['id']) || !isset($option['color']['name'])) {
            $validator->getMessageBag()->add('is_valid_build_options', "A valid color is required for \"{$buildingOption->option->name}\"");
            return false;
        }

        // building color
        if ($buildingOption->color && $buildingOption->color->id === $option['color']['id']) {
            $color = $buildingOption->color;
        }

        $color = $color ?? $buildingOption->option->allowable_colors->last(function ($item) use ($option) {
                return $item->id === $option['color']['id'];
            });

        // check color option as is
        if (!$color) {
            $validator->getMessageBag()->add('is_valid_build_options', "A valid color is required for \"{$buildingOption->option->name}\"");
            return false;
        }

        $optionColor = new BuildingOptionColor([
            'color_id' => $color->id,
            'custom' => $option['color']['name']
        ]);

        $buildingOption->setRelation('option_color', $optionColor);

        if ($color->option) {
            $requiredOptions[$color->option->id] = $requiredOptions[$color->option->id] ?? 0;
            $requiredOptions[$color->option->id]++;
        }

        return true;
    }

    /**
     * @param $option
     * @return BuildingOption
     */
    private function getBuildingOption($option): ?BuildingOption {
        // allow OLD option to be saved in current order-building
        if ($this->building && $this->building->exists) {
            $buildingOption = $this->building->building_options->last(function($item) use($option) {
                return $item->option_id === $option['option_id'];
            });

            if ($buildingOption) {
                $buildingOption = $buildingOption->replicate();
                return $buildingOption;
            }
        }

        $buildingOption = new BuildingOption();
        // if ORDER not exists or OPTION is not exist in order - we can use option based on building model
        $option = $this->buildingModel->allowable_options->last(
            function ($item) use ($option) {
                return $item->id === $option['option_id'];
            });
        if(!$option) return $buildingOption;

        $buildingOption->setRelation('option', $option);
        $buildingOption->unit_price = $option->unit_price;
        $buildingOption->option_id = $option->id;
        return $buildingOption;
    }

    /**
     * @param BuildingOption $buildingOption
     * @param array $qtyLimit
     */
    private function validateQty(BuildingOption $buildingOption, array &$qtyLimit) {
        $validator = $this->validator;

        // check categories for qantity of options
        if (isset($qtyLimit[$buildingOption->option->category_id])) {
            if ($qtyLimit[$buildingOption->option->category_id] === 0) {
                $validator->getMessageBag()->add('is_valid_build_options', "\"{$buildingOption->option->category->name}\" category is reached limit of options.");
                $this->is_valid = $option['is_valid'] = false;
            } else {
                // decrease counter
                $qtyLimit[$buildingOption->option->category_id]--;
            }
        }
    }

    /**
     * @param array $requiredCategories
     */
    private function validateRequiredCategories(array $requiredCategories) {
        $validator = $this->validator;

        foreach ($requiredCategories as $requiredCategory) {
            $this->is_valid = false;
            $validator->getMessageBag()->add('is_valid_build_options', "\"{$requiredCategory->name}\" options are required");
        }
    }

    /**
     * @param array $inputOptions
     * @param array $requiredOptions
     */
    private function validateRequiredOptions(array $inputOptions, array &$requiredOptions) {
        $validator = $this->validator;

        // check another option dependencies (for example color)
        foreach ($requiredOptions as $optionId => &$requiredOptionQuantity) {
            $inputOption = array_first($inputOptions, function($inputOption) use ($optionId) {
                // consider previous checks (by is_valid flag) and to do search option
                if ($inputOption['is_valid']) return $inputOption['option_id'] === $optionId;
                return null;
            }, null);

            if ($inputOption === null) continue;
            if ($inputOption['quantity'] >= $requiredOptionQuantity) {
                unset($requiredOptions[$optionId]);
            } elseif ($inputOption['quantity'] < $requiredOptionQuantity) {
                $requiredOptionQuantity = $requiredOptionQuantity - $inputOption['quantity'];
            }
        }

        foreach ($requiredOptions as $optionID => $requiredOptionQuantity) {
            // if (!($optionQuantity >= 1)) continue;
            $reqOption = $this->buildingModel->allowable_options->last(function($item) use($optionID) {
                return $item->id === $optionID;
            });
            if (!$reqOption) continue;
            $validator->getMessageBag()->add('is_valid_build_options', "At least {$requiredOptionQuantity} x \"{$reqOption->name}\" options are required");
        }
    }

    /**
     * @param BuildingModel $buildingModel
     * @return array
     */
    private function getCategoryRequirements(BuildingModel $buildingModel): array {
        $qtyLimit = [];
        $requiredCategories = [];
        $colorOptions = [];

        $buildingModel->load(['allowable_options.category' => function($q) {
            // load trashed option category info (strict dependency)
            $q->withTrashed();
        }, 'allowable_options.allowable_colors.option']);
        $buildingModel->allowable_options->each(function ($allowableOption, $key) use (&$requiredCategories, &$qtyLimit, &$colorOptions) {
            $requiredCategories = $this->getRequiredCategories($requiredCategories, $allowableOption);
            $qtyLimit = $this->getQtyLimit($qtyLimit, $allowableOption);

            // collect options which are force required by color (avoid error when option is not in building_model.allowable_options list)
            $allowableOption->allowable_colors->each(function($allowableColor) use (&$colorOptions) {
                if ($allowableColor->option) {
                    $colorOptions[$allowableColor->option->id] = $allowableColor->option;
                }
            });
        });
        $buildingModel->allowable_options = $buildingModel->allowable_options->merge(array_values($colorOptions));

        return [
            'qty_limit' => $qtyLimit,
            'required_categories' => $requiredCategories,
        ];
    }

    /**
     * @param array $requiredCategories
     * @param Option $allowableOption
     * @return array
     */
    private function getRequiredCategories(array &$requiredCategories, Option $allowableOption): array {
        if ($allowableOption->category) {
           if ($allowableOption->category->is_required && !isset($requiredCategories[$allowableOption->category->id])) {
                $requiredCategories[$allowableOption->category->id] = $allowableOption->category;
            }
            return $requiredCategories;  
        }
       return [];
    }

    /**
     * @param array $qtyLimit
     * @param Option $allowableOption
     * @return array
     */
    private function getQtyLimit(array &$qtyLimit, Option $allowableOption): array {
        if ($allowableOption->category) {
            if ($allowableOption->category->qty_limit !== null && !isset($qtyLimit[$allowableOption->category->id])) {
                $qtyLimit[$allowableOption->category->id] = $allowableOption->category->qty_limit;
            }
            return $qtyLimit;   
        }
        return [];
    }

    /**
     * @return mixed
     */
    public function getBuildingOptions()
    {
        return $this->buildingOptions;
    }
}