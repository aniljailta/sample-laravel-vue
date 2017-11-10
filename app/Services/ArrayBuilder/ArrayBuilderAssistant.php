<?php

namespace App\Services\ArrayBuilder;

use Arr;
use DB;

use App\Validators\Validator as DefaultValidator;
use App\Services\ArrayBuilder\ArrayBuilder as ArrayBuilder;
use App\Services\ArrayBuilder\ArrayBuilderValidator as ArrayBuilderValidator;

use Llama\Database\Eloquent\EloquentBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model as Model;

class ArrayBuilderAssistant
{
    /*
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Array for ArrayQueryBuilder
     */
    protected $arrayQuery;

    /**
     * Contains query from ArrayQueryBuilder
     */
    protected $query;

    /**
     * Contains result data from ArrayQueryBuilder
     */
    protected $result;

    /**
     * Contains aggregates
     */
    protected $queryAgregate;

    /**
     * Contains error messages from validator
     */
    protected $messages = [];

    /**
     * ArrayBuilderAssistant constructor.
     */
    public function __construct()
    {
        $this->arrayBuilder = new ArrayBuilder();
    }

    /**
     * Run query with aggregates and return only aggregates
     * @return mixed
     */
    public function aggregate() {
        // TODO: use raw aggregates, instead of formatted to table related, by assistant
        $aggregate = ['count.total_rows' => '*'];
        if (!empty($this->arrayQuery['aggregate'])) {
            $aggregate = array_merge($aggregate, $this->arrayQuery['aggregate']);
        }

        // Return result with pagination and aggregates
        $this->queryAgregate = $this->queryAgregate($this->query, $aggregate);
        $aggregates = $this->queryAgregate->get()->first();
        return $aggregates;
    }

    /**
     * Run query with aggregates and return paginated result
     * @param int $perPage
     * @param int $page
     * @return array
     */
    public function paginate(int $page = null, int $perPage = null) {
        $query = $this->query;
        $perPage = $perPage ?: $query->getModel()->getPerPage();
        $page = $page ?: 1;

        $aggregates = $this->aggregate();
        // apply new visibles attributes for aggregates for each model
        $this->query = $query->take($perPage)->skip(($page-1) * $perPage);

        $items = $this->get();
        $items = new LengthAwarePaginator($items, $aggregates->total_rows, $perPage, $page);
        $items = $items->toArray();

        $items['aggregates'] = $aggregates;
        return $items;
    }

    /**
     * Run query and get result (without aggregates)
     */
    public function get() {
        $query = $this->query;

        // get visible list from patched model
        $visible = $query->getModel()->getVisible();

        $items = $query->get();
        $items->transform(function($item, $key) use($visible) {
            return $item->setVisible($visible);
        });

        return $items;
    }

    /**
     * Aadd new query + sub query for counting/calculate/aggregate data after main query (after group_by/etc)
     * @param EloquentBuilder $query
     * @param array $aggregate
     * @return array
     */
    public function queryAgregate($query, array $aggregate = []) {
        // create inital aggregate query with main query as sub-query
        $queryBuilder = DB::table(DB::raw("({$query->toSql()}) as aggregate"))->mergeBindings($query->applyScopes()->getQuery());

        // parse aggregate keys from dot anotaion
        // format: func1.func2.alias => field
        // will be as func1(func2(field)) as alias
        // the similar method from --> \App\Services\ArrayBuilder\ArrayBuilder
        foreach ($aggregate as $fArgs => $field) {
            $functionArray = array_reverse(explode('.', $fArgs));
            $alias = array_shift($functionArray);

            $queryString = $field;
            array_walk($functionArray, function ($el) use (&$queryString) {
                $queryString = "{$el}({$queryString})";
            });

            $select = "{$queryString} as {$alias}";
            $queryBuilder->selectRaw($select);
        }

        return $queryBuilder;
    }

    /**
     * @return ArrayBuilderAssistant
     */
    public function apply() {
        $this->query = $this->model->newQuery();
        $this->query = $this->arrayBuilder->apply($this->query, $this->arrayQuery);

        return $this;
    }

    /**
     * @param null $validator
     * @return array
     */
    public function validate($validator = null) {
        $validatorClass = DefaultValidator::class;
        if ($validator)
            $validatorClass = get_class($validator);

        $arrayBuilderValidator = new ArrayBuilderValidator(new $this->model, $this->arrayQuery);
        $arrayBuilderValidator->defaultValidator($validatorClass);
        $arrayBuilderValidator->parseQuery();

        $this->messages = [];
        if (!empty($arrayBuilderValidator->messages)) {
            $this->messages = $arrayBuilderValidator->messages;
        }

        return $this->messages;
    }

    /**
     * @return bool
     */
    public function isValid() {
        return (count($this->messages) === 0);
    }

    /**
     * @param Model $model
     */
    public function setModel(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $arrayQuery
     */
    public function setArrayQuery(array $arrayQuery)
    {
        $this->arrayQuery = $arrayQuery;
    }

    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param mixed $query
     * @return ArrayBuilderAssistant
     */
    public function setQuery($query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return mixed
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
