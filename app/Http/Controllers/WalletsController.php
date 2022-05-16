<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
//use Core\Repositories\Contracts\HistoryWithdrawInterface;
use Illuminate\Http\Request;
use Core\Repositories\SettingRepository;
use Core\Repositories\Eloquents\WalletRepository;
use Core\Repositories\Eloquents\WithdrawTokenRepository;
use Core\Services\GetGasPriceService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// use Web3\Web3;
//use Web3\Contract;
//use Ethereum\Ethereum;
//use Ethereum\DataType\SendTransaction;
use Core\Repositories\Eloquents\WalletGameRepository;
use Core\Repositories\Redis\RedisWalletGameRepository;
use Core\Repositories\Eloquents\HistoryWalletRepository;
use Core\Repositories\Eloquents\HistoryWithdrawRepository;
//use Core\Repositories\Contracts\UserInterface;
use Core\Factories\NotificationFactory;
use Core\Repositories\Redis\RedisUserRepository;

class WalletsController extends Controller
{

    public $walletRepository;
    public $settingRepository;
    public $getGasPriceService;
    public $withdrawTokenRepository;
    public $walletGameRepository;
    public $redisWalletGameRepository;
    public $historyWalletRepository;
    public $historyWithdrawRepository;
    public $userRepository;
    public $fee_withdraw = 0;

