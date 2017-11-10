<?php

namespace App\Http\Requests\Trailers;

use App\Models\Trailer;
use Illuminate\Foundation\Http\FormRequest;
use Store;
use Exception;
use Entrust;

class DestroyTrailerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (!Entrust::hasRole('administrator')) return false;

        $id = $this->route('trailer');

        try {
            $item = Trailer::findOrFail($id);
            Store::set('trailer', $item);

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
        return [
            //
        ];
    }
}
