<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Qrcode\QrCodeRepository; 

class QrcodeController extends Controller
{   
    /**
     * @var route_name
     */
    private $route_name = 'qrcode';

    /**
     * @var QrCodeRepository
     */
    private $qrcode;

    /**
     * @var params array
     */
    private $params = [];

    /**
     * @param QrCodeRepository $qrcode
     */
    public function __construct(QrCodeRepository $qrcode)
    {
        $this->params = [
            'breadcrumbs' => [
                ['url' => '/', 'page' => 'Dashboard'],
                ['url' => $this->route_name, 'page' => 'Qr Build']
            ],
            'title' => 'Qr Build',
            'subtitle' => 'QR',
            'search' => 'yes',
            'filter' => '',
            'items_per_page' => 500,
            'route_name' => $this->route_name,
            'route' => '/' . $this->route_name,
            'data' => null,
        ];

        $this->qrcode = $qrcode; 
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort(404);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //

    }

    /**
     * Display the specified resource.
     *
     * @param  string  $identifier
     * @return \Illuminate\Http\Response
     */
    public function show($identifier)
    {
        $type = $this->qrcode->getType($identifier)->type;
        return redirect()->to('qr-'.$type.'/'.$identifier);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @param  string  $identifier
     * @return \Illuminate\Http\Response
     */
    public function qrBuildLocation($identifier)
    {
        if ($this->qrcode->checkexpiry($identifier) ){
            abort('506');
        }

        $type = $this->qrcode->getType($identifier)->type;
        return redirect()->to('/#/qr-'.$type.'/'.$identifier);
    }
}
