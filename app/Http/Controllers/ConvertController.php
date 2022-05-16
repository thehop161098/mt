<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConvertController extends Controller
{
    public function index()
    {
        return view('frontend.convert.index');
    }
}
