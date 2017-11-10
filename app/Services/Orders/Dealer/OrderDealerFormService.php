<?php

namespace App\Services\Orders\Dealer;

use App\Exceptions\BusinessException;

use App\Models\File;
use App\Models\OrderOption;
use App\Models\RtoCompany;
use App\Repositories\Plants\PlantRepository;
use App\Services\Orders\OrderFormService;
use App\Services\Orders\OrderCalculator as OrderCalculator;

use App\Models\Style;
use App\Models\Building;
use App\Models\BuildingModel;
use App\Models\Order;
use App\Models\OrderReference;
use App\Models\BuildingPackage;
use App\Models\Dealer;

use App\Validators\Building\BuildingOptions;
use App\Validators\Order\OrderOptions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use PDF;
use Storage;
use Store;
use DB;
use Uuid;
use Helper;
use Entrust;
use Validator;

use Carbon\Carbon;

class OrderDealerFormService extends OrderFormService
{

    /**
     * /dealer-order-form data mapper
     * Return structured order data for dealer order form
     * @param Order $order
     * @return Order|array
     */
    public function toArray(Order $order): array {
        // State
        $resState = [
            'id' => $order->uuid,
            'dealer_notes' => $order->dealer_notes,
            'note_admin' => $order->note_admin,
            'note_dealer' => $order->note_dealer,
            'status' => $order->status,
            'status_id' => $order->status_id,
            'updated_at' => $order->updated_at->format('m/d/Y H:i:s T'),
            'is_esigned' => false,
            'signer_role' => null
        ];

        // Dealer
        $dealerData = [];
        $dealerData['sales_person'] = $order->sales_person;
        if ($order->dealer) {
            $dealerData['id'] = $order->dealer->id;
            $dealerData['business_name'] = $order->dealer->business_name;
            $dealerData['phone_number'] = $order->dealer->phone;
            $dealerData['email'] = $order->dealer->email;
            $dealerData['tax_rate'] = $order->dealer->tax_rate;
            $dealerData['deposit_type'] = $order->dealer->deposit_type;
            $dealerData['cash_sale_deposit_rate'] = $order->dealer->cash_sale_deposit_rate;

            $dealerData['business_address'] = ($order->dealer->location) ? $order->dealer->location->address : '';
        }

        $resDealer = $dealerData;

        // Building
        $building = $order->building;
        if(!empty($building)){
            $resBuilding = [
                'building_condition' => $order->building_condition ?? null,
                'sale_type' => $order->sale_type ?? null,
                'serial' => $building->serial_number ?? null,
                'building_package' => $building->building_package ?? null,
                'building_style' => $building->building_model->style ?? null,
                'building_dimension' => $building->building_model ?? null,
                'custom_build_options' => $building->building_options ?? [],
                'latest_building_status' => $building->building_history->last()->building_status ?? [],
                'threedoptions' => $order->threedoptions
            ];
        }

        $inventoryBuilding = [
            'serial' => '',
            'price' => 0,
            'shellPrice' => 0,
            'totalOptions' => 0,
            'securityDeposit' => 0,
            'options' => []
        ];

        if ($order->sale_type === 'dealer-inventory') {
            $options = [];
            $building->building_options->each(function($item, $key) use(&$options) {
                $options[] = "{$item->option->name} x {$item->quantity}";

                if ($item->category->group === 'siding' && $item->color) {
                    array_unshift($options, "Siding color: {$item->color->name}");
                }

                if ($item->category->group === 'trim' && $item->color) {
                    array_unshift($options, "Trim color: {$item->color->name}");
                }

                if ($item->category->group === 'roof' && $item->color) {
                    array_unshift($options, "Roof color: {$item->color->name}");
                }
            });

            $inventoryBuilding = [
                'serial' => $building->serial_number,
                'shellPrice' => (double) $building->shell_price,
                'totalOptions' => (double) $building->total_options,
                'price' => (double) $building->total_price,
                'securityDeposit' => (double) $building->security_deposit,
                'options' => $options
            ];
        }

        $resBuilding['inventory_building'] = $inventoryBuilding;

        $resOrder = [
            'type' => $order->type,
            'payment_type' => $order->payment_type,
            'payment_method' => $order->payment_method,
            'promo99' => boolval($order->promo99),
            'gross_buydown' => $order->gross_buydown,
            'amount_received' => $order->amount_received,
            'transaction_id' => $order->transaction_id,
            'date' => ($order->order_date !== null) ? date_create_from_format('Y-m-d', $order->order_date)->format('m/d/Y'): null,
            'customer_expects_date' => [
                'start' => ($order->ced_start !== null) ? date_create_from_format('Y-m-d', $order->ced_start)->format('m/d/Y'): null,
                'end' => ($order->ced_end !== null) ? date_create_from_format('Y-m-d', $order->ced_end)->format('m/d/Y'): null,
            ],
            'rto_term' => $order->rto_term,
            'delivery_remarks' => [
                'level_pad' => boolval($order->dr_level_pad),
                'soft_when_wet' => boolval($order->dr_soft_when_wet),
                'width_restrictions' => boolval($order->dr_width_restrictions),
                'height_restrictions' => boolval($order->dr_height_restrictions),
                'requires_site_visit' => boolval($order->dr_requires_site_visit),
                'must_cross_neighboring_property' => boolval($order->dr_must_cross_neighboring_prop),
                'notes' => $order->dr_notes,
            ],
            'signature_method_id' => $order->signature_method_id,
            'options' => $order->options,
            'change_order_fee' => $order->change_order_fee,
            'original_order' => $order->original_order,
            'sales_tax_rate' => $order->sales_tax_rate,
            'mf_deposit_received' => $order->mf_deposit_received,
            'rto_deposit_received' => $order->rto_deposit_received,
            'amount_due' => $order->amount_due,
        ];

        // Customer/renter
        $customerFields = [
            'learning_about_us',
            'learning_about_us_other',
            'first_name',
            'last_name',
            'email',
            'phone_number',
            'address',
            'city',
            'state',
            'zip',
            'building_in_same_address',
            'building_location_address',
            'building_location_city',
            'building_location_state',
            'building_location_zip'
        ];
        $orderReference = collect($order->order_reference);

        $resRenter = $orderReference->except($customerFields);
        $resRenter['email_instead_of_mail'] = boolval($resRenter['email_instead_of_mail']);


        $resCustomer = $orderReference->only($customerFields);
        $resCustomer['building_in_same_address'] = boolval($resCustomer['building_in_same_address']);

        $resAttachments = $order->files;

        $file = $order->files()->where('category_id', 'complete_order_documents')->first();
        if ($file) {
            $signatures = $file->signs()->get();
            $signatureData = $this->getFileSignatureDataByRole($signatures, 'customer');
            $resState['is_esigned'] = $signatureData['is_esigned'];
            $resState['signer_role'] = $signatureData['signer_role'];
        }

        $adjustedOrder = [
            'state' => $resState,
            'dealer' => $resDealer,
            'customer' => $resCustomer,
            'building' => $resBuilding,
            'order' => $resOrder,
            'renter' => $resRenter,
            'attachments' => $resAttachments
        ];

        return $adjustedOrder;
    }

