<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Core\Repositories\Contracts\HistoryWalletInterface;
use Core\Traits\RedisUser;

class HistoryWalletController extends Controller
{
    use RedisUser;
    private $historyWalletRepository;

    public function __construct(HistoryWalletInterface $historyWalletRepository)
    {
        $this->historyWalletRepository = $historyWalletRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function readAll()
    {
        $user = $this->getUser();
        $this->historyWalletRepository->readAll([['user_id', $user->id]]);
        return response([
            'success' => true
        ]);
    }
}
