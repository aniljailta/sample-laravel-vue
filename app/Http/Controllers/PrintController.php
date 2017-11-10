<?php

namespace App\Http\Controllers;

use App\Models\Dealer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Qrcode\QrCodeRepository; 

class PrintController extends Controller
{
    
    /**
     * @var QrCodeRepository
     */
     protected $qrcode;

    /**
     * @param QrCodeRepository $qrcode
     */
     public function __construct(QrCodeRepository $qrcode){
        $this->qrcode = $qrcode;
     }   

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showprint(string $identifier)
    {
        $item = $this->qrcode->getbyIdentifier($identifier);
        $dealer = Dealer::find(1);

        if ($item->type == "location") {
            $label = "Location Status Update";
        } else {
            $label = "Build Status Update";
        }
        return view('qrcode.print', [
            'item' => $item,
            'label' => $label,
            'dealer' => $dealer
        ]);
    }

}