    /**
     * /dealer-order-form data mapper
     * Method should be called after all validations
     * @param array $inputs
     * @return Order
     * @throws BusinessException
     */
    public function toModel(array $inputs): Order {
        $order = $this->initOrder($inputs);

        $statusesNotAllowedToModify = [
            'signature_pending', 'submitted', 'cancellation_requested', 'cancelled'
        ];

        if ($this->isCancellationRequested($order, $inputs)) {
            $order->status_id = $inputs['status_id'];
            $order->status_id = array_get($inputs, 'status_id', $order->status_id);
            $order->note_dealer = array_get($inputs, 'note_dealer', $order->note_dealer);

            return $order;
        }

        // black list for dealer
        if ($order->status_id === 'sale_generated') {
            throw new BusinessException(trans('exceptions.orders.is_not_allowed_to_update', [
                'contact_name' => Dealer::findOrFail(1)->business_name
            ]));
        }

        // return model with only dealer notes changed in case
        // if status does not allow changes in order's data
        if (in_array($order->status_id, $statusesNotAllowedToModify)) {
            $order->note_dealer = array_get($inputs, 'note_dealer', $order->note_dealer);
            $order->dealer_notes = array_get($inputs, 'dealer_notes', $order->dealer_notes);

            return $order;
        }

        // parse order changes (if status/order state is allow to do it)
        $order = $this->parse($order, $inputs);

        return $order;
    }

