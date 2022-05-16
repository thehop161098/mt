<?php

namespace Core\Services;

use Core\Repositories\SettingRepository;
use Core\Repositories\Eloquents\HistoryWithdrawRepository;

class WithdrawService
{
    public $historyWithdrawRepository;
    private $settingRepository;

    public function __construct(
        SettingRepository $settingRepository,
        HistoryWithdrawRepository $historyWithdrawRepository
    ) {
        $this->settingRepository = $settingRepository;
        $this->historyWithdrawRepository = $historyWithdrawRepository;
    }

    /**
     *
     */
    public function cronCheckStatusHistory()
    {
        $withdrawHistory = $this->historyWithdrawRepository->list([
            ['status', config('constants.status_withdraw.temp')]
        ]);
        $minutesLimit = $this->settingRepository->getParam('time_expired_withdraw');
        if (empty($minutesLimit)) {
            $minutesLimit = config("constants.minutes_limit");
        }

        if (!empty($withdrawHistory)) {
            foreach ($withdrawHistory as $withdraw) {
                $date1 = strtotime(date("Y-m-d H:i:s"));
                $date2 = strtotime($withdraw->created_at);
                $minutes = round(abs($date1 - $date2) / 60);
                if ($minutes > $minutesLimit) {
                    $this->historyWithdrawRepository->update(
                        [['id', $withdraw->id]],
                        [
                            'status' => config('constants.status_withdraw.expired'),
                            'reason' => config('constants.reason_expired_withdraw')
                        ]
                    );
                }
            }
        }
    }
}
