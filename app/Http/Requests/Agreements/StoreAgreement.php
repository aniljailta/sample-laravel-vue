<?php
namespace App\Http\Requests\Agreements;

use Illuminate\Support\Collection;
use Store;
use App\Http\Requests\Request;

class StoreAgreement extends Request
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        $authorized = true;
        if (!auth()->user()->hasRole('administrator')) {
            $authorized = false;
        }

        return $authorized;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'service_agreement_accepted' => 'required'
        ];
    }
}