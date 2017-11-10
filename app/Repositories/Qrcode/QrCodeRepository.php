<?php

namespace App\Repositories\Qrcode;

use Illuminate\Support\Facades\DB;
use App\Models\Qrcode;
use App\Models\Building;
use App\Exceptions\GeneralException;

use Entrust;

class QrCodeRepository
{
    /**
     * @var Qrcode
     */
    protected $model;

    /**
     * @param Qrcode $model
     */
    public function __construct(Qrcode $model)
    {
        $this->model = $model;
    }

    /**
     * @param int $building_id
     * @param     $type
     * @return
     * @internal param building_id $int
     */
    public function get(int $building_id, $type)
    {
        return $this->model
            ->getQrCode($building_id, $type)
            ->join('buildings', 'qrcodes.building_id', 'buildings.id')
            ->select('qrcodes.*', 'buildings.serial_number')
            ->get();
    }

    /**
     * @param array $input input
     * @return Qrcode|QrCodeRepository|bool|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static[]
     * @throws GeneralException
     */
    public function create(array $input)
    {
        $data = $input['data'];
        if ($this->checkeligibility($data['building_id'])) {
            $qrcode = $this->createQrStub($data);
            if ($qrcode->save()) {
                return $qrcode;
            }
            throw new GeneralException('Something went wrong');
        }
        return false;
    }

    /**
     * @param array input
     * @return Qrcode|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    protected function createQrStub(array $input)
    {
        $qrcode = $this->model;
        if (isset($input['id'])) {
            $qrcode = $qrcode::find($input['id']);
        } else {
            $qrcode = new $qrcode;
            $qrcode->building_id = $input['building_id'];
            $qrcode->type = $input['type'];
            $qrcode->identifier = strtolower(str_random(30));
            if ($input['type'] === "build") {
                $mid = "qr-build";
            } else {
                $mid = "qr-location";
            }
            $url = $_SERVER["HTTP_HOST"] . '/' . $mid . '/' . $qrcode->identifier;
            $path = 'qrcodes/usc_' . $input['building_id'] . '_' . $qrcode->type . '.png';
            $qrCodeImage = \QrCode::format('png')->size(400)->margin(2)->generate($url);
            \Storage::disk('public')->put($path, $qrCodeImage);
            $qrcode->path = $path;
        }

        $qrcode->created_by = \Auth::user()->id;
        $qrcode->expire_on = $input['expire_on'];
        return $qrcode;
    }

    /**
     * @param integer $building_id
     * @return bool
     */
    protected function checkeligibility(int $building_id)
    {
        $building = Building::find($building_id);
        if ($building->serial_number) {
            if ($building->last_status->building_status->priority != 0) {
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * @param integer input
     * @return bool
     */
    protected function _checkifqrexists(int $building_id)
    {
        if ($this->model->where('building_id', $building_id)->count()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string input
     */
    public function getbyIdentifier(string $identifier)
    {
        return $this->model
            ->getByIdentifier($identifier)
            ->join('buildings', 'qrcodes.building_id', 'buildings.id')
            ->select('qrcodes.*', 'buildings.serial_number')
            ->first();
    }

    /**
     * @param string input
     */
    public function getdeepByIdentifier(string $identifier)
    {
        return $this->model
            ->with(['building', 'building.last_status', 'building.last_status.user', 'building.last_status.building_status'])
            ->getByIdentifier($identifier)
            ->first();
    }

    /**
     * @param string input
     * @return bool
     */
    public function checkexpiry(string $identifier)
    {
        $date = $this->model->getByIdentifier($identifier)->first()->expire_on;
        if (time() > strtotime($date)) {
            return true;
        }
        return false;
    }

    /**
     * @param string input
     */
    public function getType(string $identifier)
    {
        return $this->model->getByIdentifier($identifier)->first();
    }
}
