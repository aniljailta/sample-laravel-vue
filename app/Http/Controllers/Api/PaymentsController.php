<?php

namespace App\Http\Controllers\Api;

use DB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\IndexPaymentRequest;
use App\Http\Requests\AddPaymentRequest;
use App\Http\Requests\DeletePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;

use App\Models\Payment;

use App\Services\ArrayBuilder\ArrayBuilderAssistant;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @param IndexPaymentRequest $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return \Illuminate\Http\Response
     */
    public function index(IndexPaymentRequest $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new Payment());
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
     * Store a newly created resource in storage.
     *
     * @param  AddPaymentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddPaymentRequest $request)
    {
        try
        {
            $paymentParams = $request->all();
            $payment = Payment::create($paymentParams);

            $modelName = $paymentParams['paymentable_type'] == 'user' ? 'App\Models\User' : 'App\Models\RtoCompany';
            $payee = $modelName::find($paymentParams['paymentable_id']);
            $payee->payments()->save($payment);

            return response()->json(['Payment successfully created.']);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['msg' => 'Something went wrong.'], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @param  ArrayBuilderAssistant  $abAssistant
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new Payment());
        $abAssistant->setArrayQuery($request->all());
        $abAssistant->validate();
        if (!$abAssistant->isValid()) return response()->json($abAssistant->getMessages(), 400);

        $query = $abAssistant->apply()->getQuery();

        try {
            $item = $query->findOrFail($id);
            return response()->json($item);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['Payment is not found.'], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdatePaymentRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, UpdatePaymentRequest $request)
    {
        try
        {
            DB::transaction(function() use($request, $id) {
                $payment = $request->data['requestedPayment'];
                $paymentParams = $request->all();

                $payment->update($paymentParams);
                $modelName = $paymentParams['paymentable_type'] == 'user' ? 'App\Models\User' : 'App\Models\RtoCompany';
                $payee = $modelName::find($paymentParams['paymentable_id']);
                $payee->payments()->save($payment);
            });

            return response()->json(['msg' => 'Payment successfully updated.']);
        } catch (Exception $e)
        {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DeletePaymentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeletePaymentRequest $request)
    {
        try
        {
            // get data which has got through validator
            $payment = $request->data['requestedPayment'];
            $payment->delete();

            return response()->json(['Payment successfully deleted.']);
        } catch (Exception $e)
        {
            return response()->json(['Something went wrong.'], 422);
        }
    }
}
