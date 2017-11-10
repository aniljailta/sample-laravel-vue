<?php

namespace App\Repositories\PriceGroups;

use App\Models\Building;
use App\Models\BuildingModel;
use App\Models\Option;
use App\Models\PriceGroup;
use App\Models\PriceGroupPrice;
use App\Repositories\BaseRepository;
use App\Services\PriceGroups\PriceGroupService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;

class PriceGroupRepository extends BaseRepository
{
    const MODEL = PriceGroup::class;

    protected $price_group, $options, $models, $price_group_prices;

    /**
     * PriceGroupRepository constructor.
     */
    public function __construct()
    {
        $this->price_group = new PriceGroup();

        // Below 2 models is for getting price list for price group based on category selection
        $this->options = new Option();
        $this->models = new BuildingModel();
        $this->price_group_prices = new PriceGroupPrice();
    }

    public function save(PriceGroup $item)
    {
        $item->save();
        return $item;
    }

    /**
     * @param $input
     */
    public function create($input)
    {
        $data = $input['data'];
        $priceGroup = $this->createUserStub($data);
        $priceGroupPricesRepository = new PriceGroupPriceRepository();
        DB::transaction(function () use ($priceGroup, $data, $priceGroupPricesRepository) {
            $priceGroupCount = (self::MODEL)::where('category', $data['category'])->count();
            if ($priceGroupCount == 0) {
                $note = 'This is your very first ' . $data['category'] . ' price group and is a snapshot of the original price structure. If you would like to track historical prices, you should not make changes to this price group.';
                $publishedPriceGroup = $this->price_group->create([
                    'name' => 'Original',
                    'category' => $data['category'],
                    'status' => 'published',
                    'published_at' => Carbon::now(),
                    'notes' => $note
                ]);
                $priceGroupPricesRepository->getPriceListByPriceGroupId($publishedPriceGroup->id, $publishedPriceGroup->category);

                // Below code is for updating models_price_group_id & options_price_group_id to all existing buildings
                $buildingData = [];
                if ($data['category'] == 'options') {
                    $buildingData['options_price_group_id'] = $publishedPriceGroup->id;
                }

                if ($data['category'] == 'models') {
                    $buildingData['models_price_group_id'] = $publishedPriceGroup->id;
                }

                if (!empty($buildingData)) {
                    Building::query()->update($buildingData);
                }
            }
            if ($draftPriceGroup = $this->price_group->create($priceGroup)) {
                $priceGroupPricesRepository->getPriceListByPriceGroupId($draftPriceGroup->id, $draftPriceGroup->category);
                return true;
            }
            throw new GeneralException(trans('exceptions.price-groups.create_error'));
        });
    }

    /**
     * @param $input
     * @return bool
     */
    public function update($input)
    {
        $data = $input['data'];
        $price_group = $this->updateUserStub($data);
        DB::transaction(function () use ($price_group) {
            if ($price_group->save()) {
                return true;
            }
            throw new GeneralException(trans('exceptions.price-groups.update_error'));
        });
        return true;
    }

    /**
     * @param $input
     * @return mixed
     */
    protected function createUserStub($input)
    {
        return [
            'name'     => $input['name'],
            'category' => $input['category'],
            'status'   => 'draft',
            'notes'    => !empty($input['notes']) && $input['notes'] != 'undefined' ? $input['notes'] : ''
        ];
    }

    /**
     * @param $input
     * @return mixed
     */
    protected function updateUserStub($input)
    {
        $price_group = self::MODEL;
        $price_group = $price_group::find($input['id']);
        $price_group->name = $input['name'];
        $price_group->notes = $input['notes'] != 'undefined' || $input['notes'] != 'null' ? $input['notes'] : '';
        return $price_group;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $price_group = self::MODEL;
        $price_group = $price_group::findOrFail($id);
        return $price_group->delete();
    }

