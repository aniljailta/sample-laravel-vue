<?php

namespace App\Services\ArrayBuilder\ArrayBuilderValidator;

use App\Services\ArrayBuilder\RelationTrait;

class BuildOrder
{
    use RelationTrait;
    
    public $model;
    public $attributes;
    
    public $stringPath = '';

    public $rules = [];
    public $mapRules = [];

    public $source = [];
    public $dest = [];

    /**
     * BuildOrder constructor.
     * @param $model
     * @param $arrayOrder
     */
    public function __construct($model, $arrayOrder)
    {
        $class = get_class($model);

        $this->model = $model;
        $this->attributes = collect($this->model->getVisible());
        $this->rules = $class::$rules ?? [];

        $this->source = $arrayOrder;
    }

    public function parse() {
        $orderFields = $this->buildOrderBy($this->source);

        foreach ($orderFields as $orderItem) {
            $model = $this->model;
            $attributes = $this->attributes;
            $orderField = $orderItem[0];
            $orderDirection = $orderItem[1];

            if (str_contains($orderItem[0], '.')) {
                $orderParams = explode('.', $orderItem[0]);
                $orderField = array_pop($orderParams);

                //if (!method_exists($model, 'getRelationsClassName'))
                //    continue;
                
                $model = $this->getRelation($model, $orderParams);
                if (!$model) 
                    continue;
                
                $attributes = collect($model->getVisible());
            }

            // TODO: check model properties + aliases
            if (!$attributes->isEmpty() && $attributes->search($orderField) === false) {
                // TODO: fallback
                // continue;
            }

            if (array_search($orderField, $model->getVisible()) !== false) {
                $this->dest[] = "{$model->getTable()}.{$orderField} {$orderDirection}";
            } else {
                $this->dest[] = "{$orderField} {$orderDirection}";
            }
        }

        if (!$this->attributes->isEmpty()) {
            // TODO: rules
            $fields = implode(',', $this->attributes->toArray());
            // $this->setRule($this->getArrayPath('fields', $this->stringPath), "in:{$fields}");
        }
    }

    /**
     * @param array|string $order
     * @return array|void
     */
    protected function buildOrderBy($order)
    {
        $orderFields = [];

        if (is_array($order)) {
            foreach ($order as $orderItem) {
                $orderFields[] = $this->buildOrderBySingle($orderItem);
            }

            return $orderFields;
        }

        $orderFields[] = $this->buildOrderBySingle($order);
        return $orderFields;
    }

    /**
     * @param string $order
     * @return mixed
     * @internal param Builder
     */
    protected function buildOrderBySingle($order)
    {
        $order = strtolower($order);
        $orderBy = str_replace([' asc', ' desc'], '', $order);
        $orderDirection = ends_with($order, ' desc') ? 'desc' : 'asc';

        return [
            $orderBy, $orderDirection
        ];
    }

    /**
     * String path used for validation rules path
     * @param $path
     */
    public function setStringPath($path) {
        $this->stringPath = $path;
    }

    /**
     * Add rule to rule map (laravel compatibility)
     * @param $path
     * @param $rule
     */
    protected function setRule($path, $rule) {
        $this->mapRules[$path] = $rule;
    }

    /**
     * Make array path as string (for validator)
     * @param $append
     * @param string $current
     * @return string
     */
    protected function getArrayPath($append, $current = '') {
        if ($current === '')
            return $append;
        return $current."[{$append}]";
    }
}
