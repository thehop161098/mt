<?php

namespace Core\Services;

use Core\Functions\UpdateWallet\Facade\UpdateWalletFacade;
use Core\Repositories\Eloquents\DiscountRepository;
use Core\Repositories\Eloquents\HistoryDiscountRepository;
use Core\Repositories\SettingRepository;
use Core\Repositories\Eloquents\WalletRepository;
use Core\Traits\RedisWallet;
use Illuminate\Support\Facades\DB;
use File;
use Core\Repositories\Redis\RedisWalletGameRepository;
use Core\Repositories\Eloquents\HistoryWalletRepository;
use Core\Factories\NotificationFactory;
use Web3\Web3;

class DepositService
{
    use RedisWallet;

    public $redisWalletGameRepository;
    public $historyWalletRepository;
    private $settingRepository;
    private $walletRepository;
    private $discountRepository;
    private $historyDiscountRepository;
    private $updateWalletFacade;

    public function __construct(
        SettingRepository $settingRepository,
        WalletRepository $walletRepository,
        RedisWalletGameRepository $redisWalletGameRepository,
        HistoryWalletRepository $historyWalletRepository,
        DiscountRepository $discountRepository,
        HistoryDiscountRepository $historyDiscountRepository,
        UpdateWalletFacade $updateWalletFacade
    ) {
        $this->settingRepository = $settingRepository;
        $this->walletRepository = $walletRepository;
        $this->redisWalletGameRepository = $redisWalletGameRepository;
        $this->historyWalletRepository = $historyWalletRepository;
        $this->discountRepository = $discountRepository;
        $this->historyDiscountRepository = $historyDiscountRepository;
        $this->updateWalletFacade = $updateWalletFacade;
    }

    public function delCache() {
        $this->redisWalletGameRepository->delRedis(348);
    }

    /**
     *
     */
    public function cronjobPlusAmount()
    {
        $disabledDeposit = $this->settingRepository->getParam('disabled_deposit');
        if ($disabledDeposit == config('constants.publish.yes')) {
            return;
        }
        
        $web3 = new Web3(env('RPC'));

        $arrIds = $this->readFile();
        $wallets = $this->walletRepository->getTwentyWalletOfUser($arrIds, 50);
        if ($wallets->isEmpty()) {
            $arrIds = [0];
            $wallets = $this->walletRepository->getTwentyWalletOfUser($arrIds, 50);
        }
        if (!empty($wallets)) {
            $arrCoins = $this->settingRepository->getParam('coins', false, true);

            foreach ($wallets as $wallet) {
                $infoUser = $wallet->user;
                if (empty($infoUser)) {
                    continue;
                }

                $api = file_get_contents(config('api.apiEtherscan.getPriceUSD'));
                $dataApi = !empty($api) ? json_decode($api) : null;
                if (!empty($dataApi) && isset($dataApi->binancecoin->usd)) {
                    array_push($arrIds, $wallet->id);

                    $priceBnbUsdt = floatval($dataApi->binancecoin->usd);
                    $nameOther = $wallet->coin;
                    $isCoin = array_search($wallet->coin, array_column($arrCoins, 'id'));
                    if ((int)$isCoin >= 0) {
                        $nameOther = $arrCoins[$isCoin]['name-other'];
                    }
    
                    $balanceBsc = "0";
                    try {
                        $web3->getEth()->getBalance($wallet->code, function ($err, $data) use (&$balanceBsc) {
                            if ($err !== null) {
                                return;
                            }
                            $balanceBsc = $data->toString();
                        });
                    } catch (\Throwable $th) {
                        $balanceBsc = "0";
                    }
 
                    if (floatval($balanceBsc) > floatval($wallet->amount_bsc)) {
                        try {
                            DB::beginTransaction();
                            $amountDepositBnb = (floatval($balanceBsc) / pow(10, 18)) - floatval($wallet->amount_bsc);
                            $amountDeposit = $amountDepositBnb * $priceBnbUsdt;
  
                            $amountDiscount = 0;
                            // check promotion current
                            $promotion = $this->discountRepository->getDiscountCurrent($amountDeposit);
                            if ($promotion && $promotion->discount > 0) {
                                $isDiscount = $this->historyDiscountRepository->isDiscount($wallet->user_id,
                                    $promotion->id);
                                if ($isDiscount === 0) {
                                    // plus money to discount wallet
                                    $amountDiscount = $amountDeposit * $promotion->discount / 100;
                                    $note = strtr(config('constants.history_discount'), [
                                        '{AMOUNT}' => $amountDiscount,
                                        '{NAME}' => $promotion->name
                                    ]);
                                    $history = [
                                        'user_id' => $wallet->user_id,
                                        'wallet' => config('constants.discount_wallet'),
                                        'amount' => $amountDiscount,
                                        'note' => $note,
                                        'type' => config('constants.type_history.discount'),
                                    ];
                                    $this->updateWalletFacade->update($wallet->user_id, [$history]);
                                    $this->historyDiscountRepository->create([
                                        'user_id' => $wallet->user_id,
                                        'discount_id' => $promotion->id,
                                        'deposit' => $amountDeposit,
                                        'discount' => $amountDiscount
                                    ]);
                                }
                            }

                            // plus money to main wallet
                            $note = strtr(config('constants.history_cron_deposit'), [
                                '{CODE}' => $wallet->code,
                                '{AMOUNT}' => number_format(floatval($amountDepositBnb), 5).' '.$nameOther,
                                '{NETWORK}' => '(BEP20)'
                            ]);

                            $history = [
                                'user_id' => $wallet->user_id,
                                'wallet' => config('constants.main_wallet'),
                                'amount' => $amountDeposit,
                                'note' => $note,
                                'type' => config('constants.type_history.cron_deposit'),
                            ];
                            $this->updateWalletFacade->update($wallet->user_id, [$history]);

                            // update new amount for wallet
                            $this->walletRepository->updateAmount($wallet, floatval($balanceBsc), 'amount_bsc');

                            DB::commit();

                            // update redis wallet
                            $this->updateWallet($wallet->user_id, config('constants.discount_wallet'), $amountDiscount);
                            $this->updateWallet($wallet->user_id, config('constants.main_wallet'), $amountDeposit);

                            // Send mail
                            $dataMail = [
                                'email' => $infoUser->email,
                                'fullname' => $infoUser->full_name,
                                'amount' => number_format(floatval($amountDepositBnb), 5) . ' ' . $nameOther,
                                'address' => $wallet->code,
                                'network' => ' - (BEP20)'
                            ];
                            $notify = new NotificationFactory();
                            $notify->produceNotification(config('constants.email.deposit_success'), $dataMail);

                        } catch (\Exception $e) {
                            DB::rollback();
                            $this->redisWalletGameRepository->delRedis($wallet->user_id);
                        }
                    }
                }
            }
            $this->writeFile($arrIds);
        }
    }

    /**
     * @return array
     */
    public function readFile()
    {
        $url = public_path() . '/uploads/json/array_ids.json';
        $getFile = file_get_contents($url);
        $jsonData = json_decode($getFile, true);
        if (!empty($jsonData) && count($jsonData) > 0) {
            return $jsonData;
        }
        return [0];
    }

    /**
     * @return boolean
     * @var array
     *
     */
    public function writeFile(array $array)
    {
        $data = json_encode($array);
        $file = 'array_ids.json';
        $destinationPath = public_path() . "/uploads/json/";
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        File::put($destinationPath . $file, $data);
        return true;
    }
}
