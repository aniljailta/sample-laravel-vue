<?php

namespace App\Http\Controllers\Api;

use Log;
use Auth;
use Exception;
use Response;
use Uuid;
use Storage;
use Store;
use PDF;
use App\Models\File;
use App\Http\Requests;

use App\Services\Files\FileService;
use App\Services\Files\PdfWrapperService;
use App\Services\ArrayBuilder\ArrayBuilderAssistant;

use Illuminate\Http\Request;
use App\Http\Requests\GetFileRequest;
use App\Http\Requests\UploadFileRequest;
use App\Http\Requests\DeleteFileRequest;
use App\Http\Controllers\Controller;

class FilesController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param ArrayBuilderAssistant $abAssistant
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ArrayBuilderAssistant $abAssistant)
    {
        $model = new File;
        $abAssistant->setModel($model);
        $abAssistant->setArrayQuery($request->all());
        $abAssistant->validate();
        if (!$abAssistant->isValid()) {
            return response()->json($abAssistant->getMessages(), 400);
        }

        $query = $abAssistant->apply()->getQuery();
        $result = $abAssistant
            ->setQuery($query)
            ->paginate(
                request('page'),
                request('per_page') ? request('per_page') : $this->getPerPageSetting()
            );

        return response()->json($result);
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
        $storableItem = Store::get('storable_item');
        $storableId = $request->input('storable_id');
        $storableKey = $storableItem->id;
        $storableType = $storableItem->getMorphClass();

        if ($storableType === 'building') {
            $storableKey = $storableItem->serial_number;
        }

        if ($storableType === 'order') {
            $storableId = $storableItem->id;
        }

        try {
            // Add files
            $file = $request->file('upload_files');
            $fileService->store($file, [
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
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteFileRequest|Request $request
     * @param $fileService
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

    /**
     * Display the specified resource.
     *
     * @param  GetFileRequest $request
     * @param $id
     * @param PdfWrapperService $pdfWrapperService
     * @return \Illuminate\Http\Response
     */
    public function show(GetFileRequest $request, $id, PdfWrapperService $pdfWrapperService)
    {
        try {
            $file = File::findOrFail($id);

            if ($file->storable_type === 'building')
            {
                $file->load('building');
                if ($file->building && $file->building->serial_number) {
                    $data = [ 'serial_number' => $file->building->serial_number ];
                    $fileContent = $pdfWrapperService->wrap($file, $data);
                    return $fileContent->stream($file->name);
                }
            }

            if ($file->storable_type === 'order')
            {
                $file->load(['order', 'order.building']);
                if ($file->order && $file->order->building && $file->order->building->serial_number) {
                    $data = [ 'serial_number' => $file->order->building->serial_number ];
                    $fileContent = $pdfWrapperService->wrap($file, $data);
                    return $fileContent->stream($file->name);
                }
            }

            $path = storage_path('app/public').$file->path;
            return response()->file($path, [
                'Content-Type' => $file->mime,
                'Content-Disposition' => 'inline; filename="'.$file->name.'"',
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['Something went wrong.'], 422);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function categories(Request $request)
    {
        $categories = File::$categories;

        return response()->json($categories);
    }
}