    /** Find or fail order by UUID (if not new order)
     *  OR Initialize new Order
     * @param array $inputs
     * @return Order
     */
    protected function initOrder(array $inputs): Order {
        if (array_get($inputs,'id') && array_get($inputs,'save_as') !== 'new') {
            $order = Order::where('uuid', array_get($inputs,'id'))
                ->with(['options'])
                ->firstOrFail();

            // If current order sale type is dealer-inventory, but user select it to custom-order
            // we need to use fresh/new Building
            // Avoid to 'modify' dealer-inventory Building
            if (array_get($inputs,'sale_type') === 'custom-order' && $order->sale_type === 'dealer-inventory') {
                $order->setRelation('building', $this->initBuilding());
            }
            $order->threedoptions =  $inputs["threedoptions"] ?? null;
            return $order;
        }

        // init new order
        $order = new Order;

        // Add order reference if available
        if (!empty($inputs['reference_id'])) {
            $orderReference = OrderReference::find($inputs['reference_id']);
        } else {
            $orderReference = new OrderReference;
        }
        $order->setRelation('building', $this->initBuilding());
        $order->setRelation('order_reference', $orderReference);

        $order->uuid = Uuid::generate(4)->string;
        $order->status_id = Order::INITIAL_STATUS_ID; // default on new
        if (isset($inputs["threedoptions"])) {
            $order->threedoptions = $inputs["threedoptions"];
        }
        return $order;
    }

    /**
     * Parse order data from dealer order form to Order entity
     * @param Order $order
     * @param array $inputs
     * @return Order
     */
    private function parseOrder(Order &$order, array $inputs = []): Order {
        if (Entrust::hasRole('administrator'))
        {
            if (array_key_exists('note_admin', $inputs)) $order->note_admin = $inputs['note_admin'];
        }

        if (array_key_exists('status_id', $inputs)) $order->status_id = $inputs['status_id'];
        if (array_key_exists('note_dealer', $inputs)) $order->note_dealer = $inputs['note_dealer'];
        if (array_key_exists('dealer_notes', $inputs)) $order->dealer_notes = $inputs['dealer_notes'];
        if (array_key_exists('type', $inputs)) $order->type = $inputs['type'];
        if (array_key_exists('dealer_id', $inputs)) $order->dealer_id = $inputs['dealer_id'];
        if (array_key_exists('customer_id', $inputs)) $order->customer_id = $inputs['customer_id'];
        if (array_key_exists('sales_person', $inputs)) $order->sales_person = $inputs['sales_person'];
        if (array_key_exists('payment_type', $inputs)) $order->payment_type = $inputs['payment_type'];
        if (array_key_exists('payment_method', $inputs)) $order->payment_method = $inputs['payment_method'];
        if (array_key_exists('promo99', $inputs)) $order->promo99 = intval($inputs['promo99']);
        if (array_key_exists('building_condition', $inputs)) $order->building_condition = $inputs['building_condition'];
        if (array_key_exists('sale_type', $inputs)) $order->sale_type = $inputs['sale_type'];
        // if (array_key_exists('building_id', $inputs)) $order->building_id = $inputs['building_id'];

        if (array_key_exists('rto_term', $inputs)) $order->rto_term = $inputs['rto_term'];
        if (array_key_exists('gross_buydown', $inputs)) $order->gross_buydown = $inputs['gross_buydown'];
        if (array_key_exists('amount_received', $inputs)) $order->amount_received = $inputs['amount_received'];
        if (array_key_exists('transaction_id', $inputs)) $order->transaction_id = $inputs['transaction_id'];

        if (array_key_exists('delivery_remarks', $inputs)) {
            $deliveryInputs = $inputs['delivery_remarks'];
            if (array_key_exists('level_pad',$deliveryInputs)) $order->dr_level_pad = intval($deliveryInputs['level_pad']);
            if (array_key_exists('soft_when_wet',$deliveryInputs)) $order->dr_soft_when_wet = intval($deliveryInputs['soft_when_wet']);
            if (array_key_exists('width_restrictions',$deliveryInputs)) $order->dr_width_restrictions = intval($deliveryInputs['width_restrictions']);
            if (array_key_exists('height_restrictions',$deliveryInputs)) $order->dr_height_restrictions = intval($deliveryInputs['height_restrictions']);
            if (array_key_exists('requires_site_visit',$deliveryInputs)) $order->dr_requires_site_visit = intval($deliveryInputs['requires_site_visit']);
            if (array_key_exists('must_cross_neighboring_property',$deliveryInputs)) $order->dr_must_cross_neighboring_prop = intval($deliveryInputs['must_cross_neighboring_property']);
            if (array_key_exists('notes',$deliveryInputs)) $order->dr_notes = $deliveryInputs['notes'];
        }

        if(array_key_exists('order_date', $inputs)) $order->order_date = ($inputs['order_date']) ? date_create_from_format('m/d/Y', $inputs['order_date'])->format('Y-m-d') : null;

        if(array_key_exists('ced', $inputs)) {
            $ced = $inputs['ced'];
            if(array_key_exists('start', $ced)) $order->ced_start = ($ced['start']) ? date_create_from_format('m/d/Y', $ced['start'])->format('Y-m-d') : null;
            if(array_key_exists('end', $ced)) $order->ced_end = ($ced['end']) ? date_create_from_format('m/d/Y', $ced['end'])->format('Y-m-d') : null;
        }

        if (array_key_exists('signature_method_id', $inputs)) $order->signature_method_id = $inputs['signature_method_id'];
        if (array_key_exists('sales_tax_rate', $inputs)) $order->sales_tax_rate = $inputs['sales_tax_rate'];

        return $order;
    }

