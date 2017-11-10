<?php

namespace App\Presenters\Orders;

use Hemp\Presenter\Presenter;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class OrderBuildingPdfPresenter extends Presenter
{
    /**
     * @return string
     */
    public function getTxtMaterialSubTotalAttribute()
    {
        return '$' . number_format($this->material_sub_total, 2);
    }

    /**
     * @return string
     */
    public function getTxtOptionsSubTotalWithoutMaterialsAttribute()
    {
        return '$' . number_format($this->options_sub_total_without_materials, 2);
    }

    /**
     * @return string
     */
    public function getTxtOptionsWithoutMaterialsAttribute()
    {
        return '$' . number_format($this->options_without_meterials, 2);
    }

    /**
     * @return string
     */
    public function getOptionsSubTotalWithoutMaterialsAttribute()
    {
        $total = 0;
        $options = $this->options_without_materials;
        foreach ($options as $option) {
            $total += $option->total_price;
        }

        return $total;
    }

    /**
     * @return string
     */
    public function getMaterialSubTotalAttribute()
    {
        $total = 0;
        $materials = $this->materials;
        foreach ($materials as $material) {
            if (!$material) continue;

            $total += $material->total_price;
        }

        return $total;
    }

    /**
     * @return Collection
     */
    public function getOptionsWithoutMaterialsAttribute()
    {
        $buildingOptions = collect();
        if (!$this->model->building_options) return $buildingOptions;

        $buildingOptions = clone $this->model->building_options;
        $materials = $this->getMaterialsAttribute();

        foreach ($materials as $material) {
            if (!$material) continue;

            $key = $this->model->building_options->search(function ($bo, $key) use ($material) {
                return ($material->option_id === $bo->option_id);
            });

            $buildingOptions->forget($key);
        }

        return $buildingOptions;
    }

    /**
     * Get material options only
     * @return array
     */
    public function getMaterialsAttribute()
    {
        return [
            'roof' => $this->model->roof,
            'trim' => $this->model->trim,
            'siding' => $this->model->siding,
        ];
    }
}