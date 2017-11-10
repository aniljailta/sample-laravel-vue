<?php

namespace App\Services\Orders;

use App\Http\Requests\Request;
use App\Models\FileSign;
use App\Models\Location;
use App\Models\Order;
use App\Models\BuildingHistory;
use App\Models\BuildingStatus;

use App\Events\Orders\OrderWasUpdated;
use App\Events\Orders\OrderCustomerWasUpdated;

use App\Services\Building\BuildingService;
use App\Services\Esignatures\EsignaturesService;
use App\Services\Locations\LocationService;
use Auth;
use Event;
use DB;
use Exception;
use Illuminate\Support\Facades\Log;
use Uuid;
use PDF;
use Storage;
use Store;
use Helper;

use Carbon\Carbon;

class OrderService
{
    public $messages = [];

    public function __construct()
    {
    }

    /**
     * @param Order|null $order
     * @return Order
     */
    public function saveDealerOrder(Order $order = null): Order
    {
        DB::transaction(function () use (&$order) {
            if ($order->status_id === 'submitted') {
                $order->date_submitted = Carbon::now();

                if ($order->sale && $order->sale->status_id == 'invoiced') {
                    $order->sale->status_id = 'updated';
                    $order->sale->save();
                }

                // original order statuses which handle original order cancellation
                $cancellationStatuses = ['submitted', 'signature_pending', 'signed'];
                if ($order->original_order && in_array($order->original_order->status_id, $cancellationStatuses)) {
                    $order->original_order->status_id = 'cancelled';
                    $order->original_order->save();
                }
            }

            if (!$order->exists) {
                $order->save();
            }

            // update options if provided
            if ($order->relationLoaded('options')) {
                $order->options()->delete();
                $order->options()->saveMany($order->options);
            }

            // update building if provided
            if ($order->relationLoaded('building')) {
                $building = $order->building;
                $building->order_id = $order->id;
                if ($order->sale_type !== 'dealer-inventory') {
                    $building = (new BuildingService)
                        ->setBuilding($building)
                        ->save($building->toArray());
                } else {
                    // save dealer-inventory (associate building.order_id)
                    $building->save();
                }

                $order->building_id = $building->id;
            }

            // update order reference if provided
            if ($order->relationLoaded('order_reference')) {
                // update customer entity
                event(new OrderCustomerWasUpdated($order));

                $order->order_reference->save();
                $order->reference_id = $order->order_reference->id;
            }

            $order->save();

            $orderSericeContact = new OrderContactService;
            $orderSericeContact->saveOrderLeadContacts($order);

            event(new OrderWasUpdated($order));
        });

        return $order;
    }

    /**
     * @param Order|null $order
     * @return Order
     */
    public function saveCustomerOrder(Order $order = null): Order
    {
        DB::transaction(function () use (&$order) {
            // update building if provided
            if ($order->relationLoaded('building')) {
                $building = $order->building;
                $building->order_id = $order->id;
                $building = (new BuildingService)
                    ->setBuilding($building)
                    ->save($building->toArray());

                $order->building_id = $building->id;
            }

            // update order reference if provided
            if ($order->relationLoaded('order_reference')) {
                // update customer entity
                event(new OrderCustomerWasUpdated($order));

                $order->order_reference->save();
                $order->reference_id = $order->order_reference->id;
            }

            $order->save();
            event(new OrderWasUpdated($order));
        });

        return $order;
    }

    /**
     * Change order (on sale generated) affects original order (and dependencies)
     * @param Order $order
     * @return Order
     */
    public function changeOrder(Order $order): Order {
        $originalOrder = $order->original_order;
        $originalOrder->status_id = 'cancelled';
        $originalOrder->save();

        // cancell esignature requiests if exists
        $fileSigns = FileSign::whereHas('file.order', function ($q) use($originalOrder) {
            $q->where('id', $originalOrder->id);
        })->where('is_esigned', false);

        $fileSigns->each(function($fileSign) {
            (new EsignaturesService)->cancelSignatureRequest($fileSign->esign_signature_request_id);
        });

        // update and replace original sale
        // if change order have no sale yet (avoid duplicates)
        if ($originalOrder->sale && !$order->sale) {
            $originalOrder->sale->order_id = $order->id;
            $originalOrder->sale->building_id = $order->building_id;
            $originalOrder->sale->status_id = 'updated';
            $originalOrder->sale->save();
            $order->refresh();
        }

        $originalBuildingStatus = $originalOrder->building->last_status->building_status->name;
        // update building serial number
        // based on 'counter' part
        (new BuildingService)->update($order->building, [], [
            'update_serial_number' => true,
            'base_serial_number' => $order->original_order->building->serial_number,
            'next_status' => $order->building->last_status->building_status->name === 'Draft' ? true : false
        ]);

        // set original building status to "draft"
        // if not draft (avoid duplicate)
        if ($originalBuildingStatus !== 'Draft') {
            $status = BuildingStatus::where('name', 'Draft')->first();
            $newStatus = BuildingHistory::create([
                'building_id' => $originalOrder->building->id,
                'status_id' => $status->id,
            ]);
            $originalOrder->building->status_id = $newStatus->id;
            $originalOrder->building->save();
        }

        $this->messages[] = trans('orders.messages.change_order_has_been_processed');

        if ($originalBuildingStatus === 'Pending') {
            $this->messages[] = trans('orders.messages.order_building_changed_to_draft');
        }

        // message for building in production
        if (!in_array($originalBuildingStatus, ['Draft', 'Pending'])) {
            $this->messages[] = trans('orders.messages.order_building_in_production');
        }

        return $order;
    }

    /**
     * Check if sale should be updated, based on order status
     * @param Order $order
     * @param array $params
     * @return bool
     */
    public function saleShouldBeUpdated(Order $order, array $params = []): bool {
        $orderStatusesAffectsSale = ['cancelled', 'submitted'];
        return ($order->sale && in_array(array_get($params, 'status_id'), $orderStatusesAffectsSale));
    }

    /**
     * @param $order
     * @return Location
     */
    public function generateLocation(&$order): Location
    {
        $locationParams = [];

        $refereces = $order->order_reference;
        if ($refereces->building_in_same_address) {
            $locationParams['address'] = $refereces->address;
            $locationParams['city'] = $refereces->city;
            $locationParams['state'] = $refereces->state;
            $locationParams['zip'] = $refereces->zip;
        } else {
            $locationParams['address'] = $refereces->building_location_address;
            $locationParams['city'] = $refereces->building_location_city;
            $locationParams['state'] = $refereces->building_location_state;
            $locationParams['zip'] = $refereces->building_location_zip;
        }

        $locationParams['name'] = "{$order->order_reference->first_name} {$order->order_reference->last_name} ({$order->building->serial_number})";
        $locationParams['country'] = 'US';
        $locationParams['latitude'] = null;
        $locationParams['longitude'] = null;
        $locationParams['is_geocoded'] = 'no';
        $locationParams['category'] = Location::CATEGORY_CUSTOMER;

        $location = (new LocationService)->create($locationParams);
        return $location;
    }
}
