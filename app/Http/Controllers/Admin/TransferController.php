<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Core\Repositories\Contracts\HistoryWalletInterface;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    private $transferRepository;

    public function __construct(HistoryWalletInterface $transferRepository)
    {
        $this->transferRepository = $transferRepository;
    }

    public function index(Request $request)
    {
        $conds = ['type' => config('constants.type_history.internal_transfer')];
        $condsRelation = [];
        if ($request->search) {
            $condsRelation = [['email', 'like', '%' . $request->search . '%']];
        }
        $transfers = $this->transferRepository->findAll($conds, $condsRelation);
        return view("admin.transfer.index", compact('transfers'));
    }
}
