<?php

namespace App\Services\ArrayBuilder\ArrayBuilderValidator;

use Illuminate\Database\Eloquent\Model;

use App\Services\ArrayBuilder\ArrayBuilderValidator\BuildWheres as BuildWheres;
use App\Services\ArrayBuilder\ArrayBuilderValidator\BuildWhereHas as BuildWhereHas;
use App\Services\ArrayBuilder\ArrayBuilderValidator\BuildFields as BuildFields;
use App\Services\ArrayBuilder\ArrayBuilderValidator\BuildOrder as BuildOrder;
use App\Services\ArrayBuilder\ArrayBuilderValidator\BuildGroup as BuildGroup;
use App\Services\ArrayBuilder\ArrayBuilderValidator\BuildFunctions as BuildFunctions;

class LayerParser
{

    public $model;
    public $name;
    public $class;

    public $validator;
    public $rules;
    public $messages = [];

    public $path = '';
    public $stringPath;
    
    public $source = [];
    public $dest = [];

    /**
     * LayerParser constructor.
     * @param Model $eloquent
     * @param array $arrayQuery
     */
    public function __construct(Model $eloquent, array $arrayQuery)
    {
        $this->model = $eloquent;
        $class = get_class($this->model);

        $this->class = $class;
        $this->name = strtolower(class_basename($class));
        $this->source = $arrayQuery;
    }

    /**
     * Init string path of provided data layer (for correct validation rules)
     */
    protected function initPath() {
        if ($this->path === 'include')
            $this->stringPath = "{$this->path}.{$this->name}";
        else
            $this->stringPath = $this->path;
    }

    /**
     * Parse array query
     */
    public function parse() {
        $this->initPath();
        
        $arrayQuery = $this->source;

        if (!empty($arrayQuery['fn'])) {
            $this->parseFunctions($arrayQuery['fn']);
        }

        if (!empty($arrayQuery['where'])) {
            $this->parseWheres($arrayQuery['where']);
        }

        if (!empty($arrayQuery['fields'])) {
            $this->parseFields($arrayQuery['fields']);
        }

        if (!empty($arrayQuery['order_by'])) {
            $this->parseOrder($arrayQuery['order_by']);
        }

        if (!empty($arrayQuery['group_by'])) {
            $this->parseGroup($arrayQuery['group_by']);
        }

        // TODO: validation / checks
        if (!empty($arrayQuery['join_relation'])) {
            $this->dest['join_relation'] = $arrayQuery['join_relation'];
        }
        if (!empty($arrayQuery['join_relation_where'])) {
            $this->dest['join_relation_where'] = $arrayQuery['join_relation_where'];
        }
        if (!empty($arrayQuery['left_join_relation'])) {
            $this->dest['left_join_relation'] = $arrayQuery['left_join_relation'];
        }
        if (!empty($arrayQuery['left_join_relation_where'])) {
            $this->dest['left_join_relation_where'] = $arrayQuery['left_join_relation_where'];
        }
        if (!empty($arrayQuery['right_join_relation'])) {
            $this->dest['right_join_relation'] = $arrayQuery['right_join_relation'];
        }
        if (!empty($arrayQuery['left_join_relation_where'])) {
            $this->dest['left_join_relation_where'] = $arrayQuery['left_join_relation_where'];
        }
        if (!empty($arrayQuery['cross_join_relation'])) {
            $this->dest['cross_join_relation'] = $arrayQuery['cross_join_relation'];
        }

        // need to initiate validation below, based on collected validation rules and validators
    }

    /**
     * Validate data layer based on built rules and priveded validator class name
     * @param null $validatorClass
     * @return $this
     */
    public function validate($validatorClass = null) {
        $class = $this->class;

        if (empty($this->rules)) return $this;

        if (!empty($class::$validator)) {
            $validatorClass = $class::$validator;
        }

        if (!empty($validatorClass)) {

            if ($this->path === 'include') {
                $data[$this->path][$this->name] = $this->source;
            } else {
                $data = $this->source;
            }

            $rules = collect($this->rules)->collapse()->toArray();

            $this->validator = $validatorClass::make($data)->addRules($rules);
            $this->validator->passes();

            //if ($this->path === 'include')
            //    dd($this->validator->messages()->toArray());

            $this->messages = $this->validator->messages()->toArray();
        }
    }

    /**
     * Parse requested 'where conditions'
     * @param $where
     */
    private function parseWheres($where) {
        $buildWheres = new BuildWheres($this->model, $where);
        $buildWheres->setStringPath($this->stringPath);
        $buildWheres->parse();

        // add validate rules to validate queue
        if (!empty($buildWheres->mapRules)) {
            $this->rules[] = $buildWheres->mapRules;
        }

        if (!empty($buildWheres->dest)) {
            $this->dest['where'] = $buildWheres->dest;
        }
    }

    /**
     * Parse requested fields
     * @param $fields
     */
    private function parseFields($fields) {
        $buildFields = new BuildFields($this->model, $fields);
        $buildFields->setStringPath($this->stringPath);
        $buildFields->parse();

        // add validate rules to validate queue
        if (!empty($buildFields->mapRules)) {
            $this->rules[] = $buildFields->mapRules;
        }

        if (!empty($buildFields->dest)) {
            $this->dest['fields'] = $buildFields->dest;
        }
    }

    /**
     * Parse requested order
     * @param $order
     */
    private function parseOrder($order) {
        $buildOrder = new BuildOrder($this->model, $order);
        $buildOrder->setStringPath($this->stringPath);
        $buildOrder->parse();

        // add validate rules to validate queue
        if (!empty($buildOrder->mapRules)) {
            $this->rules[] = $buildOrder->mapRules;
        }

        if (!empty($buildOrder->dest)) {
            $this->dest['order_by'] = $buildOrder->dest;
        }
    }

    /**
     * Parse requested group
     * @param $group
     */
    private function parseGroup($group) {
        $buildGroup = new BuildGroup($this->model, $group);
        $buildGroup->setStringPath($this->stringPath);
        $buildGroup->parse();

        // add validate rules to validate queue
        if (!empty($buildGroup->mapRules)) {
            $this->rules[] = $buildGroup->mapRules;
        }

        if (!empty($buildGroup->dest)) {
            $this->dest['group_by'] = $buildGroup->dest;
        }
    }

    /**
     * Parse requested functions
     * @param $fn
     */
    private function parseFunctions($fn) {
        $buildFunctions = new BuildFunctions($this->model, $fn);
        $buildFunctions->setStringPath($this->stringPath);
        $buildFunctions->parse();

        // add validate rules to validate queue
        if (!empty($buildFunctions->mapRules)) {
            $this->rules[] = $buildFunctions->mapRules;
        }

        if (!empty($buildFunctions->dest)) {
            $this->dest['fn'] = $buildFunctions->dest;
        }
    }

    /**
     * Name of relation / or main model
     * @param $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Root array path (used for building destination query array (without invalid values))
     * @param $path
     */
    public function setPath($path) {
        $this->path = $path;
    }

}
