<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * get Api Coins
     *
     * @return \Illuminate\Http\Response
     */
    public function getCoins(Request $request)
    {
        try {
            dd($request);
//            if ($result = $this->_userRepository->confirmPassword($request)) {
//                return response()->json(['meta' => ['code' => Response::HTTP_OK, 'message' => 'OK'], 'result' => 'OK'], Response::HTTP_OK);
//            } else {
//                return response()->json(['meta' => ['code' => 401, 'message' => ['error' => 'Unauthorised']]], 401);
//            }
        } catch (Exception $e) {
            throw $e;
        }
    }
}
