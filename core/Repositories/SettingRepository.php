<?php

namespace Core\Repositories;

use App\Models\Setting;
use Core\Interfaces\SettingInterface;
use Illuminate\Support\Facades\DB;

class  SettingRepository implements SettingInterface
{

    /**
     * define all option
     */
    public $defineOptions = [
        "pageSetting" => [
            'label' => 'Website',
            'icon' => '<i class="fa fa-globe"></i>',
            'items' => [
                ['name' => 'logoFE', 'type' => 'file', 'class' => '', 'htmlOptions' => [], 'rules' => ''],
                ['name' => 'favicon', 'type' => 'file', 'class' => '', 'htmlOptions' => [], 'rules' => ''],
                [
                    'name' => 'defaultPageTitle',
                    'type' => 'input',
                    'class' => '',
                    'placeholder' => 'Title web',
                    'htmlOptions' => ['maxlength' => 80],
                    'rules' => ''
                ],
                [
                    'name' => 'meta_description',
                    'type' => 'textarea',
                    'class' => '',
                    'placeholder' => '',
                    'htmlOptions' => ['cols' => 77, 'rows' => 4],
                    'rules' => ''
                ],
                [
                    'name' => 'copyrightOnFooter',
                    'type' => 'textarea',
                    'class' => 'editor-full',
                    'placeholder' => '',
                    'htmlOptions' => ['id' => 'editor-full-3'],
                    'rules' => ''
                ],
                [
                    'name' => 'coins',
                    'type' => 'input',
                    'class' => '',
                    'placeholder' => 'Enter Ids coins...',
                    'htmlOptions' => [],
                    'rules' => '',
                    'note' => 'Link: https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&order=market_cap_desc&per_page=20&page=&sparkline=false'
                ],
                [
                    'name' => 'amount_withdraw_min',
                    'type' => 'input',
                    'class' => '',
                    'placeholder' => 'Enter Minimum withdrawal amount...',
                    'htmlOptions' => [],
                    'rules' => ''
                ],
                [
                    'name' => 'gas_limit',
                    'type' => 'input',
                    'class' => '',
                    'placeholder' => 'Enter Gas Limit...',
                    'htmlOptions' => [],
                    'rules' => ''
                ],
                [
                    'name' => 'time_expired_withdraw',
                    'type' => 'input',
                    'class' => '',
                    'placeholder' => 'Withdrawal expiry time (minutes)...',
                    'htmlOptions' => [],
                    'rules' => ''
                ],
                [
                    'name' => 'publish',
                    'type' => 'radio',
                    'class' => '',
                    'placeholder' => '',
                    'htmlOptions' => [],
                    'rules' => ''
                ],
                [
                    'name' => 'time_withdraw_discount',
                    'type' => 'input',
                    'class' => '',
                    'placeholder' => 'Withdrawal discount after (days)...',
                    'htmlOptions' => [],
                    'rules' => ''
                ],
                [
                    'name' => 'publish_wheel',
                    'type' => 'radio',
                    'class' => '',
                    'placeholder' => '',
                    'htmlOptions' => [],
                    'rules' => ''
                ],
                [
                    'name' => 'fee_withdraw',
                    'type' => 'input',
                    'class' => '',
                    'placeholder' => 'Enter % Fee Withdraw...',
                    'htmlOptions' => [],
                    'rules' => ''
                ],
                [
                    'name' => 'publish_bot',
                    'type' => 'radio',
                    'class' => '',
                    'placeholder' => '',
                    'htmlOptions' => [],
                    'rules' => ''
                ]
            ],
        ],
        "controlSetting" => [
            'label' => 'Control',
            'icon' => '<i class="fa fa-refresh"></i>',
            'items' => [
                [
                    'name' => 'max_profit',
                    'type' => 'input',
                    'class' => '',
                    'placeholder' => '',
                    'htmlOptions' => [],
                    'rules' => ''
                ],
                [
                    'name' => 'max_profit_admin',
                    'type' => 'input',
                    'class' => '',
                    'placeholder' => '',
                    'htmlOptions' => [],
                    'rules' => ''
                ],
                [
                    'name' => 'min',
                    'type' => 'input',
                    'class' => '',
                    'placeholder' => '',
                    'htmlOptions' => [],
                    'rules' => ''
                ],
                [
                    'name' => 'max',
                    'type' => 'input',
                    'class' => '',
                    'placeholder' => '',
                    'htmlOptions' => [],
                    'rules' => ''
                ],
                [
                    'name' => 'prize',
                    'type' => 'input',
                    'class' => '',
                    'placeholder' => 'Prize x yellow candle',
                    'htmlOptions' => [],
                    'rules' => ''
                ],
                [
                    'name' => 'max_amount_win',
                    'type' => 'input',
                    'class' => '',
                    'placeholder' => 'Max amount allow win',
                    'htmlOptions' => [],
                    'rules' => ''
                ],
            ]
        ],
        "depositSetting" => [
            'label' => 'Deposit',
            'icon' => '<i class="fa fa-exchange"></i>',
            'items' => [
                [
                    'name' => 'disabled_deposit',
                    'type' => 'radio',
                    'class' => '',
                    'placeholder' => '',
                    'htmlOptions' => [],
                    'rules' => ''
                ],
                [
                    'name' => 'root_address',
                    'type' => 'input',
                    'class' => '',
                    'placeholder' => '',
                    'htmlOptions' => [],
                    'rules' => ''
                ],
            ]
        ],
    ];
    protected $_setting;
    protected $arrayFieldJson = ['coins'];

