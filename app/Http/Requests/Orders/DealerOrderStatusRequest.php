<?php
namespace App\Http\Requests\Orders;

use App\Http\Requests\Request;

class DealerOrderStatusRequest extends Request
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'id' => ['required', 'uuid']
        ];
    }

    /**
     * @return array
     */
    protected function validationData(): array
    {
        $this->merge(
            [
                'id' => $this->segment(3),
            ]
        );

        return parent::validationData();
    }
}