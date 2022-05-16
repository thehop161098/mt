<?php

namespace Core\Services;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Core\Functions\UpdateWallet\Facade\UpdateWalletFacade;
use Core\Repositories\Eloquents\AutoBotRepository;
use Core\Repositories\Eloquents\HistoryBotRepository;
use Core\Traits\RedisWallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BotService
{
    use RedisWallet;

    private $autoBotRepository;
    private $historyBotRepository;
    private $updateWalletFacade;

    public function __construct(
        AutoBotRepository $autoBotRepository,
        HistoryBotRepository $historyBotRepository,
        UpdateWalletFacade $updateWalletFacade
    ) {
        $this->autoBotRepository = $autoBotRepository;
        $this->historyBotRepository = $historyBotRepository;
        $this->updateWalletFacade = $updateWalletFacade;
    }

    public function buyBot($botId, $timeSelect)
    {
        return [
            'success' => false,
            'message' => 'The transaction has ended, the bot has a problem during the transaction'
        ];
        
        $user = Auth::user();

        $currentBot = $this->historyBotRepository->currentBotActive($user->id);
        if ($currentBot) {
            return [
                'success' => false,
                'message' => 'You have already bought!'
            ];
        }

        $autoBot = $this->autoBotRepository->find($botId);
        if (!$autoBot) {
            return [
                'success' => false,
                'message' => 'Package auto bot invalid!'
            ];
        }

        $wallet = $this->getWallet($user->id, config('constants.main_wallet'));
        $wallet['amount'] = floatval($wallet['amount']);

        if (empty($wallet) || $wallet['amount'] < $autoBot->price) {
            return [
                'success' => false,
                'message' => 'You dont have enough money!'
            ];
        }

        switch ($timeSelect) {
            case 7:
                $time = 7;
                $commission = $autoBot->commission_7;
                break;
            case 21:
                $time = 21;
                $commission = $autoBot->commission_21;
                break;
            case 30:
                $time = 30;
                $commission = $autoBot->commission_30;
                break;
            case 90:
                $time = 90;
                $commission = $autoBot->commission_90;
                break;
            default:
                return [
                    'success' => false,
                    'message' => 'Time invalid'
                ];
        }

        if (!$commission) {
            return [
                'success' => false,
                'message' => 'Not found bot'
            ];
        }

        $time_expired = CarbonImmutable::now();
        $day = 1;
        $timeCheck = $time;
        while ($day <= $timeCheck) {
            $time_expired = $time_expired->addDay(1);
            if ($time_expired->dayOfWeek == Carbon::SUNDAY || $time_expired->dayOfWeek == Carbon::SATURDAY) {
                $timeCheck++;
            }
            $day++;
        }

        try {
            DB::beginTransaction();
            $this->historyBotRepository->create([
                'user_id' => $user->id,
                'bot_id' => $autoBot->id,
                'price' => $autoBot->price,
                'commission' => $commission,
                'time' => $time,
                'time_expired' => $time_expired->format('Y-m-d'),
                'status' => true
            ]);
            $newWallet = $wallet;
            $newWallet['amount'] = $wallet['amount'] - $autoBot->price;

            $note = strtr(config('constants.history_buy_bot'), [
                '{NAME}' => $autoBot->name
            ]);

            $history = [
                'user_id' => $user->id,
                'wallet' => config('constants.main_wallet'),
                'amount' => $autoBot->price * (-1),
                'note' => $note,
                'type' => config('constants.type_history.buy_bot'),
            ];

            $this->updateWalletFacade->update($user->id, [$history]);

            $amount = 0;
            if ($autoBot->commission_f1 && $time === 90 && $user->parent) {
                $amount = $autoBot->price * $autoBot->commission_f1;
                $note = strtr(config('constants.history_commission_bot'), [
                    '{AMOUNT}' => $amount,
                    '{USER}' => $user->full_name ? $user->full_name : $autoBot->email,
                    '{NAME}' => $autoBot->name
                ]);

                $history = [
                    'user_id' => $user->parent->id,
                    'wallet' => config('constants.main_wallet'),
                    'amount' => $amount,
                    'note' => $note,
                    'type' => config('constants.type_history.commission_bot'),
                ];
                $this->updateWalletFacade->update($user->parent->id, [$history]);
            }

            DB::commit();

            $this->updateWallet($user->id, config('constants.main_wallet'), $autoBot->price * (-1));
            if ($amount) {
                $this->updateWallet($user->parent->id, config('constants.main_wallet'), $amount);
            }
            return [
                'success' => true,
                'message' => 'Buy successful'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'success' => false,
                'message' => 'Cannot buy bot'
            ];
        }
    }

    public function unLock($currentBot, $userId)
    {
        try {
            return [
                'success' => false,
                'message' => 'The transaction has ended, the bot has a problem during the transaction'
            ];

            DB::beginTransaction();

            $this->historyBotRepository->updateExpired($currentBot->id);
            $note = strtr(config('constants.history_commission_bot_unlock'), [
                '{AMOUNT}' => $currentBot->price,
            ]);

            $history = [
                'user_id' => $userId,
                'wallet' => config('constants.main_wallet'),
                'amount' => $currentBot->price,
                'note' => $note,
                'type' => config('constants.type_history.commission_bot_unlock'),
            ];
            $this->updateWalletFacade->update($userId, [$history]);

            DB::commit();
            $this->updateWallet($userId, config('constants.main_wallet'), $currentBot->price);

            return [
                'success' => true,
                'message' => 'Unlock successful'
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'success' => false,
                'message' => 'Cannot unlock'
            ];
        }
    }

    public function resetTime() {
        $history = $this->historyBotRepository->findAll();
        if ($history) {
            foreach ($history as $row) {
                $time_expired = new CarbonImmutable($row->created_at);
                $day = 1;
                $timeCheck = $row->time;
                while ($day <= $timeCheck) {
                    $time_expired = $time_expired->addDay(1);
                    if ($time_expired->dayOfWeek == Carbon::SUNDAY || $time_expired->dayOfWeek == Carbon::SATURDAY) {
                        $timeCheck++;
                    }
                    $day++;
                }
                $this->historyBotRepository->updateTime($row->id, $time_expired->format('Y-m-d'));
            }
        }
    }
}
