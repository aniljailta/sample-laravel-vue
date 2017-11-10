<?php

namespace App\Services\PriceGroups;

use App\Exceptions\ArrayBuilderValidationException;
use App\Models\BuildingModel;
use App\Models\Option;
use App\Models\PriceGroup;
use App\Repositories\PriceGroups\PriceGroupPriceRepository;
use App\Repositories\PriceGroups\PriceGroupRepository;
use App\Services\ArrayBuilder\ArrayBuilderAssistant;
use Carbon\Carbon;
use App\Exceptions\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Writers\LaravelExcelWriter;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use DB;

class PriceGroupService
{
    protected $priceGroupRepo;

    /**
     * PriceGroupService constructor.
     */
    public function __construct()
    {
        $this->priceGroupRepo = new PriceGroupRepository();
    }

    /**
     * @return string
     */
    public function publishPriceGroup()
    {
        $currentDate = Carbon::now()->format('Y-m-d');
        try {
            DB::beginTransaction();
            $priceGroups = $this->priceGroupRepo->getPriceGroupsByDate($currentDate);
            if (count($priceGroups)) {
                $notification = '';
                foreach ($priceGroups as $priceGroup) {
                    $response = $this->updatePriceGroupToPublish($priceGroup);
                    if ($response) {
                        $notification .= $priceGroup->name . " published successfully.\n";
                    }
                }
                DB::commit();
                return $notification;
            } else {
                return "There are no record found for today.\n";
            }
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error($e);
            return $e->getMessage();
        }
    }

    /**
     * @param PriceGroup $priceGroup
     * @return bool
     */
    public function updatePriceGroupToPublish(PriceGroup $priceGroup)
    {
        // set status archived - other published price groups which status is "publish" and whose category is same

        $this->priceGroupRepo
            ->query()
            ->where('category', $priceGroup->category)
            ->where('status', 'published')
            ->update([
                'status' => 'archived',
                'archived_at' => Carbon::now(),
            ]);


        // publish price group whose publish date is current data
        $this->priceGroupRepo
            ->query()
            ->where('id', $priceGroup->id)
            ->update([
                'status' => 'published',
                'published_at' => Carbon::now(),
            ]);

        // get prices from price group prices for published price group
        $price_lists = (new PriceGroupPriceRepository)
            ->query()
            ->where('category', $priceGroup->category)
            ->where('price_group_id', $priceGroup->id)
            ->get();

        if (count($price_lists) > 0) {
            foreach ($price_lists as $key => $price_list) {
                if ($priceGroup->category == 'models') {
                    BuildingModel::where('id', $price_list->item_id)->update(['shell_price' => $price_list->price]);
                } else {
                    Option::where('id', $price_list->item_id)->update(['unit_price' => $price_list->price]);
                }
            }
        }
        return true;
    }

    /**
     * @param array                 $arrayQuery
     * @param ArrayBuilderAssistant $abAssistant
     * @return LaravelExcelWriter
     * @throws ValidationException
     * @internal param $request
     * @internal param string $type
     */
    public function exportPriceGroup(array $arrayQuery, ArrayBuilderAssistant $abAssistant): LaravelExcelWriter
    {
        $abAssistant->setModel(new PriceGroup());
        $abAssistant->setArrayQuery($arrayQuery);
        $abAssistant->validate();
        if (!$abAssistant->isValid()) {
            throw new ValidationException($abAssistant->getMessages());
        }

        $result = $abAssistant->apply()->get()->toArray();
        $header = $this->getExportDataHeader($result[0]);

        return Excel::create('price_group_export_' . time(), function ($excel) use ($result, $header) {
            $excel->sheet('Price Group Export ' . time(), function ($sheet) use ($result, $header) {
                $sheet->row(1, $header);
                $rowNumber = 2;
                foreach ($result as $key => $item) {
                    $data = $this->getExportDataValues($header, $item);
                    $sheet->row($rowNumber, $data);
                    $rowNumber++;
                }
            });
        });
    }

    /**
     * @param $item
     * @return array
     */
    protected function getExportDataHeader($item)
    {
        $header = [];
        if (isset($item['id'])) {
            $header['id'] = 'Price Group ID';
        }
        if (isset($item['name'])) {
            $header['name'] = 'Name';
        }
        if (isset($item['category'])) {
            $header['category'] = 'Category';
        }
        if (isset($item['status'])) {
            $header['status'] = 'Status';
        }
        if (isset($item['publish_date'])) {
            $header['date_scheduled'] = 'Date Scheduled';
        }
        if (isset($item['published_at'])) {
            $header['date_published'] = 'Date Published';
        }
        if (isset($item['archived_at'])) {
            $header['date_archived'] = 'Date Archived';
        }
        return $header;
    }

    /**
     * @param $header
     * @param $item
     * @return array
     */
    protected function getExportDataValues($header, $item)
    {
        $data = [];
        if (isset($header['id'])) {
            $data[] = $item['id'];
        }
        if (isset($header['name'])) {
            $data[] = $item['name'];
        }
        if (isset($header['category'])) {
            $data[] = $item['category'];
        }
        if (isset($header['status'])) {
            $data[] = $item['status'];
        }
        if (isset($header['date_scheduled'])) {
            $data[] = $item['publish_date'];
        }
        if (isset($header['date_published'])) {
            $data[] = $item['published_at'];
        }
        if (isset($header['date_archived'])) {
            $data[] = $item['archived_at'];
        }
        return $data;
    }

}
