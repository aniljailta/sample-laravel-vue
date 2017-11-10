<?php

namespace App\Repositories\PriceGroups;

use App\Models\BuildingModel;
use App\Models\Option;
use App\Models\PriceGroup;
use App\Models\PriceGroupPrice;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PriceGroupPriceRepository extends BaseRepository
{
    const MODEL = PriceGroupPrice::class;

    protected $models, $options, $priceGroup;

    /**
     * PriceGroupsPriceRepository constructor.
     */
    public function __construct()
    {
        $this->models = new BuildingModel();
        $this->options = new Option();
        $this->priceGroup = new PriceGroup();
    }

    /**
     * @param PriceGroupPrice $priceGroupPrice
     * @return PriceGroupPrice
     */
    public function save(PriceGroupPrice $priceGroupPrice): PriceGroupPrice
    {
        $priceGroupPrice->save();
        return $priceGroupPrice;
    }

    /**
     * @param int $id
     * @return PriceGroupPrice
     */
    public function findById(int $id): PriceGroupPrice
    {
        return (self::MODEL)::findOrFail($id);
    }

    /**
     * @param int    $id
     * @param string $category
     * @return mixed
     * @internal param $data
     */
    public function getPriceListByPriceGroupId(int $id, string $category): Collection
    {
        $result = $this->getPriceLists($id, $category);
        if (count($result) == 0) {
            if (!empty($category) && $category == 'models') {
                $price_lists = $this->models
                    ->select('building_models.id', 'building_models.name', 'building_models.description', 'building_models.shell_price as price')
                    ->where('building_models.is_active', 'yes')
                    ->get();
            } else {
                $price_lists = $this->options
                    ->select('options.id', 'options.name', 'options.description', 'options.unit_price as price')
                    ->where('options.is_active', 'yes')
                    ->get();
            }

            foreach ($price_lists as $price_list) {
                (self::MODEL)::create([
                    'price_group_id' => $id,
                    'category' => $category,
                    'item_id' => $price_list->id,
                    'item_name' => $price_list->name,
                    'item_description' => $price_list->description,
                    'price' => $price_list->price,
                ]);
            }
        }
        return $this->getPriceLists($id, $category);
    }

    protected function getPriceLists($id, $category)
    {
        return (self::MODEL)::query()
            ->select('id', 'price_group_id', 'category', 'item_id', 'item_name', 'item_description', 'price')
            ->where('price_group_id', $id)
            ->where('category', $category)
            ->get();
    }

    public function getPriceListsByIdAndCategory(int $id, string $category)
    {
        return (self::MODEL)::query()
            ->where('price_group_id', $id)
            ->where('category', $category)
            ->get();
    }

    /**
     * @param $data
     * @return string|static
     */
    public function updatePriceListByPriceGroup($data)
    {
        $response = '';
        $existingData = $this->getPriceListsByIdAndCategory($data['price_group_id'], $data['category']);
        $modelOptionsData = collect();

        if (!empty($data['status']) && $data['status'] == 'published') {
            if (!empty($data['category']) && $data['category'] == 'models') {
                $modelOptionsData = $this->models
                    ->select('building_models.id', 'building_models.name', 'building_models.shell_price')
                    ->where('building_models.is_active', 'yes')
                    ->get();
            } else {
                $modelOptionsData = $this->options
                    ->select('options.id', 'options.name', 'options.unit_price')
                    ->where('options.is_active', 'yes')
                    ->get();
            }
        }

        if (!empty($data['price_lists'])) {
            foreach ($data['price_lists'] as $key => $value) {
                $collection = $existingData->first(function ($collect_value) use ($value) {
                    if ($collect_value->item_id == intval($value['id'])) {
                        return $collect_value;
                    }
                });
                if (!empty($collection) && count($collection) > 0) {
                    $collection->price = $value['price_list_price'];
                    $response = $collection->save();
                } else {
                    $response = $this->save(new PriceGroupPrice([
                        'price_group_id' => $data['price_group_id'],
                        'category' => $data['category'],
                        'item_id' => $value['id'],
                        'item_name' => $value['name'],
                        'item_description' => $value['description'],
                        'price' => $value['price_list_price'],
                    ]));
                }

                if (!empty($data['status']) && $data['status'] == 'published') {
                    $modelOptionsCollection = $modelOptionsData->first(function ($collect_value) use ($value) {
                        if ($collect_value->id == intval($value['id'])) {
                            return $collect_value;
                        }
                    });
                    if (!empty($modelOptionsCollection) && count($modelOptionsCollection) > 0) {
                        if (!empty($data['category']) && $data['category'] == 'models') {
                            $modelOptionsCollection->shell_price = $value['price_list_price'];
                        } else {
                            $modelOptionsCollection->unit_price = $value['price_list_price'];
                        }
                        $response = $modelOptionsCollection->save();
                    }
                }
            }

            if (!empty($data['status']) && $data['status'] == 'published') {
                $this->priceGroup->where('id', $data['price_group_id'])->update(['published_at' => Carbon::now()]);
            }
        }
        return $response;
    }
}