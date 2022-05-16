<?php

namespace App\Http\Controllers;

class TradingController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('frontend.trading.index');
    }
}
