<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Core\Repositories\SettingRepository;

class SettingsController extends Controller
{
    public $_settingRespository;

    public function __construct(SettingRepository $settingRespository)
    {
        $this->_settingRespository = $settingRespository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $defineOptions = $this->_settingRespository->defineOptions;
        return view("admin.setting.index", ['defineOptions' => $defineOptions])->with('callbackSetting', $this);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->_settingRespository->update($request);
        return redirect()->route('admin/settings.index');
    }
}
