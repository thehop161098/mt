<?php

namespace Core\Services;

use Core\Functions\UpdateWallet\Facade\UpdateWalletFacade;
use Core\Repositories\Contracts\HistoryWalletInterface;
use Core\Repositories\Eloquents\WheelRepository;
use Core\Repositories\Eloquents\WheelSettingRepository;
use Core\Traits\RedisWallet;

class LuckyWheelService
{
    use RedisWallet;

    private $wheelSettingRepository;
    private $wheelRepository;
    private $updateWalletFacade;
    private $historyWalletRepository;

    public function __construct(
        WheelSettingRepository $wheelSettingRepository,
        WheelRepository $wheelRepository,
        UpdateWalletFacade $updateWalletFacade,
        HistoryWalletInterface $historyWalletRepository
    ) {
        $this->wheelSettingRepository = $wheelSettingRepository;
        $this->wheelRepository = $wheelRepository;
        $this->updateWalletFacade = $updateWalletFacade;
        $this->historyWalletRepository = $historyWalletRepository;
    }

    public function spin($userId)
    {
        if (config('settings.publish_wheel') !== '1') {
            return [
                'status' => 422,
                'message' => 'Functional under maintenance'
            ];
        }

        $isSpinToday = $this->historyWalletRepository->isSpinWheel([
            ['user_id', $userId],
            ['type', config('constants.type_history.lucky_wheel')]
        ]);
        if ($isSpinToday > 0) {
            return [
                'status' => 422,
                'message' => 'You have already spin today'
            ];
        }

        $wallet = $this->getWallet($userId, config('constants.main_wallet'));
        if (empty($wallet)) {
            return [
                'status' => 422,
                'message' => 'Cannot find you wallet'
            ];
        }

        $randomSpin = null;

        $setting = $this->wheelSettingRepository->getSettingWheel($wallet['amount']);
        $prize = $setting ? $setting->prize : 0;
        $wheels = $this->wheelRepository->getWheels('prize')->toArray();
        $arrPrize = [];
        if (!empty($wheels)) {
            $arrWheels = [];
            foreach ($wheels as $wheel) {
                $arrPrize[(string)$wheel['prize']][] = $wheel['sort'];
                if ($prize > 0 && $wheel['prize'] <= $prize && !isset($arrWheels[$wheel['prize']])) {
                    $arrWheels[(string)$wheel['prize']] = $wheel;
                }
            }

            $results = [];
            if (count($arrWheels) > 0) {
                $arrWheels = array_values($arrWheels);
                if (count($arrWheels) === 1) {
                    $results[(string)$arrWheels[0]['prize']] = 100;
                } elseif (count($arrWheels) === 2) {
                    $results[(string)$arrWheels[0]['prize']] = 80;
                    $results[(string)$arrWheels[1]['prize']] = 20;
                } else {
                    $percentFirst = 80;
                    $results[(string)$arrWheels[1]['prize']] = 20;

                    for ($i = 2; $i < count($arrWheels); $i++) {
                        $percent = $percentFirst / 20;
                        $results[(string)$arrWheels[$i]['prize']] = $percent;
                        $percentFirst -= $percent;
                    }
                    $results[(string)$arrWheels[0]['prize']] = $percentFirst;
                }
                ksort($results);
                $random = mt_rand() / mt_getrandmax() * 100;

                $temp = 0;
                foreach ($results as $prizeResult => $result) {
                    if ($random > $temp && $random < $result + $temp) {
                        $randomSpin = $prizeResult;
                        break;
                    } else {
                        $temp = $result + $temp;
                    }
                }
            }
        }

        if ($randomSpin !== null && !empty($arrPrize)) {
            try {
                $randomResult = $arrPrize[$randomSpin][random_int(0, count($arrPrize[$randomSpin]) - 1)];

                $randomSpin = (double)$randomSpin;
                if ($randomSpin === 0) {
                    return [
                        'status' => 401,
                        'message' => 'Better luck next time',
                        'spin' => $randomResult
                    ];
                }

                $note = strtr(config('constants.history_lucky_wheel'), [
                    '{AMOUNT}' => $randomSpin
                ]);

                $history = [
                    'user_id' => $userId,
                    'wallet' => config('constants.main_wallet'),
                    'amount' => $randomSpin,
                    'note' => $note,
                    'type' => config('constants.type_history.lucky_wheel'),
                ];
                $this->updateWalletFacade->update($userId, [$history]);
                $this->updateWallet($userId, config('constants.main_wallet'), $randomSpin);

                return [
                    'status' => 200,
                    'spin' => $randomResult
                ];
            } catch (\Exception $e) {
                return [
                    'status' => 422,
                    'message' => 'Cannot spin Lucky Wheel'
                ];
            }
        }

        return [
            'status' => 422,
            'message' => 'Cannot spin Lucky Wheel'
        ];
    }
}
