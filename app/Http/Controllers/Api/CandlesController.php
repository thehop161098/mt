<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Core\Repositories\Contracts\CandleInterface;
use Core\Repositories\Contracts\NewCandleInterface;
use Core\Traits\RedisUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandlesController extends Controller
{
    use RedisUser;

    private $candleRepository;
    private $newCandleRepository;

    public function __construct(CandleInterface $candleRepository, NewCandleInterface $newCandleRepository)
    {
        $this->candleRepository = $candleRepository;
        $this->newCandleRepository = $newCandleRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $limit = 20;
        $candles = $this->candleRepository->find('bitcoin', $limit);
        $newCandle = $this->newCandleRepository->where('bitcoin');
        $user = RedisUser::getUser(Auth::user());

        return response([
            'candles' => $candles, 'newCandle' => $newCandle, 'limit' => $limit, "orderEventName" => "order.$user->id"
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCirCleHistory()
    {
        $circleHistory = $this->candleRepository->findCircleHistory('bitcoin', 100);
        return response(['circleHistory' => $circleHistory]);
    }
}
