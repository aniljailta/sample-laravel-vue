<?php

namespace App\Services\ArrayBuilder;

use App\Services\ArrayBuilder\RelationTrait as RelationTrait;
use Llama\Database\Eloquent\EloquentBuilder;
use Williamoliveira\ArrayQueryBuilder\ArrayBuilder as BaseArrayBuilder;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Grammars\MySqlGrammar;
use Illuminate\Database\Query\Grammars\PostgresGrammar;

class ArrayBuilder extends BaseArrayBuilder
{
    use RelationTrait;

    protected $aliases = [
        'eq' => '=',
        'neq' => '<>',
        'gt' => '>',
        'gte' => '>=',
        'lt' => '<',
        'lte' => '<=',
        'nlike' => 'not like',
        'nin' => 'not in',
        'isnull' => 'is null',
        'in' => 'is null',
        'notnull' => 'not null',
        'nn' => 'not null',
        'inq' => 'in'
    ];

    /**
     * @param Builder|QueryBuilder $query
     * @param array $arrayQuery
     * @return Builder|QueryBuilder
     * @throws \InvalidArgumentException
     */
    public function apply($query, array $arrayQuery)
    {
        if (isset($arrayQuery['include'])) {
            if ($query instanceof QueryBuilder) {
                throw new \InvalidArgumentException(
                    QueryBuilder::class . " does not support relations, you need " . Builder::class . " for that."
                );
            }

            $this->buildIncludes($query, $arrayQuery['include']);
        }

        if ($query instanceof EloquentBuilder) {

            if (isset($arrayQuery['join_relation'])) {
                $this->buildJoinRelation($query, $arrayQuery['join_relation']);
            }

            if (isset($arrayQuery['join_relation_where'])) {
                $this->buildJoinRelationWhere($query, $arrayQuery['join_relation_where']);
            }

            if (isset($arrayQuery['left_join_relation'])) {
                $this->buildLeftJoinRelation($query, $arrayQuery['left_join_relation']);
            }

            if (isset($arrayQuery['left_join_relation_where'])) {
                $this->buildLeftJoinRelationWhere($query, $arrayQuery['leftJoinRelationWhere']);
            }

            if (isset($arrayQuery['right_join_relation'])) {
                $this->buildRightJoinRelation($query, $arrayQuery['right_join_relation']);
            }

            if (isset($arrayQuery['left_join_relation_where'])) {
                $this->buildRightJoinRelationWhere($query, $arrayQuery['left_join_relation_where']);
            }

            if (isset($arrayQuery['cross_join_relation'])) {
                $this->buildCrossJoinRelation($query, $arrayQuery['cross_join_relation']);
            }
        }

        if (isset($arrayQuery['fields'])) {
            $this->buildFields($query, $arrayQuery['fields']);
        }

        if (isset($arrayQuery['where'])) {
            $this->buildWheres($query, $arrayQuery['where']);
        }

        if (isset($arrayQuery['group_by'])) {
            $this->buildGroupBy($query, $arrayQuery['group_by']);
        }

        if (isset($arrayQuery['order_by'])) {
            $this->buildOrderBy($query, $arrayQuery['order_by']);
        }

        // should be called after order_by (for making alias correct)
        if (isset($arrayQuery['fn'])) {
            $this->applyAliases($query, $arrayQuery['fn']);
            $this->buildFunctions($query, $arrayQuery['fn']);
        }

        return $query;
    }

    /**
     * @param Builder|QueryBuilder|EloquentBuilder|Relation $queryBuilder
     * @param array $strings
     * @return mixed
     * @internal param $args
     */
    public function applyAliases($queryBuilder, array &$strings = []) {
        if (!method_exists($queryBuilder, 'getRelationAliases')) {
            if (in_array(class_basename($queryBuilder), ['BelongsTo', 'BelongsToMany', 'HasOne'])) {
                $queryBuilder = $queryBuilder->getQuery();
            } else
            {
                return $strings;
            }
        }

        $aliases = $queryBuilder->getRelationAliases();

        foreach ($strings as $index => &$string) {
            $parent = $this->getParentRelations($string);

            if(array_key_exists($parent, $aliases)) {
                $string = str_replace($parent, $aliases[$parent], $string);
            } else {
                $model = $queryBuilder->getModel();

                // allow function aliases to be used as is ($this->buildFunction should be called after order)
                if (!in_array($string, $model->getVisible()))
                    continue;

                // do not query to db if it is custom attribute
                if (method_exists($model, camel_case('get_'.$string.'_attribute')))
                    continue;

                $string = $model->getTable().'.'.$string;
            }
        }

        return $strings;
    }

