<?php

namespace App\Http\Controllers\Api;

use DB;
use Log;
use Store;
use Auth;
use App\Models\Dealer;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\Files\FileService;
use App\Http\Requests\Dealers\UploadFileRequest;
use App\Http\Requests\Dealers\DeleteFileRequest;

class DealerFilesController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UploadFileRequest $request
     * @param FileService $fileService
     * @return \Illuminate\Http\Response
     */
    public function store(UploadFileRequest $request, FileService $fileService)
    {
        $storableId = $request->storable_id;
        $storableKey = $request->storable_id;
        $storableType = $request->storable_type;

        try {
            // Add files
            $files = $request->file('upload_files');
            $fileService->store($files, [
                'user_id' => Auth::check() ? Auth::user()->id : null,
                'key' => $storableKey,
                'type' => $storableType,
                'id' => $storableId,
                'category_id' => $request->input('category_id')
            ]);

            $message = $fileService->success() ? $fileService->messages : ['File successfully uploaded.'];
            $payload = $fileService->files;
            return response()->json([
                'message' => $message,
                'payload' => $payload
            ], 200);
        } catch (Exception $e) {
            Log::error($e);

            $message = $fileService->error() ? $fileService->errors : ['File has not been uploaded.'];
            return response()->json($message, 422);
        }

        return response()->json(['File has not been uploaded.'], 422);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteFileRequest $request
     * @param FileService $fileService
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteFileRequest $request, FileService $fileService)
    {
        try
        {
            // get data which has got through validator
            $file = Store::get('file');
            // $file->delete();
            $fileService->delete($file);
            if($fileService->error()) return response()->json($fileService->errors, 422);

            return response()->json(['File successfully deleted.'], 200);
        } catch (Exception $e)
        {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }
}
