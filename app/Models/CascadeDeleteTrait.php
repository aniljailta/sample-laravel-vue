<?php

namespace App\Models;

trait CascadeDeleteTrait {

    /*
     * Contains relationship names for firing 'deleting' event
     */
    protected $childRelations = [];

    /**
     * Method overwrites standard model method.
     * Method adding code for FIRING event 'deleting' for children relations
     * @return bool
     * @throws Exception
     */
    public function delete() {
        if (is_null($this->getKeyName())) {
            throw new Exception('No primary key defined on model.');
        }

        if ($this->exists) {

            if ($this->fireModelEvent('deleting') === false) {
                return false;
            }

            if ( isset($this->childRelations) ) {

                \DB::transaction(function () {
                    foreach($this->childRelations as $childRelation) {

                        // skip relations if it is phantom
                        if ( !method_exists($this, $childRelation) ) {
                            continue;
                        }

                        $relation = $this->$childRelation();
                        $relatedModel = $relation->getRelated();

                        // call with trashed (for reliability)
                        if ( method_exists($relatedModel, 'withTrashed') ) {
                            $relation->withTrashed();
                        }

                        $relation = $relation->get();
                        $class = get_class($relation);
                        if( $class == 'Illuminate\Database\Eloquent\Collection' )
                        {
                            foreach( $relation as $item ) {
                                $item->fireModelEvent('deleting');
                            }
                        } else
                        {
                            $relation->fireModelEvent('deleting');
                        }
                    }
                });
            }

            // Here, we'll touch the owning models, verifying these timestamps get updated
            // for the models. This will allow any caching to get broken on the parents
            // by the timestamp. Then we will go ahead and delete the model instance.
            $this->touchOwners();

            $this->performDeleteOnModel();

            $this->exists = false;

            // Once the model has been deleted, we will fire off the deleted event so that
            // the developers may hook into post-delete operations. We will then return
            // a boolean true as the delete is presumably successful on the database.
            $this->fireModelEvent('deleted', false);

            return true;
        }
    }

    public function setChildRelations(array $relations) {
        $this->childRelations = $relations;
    }

}