<?php

namespace App\Validators;

use App\Validators\EnchantValidatorTrait;
use Fadion\ValidatorAssistant\ValidatorAssistant;

class Validator extends ValidatorAssistant {
    
    public function addRules($rules) {
        $rules = array_merge_recursive($this->rules, $rules);
        
        $this->rules = $rules;
        return $this;
    }
    
}