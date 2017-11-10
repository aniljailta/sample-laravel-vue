<?php

namespace App\Http\Requests\Esigns;

use Route;
use App\Http\Requests\Request;

class EsignOrderRequest extends Request
{
    public $redirectOnFail = false;

    /**
     * @param null $keys
     * @return array
     */
    public function all($keys = null)
    {
        return array_replace_recursive(parent::all(), $this->route()->parameters());
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'order_uuid' => ['required', 'uuid']
        ];
    }

    public function errorResponse($errors)
    {
        return new Response(view('esign.errors')->withErrors($errors, 422));
    }
}