    public function __construct(
        WalletRepository $walletRepository,
        SettingRepository $settingRepository,
        GetGasPriceService $getGasPriceService,
        WithdrawTokenRepository $withdrawTokenRepository,
        WalletGameRepository $walletGameRepository,
        RedisWalletGameRepository $redisWalletGameRepository,
        HistoryWalletRepository $historyWalletRepository,
        HistoryWithdrawRepository $historyWithdrawRepository,
        RedisUserRepository $userRepository
    ) {
        $this->walletRepository = $walletRepository;
        $this->settingRepository = $settingRepository;
        $this->getGasPriceService = $getGasPriceService;
        $this->withdrawTokenRepository = $withdrawTokenRepository;
        $this->withdrawTokenRepository = $withdrawTokenRepository;
        $this->walletGameRepository = $walletGameRepository;
        $this->redisWalletGameRepository = $redisWalletGameRepository;
        $this->historyWalletRepository = $historyWalletRepository;
        $this->historyWithdrawRepository = $historyWithdrawRepository;
        $this->userRepository = $userRepository;

        $fee_withdraw = $this->settingRepository->getParam('fee_withdraw');
        if (!$fee_withdraw) {
            $fee_withdraw = config('constants.fee_withdraw');
        }
        $this->fee_withdraw = $fee_withdraw;
        view()->share(['fee_withdraw' => $fee_withdraw]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $userWallets = $user->walletGames;
        $wallet_main = $wallet_discount = 0;
        if (!empty($userWallets)) {
            foreach ($userWallets as $wallet) {
                if ($wallet->type === config('constants.main_wallet')) {
                    $wallet_main = $wallet->amount;
                } elseif ($wallet->type === config('constants.discount_wallet')) {
                    $wallet_discount = $wallet->amount;
                }
            }
        }

        $coins = $this->settingRepository->getParam('coins', true);
        $wallets = $this->walletRepository->getAllCoinUserInConfig(Auth::user(), !empty($coins) ? $coins : [null]);
        if (!empty($wallets)) {
            $arrCoins = $this->settingRepository->getParam('coins', false, true);
            foreach ($wallets as $wallet) {
                $wallet->balance = $wallet_main + $wallet_discount;
                $wallet->image = asset('frontend/images/icons/icBitCoin.png');
                $wallet->symbol = '';
                $wallet->nameOther = 'BNB';
                if (!empty($arrCoins)) {
                    $isCoin = array_search($wallet->coin, array_column($arrCoins, 'id'));
                    if ((int)$isCoin >= 0) {
                        $wallet->image = $arrCoins[$isCoin]['image'];
                        $wallet->symbol = $arrCoins[$isCoin]['symbol'];
                        $wallet->nameOther = $arrCoins[$isCoin]['name-other'];
                    }
                }
            }
        }

        $transactions = $this->historyWithdrawRepository->findAll([
            ['user_id', Auth::user()->id]
        ]);
  
        return view('frontend.wallets.wallet', [
            'wallets' => $wallets,
            'isEnabled2FA' => $user->google2fa_enable,
            'transactions' => $transactions,
            'wallet_main' => number_format($wallet_main, 2),
            'wallet_discount' => number_format($wallet_discount, 2)
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function exchange()
    {
        $user = Auth::user();
        $internalWithdraw = $this->historyWalletRepository->findAll([
            ['type', config('constants.type_history.internal_transfer')],
            ['user_id', $user->id]
        ]);
        $this->historyWalletRepository->readAll([
            ['user_id', $user->id],
            ['type', config('constants.type_history.internal_transfer')]
        ]);
        $wallet_main = !empty($user->walletMain) ? $user->walletMain->amount : 0;

        return view('frontend.wallets.exchange', [
            'internalWithdraws' => $internalWithdraw,
            'accountDemo' => $this->getAmountWalletGameOfUser(config("constants.trial_wallet")),
            'accountLive' => $this->getAmountWalletGameOfUser(config("constants.main_wallet")),
            'accountPromotion' => $this->getAmountWalletGameOfUser(config("constants.discount_wallet")),
            'isEnabled2FA' => $user->google2fa_enable,
            'wallet_main' => $wallet_main
        ]);
    }

    public function getAmountWalletGameOfUser($type)
    {
        $rs = $this->walletGameRepository->where([['type', $type], ['user_id', Auth::user()->id]]);
        if (!empty($rs)) {
            return $rs->amount;
        }
        return 0;
    }

    /**
     * Display a listing of the resource.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function deposit(Request $request)
    {
        $history = $this->historyWalletRepository->searchCommissions($request,
            config('constants.type_history.cron_deposit'), Auth::user()->id);
        $historyDiscount = $this->historyWalletRepository->searchCommissions($request,
            config('constants.type_history.discount'), Auth::user()->id);

        $this->historyWalletRepository->readAll([
            ['user_id', Auth::user()->id],
            ['type', config('constants.type_history.cron_deposit')]
        ]);
        return view('frontend.wallets.deposit', ['history' => $history]);
    }

    function ajaxGetInternalWithdraw(Request $request)
    {
        if ($request->ajax()) {
            $internalWithdraws = $this->historyWalletRepository->findAll([
                ['type', config('constants.type_history.internal_transfer')],
                ['user_id', Auth::user()->id]
            ]);
            return view('frontend.wallets.pagination_data', compact('internalWithdraws'))->render();
        }
    }

    function ajaxGetDataWithdraw(Request $request)
    {
        if ($request->ajax()) {
            $transactions = $this->historyWithdrawRepository->findAll([
                ['user_id', Auth::user()->id]
            ]);
            return view('frontend.wallets.transaction', compact('transactions'))->render();
        }
    }

    public function ajaxGetGasPrice()
    {
        echo json_encode([
            'success' => config('constants.statusApi.success'),
            'data' => $this->getGasPriceService->getGasPrice()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function withdraw(Request $request)
    {
        $user = Auth::user();
        if ($user->verify != config('constants.verify_user')) {
            return response([
                'success' => config('constants.statusApi.failed'),
                'msg' => 'User not verify.'
            ]);
        }
        if (!$user->google2fa_enable) {
            return response([
                'success' => config('constants.statusApi.failed'),
                'msg' => 'You must have 2FA enabled to make a withdrawal.'
            ]);
        }
        $withdrawPending = $this->historyWithdrawRepository->list([
            ['user_id', $user->id],
            ['status', config("constants.status_withdraw.temp")]
        ]);
        if ($withdrawPending->count() > 0) {
            return response([
                'success' => config('constants.statusApi.failed'),
                'msg' => 'You have a withdrawal order waiting for confirmation'
            ]);
        }

        if (!isset($request->wallet) || empty($request->wallet) || !in_array($request->wallet,
                [config('constants.main_wallet'), config('constants.discount_wallet')])) {
            return response([
                'success' => config('constants.statusApi.failed'),
                'msg' => 'Wallet invalid'
            ]);
        }

        if (!isset($request->address) || empty($request->address)) {
            return response([
                'success' => config('constants.statusApi.failed'),
                'msg' => 'Address invalid'
            ]);
        }

        if (!isset($request->amount) || empty($request->amount) || !is_numeric($request->amount)) {
            return response([
                'success' => config('constants.statusApi.failed'),
                'msg' => 'Amount invalid'
            ]);
        }

        if ($request->amount < config('constants.min_withdraw')) {
            return response([
                'success' => config('constants.statusApi.failed'),
                'msg' => 'Minimum ' . config('constants.min_withdraw') . ' BIT'
            ]);
        }

        if (!isset($request->network) || empty($request->network) || !in_array($request->network, ['ERC20', 'BEP20'])) {
            return response([
                'success' => config('constants.statusApi.failed'),
                'msg' => 'Network invalid'
            ]);
        }

        $walletGame = $this->walletGameRepository->where([
            ['user_id', $user->id],
            ['type', $request->wallet],
        ]);

        if (empty($walletGame)) {
            return response([
                'success' => config('constants.statusApi.failed'),
                'msg' => 'Cannot find your wallet address to send'
            ]);
        }

        $amount = $request->amount;
        $amountFee = $amount * $this->fee_withdraw / 100;

        if ($request->wallet === config('constants.main_wallet')) {
            if ($walletGame->amount < $amount) {
                return response([
                    'success' => config('constants.statusApi.failed'),
                    'msg' => 'Wallet is not enough'
                ]);
            }

            $daysLimit = $this->settingRepository->getParam('time_withdraw_discount');
            if (empty($daysLimit) || !is_numeric($daysLimit)) {
                $daysLimit = 0;
            }

            $depositDiscount = $this->historyWalletRepository->getDepositDiscountNewest([
                ['user_id', $user->id],
                ['type', config('constants.type_history.discount')],
                ['created_at', '>=', Carbon::now()->subDays($daysLimit)]
            ]);
            if (!empty($depositDiscount)) {
                return response([
                    'success' => config('constants.statusApi.failed'),
                    'msg' => "You can withdraw after $daysLimit days"
                ]);
            }
        } else {
            if ($walletGame->amount < $amount * 2) {
                return response([
                    'success' => config('constants.statusApi.failed'),
                    'msg' => 'Wallet is not enough'
                ]);
            }

            $totalDiscount = $this->historyWalletRepository->where([
                ['user_id', $user->id],
                ['type', config('constants.type_history.discount')]
            ])->sum('amount');
            $totalDiscountWithdraw = $this->historyWithdrawRepository->where([
                ['user_id', $user->id],
                ['wallet', $request->wallet],
                ['status', config('constants.status_withdraw.approved')]
            ])->sum('amount');
            $totalDiscount -= $totalDiscountWithdraw;

            if ($walletGame->amount < $totalDiscount) {
                return response([
                    'success' => config('constants.statusApi.failed'),
                    'msg' => "You can only withdraw the profit"
                ]);
            }
        }

        $dataSave = [
            'user_id' => $user->id,
            'wallet' => $request->wallet,
            'network' => $request->network,
            'coin' => $request->coin ?? "",
            'amount_fee' => $amountFee,
            'amount' => $amount,
            'code' => $request->address,
            'status' => config("constants.status_withdraw.temp"),
        ];

        $api = file_get_contents(config('api.apiEtherscan.getPriceUSD'));
        $dataApi = !empty($api) ? json_decode($api) : null;
        if (empty($dataApi) || !isset($dataApi->binancecoin->usd)) {
            return response([
                'success' => config('constants.statusApi.failed'),
                'msg' => "Failed to get price BNB"
            ]);
        }
        $priceBNB = floatval($dataApi->binancecoin->usd);
      
        $amount_convert = ($amount - $amountFee) / $priceBNB;
        $dataSave['amount_convert'] = $amount_convert;

        $result = $this->historyWithdrawRepository->create($dataSave);
        if ($result) {
            $dataSave['email'] = $user->email;
            $dataSave['fullname'] = $user->full_name;
            $dataSave['time'] = date("Y-m-d h:i:s");
            $dataSave['id'] = $result->id;

            //Send mail
            $notify = new NotificationFactory();
            $notify->produceNotification(config('constants.email.withdraw_temp'), $dataSave);
            return response([
                'success' => config('constants.statusApi.success'),
                'msg' => 'Success. Please check your mail'
            ]);
        }
        return response([
            'success' => config('constants.statusApi.failed'),
            'msg' => 'Cannot save data'
        ]);
    }

    public function userConfirmWithdraw(Request $request)
    {
        $strHash = $request->id ?? "";
        $id = substr($strHash, 32);
        $hash = substr($strHash, 0, 32);
        $checkHashId = md5($id . env('SECRET_KEY_APP')) == $hash;
        if (!$checkHashId) {
            abort(404);
        }
        $historyWithdraw = $this->historyWithdrawRepository->first([['id', $id]]);
        if (empty($historyWithdraw)) {
            return view('frontend.error.warning', ['message' => "The link is invalid"]);
        }

        if ($historyWithdraw->status !== config('constants.status_withdraw.temp')) {
            return view('frontend.error.warning', ['message' => "The withdrawal order has been processed"]);
        }

        $minutesLimit = $this->settingRepository->getParam('time_expired_withdraw');
        if (empty($minutesLimit)) {
            $minutesLimit = config("constants.minutes_limit");
        }
        $date1 = strtotime(date("Y-m-d H:i:s"));
        $date2 = strtotime($historyWithdraw->created_at);
        $minutes = round(abs($date1 - $date2) / 60, 2);
        if ($minutes > $minutesLimit) {
            return view('frontend.error.warning', ['message' => "The link has expired"]);
        }

        $user = $this->userRepository->first([['id', $historyWithdraw->user_id]]);
        if ($historyWithdraw->wallet === config('constants.main_wallet')) {
            $wallet = $user->walletMain;
            $totalWithdraw = $historyWithdraw->amount;
        } else {
            $wallet = $user->walletDiscount;
            $totalWithdraw = $historyWithdraw->amount * 2;
        }
        if (empty($wallet)) {
            return view('frontend.error.warning', ['message' => "Cannot find your wallet"]);
        }

        if ($wallet->amount < $totalWithdraw) {
            return view('frontend.error.warning', ['message' => "Your wallet is not enough"]);
        }

        try {
            DB::beginTransaction();
            $this->historyWithdrawRepository->update([
                ['id', $id],
                ['status', config('constants.status_withdraw.temp')],
            ],
                ['status' => config('constants.status_withdraw.pending')]
            );
            $this->redisWalletGameRepository->update(
                ['amount' => -floatval($totalWithdraw)],
                [['user_id', $historyWithdraw->user_id], ['type', $historyWithdraw->wallet]],
                $historyWithdraw->user_id
            );
            $note = strtr(config('constants.history_withdraw.pending'), [
                '{AMOUNT}' => floatval($historyWithdraw->amount),
            ]);
            $this->saveHistoryWallet($historyWithdraw->user_id, $historyWithdraw->wallet,
                -floatval($totalWithdraw), $note, config('constants.type_history.withdraw_pending'));

            DB::commit();

            $dataSend = [
                '{USER}' => !empty($user) ? $user->full_name : 'N/A',
                '{AMOUNT}' => number_format(floatval($historyWithdraw->amount), 2)
            ];
            $notify = new NotificationFactory();
            $notify->produceNotification(config('constants.telegram.withdraw_request'), $dataSend);

            return view('frontend.error.warning', ['message' => "Confirm success"]);
        } catch (\Exception $e) {
            DB::rollback();
            return view('frontend.error.warning', ['message' => "Withdraw unsuccessful"]);
        }
    }

    public function saveHistoryWallet(
        $userId,
        $wallet,
        $amount,
        $note,
        $type
    ) {
        return $this->historyWalletRepository->create([
            'user_id' => $userId,
            'wallet' => $wallet,
            'amount' => $amount,
            'note' => $note,
            'type' => $type
        ]);
    }

    public function getAmountWallet(array $arrCode = [])
    {
        $strCode = implode(',', $arrCode);
        $api = file_get_contents(config('api.apiEtherscan.balanceMulti')) . $strCode;
        $dataApi = !empty($api) ? json_decode($api, true) : null;
        $rs = [];
        if (!empty($dataApi) && !empty($dataApi['result'])) {
            foreach ($dataApi['result'] as $val) {
                $value = $dataApi['result'][$val];
                $rs[$value['account']] = floatval($value['balance']) / pow(10, 18);
            }
        }
        return $rs;
    }

    public function getAmountUSDTWallet(string $address)
    {
        $api = file_get_contents(config('api.apiEtherscan.account') . '&contractaddress=' . env('CONTRACT_ADDRESS_USDT') . '&address=' . $address);
        $dataApi = !empty($api) ? json_decode($api) : null;
        $rs = 0;
        if (!empty($dataApi) && $dataApi->status == config('constants.statusApi.success')) {
            $rs = floatval($dataApi->result) / pow(10, 6);
        }

        return $rs;
    }

    public function ajaxInternalTransfer(Request $request)
    {
        if (!Auth::user()->google2fa_enable) {
            return response([
                'success' => config('constants.statusApi.failed'),
                'msg' => 'You must have 2FA enabled to make a withdrawal.'
            ]);
        }
        $walletGame = $this->walletGameRepository->where([
            ['user_id', Auth::user()->id],
            ['type', config('constants.main_wallet')]
        ]);
        if (empty($walletGame)) {
            return response([
                'success' => config('constants.statusApi.failed'),
                'msg' => 'Failed wallet main'
            ]);
        }
        if (!isset($request->amount) || empty($request->amount) || !is_numeric($request->amount)) {
            return response([
                'success' => config('constants.statusApi.failed'),
                'msg' => 'Amount invalid'
            ]);
        }

        if ($request->amount < config('constants.min_transfer')) {
            return response([
                'success' => config('constants.statusApi.failed'),
                'msg' => 'Minimum ' . config('constants.min_transfer') . ' BIT'
            ]);
        }

        if ($walletGame->amount < floatval($request->amount)) {
            return response([
                'success' => config('constants.statusApi.failed'),
                'msg' => 'The balance in the wallet is not enough'
            ]);
        }

        $wallet = $this->walletRepository->first([
            ['user_id', "<>", Auth::user()->id],
            ['code', $request->code],
        ]);
        if (empty($wallet)) {
            return response([
                'success' => config('constants.statusApi.failed'),
                'msg' => 'Failed wallet'
            ]);
        }
        $this->saveTransferTo($request, $walletGame);
        $this->saveTransferFrom($request, $wallet, $wallet->user);
        return response([
            'success' => config('constants.statusApi.success'),
            'msg' => 'Transfer success'
        ]);
    }

    /**
     * @param Request $request
     * @param $walletGame
     */
    public function saveTransferTo(Request $request, $walletGame): void
    {
        $this->redisWalletGameRepository->update(
            ['amount' => -floatval($request->amount)],
            [['id', $walletGame->id]],
            Auth::user()->id
        );
        // save history walletGame
        $noteTo = strtr(config('constants.history_internal_transfer.to'), [
            '{AMOUNT}' => floatval($request->amount),
            '{CODE}' => $request->code
        ]);
        $this->saveHistoryWallet(Auth::user()->id, config('constants.main_wallet'),
            -floatval($request->amount), $noteTo, config('constants.type_history.internal_transfer'));
    }

    /**
     * @param Request $request
     * @param $wallet
     */
    public function saveTransferFrom(Request $request, $wallet, $user_to): void
    {
        $walletGameMain = $this->walletGameRepository->where([
            ['user_id', $wallet->user_id],
            ['type', config('constants.main_wallet')]
        ]);
        $this->redisWalletGameRepository->update(
            ['amount' => floatval($request->amount)],
            [['id', $walletGameMain->id]],
            $walletGameMain->user_id
        );
        // save history walletGame
        $walletUserFrom = $this->walletRepository->first([
            ['user_id', Auth::user()->id],
            ['cate_id', config('constants.walletCate.eth')],
        ]);
        $noteFrom = strtr(config('constants.history_internal_transfer.from'), [
            '{AMOUNT}' => floatval($request->amount),
            '{CODE}' => isset($walletUserFrom->code) ? $walletUserFrom->code : "",
        ]);
        $rs = $this->saveHistoryWallet($walletGameMain->user_id, config('constants.main_wallet'),
            floatval($request->amount),
            $noteFrom, config('constants.type_history.internal_transfer'));
        //Send mail
        if ($rs) {
            $infoUser = $this->userRepository->first([['id', $wallet->user_id]]);
            $dataMail = [
                'email' => $infoUser->email,
                'user_from' => Auth::user()->full_name,
                'amount' => floatval($request->amount),
                'time' => date("Y-m-d H:i:S"),
                'user_to' => !empty($user_to) ? $user_to->full_name : 'N/A',
            ];
            $notify = new NotificationFactory();
            $notify->produceNotification(config('constants.email.internal_withdraw'), $dataMail);
        }
    }

    public function refill()
    {
        $accountDemo = $this->getAmountWalletGameOfUser(config("constants.trial_wallet"));
        $amount = config("constants.amount_plus_trial") - $accountDemo;
        if ($accountDemo >= config("constants.amount_plus_trial")) {
            return [
                'success' => false,
                'message' => 'The wallet is full!',
            ];
        }
        if ($amount > 0) {
            $note = strtr(config('constants.history_refill'), [
                '{AMOUNT}' => $amount
            ]);
            $type = config('constants.type_history.refill');
            $typeWallet = config('constants.trial_wallet');
            $this->calMoneyAndSaveHistory(auth()->user()->id, $typeWallet, $amount, $note, $type);
        }
        return [
            'success' => true,
            'amount' => number_format(config("constants.amount_plus_trial"), 2),
            'message' => 'Refill successfully!',
        ];
    }

    private function calMoneyAndSaveHistory(
        $userId,
        $wallet,
        $amount,
        $note,
        $type
    ) {
        // update money wallet main
        $this->redisWalletGameRepository->update(['amount' => $amount], [
            ['user_id', $userId],
            ['type', $wallet]
        ], $userId);

        // save history wallet
        $this->historyWalletRepository->create([
            'user_id' => $userId,
            'wallet' => $wallet,
            'amount' => $amount,
            'note' => $note,
            'type' => $type,
        ]);
    }
}