    private function getParentRelations($string) {
        $arr = explode('.', $string);
        array_pop($arr);
        return implode('.', $arr);
    }

    /**
     * Get operator and value from 'where' array
     * @param $where
     * @return array
     */
    private function getWheresConditions($where) {
        // Operator is omitted on query, assumes '='
        // Example: 'foo' => 'bar'
        $whereOperator = is_array($where) ? array_keys($where)[0] : '=';
        $whereValue = is_array($where) ? $where[$whereOperator] : $where;

        $whereOperator = $this->parseOperator($whereOperator);

        return [
            'operator' => $whereOperator,
            'value' => $whereValue
        ];
    }

    /**
     * @param Builder|QueryBuilder $queryBuilder
     * @param $args
     */
    protected function buildFunctions($queryBuilder, $args)
    {
        $model = $queryBuilder->getModel();
        // parse aggregate keys from dot anotaion
        // format: func1.func2.alias => field
        // will be as func1(func2(field)) as alias
        foreach ($args as $fArgs => $field) {
            $functionArray = array_reverse(explode('.', $fArgs));
            $alias = array_shift($functionArray);

            // skip function if alias name = hidden attribute
            if (in_array($alias, $model->getHidden())) continue;

            $queryString = $field;
            array_walk($functionArray, function ($el) use (&$queryString) {
                $queryString = "{$el}({$queryString})";
            });

            $select = "{$queryString} as {$alias}";
            $queryBuilder->selectRaw($select);
            // dd($queryBuilder);

            // add alias to visible array
            if (!in_array($alias, $model->getVisible())) $model->addVisible($alias);
        }
    }

    protected function buildFields($queryBuilder, $columns = ['*'])
    {
        $this->applyAliases($queryBuilder, $columns);
        $queryBuilder->select($columns);
    }

    /**
     * @param Builder|QueryBuilder $queryBuilder
     * @param array|string $order
     */
    protected function buildOrderBy($queryBuilder, $order)
    {
        if (!is_array($order)) $order = [$order];
        $this->applyAliases($queryBuilder, $order);

        foreach ($order as $orderItem) {
            $this->buildOrderBySingle($queryBuilder, $orderItem);
        }

        return;
    }

    /**
     * @param Builder|QueryBuilder $queryBuilder
     * @param array|string $columns
     */
    protected function buildGroupBy($queryBuilder, $columns)
    {
        $this->applyAliases($queryBuilder, $columns);
        $queryBuilder->groupBy($columns);
    }

    /**
     * @param Builder|QueryBuilder $queryBuilder
     * @param string $field
     * @param string|null $operator
     * @param array|string $value
     * @param string $boolean
     */
    protected function buildWhere($queryBuilder, $field, $operator, $value, $boolean = 'and')
    {
        $tablePrefix = $queryBuilder->getModel()->getTable().'.';

        switch ($operator) {
            case 'between':
                $queryBuilder->whereBetween($tablePrefix.$field, [$value[0], $value[1]], $boolean);
                return;
            case 'is null':
                $queryBuilder->whereNull($tablePrefix.$field, $boolean);
                return;
            case 'not null':
                $queryBuilder->whereNotNull($tablePrefix.$field, $boolean);
                return;
            case 'in':
                $queryBuilder->whereIn($tablePrefix.$field, (!is_array($value) ? [$value] : $value), $boolean);
                return;
            case 'not in':
                $queryBuilder->whereNotIn($tablePrefix.$field, (!is_array($value) ? [$value] : $value), $boolean);
                return;
            case 'search':
                $this->buildTextSearchWhere($queryBuilder, $tablePrefix.$field, $value, $boolean);
                return;
            default: {
                $queryBuilder->where($tablePrefix.$field, $operator, $value, $boolean);
            }
        }
    }

