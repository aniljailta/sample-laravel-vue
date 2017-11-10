<?php

namespace App\Services\ArrayBuilder;

use Arr;
use DB;

use App\Services\ArrayBuilder\ArrayBuilderValidator\LayerParser as LayerParser;
use App\Services\ArrayBuilder\RelationTrait;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Llama\Database\Eloquent\EloquentBuilder;

class ArrayBuilderValidator
{
    use RelationTrait;

    public $model;
    public $src = [];
    public $request = [];
    public $aggregates = [];

    public $validatorClass; // default
    public $messages = []; // from validator
    public $rules = [];

    public function __construct(Model $eloquent, array $arrayQuery)
    {
        $this->model = $eloquent;
        $this->src = $arrayQuery;
    }

    /**
     * @param $validatorClass
     */
    public function defaultValidator($validatorClass) {
        $this->validatorClass = $validatorClass;
    }

    public function addMessages($messages) {
        $this->messages = array_merge($this->messages, $messages);
    }

    public function parseQuery() {
        $parser = new LayerParser($this->model, $this->src);
        $parser->parse();
        $parser->validate($this->validatorClass);

        if ($parser->messages) {
            $this->addMessages($parser->messages);
        }

        $this->request = $parser->dest;
        $this->rules[$parser->name] = $parser->rules;

        // relations
        if (!empty($this->src['include'])) {
            $this->parseIncludes($this->model, $this->src['include']);
        }

        // aggregates
        if (!empty($this->src['aggregate'])) {
            $this->parseAggregate($this->model, $this->src['aggregate']);
        }

        return $this;
    }

    /**
     * Parse includes
     * @param $model
     * @param $includes
     */
    public function parseIncludes($model, $includes) {
        
        $class = get_class($model);
        
        foreach ($includes as $includeName => $include) {
            // Support for array includes, example: ['user', 'post']
            // If it's a single dimension array the key will be numeric
            $includeName = is_numeric($includeName) ? $include : $includeName;

            // if deep relations
            if (str_contains($includeName, '.')) {
                $includeNames = explode('.', $includeName);
            } else {
                $includeNames = [$includeName];
            }

            $relation = $this->getRelation($model, $includeNames);

            // TODO: can return error message here
            if (!$relation) 
                continue;

            if ($include === 'true' || $includes === true)
                $this->request['include'][$includeName] = true;

            if ($include === $includeName)
                $this->request['include'][] = $includeName;

            if (empty($include['where']) &&
                empty($include['fields']) &&
                empty($include['group_by']) &&
                empty($include['order_by']) &&
                empty($include['fn'])) {
                continue;
            }

            $parser = new LayerParser($relation, $include);
            $parser->setName($includeName);
            $parser->setPath('include');
            $parser->parse();
            $parser->validate($this->validatorClass);

            if ($parser->messages) {
                $this->addMessages($parser->messages);
            }

            $this->request['include'][$includeName] = $parser->dest;
            $this->rules[$includeName] = $parser->rules;
        }

    }

    /**
     * @param $model
     * @param $agrs
     * @return $this
     */
    public function parseAggregate($model, $agrs) {
        $parser = new LayerParser($model, ['fn' => $agrs]);
        $parser->setName('aggregate');
        $parser->parse();
        $parser->validate($this->validatorClass);
        
        if ($parser->messages) {
            $this->addMessages($parser->messages);
        }

        // TODO: use raw aggregates, instead of formatted to table related, by assistant
        $this->aggregates = array_merge($this->aggregates, $this->src['aggregate']);
        $this->rules[$parser->name] = $parser->rules;
        
        return $this;
    }
}
