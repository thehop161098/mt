<?php

namespace Core\Repositories\Eloquents;

use App\Models\HistoryWithdraw;
use Core\Repositories\Contracts\HistoryWithdrawInterface;

class HistoryWithdrawRepository implements HistoryWithdrawInterface
{

    private $historyWithdraw;

    public function __construct(HistoryWithdraw $historyWithdraw)
    {
        $this->historyWithdraw = $historyWithdraw;
    }

    public function list($where)
    {
        return HistoryWithdraw::where($where)->get();
    }

    public function find($id)
    {
        return HistoryWithdraw::find($id);
    }

    public function first($where)
    {
        return HistoryWithdraw::where($where)->first();
    }

    public function create($data)
    {
        return $this->historyWithdraw->create($data);
    }

    public function update($where, $data)
    {
        return HistoryWithdraw::where($where)->update($data);
    }

    public function findAll($where)
    {
        return $this->historyWithdraw
            ->where($where)
            ->whereNotIn('status', [
                config('constants.status_withdraw.temp'),
                config('constants.status_withdraw.expired'),
            ])->orderBy('created_at',
                'desc')
            ->paginate(config('constants.PAGINATE_50'));
    }

    public function where($where)
    {
        return HistoryWithdraw::where($where);
    }

    public function getWithdrawalMainNewest($where)
    {
        return $this->historyWithdraw
            ->where($where)
            ->whereNotIn('status', [
                config('constants.status_withdraw.reject'),
                config('constants.status_withdraw.expired'),
            ])->latest()->first();
    }
}
