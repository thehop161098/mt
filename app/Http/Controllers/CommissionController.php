<?php

namespace App\Http\Controllers;

use Core\Repositories\Contracts\HistoryWalletInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommissionController extends Controller
{
    public function __construct(
        HistoryWalletInterface $commissionRepository
    ) {
        $this->commissionRepository = $commissionRepository;

    }

    public function daily(Request $request)
    {
        $commissions = $this->commissionRepository->searchCommissions($request,
            config('constants.type_history.commission_daily'), Auth::user()->id);
        $this->commissionRepository->readAll([
            ['user_id', Auth::user()->id],
            ['type', config('constants.type_history.commission_daily')]
        ]);
        return view('frontend.commission.daily', compact('commissions'));
    }

    public function agency(Request $request)
    {
        $commissions = $this->commissionRepository->searchCommissions($request,
            config('constants.type_history.commission_agency_parent'), Auth::user()->id);
        $this->commissionRepository->readAll([
            ['user_id', Auth::user()->id],
            ['type', config('constants.type_history.commission_agency_parent')]
        ]);
        return view('frontend.commission.agency', compact('commissions'));
    }

    public function masterib(Request $request)
    {
        $commissions = $this->commissionRepository->searchCommissions($request,
            config('constants.type_history.commission_level'), Auth::user()->id);
        $this->commissionRepository->readAll([
            ['user_id', Auth::user()->id],
            ['type', config('constants.type_history.commission_level')]
        ]);
        return view('frontend.commission.masterib', compact('commissions'));
    }

    public function imcome(Request $request)
    {
        $commissions = $this->commissionRepository->searchCommissions($request,
            config('constants.type_history.commission_from_child'), Auth::user()->id);
        $this->commissionRepository->readAll([
            ['user_id', Auth::user()->id],
            ['type', config('constants.type_history.commission_from_child')]
        ]);
        return view('frontend.commission.imcome', compact('commissions'));
    }

    public function bot(Request $request)
    {
        $commissions = $this->commissionRepository->searchCommissions($request,
            config('constants.type_history.commission_bot'), Auth::user()->id);
        $this->commissionRepository->readAll([
            ['user_id', Auth::user()->id],
            ['type', config('constants.type_history.commission_bot')]
        ]);
        $this->commissionRepository->readAll([
            ['user_id', Auth::user()->id],
            ['type', config('constants.type_history.commission_bot_daily')]
        ]);
        $this->commissionRepository->readAll([
            ['user_id', Auth::user()->id],
            ['type', config('constants.type_history.commission_bot_daily_parent')]
        ]);
        return view('frontend.commission.bot', compact('commissions'));
    }
}
