<?php

namespace App\Services\PriceGroups;

use App\Models\PriceGroupPrice;
use App\Repositories\PriceGroups\PriceGroupPriceRepository;
use App\Repositories\PriceGroups\PriceGroupRepository;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PriceGroupPriceService
{
    protected $priceGroupPriceRepo, $priceGroupRepo;

    /**
     * PriceGroupService constructor.
     */
    public function __construct()
    {
        $this->priceGroupPriceRepo = new PriceGroupPriceRepository();
        $this->priceGroupRepo = new PriceGroupRepository();
    }

    /**
     * @param array $priceGroupData
     * @param UploadedFile $uploadedFile
     * @return \Illuminate\Http\JsonResponse
     */
    public function importPriceList(array $priceGroupData, UploadedFile $uploadedFile)
    {
        try {
            $priceGroupData['status'] = 'draft';
            $priceGroup = $this->priceGroupRepo->query()->create($priceGroupData);
            $priceGroupId = $priceGroup->id;
            $category = $priceGroup->category;
            $newData = Excel::load($uploadedFile->getRealPath())->get();
            $existingData = $this->priceGroupPriceRepo->getPriceListsByIdAndCategory($priceGroupId, $category);

            if (!empty($newData) && $newData->count()) {
                foreach ($newData->toArray() as $key => $value) {
                    $collection = $existingData->first(function ($collect_value) use ($value) {
                        if ($collect_value->item_id == intval($value[3])) {
                            return $collect_value;
                        }
                    });

                    if (!empty($collection) && count($collection) > 0) {
                        $collection->price = $value[5];
                        $collection->save();
                    } else {
                        $this->priceGroupPriceRepo->save(new PriceGroupPrice([
                            'price_group_id' => $priceGroupId,
                            'category' => $category,
                            'item_id' => $value[3],
                            'item_name' => $value[4],
                            'item_description' => $value[5],
                            'price' => $value[6],
                        ]));
                    }
                }
                unlink($uploadedFile->getRealPath());
                return response()->json(['msg' => 'Price Lists imported successfully.']);
            }
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['msg' => 'Something went wrong.'], 422);
        }
    }

    /**
     * @param int    $priceGroupId
     * @param string $category
     * @return LaravelExcelWriter
     * @internal param $request
     */
    public function exportPriceList(int $priceGroupId, string $category): LaravelExcelWriter
    {
        try {
            $result = $this->priceGroupPriceRepo->getPriceListByPriceGroupId($priceGroupId, $category)->toArray();
            $priceGroupName = $this->priceGroupRepo
                ->query()
                ->where('id', $priceGroupId)
                ->where('category', $category)
                ->pluck('name')
                ->first();

            $header = [
                'id' => 'ID',
                'price_group_id' => 'Price Group ID',
                'category' => 'Category',
                'item_id' => 'Item ID',
                'item_name' => 'Item Name',
                'item_description' => 'Item Description',
                'price' => 'Price',
            ];
            $fileName = date("mdY") . '_' . $category . '_' . $priceGroupName;
            return Excel::create(strtolower($fileName), function ($excel) use ($result, $header) {
                $excel->sheet('Price List Export ' . date("mdY"), function ($sheet) use ($result, $header) {
                    $sheet->row(1, $header);
                    $rowNumber = 2;
                    foreach ($result as $key => $item) {
                        $data = [
                            'id' => $item['id'],
                            'price_group_id' => $item['price_group_id'],
                            'category' => $item['category'],
                            'item_id' => $item['item_id'],
                            'item_name' => $item['item_name'],
                            'item_description' => $item['item_description'],
                            'price' => $item['price'],
                        ];
                        $sheet->row($rowNumber, $data);
                        $rowNumber++;
                    }
                });
            });
        } catch (\Exception $e) {
            dd($e->getMessage());
            return $e->getMessage();
        }
    }
}
