<?php

namespace App\Http\Controllers;
use App\Models\Setting;
use Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {   
        return view('spa');
    }
}