    public function __construct()
    {
        $this->_setting = new Setting();
    }

    public static function getLogo()
    {
        $setting = new self();
        $logo = $setting->_setting::firstWhere('key', 'logoFE');
        return $logo && !empty($logo->value) ? $logo->value : 'frontend/images/logo/logo.png';
    }

    public static function getSetting()
    {
        $condition = ['logoFE', 'favicon', 'defaultPageTitle', 'meta_description', 'publish_wheel', 'publish_bot'];
        return Setting::whereIn('key', $condition)->get();
    }

    /**
     * Update
     */
    public function update($request)
    {
        $fileOld = [
            'logoFE' => $this->getParam('logoFE'),
            'favicon' => $this->getParam('favicon'),
            'logoBE' => $this->getParam('logoBE'),
            'schemaFile' => $this->getParam('schemaFile'),
        ];
        DB::beginTransaction();

        try {
            if ($request->has('Settings')) {
                Setting::truncate();

                foreach ($this->defineOptions as $key => $option) {
                    if (isset($option['items'])) {
                        foreach ($option['items'] as $item) {
                            $setting = new Setting();
                            $itemObject = (object)$item;
                            if ($itemObject->type != 'file') {
                                $this->makeSaveData($request, $itemObject, $setting);
                            } else {
                                $this->makeSaveFile($request, $itemObject, $setting, $fileOld);
                            }
                        }
                    }
                }
                return true;
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw new GeneralException(__('There was a problem creating your account.'));
        }

        DB::commit();

        return false;
    }

    /**
     * @return String
     * @var String
     * @Get Params
     */
    public function getParam($key, $isReturnStringJson = false, $isReturnValueDecode = false)
    {
        $setting = Setting::where('key', $key)->first();
        if (!empty($setting)) {
            if (in_array($key, $this->arrayFieldJson)) {
                $valDecode = json_decode($setting->value, true);
                if ($isReturnValueDecode) {
                    return $valDecode;
                }
                $value = !empty($valDecode) ? array_column($valDecode, 'id') : null;
                $implodeValue = !empty($value) ? implode(', ', $value) : "";
                return $isReturnStringJson ? $value : $implodeValue;
            }
            return $setting->value;
        }
        return '';
    }

    /**
     * @param $request
     * @param object $itemObject
     * @param Setting $setting
     */
    public function makeSaveData($request, object $itemObject, Setting $setting): void
    {
        $setting->key = $itemObject->name;
        $setting->value = $request->Settings[$itemObject->name];
        if (in_array($itemObject->name, $this->arrayFieldJson) && !empty($request->Settings[$itemObject->name])) {
            $stringValue = $request->Settings[$itemObject->name];
            $explodeComma = explode(",", $stringValue);
            if (!empty($explodeComma)) {
                $arrIds = [];
                $arrNameOther = [];
                foreach ($explodeComma as $comma) {
                    $explodeValue = explode("_", $comma);
                    if (isset($explodeValue[0]) && !empty($explodeValue[0])) {
                        $arrIds[] = preg_replace('/\s+/', '', $explodeValue[0]);
                        $arrNameOther[] = isset($explodeValue[1]) && !empty($explodeValue[1]) ? $explodeValue[1] : "";
                    }
                }
                if (!empty($arrIds)) {
                    $api = config('api.apiCoingecko.coinsMarkets') . '?vs_currency=usd&order=market_cap_desc&ids=' . implode(",",
                            $arrIds);
                    $dataApi = json_decode(file_get_contents($api));
                    $arrData = [];
                    if (is_array($dataApi) && !empty($dataApi)) {
                        foreach ($dataApi as $valApi) {
                            $keyNameOther = array_search($valApi->id, $arrIds);
                            $arrData[] = [
                                'id' => $valApi->id,
                                'image' => $valApi->image,
                                'symbol' => $valApi->symbol,
                                'name-other' => isset($arrNameOther[$keyNameOther]) ? $arrNameOther[$keyNameOther] : "",
                            ];
                        }
                    }
                    $setting->value = json_encode($arrData);
                }
            }
        }
        $setting->save();
    }

    /**
     * @param $request
     * @param object $itemObject
     * @param Setting $setting
     * @param array $fileOld
     */
    public function makeSaveFile($request, object $itemObject, Setting $setting, array $fileOld): void
    {
        if ($request->hasFile("Settings") && isset($request->Settings[$itemObject->name]) && !empty($request->Settings[$itemObject->name])) {
            $files = $request->Settings[$itemObject->name];
            $fileName = time() . rand() . "." . $files->getClientOriginalExtension();
            $destinationPath = public_path('/images/settings');
            $files->move($destinationPath, $fileName);
            $setting->key = $itemObject->name;
            $setting->value = $fileName;
            if (isset($fileOld[$itemObject->name]) && !empty($fileOld[$itemObject->name])) {
                $this->removeImage('images/settings/' . $fileOld[$itemObject->name]);
            }
            $setting->save();
        } else {
            $value = '';
            if (isset($fileOld[$itemObject->name])) {
                $value = $fileOld[$itemObject->name];
            }
            $setting->key = $itemObject->name;
            $setting->value = $value;
            $setting->save();
        }
    }

    /**
     * @return Boolean
     * @var String
     */
    public function removeImage($pathImg)
    {
        if (file_exists(public_path($pathImg))) {
            unlink(public_path($pathImg));
            return true;
        }

        return false;
    }

    /**
     * @return String
     * @var String
     * @Get Params
     */
    public function getParamViewSetting($key)
    {
        $setting = Setting::where('key', $key)->first();
        if (!empty($setting)) {
            if (in_array($key, $this->arrayFieldJson)) {
                $valDecode = json_decode($setting->value, true);
                if (!empty($valDecode)) {
                    $value = !empty($valDecode) ? array_column($valDecode, 'id') : null;
                    $nameOther = !empty($valDecode) ? array_column($valDecode, 'name-other') : null;
                    $rs = [];
                    foreach ($value as $key => $val) {
                        $valNameOther = isset($nameOther[$key]) && !empty($nameOther[$key]) ? '_' . $nameOther[$key] : "";
                        $rs[] = $val . $valNameOther;
                    }
                    return implode(', ', $rs);
                }
            }
            return $setting->value;
        }
        return '';
    }

    /**
     * @return String
     * @var Array
     */
    public function getHtmlOption($array)
    {
        $html = '';
        if (isset($array) && !empty($array)) {
            foreach ($array as $key => $value) {
                $html .= $key . '="' . $value . '" ';
            }
        }

        return $html;
    }

    /**
     * @return String
     * @var String
     */
    public function getAttributeName($field)
    {
        $attrbuteName = [
            'logoFE' => 'Logo Frontend',
            'favicon' => 'Favicon',
            'logoBE' => 'Logo Admin',
            'defaultPageTitle' => 'Title',
            'copyrightOnFooter' => 'Copyright',
            'companyAddress' => 'Address company',
            'companyCellPhone' => 'Hotline',
            'companyPhone' => 'Company Phone',
            'companyEmail' => 'Email',
            'companyName' => 'Company name',
            'companyTaxcode' => 'Taxcode',
            'companyTimeWork' => 'Time work',
            'meta_description' => 'Description',
            'googleMap' => 'Google Map',
            'googleAnalytics' => 'Google Analytics',
            'schemaText' => 'Schema',
            'schemaFile' => 'File Schema',
            'webmastersTools' => 'Webmasters Tools',
            'amount_withdraw_min' => 'Minimum withdrawal amount',
            'gas_limit' => 'Gas Limit',
            'contract_usdt' => 'CONTRACT USDT',
            'time_expired_withdraw' => 'Withdrawal expiry time (minutes)',
            'time_withdraw_discount' => 'Withdrawal discount time (days)',
            'max_amount_win' => 'Max Amount Win',
            'fee_withdraw' => 'Fee Withdraw',
            'disabled_deposit' => 'Disabled Deposit',
        ];

        if (isset($attrbuteName[$field])) {
            return $attrbuteName[$field];
        }

        return $field;
    }
}
