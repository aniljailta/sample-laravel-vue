<?php

namespace App\Services\ArrayBuilder\ArrayBuilderValidator;

use App\Services\ArrayBuilder\RelationTrait;

class BuildWheres
{
    use RelationTrait;
    
    public $model;
    public $attributes;
    
    public $stringPath = '';

    public $rules = [];
    public $mapRules = [];

    public $source = [];
    public $dest = [];

    public $booleanOperators = [
        'isnull' => 'is null',
        'in' => 'is null',
        'notnull' => 'not null',
        'nn' => 'not null',
    ];

    /**
     * BuildWheres constructor.
     * @param $model
     * @param $arrayWhere
     */
    public function __construct($model, $arrayWhere)
    {
        $class = get_class($model);
        
        $this->model = $model;
        $this->attributes = collect($this->model->getVisible());
        $this->rules = $class::$rules ?? [];

        $this->source = $arrayWhere;
    }

    /**
     * @return $this
     */
    public function parse() {
        $this->getWheres($this->source, $this->dest, $this->getArrayPath('where', $this->stringPath));
        return $this;
    }

    /**
     * Parse WHERE
     * Remove not existed request/field request
     * @param $source
     * @param $dest
     * @param $stringPath
     * @return array
     */
    public function getWheres($source, &$dest, $stringPath)
    {
        if(!is_array($source)) return;

        $whereKeys = array_keys($source);
        foreach ($whereKeys as &$whereKey) {
            $model = $this->model;
            $attributes = $this->attributes;
            $whereField = $whereKey;
            
            if ($whereKey === 'or' || $whereKey === 'and')
            {
                foreach (array_keys($source[$whereKey]) as $subKey)
                    $this->getWheres($source[$whereKey], $dest[$whereKey], $this->getArrayPath($whereKey, $stringPath));
            } else
            {
                // if whereHas condition
                if (str_contains($whereKey, '.')) {

                    $whereParams = explode('.', $whereKey);
                    $whereField = array_pop($whereParams);

                    $model = $this->getRelation($model, $whereParams);
                    if (!$model)
                        continue;

                    $attributes = collect($model->getVisible());
                    $key = "{$whereKey}";
                } else {
                    $key = "{$whereKey}";
                }

                if (!$attributes->isEmpty() && $attributes->search($whereField) === false) {
                    // TODO: fallback
                    continue;
                }

                $destKey = $key;
                $this->getWhere($source, $dest[$destKey], $whereKey, $stringPath);
            }
        }
    }

    /**
     * Parse WHERE and detect + assign validation rules
     * @param $source
     * @param $dest
     * @param $whereKey
     * @param $stringPath
     */
    protected function getWhere($source, &$dest, $whereKey, $stringPath) {

        if (is_array($source[$whereKey]))
        {
            foreach ($source[$whereKey] as $operator => $value) {
                // TODO: validate operator

                if (is_array($value))
                {
                    if (isset($this->rules[$whereKey])) {
                        // make parsable array-string path for validators..
                        foreach (array_keys($value) as $key) {
                            $this->setRule(
                                $this->getArrayPath($key,
                                    $this->getArrayPath($operator,
                                        $this->getArrayPath($whereKey, $stringPath)
                                    )
                                ),
                                $this->getRule($whereKey, $operator)
                            );
                        }
                    }
                } else
                {
                    if (isset($this->rules[$whereKey])) {
                        $this->setRule($this->getArrayPath($operator, $this->getArrayPath($whereKey, $stringPath)), $this->getRule($whereKey, $operator));
                    }
                }
            }

            $dest = $source[$whereKey];
        } else
        {
            $dest = $source[$whereKey];
            if (isset($this->rules[$whereKey])) {
                $this->setRule($this->getArrayPath($whereKey, $stringPath), $this->rules[$whereKey]);
            }
        }

    }

    /**
     * @param $operator
     * @param $whereKey
     * @return mixed|string
     */
    protected function getRule($whereKey, $operator = null) {
        if ($operator) {
            if (isset($this->booleanOperators[$operator])) {
                return 'boolean';
            }
        }

        return $this->rules[$whereKey];
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
        return $current.".{$append}";
    }
}
