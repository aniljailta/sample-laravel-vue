<?php

namespace App\Services\ArrayBuilder\ArrayBuilderValidator;

use App\Services\ArrayBuilder\RelationTrait;

class BuildFunctions
{
    
    use RelationTrait;
    
    public $model;
    public $attributes;
    
    public $stringPath = '';

    public $rules = [];
    public $mapRules = [];

    public $source = [];
    public $dest = [];

    public $possibleFunctions = [
      'sum', 'count', 'distinct', 'max', 'min', 'avg'
    ];

    /**
     * BuildFields constructor.
     * @param $model
     * @param $arrayGroup
     */
    public function __construct($model, $arrayGroup)
    {
        $class = get_class($model);

        $this->model = $model;
        $this->attributes = collect($this->model->getVisible());
        $this->rules = $class::$rules ?? [];

        $this->source = $arrayGroup;
    }

    public function parse() {
        if (!is_array($this->source)) return;

        foreach ($this->source as $fArgs => $field) {
            $this->parseFunctions($fArgs, $field);
        }
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

    protected function parseFunctions($fArgs, $item) {
        $model = $this->model;
        $attributes = $this->attributes;
        $field = $item;

        // parse for getting relations table/validations
        if (str_contains($item, '.')) {
            $groupParams = explode('.', $item);
            $field = array_pop($groupParams);

            $model = $this->getRelation($model, $groupParams);
            if (!$model)
                return false;

            $attributes = collect($model->getVisible());
        }

        // set rules
        if (!$attributes->isEmpty()) {

            // add $relationName if exist
            if (!empty($groupParams)) {
                $relationName = implode('.', $groupParams);
                $fields = implode(',', $attributes->transform(function($value) use ($relationName) {
                    return "{$relationName}.{$value}";
                })->toArray());
            } else {
                $fields = implode(',', $attributes->toArray());
            }

            $this->setRule($this->getArrayPath("fn[{$fArgs}]", $this->stringPath), "in:{$fields}");
        }

        /*
        if (!$attributes->isEmpty() && $attributes->search($field) === false) {
            // TODO: fallback
            return true;
        }*/

        // $this->dest[$fArgs] = "{$model->getTable()}.{$field}";
        return true;
    }
}
