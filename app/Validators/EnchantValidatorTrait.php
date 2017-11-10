<?php

namespace App\Validators;
use Illuminate\Support\Fluent;

trait EnchantValidatorTrait {

    /*
     * Using for avoid multiple request for the same data
     * So, it should be just stored here
     */
    protected $store = [];

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function store($key, $value) {
        $this->data[$key] = $value;
        
        return $this;
    }

    /**
     * @param $key
     * @return null
     */
    public function getStore($key) {
        if (isset($this->data[$key]) )
            return $this->data[$key];

        return null;
    }

    /**
     * @param $key
     * @return null
     */
    public function clearStore($key) {
        if (isset($this->data[$key]) )
            unset($this->data[$key]);

        return null;
    }

    private function getOriginalAttributeKey($attribute, $parameters)
    {
        $attributeOrigName = $parameters[0];
        $attributeOrigKey = str_replace($attributeOrigName.'_', '', $attribute);

        return $attributeOrigKey;
    }

    /**
     * Add conditions to a given field based on a Closure.
     *
     * @param  string  $attribute
     * @param  string|array  $rules
     * @param  callable  $callback
     * @return void
     */
    public function sometimes($attribute, $rules, callable $callback)
    {
        $payload = new Fluent($this->getInputs());

        if (call_user_func($callback, $payload)) {
            foreach ((array) $attribute as $key) {
                $this->addRule($key, $rules);
            }
        }

        return $this;
    }
}