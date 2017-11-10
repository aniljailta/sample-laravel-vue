<?php

namespace App\Services\ArrayBuilder\ArrayBuilderValidator;

class BuildFields
{
    public $model;
    public $attributes;
    
    public $stringPath = '';

    public $rules = [];
    public $mapRules = [];

    public $source = [];
    public $dest = [];

    /**
     * BuildFields constructor.
     * @param $model
     * @param $arrayFields
     */
    public function __construct($model, $arrayFields)
    {
        $class = get_class($model);

        $this->model = $model;
        $this->attributes = collect($this->model->getVisible());
        $this->rules = $class::$rules ?? [];

        $this->source = $arrayFields;
    }

    public function parse() {
        foreach ($this->source as $fieldKey) {
            if (!$this->attributes->isEmpty() && $this->attributes->search($fieldKey) === false) {
                // TODO: fallback
                if ($fieldKey !== '*') continue;
            }

            $this->dest[] = $this->model->getTable().'.'.$fieldKey;
        }

        if (!$this->attributes->isEmpty()) {
            $fields = implode(',', $this->attributes->toArray());
            $this->setRule($this->getArrayPath('fields.*', $this->stringPath), "in:*,{$fields}");
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
}
