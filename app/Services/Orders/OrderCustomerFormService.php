<?php

namespace App\Services\Orders;

use App\Repositories\Plants\PlantRepository;
use App\Services\Orders\OrderFormService as OrderFormService;
use App\Services\Orders\OrderCalculator;
use App\Models\Style;
use App\Models\Building;
use App\Models\BuildingOption;
use App\Models\BuildingModel;
use App\Models\Order;
use App\Models\OrderReference;
use App\Models\BuildingPackage;
use App\Models\Dealer;

use App\Validators\Building\BuildingOptions;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PDF;
use Storage;
use Store;
use Helper;
use Validator;
use Uuid;

use Carbon\Carbon;

class OrderCustomerFormService extends OrderFormService
{

    /** Initialize new Order
     * @param array $inputs
     * @return Order
     */
    protected function initOrder(array $inputs): Order {
        $order = new Order;
        $orderReference = new OrderReference;
        $order->setRelation('building', $this->initBuilding());
        $order->setRelation('order_reference', $orderReference);

        $order->{MANUFACTURER_COMPANY_ID} = Store::get('company')->id;
        $order->uuid = Uuid::generate(4)->string;
        $order->status_id = Order::INITIAL_STATUS_ID; // default on new

        return $order;
    }

    /**
     * Build Customer Order from /customer-order-form
     * (objects shouldn't be saved in db yet)
     * @param array $inputs
     * @return Order
     */
    public function toModel(array $inputs): Order {
        $order = $this->initOrder($inputs);
        $order = $this->parse($order, $inputs);

        // customer info
        if (array_get($inputs, 'customer')) {
            $customer = array_get($inputs, 'customer');
            $order->order_reference->first_name = $customer['first_name'] ?? null;
            $order->order_reference->last_name = $customer['last_name'] ?? null;
            $order->order_reference->email = $customer['email'] ?? null;
            $order->order_reference->phone_number = $customer['phone_number'] ?? null;
            $order->order_reference->address = $customer['address'] ?? null;
            $order->order_reference->city = $customer['city'] ?? null;
            $order->order_reference->state = $customer['state'] ?? null;
            $order->order_reference->zip = $customer['zip'] ?? null;
            $order->order_reference->building_in_same_address = $customer['building_in_same_address'] ?? null;
            $order->order_reference->building_location_address = $customer['building_location_address'] ?? null;
            $order->order_reference->building_location_city = $customer['building_location_city'] ?? null;
            $order->order_reference->building_location_state = $customer['building_location_state'] ?? null;
            $order->order_reference->building_location_zip = $customer['building_location_zip'] ?? null;
        }

        // set specific order params for next calculation
        $order->building_condition = 'new';
        $order->sale_type = 'custom-order';
        $order->payment_type = 'cash';

        // Building
        $buildingModel = $order->building->building_model;
        $building = $order->building;

        $building->plant_id = (new PlantRepository)->getDefaultPlant()->id;
        $building->building_model_id = $buildingModel->id;
        $building->shell_price = $buildingModel->shell_price;
        $building->length = $buildingModel->length;
        $building->width = $buildingModel->width;
        $building->height = $buildingModel->wall_height;
        $building->total_options = $building->building_options->sum('total_price');
        $building->total_price = $building->shell_price + $building->total_options;

        $building->setRelation('siding', $this->getBuildingOptionByCategory($building, 'siding'));
        $building->setRelation('trim', $this->getBuildingOptionByCategory($building, 'trim'));
        $building->setRelation('roof', $this->getBuildingOptionByCategory($building, 'roof'));

        if ($building->building_package) {
            $building->building_package_id = $building->building_package->id;
        }

        $order->building_model_id = $building->building_model_id;
        $order->building_style_id = $building->building_model->style_id;
        $order->setRelation('building', $building);

        $orderCalculator = new OrderCalculator;
        $orderCalculator->setOrder($order);
        $orderCalculator->setDealer($order->dealer);
        $orderCalculator->setBuilding($order->building);
        $order = $orderCalculator->calculateOrder()->getOrder();

        return $order;
    }

    /**
     * Parse and Validate inputs against models
     * Return Order with models
     * @param Order $order
     * @param array $inputs
     * @return Order
     * @throws BusinessException
     * @throws ValidationException
     */
    private function parse(Order &$order, array $inputs = []) {
        $validator = Validator::make($inputs, []);

        // parse inputs to models
        if (array_get($inputs, 'dealer_id')) {
            try {
                $dealer = Dealer::where('is_active', 'yes')
                    ->where('id', array_get($inputs, 'dealer_id'))
                    ->firstOrFail();
                $order->dealer()->associate($dealer);
            } catch (ModelNotFoundException $e) {
                $validator->getMessageBag()->add('dealer_id', trans('order_form.dealer_id.is_not_exists'));
            }
        }

        if (array_get($inputs, 'building_dimension')) {
            try {
                $buildingModel = BuildingModel::where('is_active', 'yes')
                    ->where('id', array_get($inputs, 'building_dimension'))
                    ->firstOrFail();
                $order->building->building_model()->associate($buildingModel);
            } catch (ModelNotFoundException $e) {
                $validator->getMessageBag()->add('building_dimension', trans('order_form.building_dimension.is_not_exists'));
            }
        }

        if (array_get($inputs, 'building_style')) {
            try {
                $style = Style::where('is_active', 'yes')
                    ->where('id', array_get($inputs, 'building_style'))
                    ->firstOrFail();
                $order->building->building_model->style()->associate($style);
            } catch (ModelNotFoundException $e) {
                $validator->getMessageBag()->add('building_style', trans('order_form.building_style.is_not_exists'));
            }
        }

        if (array_get($inputs, 'building_package')) {
            try {
                $buildingPackage = BuildingPackage::where('is_active', 'yes')
                    ->where('id', array_get($inputs, 'building_package'))
                    ->firstOrFail();
                $order->building->building_package()->associate($buildingPackage);
            } catch (ModelNotFoundException $e) {
                $validator->getMessageBag()->add('building_package', trans('order_form.building_package.is_not_exists'));
            }
        }

        if (array_get($inputs, 'custom_build_options')) {
            $buildingOptions = new BuildingOptions($validator, $buildingModel, $order->building);
            $passes = $buildingOptions->passes(array_get($inputs, 'custom_build_options'));
            if (!$passes) {
                $validator->getMessageBag()->add('custom_build_options', trans('order_form.building_option.is_not_exists'));
            } else {
                $order->building->setRelation('building_options', $buildingOptions->getBuildingOptions());
            }
        }

        if (!$validator->getMessageBag()->isEmpty()) {
            throw new ValidationException($validator);
        }

        return $order;
    }

    /** Get building option by specific category group
     *  Used for cache trim, body, roof building properties
     * @param Building $building
     * @param string $group
     * @return BuildingOption|null
     */
    private function getBuildingOptionByCategory(Building $building, string $group): ?BuildingOption {
        return $building->building_options->first(function ($buildingOption) use ($group) {
            return $buildingOption->option->category->group === $group;
        });
    }
}
