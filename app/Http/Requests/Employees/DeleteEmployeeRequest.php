<?php

namespace App\Http\Requests\Employees;

use Store;
use Entrust;
use App\Models\User;

use App\Http\Requests\Request;
use App\Validators\LocationValidator as Validator;

class DeleteEmployeeRequest extends Request
{
    /**
     * Overwrite laravel's method, define custom validator
     */
    public function validate()
    {
        $this->validator = Validator::make();

        $this->rules();
        $this->runValidator();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // TODO: here should be checking for ownership too

        if (!Entrust::hasRole('administrator')) return false;

        $employeeId = $this->route('employee');
        try {
            $employee = User::findOrFail($employeeId);
            Store::set('employee', $employee);

            return true;
        } catch (ModelNotFoundException $e) {
            return false;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->rules;
    }
}
