<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\File;
use App\Models\Location;

use App\Repositories\Qrcode\QrCodeRepository;
use App\Http\Requests\Qrcode\CreateQrCodeRequest;
use App\Http\Requests\Qrcode\UpdateStatusRequest;
use App\Http\Requests\Qrcode\UploadFile;
use App\Http\Requests\Qrcode\ScanIdentifier;
use App\Http\Requests\Qrcode\UploadLocation;

use App\Repositories\BuildingRepository;
use App\Repositories\SalesRepository;
use App\Services\Building\BuildingService;

use App\Services\QrCode\QrCodeService;
use Intervention\Image\Facades\Image;



class QrcodeController extends Controller
{
    /**
     * @var QrCodeRepository
     */
    protected $qrcode;

    /**
     * @var BuildingRepository
     */
    protected $building;

    /**
     * @var SalesRepository
     */
    protected $sales;

    /**
     * QrcodeController constructor.
     * @param QrCodeRepository $qrcode
     * @param BuildingRepository $building
     * @param SalesRepository $sales
     */
    public function __construct(QrCodeRepository $qrcode, BuildingRepository $building, SalesRepository $sales)
    {
        $this->qrcode = $qrcode;
        $this->building = $building;
        $this->sales = $sales;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->building_id) {
            $item = $this->qrcode->get(
                $request->building_id,
                $request->type
            );
            return response()->json($item);
        } else {
            return response()->json(['msg' => 'Something went wrong'], 422);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateQrCodeRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateQrCodeRequest $request)
    {
        try {
            if ($this->qrcode->create(['data' => $request->all()])) {
                return response()->json([
                    'msg' => 'QR Code generated successfully.',
                ]);
            }
            return response()->json(['msg' => 'Building in draft status'], 422);
        } catch (Exception $e) {
            \Log::error($e);
            return response()->json(['msg' => 'Something went wrong'], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param ScanIdentifier $request
     * @param QrCodeService $qrCodeService
     * @return \Illuminate\Http\JsonResponse
     */
    public function getbyIdentifier(ScanIdentifier $request, QrCodeService $qrCodeService)
    {
        $item = $this->qrcode->getdeepByIdentifier($request->identifier);
        $image_count = $qrCodeService->getImagesCount($item->building->last_status->status_id);
        $status = $this->building->getToPrioritize($item->building_id);
        $response = [
            'item' => $item,
            'status' => $status,
            'images_count' => $image_count,
        ];
        return response()->json($response);
    }


    /**
     * @param ScanIdentifier $request
     * @param QrCodeService $qrCodeService
     * @return \Illuminate\Http\JsonResponse
     */
    public function getbyIdentifierLocation(ScanIdentifier $request, QrCodeService $qrCodeService)
    {
        $qrcode = $this->qrcode->getType($request->identifier);
        $building = Building::find($qrcode->building_id)->serial_number;

        if ($this->sales->checkifSalesExists($qrcode->building_id)) {
            $status = "sale";
            $data = $this->sales->getSaleLocation($qrcode->building_id);
            $images_count = $data->location_id ? $qrCodeService->getLocationImageCount($data->location_id) : 0;
        } else {
            $status = "non";
            $data = $qrCodeService->getDealerActiveLocation();
            $images_count = 0;
        }

        $response = [
            'item' => [
                'data' => $data,
                'building' => [
                    'sn' => $building,
                    'id' => $qrcode->building_id,
                ],
                'images_count' => $images_count,
            ],
            'status' => $status,
            'type' => 'location',
        ];

        return response()->json($response);
    }



    /**
     * Store Images and update status for build QR.
     * @param UpdateStatusRequest $request
     * @param BuildingService $buildingService
     * @param QrCodeService $qrCodeService
     * @return \Illuminate\Http\JsonResponse
     */
    public function postStatus(UpdateStatusRequest $request, BuildingService $buildingService, QrCodeService $qrCodeService)
    {
        try {
            $files = $request->file('upload_files');
            if ($files) {
                $categoryId = $qrCodeService->getStatusName($request->status_id);
                $building = Building::findOrFail($request->building_id);

                $buildingService
                    ->setBuilding($building)
                    ->saveFiles($files, [
                        'optimize_qr_files' => true,
                        'category_id' => $categoryId
                    ]);
            }
            $this->building->saveStatus($request->all());
            return response()->json([
                'msg' => 'Status updated successfully.',
            ]);
        } catch (Exception $e) {
            \Log::error($e);
            return response()->json(['msg' => 'Something went wrong'], 422);
        }
    }

    /**
     * @param UploadFile $request
     * @param BuildingService $buildingService
     * @param QrCodeService $qrCodeService
     * @return \Illuminate\Http\JsonResponse
     */
    public function postFiles(UploadFile $request, BuildingService $buildingService, QrCodeService $qrCodeService)
    {
        try {
            $files = $request->file('upload_files');
            if ($files) {
                $categoryId = $qrCodeService->getStatusName($request->status_id);
                $building = Building::findOrFail($request->building_id);

                $buildingService
                    ->setBuilding($building)
                    ->saveFiles($files, [
                        'optimize_qr_files' => true,
                        'category_id' => $categoryId,
                    ]);

                return response()->json(['msg' => 'Image uploaded successfully.',]);
            }
        } catch (Exception $e) {
            \Log::error($e);
            return response()->json(['msg' => 'Something went wrong'], 422);
        }

    }

    /**
     * Store Images for location QR.
     *
     * @param Request $request
     * @param BuildingService $buildingService
     * @return \Illuminate\Http\Response
     */
    public function postFileLocation(Request $request, BuildingService $buildingService)
    {
        try {
            $location = $this->sales->getSaleLocation($request->storable_id);
            if ($location && $location->location_id) {
                $building = Building::findOrFail($request->storable_id);

                $files = $request->file('upload_files');
                if ($files) {
                    $item = $buildingService
                        ->setBuilding($building)
                        ->saveLocationFiles($files, [
                            'location_id' => $location->location_id,
                            'optimize_qr_files' => true,
                            'payload' => $request->all(),
                        ]);
                    return response()->json($item);
                }
            }
        } catch (Exception $e) {
            \Log::error($e);
            return response()->json(['msg' => 'Something went wrong'], 422);
        }

    }

    /**
     * Store status for location QR.
     *
     * @param UploadLocation $request
     * @param BuildingService $buildingService
     * @return \Illuminate\Http\Response
     */
    public function postFilesLocation(UploadLocation $request, BuildingService $buildingService)
    {
        try {
            if ($request->type === 'update') {
                $buildingService->updateBuildingLocation($request->all());
                return response()->json(['msg' => 'Location Updated successfully.',]);
            }
        } catch (Exception $e) {
            \Log::error($e);
            return response()->json(['msg' => 'Something went wrong'], 422);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param BuildingService $buildingService
     * @return \Illuminate\Http\Response
     */
    public function updateLatLong(Request $request, BuildingService $buildingService)
    {
        try {
            $buildingService->updateLatLong($request->all());
            return response()->json(['msg' => 'Latitude Longitude Updated successfully.',]);
        } catch (Exception $e) {
            return response()->json(['msg' => 'Something went wrong'], 422);

        }
    }

}
