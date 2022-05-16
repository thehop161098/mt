<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Core\Repositories\Contracts\CandleInterface;
use Core\Repositories\Contracts\CoinInterface;
use Core\Repositories\Contracts\NewCandleInterface;
use Core\Traits\RedisUser;
use Illuminate\Http\Request;

class CandlesController extends Controller
{
    use RedisUser;

    private $candleRepository;
    private $newCandleRepository;
    private $coinRepository;
    private $limit = 89;
    private $limit_mobile = 39;

    public function __construct(
        CandleInterface $candleRepository,
        NewCandleInterface $newCandleRepository,
        CoinInterface $coinRepository
    ) {
        $this->candleRepository = $candleRepository;
        $this->newCandleRepository = $newCandleRepository;
        $this->coinRepository = $coinRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = $this->getUser();
        return response([
            'limit' => $this->limit,
            'limit_mobile' => $this->limit_mobile,
            "orderEventName" => "order.$user->id",
            "orderResultEventName" => "order.result.$user->id"
        ]);
    }

    public function getCandles(Request $request, $coin)
    {
        if (empty($coin) || $coin === "null") {
            $coinSelected = $this->coinRepository->getCoinSelected();
            if (empty($coinSelected)) {
                return response(['message' => 'Cannot get coin to find candles'], 421);
            }
            $coin = $coinSelected->name;
        } else {
            $coin = str_replace('.', '/', $coin);
        }

        $candles = $this->candleRepository->find($coin, $this->limit);
        $newCandle = $this->newCandleRepository->where($coin);
        $arrEma = $this->candleRepository->getArrayEma($coin);

        return response([
            'candles' => $candles,
            'newCandles' => [$coin => $newCandle],
            'arrEma' => $arrEma
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCirCleHistory(Request $request, $coin)
    {
        if (empty($coin) || $coin === "null") {
            return response(['circleHistory' => []]);
        }
        $coin = str_replace('.', '/', $coin);
        $circleHistory = $this->candleRepository->findCircleHistory($coin, 100);
        return response(['circleHistory' => $circleHistory]);
    }
}
