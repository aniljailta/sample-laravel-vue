<?php

namespace App\Services\QrCode;

use App\Models\BuildingStatus;
use App\Models\File;
use App\Models\Location;
use App\Models\Qrcode;
use App\Models\Building;
use App\Repositories\Qrcode\QrCodeRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class QrCodeService
{
    /**
     * Generate QR Codes for selected building (expire in 30 days)
     * @param Building $building
     * @param array|null $qrTypes
     * @return Collection
     */
    public function generateBuildingQrCodes(Building $building, ?array $qrTypes = ['location', 'build']): Collection
    {
        $qrCodes = collect();
        $qrCodeRepository = new QrCodeRepository(new Qrcode);

        foreach ($qrTypes as $qrType) {
            $qrCode = $qrCodeRepository->create([
                'data' => [
                    'building_id' => $building->id,
                    'type' => $qrType,
                    'expire_on' => Carbon::now()->addDays(Qrcode::EXPIRATION_DAYS),
                ]
            ]);
            $qrCodes->push($qrCode);
        }

        return $qrCodes;
    }

    /**
     * get images count.
     *
     * @param int $statusId
     * @return \Illuminate\Http\Response
     */

    public function getImagesCount(int $statusId)
    {
        $status = BuildingStatus::find($statusId);
        $nextPriority = $status->priority + 1;
        $newStatusid = BuildingStatus::where('priority', $nextPriority)->first();
        if ($newStatusid) {
            $newStatusid = $newStatusid->id;
        } else {
            $newStatusid = $statusId;
        }
        $category_id = $this->getStatusName($newStatusid);
        return File::where('category_id', $category_id)->count();
    }

    /**
     * get status name and convert spaces with _ .
     *
     * @param int $statusId
     * @return \Illuminate\Http\Response
     */
    public function getStatusName(int $statusId)
    {
        $statusName = BuildingStatus::find($statusId)->name;
        return preg_replace('/\s+/', '_', strtolower($statusName));
    }

    /**
     * get dealer locations
     *
     * @return \Illuminate\Http\Response
     */

    public function getDealerActiveLocation()
    {
        return Location::where('category', 'dealer')->where('is_active', 'yes')->get();
    }

    /**
     * return locations image count
     *
     * @param int $locationId
     * @return \Illuminate\Http\Response
     */
    public function getLocationImageCount(int $locationId)
    {
        return File::where('storable_id', $locationId)->where('storable_type', 'location')->count();
    }
}
