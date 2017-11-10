<?php

namespace App\Http\Requests\Users;

use Store;
use Entrust;

use Illuminate\Validation\Rule;
use App\Models\User;
use App\Http\Requests\Request;

class UpdateUserRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // TODO: here should be checking for ownership too
        if (!Entrust::hasRole('administrator')) return false;
        
        $itemID = $this->route('user');

        try {
            $item = User::findOrFail($itemID);
            Store::set('user', $item);

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
        $rules = array_merge_recursive(User::$rules, [
            'email' => [
                Rule::unique('users', 'email')
                    ->whereNot('id', $this->id)
                    ->where(COMPANY_ID, Store::get('company')->id)
            ],
        ]);

        return $rules;
    }
}
