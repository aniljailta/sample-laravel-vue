<?php

namespace App\Http\Controllers;

use App\Models\FileSign;
use Exception;
use Store;
use Log;

use App\Events\FileWasSigned;
use App\Events\FileWasAllSigned;

use Illuminate\Http\Request;

class EsignController extends Controller
{
    private $route_name = '';

    private $params = [];

    public function __construct(){}

    public function thanks()
    {
        return view('esign.thanks');
    }

    public function errors()
    {
        return view('esign.errors');
    }

    public function callback(Request $request)
    {
        // Disable Debugbar
        \Debugbar::disable();

        $callback = json_decode($request->input('json'), true);
        if (!$callback) $callback = $request->all();
        if (!$callback) return response('Wrong request.', 422);
        
        // Check required fields
        if (!array_has($callback, 'event.event_type')) {
            Log::error($callback);
            Log::error($request->all());
            return response('Invalid request', 422);
        }

        // for now we need only 2 event types
        $event = array_get($callback, 'event.event_type');
        if (!in_array($event, ['signature_request_signed', 'signature_request_all_signed', 'signature_request_canceled'])) {
            return 'Hello API Event Received';
        }

        $signatureRequestId = array_get($callback, 'signature_request.signature_request_id');
        $relatedSignatureId = array_get($callback, 'event.event_metadata.related_signature_id');
        if (!$signatureRequestId) {
            return response('Signature request ID should be provided.', 422);
        }

        try {
            // cancell signature request
            if ($event === 'signature_request_canceled') {
                FileSign::where('esign_signature_request_id', $signatureRequestId)
                    ->where('is_esigned', false)
                    ->withTrashed()
                    ->delete();
            }

            // only one party was signed document
            if ($event === 'signature_request_signed') {
                $fileSign = FileSign::with(['file.order'])
                    ->where('esign_signature_request_id', $signatureRequestId)
                    ->where('esign_signature_id', $relatedSignatureId)
                    ->firstOrFail();

                if ($fileSign->file && $fileSign->file->order) {
                    event(new FileWasSigned($fileSign->file->order, $fileSign));
                }
            }

            // all parties was signed document
            if ($event === 'signature_request_all_signed') {
                $fileSign = FileSign::with(['file.order'])
                    ->where('esign_signature_request_id', $signatureRequestId)
                    ->firstOrFail();

                if ($fileSign->file && $fileSign->file->order) {
                    event(new FileWasAllSigned($fileSign->file->order, $fileSign));
                }
            }

        } catch (Exception $e) {
            Log::error($e);
            return response('Something went wrong.', 422);
        }

        return 'Hello API Event Received';
    }
}