    /**
     * Build query to relation with whereHas method
     * $hasField can contain relation path +field (subRelation1.subRelation2.field) or relation path only (subRelation1.subRelation2)
     * @param Builder|QueryBuilder $queryBuilder
     * @param $hasField
     * @param $operator
     * @param $value
     * @param string $boolean
     */
    protected function buildWhereHas($queryBuilder, $hasField, $operator, $value, $boolean = 'and')
    {
        // parse string with relations and get {relation name} and {field}
        $relations = explode('.', $hasField, 3);
        // last element can be relation OR field
        $field = array_pop($relations);

        $relation = $this->getRelation($queryBuilder->getModel(), $relations);
        $finalRelation = $this->getRelation($relation, [$field]);

        // if final relation is exists - push back to relation path (means that there is no field detected)
        if ($finalRelation) array_push($relations, $field);

        $relationPath = implode('.', $relations);

        if (is_array($value)) {
            if (!$finalRelation) $value = [$field => $value];

            $queryBuilder->whereHas($relationPath, function ($query) use ($queryBuilder, $value, $boolean) {
                $this->buildWheres($query, $value, $boolean);
            });
        } else {
            $queryBuilder->whereHas($relationPath, function ($query) use ($queryBuilder, $field, $operator, $value, $boolean) {
                $whereConditions = $this->getWheresConditions($value);
                $this->buildWhere($query, $field, $whereConditions['operator'], $whereConditions['value'], $boolean);
            });
        }
    }

    /**
     * @param Builder|QueryBuilder $queryBuilder
     * @param array $wheres
     * @param string $boolean
     */
    protected function buildWheres($queryBuilder, array $wheres, $boolean = 'and')
    {
        foreach ($wheres as $whereField => $where) {
            if (!isset($whereField) || !isset($where)) {
                continue;
            }

            $whereField = strtolower($whereField);

            if ($queryBuilder instanceof EloquentBuilder) {
                if (method_exists($queryBuilder->getModel(), $whereField) || strpos($whereField, '.') > -1) {
                    $this->buildWhereHas($queryBuilder, $whereField, null, $where, $boolean);
                    continue;
                }
            }

            // Nested OR where
            // Example: 'or' => ['foo' => 'bar', 'x => 'y']
            if ($whereField === 'or') {
                $queryBuilder->where(function ($qb) use ($where, $queryBuilder, $boolean) {
                    $this->buildWheres($qb, $where, $boolean);
                }, null, null, 'or');

                continue;
            }

            // Nested AND where
            // Example: 'and' => ['foo' => 'bar', 'x => 'y']
            if ($whereField === 'and') {
                $queryBuilder->where(function ($qb) use ($where, $queryBuilder, $boolean) {
                    $this->buildWheres($qb, $where, $boolean);
                }, null, null, 'and');

                continue;
            }

            // Operator is omitted on query, assumes '='
            // Example: 'foo' => 'bar'
            $whereConditions = $this->getWheresConditions($where);

            $this->buildWhere($queryBuilder, $whereField, $whereConditions['operator'], $whereConditions['value'], $boolean);
        }
    }

    /**
     * @param Builder|QueryBuilder $queryBuilder
     * @param array|string $relations
     */
    protected function buildJoinRelation($queryBuilder, $relations)
    {
        $queryBuilder->joinRelation($relations);
    }

    /**
     * @param Builder|QueryBuilder $queryBuilder
     * @param array|string $relations
     */
    protected function buildJoinRelationWhere($queryBuilder, $relations)
    {
        $queryBuilder->joinRelationWhere($relations);
    }

    /**
     * @param Builder|QueryBuilder $queryBuilder
     * @param array|string $relations
     */
    protected function buildLeftJoinRelation($queryBuilder, $relations)
    {
        $queryBuilder->leftJoinRelation($relations);
    }

    /**
     * @param Builder|QueryBuilder $queryBuilder
     * @param array|string $relations
     */
    protected function buildLeftJoinRelationWhere($queryBuilder, $relations)
    {
        $queryBuilder->leftJoinRelationWhere($relations);
    }

    /**
     * @param Builder|QueryBuilder $queryBuilder
     * @param array|string $relations
     */
    protected function buildRightJoinRelation($queryBuilder, $relations)
    {
        $queryBuilder->rightJoinRelation($relations);
    }

    /**
     * @param Builder|QueryBuilder $queryBuilder
     * @param array|string $relations
     */
    protected function buildRightJoinRelationWhere($queryBuilder, $relations)
    {
        $queryBuilder->rightJoinRelationWhere($relations);
    }

    /**
     * @param Builder|QueryBuilder $queryBuilder
     * @param array|string $relations
     */
    protected function buildCrossJoinRelation($queryBuilder, $relations)
    {
        $queryBuilder->crossJoinRelation($relations);
    }
}