    /**
     * Parse order reference data from dealer order form to Order Reference entity
     * @param OrderReference $orderReference
     * @param array $inputs
     * @return OrderReference
     */
    private function parseOrderReference(OrderReference &$orderReference, array $inputs = []): OrderReference {
        if (array_key_exists('customer', $inputs)) {
            $customer = $inputs['customer'];

            if (array_key_exists('learning_about_us', $customer)) $orderReference->learning_about_us = $customer['learning_about_us'];
            if (array_key_exists('learning_about_us_other', $customer)) $orderReference->learning_about_us_other = $customer['learning_about_us_other'];

            if (array_key_exists('first_name', $customer)) $orderReference->first_name = $customer['first_name'];
            if (array_key_exists('last_name', $customer)) $orderReference->last_name = $customer['last_name'];
            if (array_key_exists('email', $customer)) $orderReference->email = $customer['email'];
            if (array_key_exists('phone_number', $customer)) $orderReference->phone_number = $customer['phone_number'];
            if (array_key_exists('address', $customer)) $orderReference->address = $customer['address'];
            if (array_key_exists('city', $customer)) $orderReference->city = $customer['city'];
            if (array_key_exists('state', $customer)) $orderReference->state = $customer['state'];
            if (array_key_exists('zip', $customer)) $orderReference->zip = $customer['zip'];

            if (array_key_exists('building_in_same_address', $customer)) $orderReference->building_in_same_address = intval($customer['building_in_same_address']);
            if (array_key_exists('building_location_address', $customer)) $orderReference->building_location_address = $customer['building_location_address'];
            if (array_key_exists('building_location_city', $customer)) $orderReference->building_location_city = $customer['building_location_city'];
            if (array_key_exists('building_location_state', $customer)) $orderReference->building_location_state = $customer['building_location_state'];
            if (array_key_exists('building_location_zip', $customer)) $orderReference->building_location_zip = $customer['building_location_zip'];
        }

        if (array_key_exists('renter', $inputs)) {
            $renter = $inputs['renter'];

            if (array_key_exists('property_ownership', $renter)) $orderReference->property_ownership = $renter['property_ownership'];
            if (array_key_exists('landlord_full_name', $renter)) $orderReference->landlord_full_name = $renter['landlord_full_name'];
            if (array_key_exists('landlord_phone_number', $renter)) $orderReference->landlord_phone_number = $renter['landlord_phone_number'];
            if (array_key_exists('text_allowed1', $renter)) $orderReference->text_allowed1 = $renter['text_allowed1'];
            if (array_key_exists('cell_phone_number2', $renter)) $orderReference->cell_phone_number2 = $renter['cell_phone_number2'];
            if (array_key_exists('text_allowed2', $renter)) $orderReference->text_allowed2 = $renter['text_allowed2'];
            if (array_key_exists('home_phone_number', $renter)) $orderReference->home_phone_number = $renter['home_phone_number'];
            if (array_key_exists('email_instead_of_mail', $renter)) $orderReference->email_instead_of_mail = intval($renter['email_instead_of_mail']);


            if (array_key_exists('renter_employer', $renter)) $orderReference->renter_employer = $renter['renter_employer'];
            if (array_key_exists('renter_employer_phone_number', $renter)) $orderReference->renter_employer_phone_number = $renter['renter_employer_phone_number'];
            if (array_key_exists('renter_employer_phone_extension', $renter)) $orderReference->renter_employer_phone_extension = $renter['renter_employer_phone_extension'];
            if (array_key_exists('renter_supervisor', $renter)) $orderReference->renter_supervisor = $renter['renter_supervisor'];
            if (array_key_exists('renter_supervisor_occupation', $renter)) $orderReference->renter_supervisor_occupation = $renter['renter_supervisor_occupation'];

            if (array_key_exists('co_renter_first_name', $renter)) $orderReference->co_renter_first_name = $renter['co_renter_first_name'];
            if (array_key_exists('co_renter_last_name', $renter)) $orderReference->co_renter_last_name = $renter['co_renter_last_name'];
            if (array_key_exists('co_renter_employer', $renter)) $orderReference->co_renter_employer = $renter['co_renter_employer'];
            if (array_key_exists('co_renter_employer_phone_number', $renter)) $orderReference->co_renter_employer_phone_number = $renter['co_renter_employer_phone_number'];
            if (array_key_exists('co_renter_employer_phone_extension', $renter)) $orderReference->co_renter_employer_phone_extension = $renter['co_renter_employer_phone_extension'];
            if (array_key_exists('co_renter_supervisor', $renter)) $orderReference->co_renter_supervisor = $renter['co_renter_supervisor'];
            if (array_key_exists('co_renter_supervisor_occupation', $renter)) $orderReference->co_renter_supervisor_occupation = $renter['co_renter_supervisor_occupation'];

            if (array_key_exists('reference1_name', $renter)) $orderReference->reference1_name = $renter['reference1_name'];
            if (array_key_exists('reference1_relationship', $renter)) $orderReference->reference1_relationship = $renter['reference1_relationship'];
            if (array_key_exists('reference1_phone_number', $renter)) $orderReference->reference1_phone_number = $renter['reference1_phone_number'];
            if (array_key_exists('reference1_address', $renter)) $orderReference->reference1_address = $renter['reference1_address'];
            if (array_key_exists('reference1_city', $renter)) $orderReference->reference1_city = $renter['reference1_city'];
            if (array_key_exists('reference1_state', $renter)) $orderReference->reference1_state = $renter['reference1_state'];
            if (array_key_exists('reference1_zip', $renter)) $orderReference->reference1_zip = $renter['reference1_zip'];

            if (array_key_exists('reference2_name', $renter)) $orderReference->reference2_name = $renter['reference2_name'];
            if (array_key_exists('reference2_relationship', $renter)) $orderReference->reference2_relationship = $renter['reference2_relationship'];
            if (array_key_exists('reference2_phone_number', $renter)) $orderReference->reference2_phone_number = $renter['reference2_phone_number'];
            if (array_key_exists('reference2_address', $renter)) $orderReference->reference2_address = $renter['reference2_address'];
            if (array_key_exists('reference2_city', $renter)) $orderReference->reference2_city = $renter['reference2_city'];
            if (array_key_exists('reference2_state', $renter)) $orderReference->reference2_state = $renter['reference2_state'];
            if (array_key_exists('reference2_zip', $renter)) $orderReference->reference2_zip = $renter['reference2_zip'];
        }

        return $orderReference;
    }

