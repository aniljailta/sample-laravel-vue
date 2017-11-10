<?php

namespace App\Http\Controllers\Api;

use DB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\IndexInvoiceRequest;
use App\Http\Requests\AddInvoiceRequest;
use App\Http\Requests\DeleteInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;

use App\Models\Invoice;

use App\Services\ArrayBuilder\ArrayBuilderAssistant;


class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param IndexInvoiceRequest $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return \Illuminate\Http\Response
     */
    public function index(IndexInvoiceRequest $request, ArrayBuilderAssistant $abAssistant)
    {
        $abAssistant->setModel(new Invoice());
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
     * @param  AddInvoiceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddInvoiceRequest $request)
    {
        try
        {
            $invoiceParams = $request->all();
            $invoice = Invoice::create($invoiceParams);

            $modelName = $invoiceParams['invoiceable_type'] == 'user' ? 'App\Models\User' : 'App\Models\RtoCompany';
            $customer = $modelName::find($invoiceParams['invoiceable_id']);
            $customer->invoices()->save($invoice);

            return response()->json(['Invoice successfully created.']);
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
        $abAssistant->setModel(new Invoice());
        $abAssistant->setArrayQuery($request->all());
        $abAssistant->validate();
        if (!$abAssistant->isValid()) return response()->json($abAssistant->getMessages(), 400);

        $query = $abAssistant->apply()->getQuery();

        try {
            $item = $query->findOrFail($id);
            return response()->json($item);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['Invoice is not found.'], 422);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateInvoiceRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, UpdateInvoiceRequest $request)
    {
        try
        {
            DB::transaction(function() use($request, $id) {
                $invoice = $request->data['requestedInvoice'];
                $invoiceParams = $request->all();

                $invoice->update($invoiceParams);
                $modelName = $invoiceParams['invoiceable_type'] == 'user' ? 'App\Models\User' : 'App\Models\RtoCompany';
                $customer = $modelName::find($invoiceParams['invoiceable_id']);
                $customer->invoices()->save($invoice);
            });

            return response()->json(['msg' => 'Invoice successfully updated.']);
        } catch (Exception $e)
        {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DeleteInvoiceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteInvoiceRequest $request)
    {
        try
        {
            // get data which has got through validator
            $invoice = $request->data['requestedInvoice'];
            $invoice->delete();

            return response()->json(['Invoice successfully deleted.']);
        } catch (Exception $e)
        {
            return response()->json(['Something went wrong.'], 422);
        }
    }
}