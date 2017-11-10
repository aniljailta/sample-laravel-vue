<?php

namespace App\Providers\Html;

use Collective\Html\FormBuilder as CollectiveFormBuilder;

class FormBuilder extends CollectiveFormBuilder
{
    /**
     * Create a select box field.
     * Support for 'disabled' attribute [ADDED]
     *
     * @param  string $name
     * @param  array $list
     * @param  string $selected
     * @param  array $options
     * @param bool $force_selected
     * @return string
     */
    public function custom_select($name, $list = [], $selected = null, $options = [], $force_selected = false)
    {
        // When building a select box the "value" attribute is really the selected one
        // so we will use that when checking the model or session for a value which
        // should provide a convenient method of re-populating the forms on post.
        if ( !$force_selected ) {
            $selected = $this->getValueAttribute($name, $selected);
        }

        $options['id'] = $this->getIdAttribute($name, $options);

        if (!isset($options['name'])) {
            $options['name'] = $name;
        }

        // We will simply loop through the options and build an HTML value for each of
        // them until we have an array of HTML declarations. Then we will join them
        // all together into one single HTML element that can be put on the form.
        $html = [];

        if (isset($options['placeholder'])) {
            $html[] = $this->placeholderOption($options['placeholder'], $selected);
            unset($options['placeholder']);
        }

        foreach ($list as $list_el)
        {
            $is_selected = $this->getSelectedValue($list_el['value'], $selected);
            $option_attr = ( isset($list_el['options']) ) ? $list_el['options'] : array();
            $option_attr['selected'] = $is_selected;
            $option_attr['value'] = $list_el['value'];

            $html[] = '<option'.$this->html->attributes($option_attr).'>'.e($list_el['display']).'</option>';
        }


        // Once we have all of this HTML, we can join this into a single element after
        // formatting the attributes into an HTML "attributes" string, then we will
        // build out a final select statement, which will contain all the values.
        $options = $this->html->attributes($options);

        $list = implode('', $html);

        return "<select{$options}>{$list}</select>";
    }

    /**
     * Get the select option for the given value.
     *
     * @param  string $display
     * @param  string $value
     * @param  string $selected
     *
     * @param $disabled
     * @return string
     */
    public function getSelectOption($display, $value, $selected, array $disabled = [])
    {
        if (is_array($display)) {
            return $this->optionGroup($display, $value, $selected, $disabled);
        }

        return $this->option($display, $value, $selected, $disabled);
    }

    /**
     * Create an option group form element.
     *
     * @param  array $list
     * @param  string $label
     * @param  string $selected
     *
     * @param $disabled
     * @return string
     */
    protected function optionGroup($list, $label, $selected, array $disabled = [])
    {
        $html = [];

        foreach ($list as $value => $display) {
            $html[] = $this->option($display, $value, $selected, $disabled);
        }

        return '<optgroup label="'.e($label).'">'.implode('', $html).'</optgroup>';
    }

    /**
     * Create a select element option.
     *
     * @param  string $display
     * @param  string $value
     * @param  string $selected
     *
     * @param $disabled
     * @return string
     */
    protected function option($display, $value, $selected, array $disabled = [])
    {
        $selected = $this->getSelectedValue($value, $selected);
        $disabled = $this->getDisabledValue($value, $disabled);

        $options = ['value' => $value, 'selected' => $selected, 'disabled' => $disabled];

        return '<option'.$this->html->attributes($options).'>'.e($display).'</option>';
    }

    /**
     * Determine if the value is disabled.
     *
     * @param  string $value
     * @param  string $disabled
     *
     * @return string|null
     */
    protected function getDisabledValue($value, $disabled)
    {
        if (is_array($disabled)) {
            return in_array($value, $disabled) ? 'disabled' : null;
        }

        return ((string) $value == (string) $disabled) ? 'disabled' : null;
    }
}