    /**
     * This method should be runned after validation
     * Parse building data from dealer order form to Building entity
     * @param Order $order
     * @param array $inputs
     * @return Building
     * @throws BusinessException
     */
    private function parseBuilding(Order &$order, array $inputs = []): Building {
        // inventory building shouldn't affect existed building
        if ($order->sale_type === 'dealer-inventory') {
            if (array_get($inputs, 'serial')) {
                try {
                    $building = Building::select('buildings.*')
                        ->join('building_locations', 'building_locations.id', '=', 'buildings.last_location_id')
                        ->where('building_locations.location_id', $order->dealer->location_id)
                        ->where('serial_number', array_get($inputs, 'serial'))
                        ->firstOrFail();
                    $order->building_id = $building->id;
                    $order->setRelation('building', $building);
                } catch (ModelNotFoundException $e) {
                    throw new BusinessException(trans('order_form.dealer_inventory.is_not_exists'));
                }
            }
            return $order->building;
        }

        $buildingModel = $order->building->building_model;
        $building = $order->building;
        $building->plant_id = (new PlantRepository)->getDefaultPlant()->id;

        if ($buildingModel && $buildingModel->exists) {
            $building->building_model_id = $buildingModel->id;
            $building->shell_price = $buildingModel->shell_price;
            $building->length = $buildingModel->length;
            $building->width = $buildingModel->width;
            $building->height = $buildingModel->wall_height;
        }

        $building->total_options = $building->building_options->sum('total_price');
        $building->total_price = $building->shell_price + $building->total_options;

        if ($building->building_package) {
            $building->building_package_id = $building->building_package->id;
        }

        $order->setRelation('building', $building);
        return $building;
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
    private function parse(Order $order, array $inputs = []) {
        $validator = Validator::make($inputs, []);

        $changedOrder = clone $order;

        // parse inputs to models
        if (array_get($inputs, 'dealer_id')) {
            try {
                $dealer = Dealer::findOrFail(array_get($inputs, 'dealer_id'));
                $changedOrder->setRelation('dealer', $dealer);
            } catch (ModelNotFoundException $e) {
                $validator->getMessageBag()->add('dealer_id', trans('order_form.dealer_id.is_not_exists'));
            }
        }

        if (array_get($inputs, 'building_dimension')) {
            try {
                $buildingModel = BuildingModel::findOrFail(array_get($inputs, 'building_dimension'));
                $changedOrder->building->setRelation('building_model', $buildingModel);
            } catch (ModelNotFoundException $e) {
                $validator->getMessageBag()->add('building_dimension', trans('order_form.building_dimension.is_not_exists'));
            }
        }

        if (array_get($inputs, 'building_style') && $changedOrder->building->building_model) {
            try {
                $style = Style::findOrFail(array_get($inputs, 'building_style'));
                $changedOrder->building->building_model->setRelation('style', $style);
            } catch (ModelNotFoundException $e) {
                $validator->getMessageBag()->add('building_style', trans('order_form.building_style.is_not_exists'));
            }
        }

        if (array_get($inputs, 'building_package')) {
            try {
                $buildingPackage = BuildingPackage::findOrFail(array_get($inputs, 'building_package'));
                $changedOrder->building->setRelation('building_package', $buildingPackage);
            } catch (ModelNotFoundException $e) {
                $validator->getMessageBag()->add('building_package', trans('order_form.building_package.is_not_exists'));
            }
        }

        if (array_get($inputs, 'custom_build_options')) {
            $buildingOptions = new BuildingOptions($validator, $buildingModel, $changedOrder->building);
            $passes = $buildingOptions->passes(array_get($inputs, 'custom_build_options'));
            if (!$passes) {
                $validator->getMessageBag()->add('custom_build_options', trans('order_form.building_option.is_not_exists'));
            } else {
                $changedOrder->building->setRelation('building_options', $buildingOptions->getBuildingOptions());
            }
        }

        if (array_get($inputs, 'order_options')) {
            $orderOptions = new OrderOptions($validator, $changedOrder);
            $passes = $orderOptions->passes(array_get($inputs, 'order_options'));
            if (!$passes) {
                $validator->getMessageBag()->add('order_options', trans('order_form.order_option.is_not_exists'));
            } else {
                $changedOrder->setRelation('options', $orderOptions->getOptions());
            }
        }

        if (!$validator->getMessageBag()->isEmpty()) {
            throw new ValidationException($validator);
        }

        // Apply inputs to model
        // + Apply building
        $this->parseOrder($changedOrder, $inputs);
        $this->parseOrderReference($changedOrder->order_reference, $inputs);
        $this->parseBuilding($changedOrder, $inputs);

        $orderCalculator = new OrderCalculator();
        $orderCalculator->setOrder($changedOrder);
        $orderCalculator->setDealer($changedOrder->dealer);
        $orderCalculator->setBuilding($changedOrder->building);
        $changedOrder = $orderCalculator->calculateOrder()->getOrder();

        if (array_get($inputs, 'amount_received')) {
            if ((double) array_get($inputs, 'amount_received') < (double) $changedOrder->min_amount_received) {
                $validator->getMessageBag()->add('amount_received', trans('order_form.amount_received.should_be_more_or_equal_min_amount_amount', [
                    'amount' => $changedOrder->min_amount_received
                ]));
            }
        }

        // check attachments if new status === submit
        if (array_get($inputs, 'status_id') === 'submitted') {
            $requiredAttachments = collect([]);

            if ($changedOrder->signature_method_id === 'manual') {
                $requiredAttachments->push('signed_order_documents');
            }

            if ($changedOrder->signature_method_id === 'e_signature' && $changedOrder->payment_type !== 'rto') {
                $requiredAttachments->push('e_signed_order_documents');
            }

            if ($changedOrder->signature_method_id === 'e_signature' && $changedOrder->paymentType === 'rto') {
                $requiredAttachments->push('rto_signature_pending');
            }

            if ($changedOrder->sale_type === 'custom-order') {
                $requiredAttachments->push('signed_building_configuration');
            }

            if ($changedOrder->payment_method !== 'credit_card') {
                $requiredAttachments->push('signed_deposit_receipt');
            }

            if ($changedOrder->payment_type !== 'cash') {
                $requiredAttachments->push('driver_license');
            }

            $validAttachments = $changedOrder->files()->whereIn('category_id', $requiredAttachments)->get();
            $requiredAttachments->each(function($attachment) use ($validAttachments, &$validator) {
                if (!$validAttachments->contains('category_id', $attachment)) {
                    $category = File::$categories[$attachment];
                    $validator->getMessageBag()->add('attachments', trans('order_form.attachments.required_attachment_is_not_present', [
                        'category' => $category['title']
                    ]));
                }
            });
        }

        if (!$validator->getMessageBag()->isEmpty()) {
            throw new ValidationException($validator);
        }

        // if e-signature option is selected
        // save only fields for [deposit receipt], return original order here + partial fields
        // ONLY after validation
        if (in_array($order->status_id, ['signature_pending', 'signed'])) {
            // $order->amount_received = array_get($inputs, 'amount_received', $changedOrder->amount_received);
            $order->payment_method = array_get($inputs, 'payment_method', $changedOrder->payment_method);
            $order->transaction_id = array_get($inputs, 'transaction_id', $changedOrder->transaction_id);
            $order->note_dealer = array_get($inputs, 'note_dealer', $changedOrder->note_dealer);
            $order->dealer_notes = array_get($inputs, 'dealer_notes', $changedOrder->dealer_notes);
            $order->status_id = array_get($inputs, 'status_id', $changedOrder->status_id);
            return $order;
        }

        // TODO: dynamic rto company based on manufacturer-rto choices
        if ($changedOrder->payment_type === 'rto' && ($rtoCompany = RtoCompany::first())) {
            $changedOrder->{RTO_COMPANY_ID} = $rtoCompany->company_id;
        }

        return $changedOrder;
    }

    /**
     * @param array $input
     *
     * @return bool
     */
    private function isCancellationRequested(Order $order, array $input): bool
    {
        $allowedStatuses = ['signature_pending', 'signed', 'submitted', 'sale_generated'];

        return in_array($order->status_id, $allowedStatuses)
            && isset($input['status_id'])
            && $input['status_id'] === 'cancellation_requested';
    }

    /**
     * @param Collection $signatures
     *
     * @param string $signerRole
     * @return array
     */
    public function getFileSignatureDataByRole(Collection $signatures, string $signerRole): array
    {
        $esigned = false;

        foreach ($signatures as $signature) {
            if ($signature->signer_role === $signerRole) {
                $esigned = $signature->is_esigned;
            }
        }

        return [
            'is_esigned' => $esigned,
            'signer_role' => $signerRole
        ];
    }
}
