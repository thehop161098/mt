<?php

namespace Core\Services;

use Core\Repositories\SettingRepository;

class GetGasPriceService
{
    private $settingRepository;

    public function __construct(SettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    /**
     * get gas price
     *
     * @return array
     */
    public function getGasPrice()
    {
        $api = file_get_contents(config('api.apiEtherscan.gasTracker'));
        $dataApi = !empty($api) ? json_decode($api) : null;
        $gasPrice = [
            'fast' => 0,
            'safeLow' => 0
        ];
        $fee = [];
        if (!empty($dataApi) && (int)$dataApi->status == config('constants.statusApi.success')) {
            $gasPrice['fast'] = floatval($dataApi->result->FastGasPrice);
            $fee['fast'] = $this->calculatorFee($gasPrice['fast']);
            $gasPrice['safeLow'] = floatval($dataApi->result->SafeGasPrice);
            $fee['safeLow'] = $this->calculatorFee($gasPrice['fast']);
        }

        return [
            'arrGasPrice' => $gasPrice,
            'gasLimit' => $this->settingRepository->getParam('amount_withdraw_min'),
            'fee' => $fee,
        ];
    }

    /**
     * @param float $speed
     *
     * @return Number
     */
    public function calculatorFee(float $speed)
    {
        $gasLimit = $this->settingRepository->getParam('gas_limit');
        if (!is_numeric($gasLimit)) {
            $gasLimit = config('constants.gasLimit');
        }
        $fee = $speed * 1000000000 * $gasLimit;
        return $this->convertCurrency($fee);
    }

    public function convertCurrency(float $amount, string $from = 'wei', string $to = 'ether')
    {
        $convertTabe = [
            'wei' => 1000000000000000000,
            'kwei' => 1000000000000000,
            'mwei' => 1000000000000,
            'gwei' => 1000000000,
            'gwei' => 1000000,
            'methere' => 1000,
            'ether' => 1,
            'kether' => 0.001,
            'mether' => 0.000001,
            'gether' => 0.000000001,
            'thether' => 0.000000000001,
        ];

        return $convertTabe[$to] * $amount / $convertTabe[$from];
    }

    /**
     * get gas price by speed
     *
     * @return Number
     */
    public function getGasPriceBySpeed(string $speed = 'safeLow')
    {
        $api = file_get_contents(config('api.apiEtherscan.gasTracker'));
        $dataApi = !empty($api) ? json_decode($api) : null;
        $gasPrice = config('constants.gasPrice');
        if (!empty($dataApi) && (int)$dataApi->status == config('constants.statusApi.success')) {
            if ($speed == 'safeLow') {
                $gasPrice = floatval($dataApi->result->SafeGasPrice) * 1000000000;
            } else {
                $gasPrice = floatval($dataApi->result->FastGasPrice) * 1000000000;
            }
        }

        return $gasPrice;
    }
}
