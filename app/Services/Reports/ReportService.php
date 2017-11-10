<?php

namespace App\Services\Reports;

class ReportService
{

    public $dimensionAttributes = [];
    public $conditionAttributes = [];
    public $totalAttributes = [];

    public static function getDimensionsAttributes() {
        return self::$dimensionAttributes;
    }

    protected function getDimensionAttributes($dimension_id = null, $extra_dimensions = null, $sorting = null)
    {
        $attrs = $this->dimensionAttributes;

        $return = array();
        if ( $dimension_id !== null )
        {
            if ( is_array($dimension_id) )
            {
                foreach ( $dimension_id as $dimension )
                    if(isset ( $attrs[$dimension]))
                        $return[$dimension] = $attrs[$dimension];
            } else
            {
                if ( isset($attrs[$dimension_id]))
                    $return = $attrs[$dimension_id];
            }
        }

        if ( $extra_dimensions !== null && is_array($extra_dimensions))
        {
            foreach($extra_dimensions as $extra_dimension)
                if(isset ( $attrs[$extra_dimension]))
                    $return[$extra_dimension] = $attrs[$extra_dimension];
        }

        if ( $sorting !== null && isset($attrs[$sorting['field']]))
        {
            $return[$sorting['field']]['direction'] = $sorting['direction'];
        }

        uasort($return, function ($a, $b) {
            if($a['index'] == $b['index']) {
                return 0;
            }
            return ($a['index'] < $b['index']) ? -1 : 1;
        });

        return $return;
    }

    protected function getConditionAttributes($condition_id = null)
    {
        $attrs = $this->conditionAttributes;

        $return = array();
        if ( $condition_id !== null )
        {
            if ( is_array($condition_id) )
            {
                foreach ( $condition_id as $condition )
                    if(isset ( $attrs[$condition]))
                        $return[$condition] = $attrs[$condition];

            } else
            {
                if ( isset($attrs[$condition_id]))
                    $return = $attrs[$condition_id];
            }
        }

        uasort($return, function ($a, $b) {
            if($a['index'] == $b['index']) {
                return 0;
            }
            return ($a['index'] < $b['index']) ? -1 : 1;
        });
        return $return;
    }

    protected function getTotalAttributes($total_id = null)
    {
        $attrs = $this->totalAttributes;

        $return = array();
        if ( $total_id !== null )
        {
            if ( is_array($total_id) )
            {
                foreach ( $total_id as $total )
                    if(isset ( $attrs[$total]))
                        $return[$total] = $attrs[$total];

            } else
            {
                if ( isset($attrs[$total_id]))
                    $return = $attrs[$total_id];
            }
        }

        uasort($return, function ($a, $b) {
            if($a['index'] == $b['index']) {
                return 0;
            }
            return ($a['index'] < $b['index']) ? -1 : 1;
        });
        return $return;
    }

    protected function getSorting(array $inputSorting) {

        if (isset($inputSorting['id']) &&
            isset($inputSorting['direction']) &&
            in_array($inputSorting['id'], array_merge($this->rulesScheme['default']['dimensions'], $this->rulesScheme['default']['dimensions_fixed']) )
        ) {
            $sorting['field'] = $inputSorting['id'];
            $sorting['direction'] = $inputSorting['direction'];
        } else {

            //default sorting
            if ( isset($this->rulesScheme['default']['dimensions_fixed'][0]) ) {
                $sorting['field'] = $this->rulesScheme['default']['dimensions_fixed'][0];
                $sorting['direction'] = 1;
            }
        }

        return $sorting;
    }

    protected function getConditions(array $inputConditions) {

    }

    protected function getDimensions(array $inputDimensions) {

    }


}
