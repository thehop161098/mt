<?php

namespace App\Http\Controllers;

//use Core\Factories\NotificationFactory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
//        toastr()->success('Data has been saved successfully!');
        return view('home');
    }
}
