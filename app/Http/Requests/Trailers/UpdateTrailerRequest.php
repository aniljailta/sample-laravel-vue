<?php

namespace App\Http\Requests\Trailers;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Request;
use App\Validators\Validator;
use App\Models\Trailer;
use Store;
class UpdateTrailerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $id = $this->route('trailer');

        try {
            $truck = Trailer::where('id', $id)->firstOrFail();
            Store::set('trailer', $truck);

            return true;
        } catch (Exception $e) {
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
        $rules=Trailer::$rules;
        $rules['name'][]='required';
        $rules['delivery_capacity'][]='required';
        return $rules;
    }
}
