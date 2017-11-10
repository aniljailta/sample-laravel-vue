<?php
namespace App\Validators\Order;

use App\Models\Order;
use App\Models\Option;
use App\Models\OrderOption;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderOptions
{

    public $validator;
    public $is_valid = true;

    public $order;
    public $orderOptions;

    /*
     * Options with 'order' category
     */
    public $options;

    /**
     * Order Options constructor.
     * @param $validator
     * @param Order $order
     */
    public function __construct($validator, Order $order)
    {
        $this->validator = $validator;
        $this->order = $order;

        // Options with 'order' category
        $this->options = Option::whereHas('category', function($q) {
            $q->where('group', 'order');
        })->get();
    }

    /**
     * @param $value
     * @return bool
     */
    public function passes($value): bool
    {
        $orderOptions = collect();

        // load order options for existed order
        if ($this->order && $this->order->exists) {
            $this->order->load('options.option');
        }

        foreach ($value as $key => &$option) {
            // validate input option and get order option
            $orderOption = $this->validateOption($option);
            if (!$orderOption) continue;

            $orderOptions->push($orderOption);
        }

        $this->orderOptions = $orderOptions;
        return $this->is_valid;
    }

    /**
     * @param array $option
     * @return OrderOption
     */
    private function validateOption(array &$option): ?OrderOption {
        $option = array_only($option, ['option_id', 'quantity']);
        $option['is_valid'] = true;

        if (!isset($option['quantity']) || !is_numeric($option['quantity']) || !($option['quantity'] >= 0)) {
            $this->validator->getMessageBag()->add('is_valid_order_options', 'Quantity of specified order option is not valid');
            $this->is_valid = $option['is_valid'] = false;
        }

        // check options as is
        if (!isset($option['option_id'])) {
            $this->validator->getMessageBag()->add('is_valid_order_options', 'Specified order option is not found');
            $this->is_valid = $option['is_valid'] = false;
            return null; // not found in db =(
        }

        $orderOption = $this->getOrderOption($option);
        // check options as is
        if (!$orderOption->option) {
            $this->validator->getMessageBag()->add('is_valid_order_options', 'Specified order option is not allowed for this model');
            $this->is_valid = $option['is_valid'] = false;
            return null; // not found in db =(
        }

        $orderOption->quantity = $option['quantity'];
        $orderOption->total_price = $orderOption->unit_price * $orderOption->quantity;
        return $orderOption;
    }

    /**
     * @param $option
     * @return OrderOption
     */
    private function getOrderOption($option): ?OrderOption {
        // allow OLD option to be saved in current order
        if ($this->order->exists) {
            $orderOption = $this->order->options->last(function($item) use($option) {
                return $item->option_id === $option['option_id'];
            });

            if ($orderOption) {
                $orderOption = $orderOption->replicate();
                return $orderOption;
            }
        }

        // if ORDER not exists or OPTION is not exist in order - we can use option based on building model
        $option = $this->options->last(function ($item) use ($option) {
            return $item->id === $option['option_id'];
        });

        $orderOption = new OrderOption;
        if(!$option) return $orderOption;

        $orderOption->setRelation('option', $option);
        $orderOption->unit_price = $option->unit_price;
        $orderOption->option_id = $option->id;
        return $orderOption;
    }

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->orderOptions;
    }
}