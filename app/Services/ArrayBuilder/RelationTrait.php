<?php
namespace App\Services\ArrayBuilder;

use Illuminate\Database\Eloquent\Relations\Relation;

trait RelationTrait {
    
    private function getRelation($model, $relationName) {
        $currentRelationName = array_shift($relationName);

        if (!method_exists($model, $currentRelationName)) {
            return null;
        }

        $related = $model->$currentRelationName();
        if (!$related instanceof Relation) {
            return null;
        }

        if (!empty($relationName)) {
            $relation = $related->getRelated();
            return $this->getRelation($relation, $relationName);
        }

        return $related->getRelated();
    }
    
}