    /**
     * @param $status
     * @param $category
     * @return mixed
     */
    public function getPriceGroupsByStatus($status, $category)
    {
        $price_group = self::MODEL;
        return $price_group::where('status', $status)->where('category', $category)->get();
    }

    /**
     * @param $category
     * @return mixed
     */
    public function getPublishedPriceGroup($category)
    {
        return $this->price_group->where('status', 'published')->where('category', $category)->first();
    }

    /**
     * @param $category
     * @return mixed
     */
    public function getPublishedPriceGroups($category)
    {
        return $this->price_group->where('status', 'published')->where('category', $category);
    }

    /**
     * @param $data
     * @param $isExist
     * @return static
     */
    public function createPriceGroups($data, $isExist)
    {
        if (!$isExist) {
            $publishPriceGroup = $this->price_group->create([
                'name' => $data['name'],
                'category' => $data['category'],
                'status' => 'published',
                'published_at' => Carbon::now(),
            ]);
        }

        $this->price_group->create([
            'name' => $data['name'],
            'category' => $data['category'],
            'status' => 'draft',
        ]);

        unset($data['name']);
        $data['price_group_id'] = !$isExist ? $publishPriceGroup->id : $data['price_group_id'];
        return $this->price_group_prices->create($data);
    }

    /**
     * Below 2 functions are for CRON for publishing price group whose published_at date is current date
     * getPriceGroupsByDate & updatePriceGroupToPublish
     * @param $date
     * @return mixed
     */

    public function getPriceGroupsByDate($date)
    {
        return $this->price_group->whereDate('publish_date', $date)->get();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getPriceListByCategory($data)
    {
        if (!empty($data['category']) && $data['category'] == 'models') {
            $price_lists = $this->models
                ->select('building_models.id', 'building_models.name', 'building_models.description', 'building_models.shell_price', 'price_group_prices.price as price_list_price')
                ->leftJoin('price_group_prices', function ($join) use ($data) {
                    $join->where('category', '=', 'models');
                    $join->where('price_group_id', '=', $data['id']);
                    $join->on('price_group_prices.item_id', '=', 'building_models.id');
                })
                ->where('building_models.is_active', 'yes')->get()->toArray();
        } else {
            $price_lists = $this->options
                ->select('options.id', 'options.name', 'options.description', 'options.unit_price', 'price_group_prices.price as price_list_price')
                ->leftJoin('price_group_prices', function ($join) use ($data) {
                    $join->where('category', '=', 'options');
                    $join->where('price_group_id', '=', $data['id']);
                    $join->on('price_group_prices.item_id', '=', 'options.id');
                })
                ->where('options.is_active', 'yes')->get()->toArray();
        }

        foreach ($price_lists as $key => $price_list) {
            $category = ($data['category'] == 'options') ? 'unit_price' : 'shell_price';
            if (!empty($price_list['price_list_price'])) {
                $price_lists[$key]['price_list_price'] = $price_list['price_list_price'];
            } else {
                $price_lists[$key]['price_list_price'] = $price_list[$category];
            }
        }

        return $price_lists;
    }

    public function getPriceGroups()
    {
        return $this->price_group->get();
    }

    /**
     * Below function is created for adding published_at date from the list
     * @param $data
     * @return mixed
     */
    public function updatePriceGroup($data)
    {
        if (!empty($data['type']) && $data['type'] == 'immediate') {
            $priceGroup = $this->price_group->findOrFail($data['id']);
            $priceGroupService = new PriceGroupService();
            return $priceGroupService->updatePriceGroupToPublish($priceGroup);
        }

        $price_group = $this->price_group
            ->whereDate('publish_date', $data['publish_date'])
            ->where('category', $data['category'])
            ->where('id', '<>', $data['id'])
            ->first();

        if (count($price_group) == 0) {
            return $this->price_group
                ->where('id', $data['id'])
                ->update([
                    'publish_date' => $data['publish_date'],
                    'status' => 'pending'
                ]);
        }

        return false;
    }
}