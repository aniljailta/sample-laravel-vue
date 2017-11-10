<?php

namespace App\Http\Controllers\Api;

use Auth;
use Validator;
use Event;
use Exception;
use DB;
use Store;
use Log;

use App\Models\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Customers\IndexCustomerRequest;
use App\Http\Requests\Customers\AddCustomerRequest;
use App\Http\Requests\Customers\UpdateCustomerRequest;
use App\Http\Requests\Customers\DeleteCustomerRequest;

use Illuminate\Http\Request;

use App\Services\ArrayBuilder\ArrayBuilderAssistant;

class CustomersController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param IndexCustomerRequest $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return \Illuminate\Http\Response
     */
    public function index(IndexCustomerRequest $request, ArrayBuilderAssistant $abAssistant)
    {
        $model = new Customer;
        $abAssistant->setModel($model);
        $abAssistant->setArrayQuery($request->all());
        $abAssistant->validate();
        if (!$abAssistant->isValid()) {
            return response()->json($abAssistant->getMessages(), 400);
        }

        $result = $abAssistant
            ->apply()
            ->paginate(
                request('page'),
                request('per_page') ? request('per_page') : $this->getPerPageSetting()
            );
        return response()->json($result);
    }

    /**
     * Get resource
     * @param $id
     * @param Request $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return array
     */
    public function show($id, Request $request, ArrayBuilderAssistant $abAssistant)
    {
        $model = new Customer;
        $abAssistant->setModel($model);
        $abAssistant->setArrayQuery($request->all());
        $abAssistant->validate();
        if (!$abAssistant->isValid()) {
            return response()->json($abAssistant->getMessages(), 400);
        }

        $query = $abAssistant->apply()->getQuery();
        try {
            $item = $query->findOrFail($id);
            return response()->json($item);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['Customer is not found.'], 422);
        }
    }

    /**
     * Store the specified resource in storage.
     *
     * @param AddCustomerRequest| $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddCustomerRequest $request)
    {
        try {
            DB::transaction(function () use ($request, &$customer) {
                $customerData = $request->all();
                $customer = Customer::create($customerData);
            });

            return response()->json([
                'payload' => $customer,
                'msg' => 'Customer successfully added.'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['msg' => 'Something went wrong.'], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCustomerRequest| $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerRequest $request, $id)
    {
        $customer = Store::get('customer');

        try {
            DB::transaction(function () use ($request, &$customer) {
                $customerData = $request->all();
                $customer->update($customerData);
            });

            return response()->json([
                'payload' => $customer,
                'msg' => 'Customer successfully updated.'
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['msg' => 'Something went wrong.'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteCustomerRequest $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteCustomerRequest $request)
    {
        try
        {
            // get data which has got through validator
            $customer = Store::get('customer');
            $customer->delete();
            return response()->json(['Customer successfully deleted.']);
        } catch (Exception $e)
        {
            return response()->json(['Something went wrong.'], 422);
        }
    }
}
