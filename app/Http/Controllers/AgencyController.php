<?php

namespace App\Http\Controllers;

use Core\Functions\TreeUser\Services\TreeUserService;
use Core\Services\AgencyService;
use Core\Traits\RedisUser;
use Illuminate\Support\Facades\Cache;

class AgencyController extends Controller
{
    use RedisUser;

    private $agencyService;
    private $treeUserService;

    public function __construct(AgencyService $agencyService, TreeUserService $treeUserService)
    {
        $this->agencyService = $agencyService;
        $this->treeUserService = $treeUserService;
    }

    public function index()
    {
        $amount = 5;
        $user = $this->getUser();
        $is_buy_agency = $user->level === 0 ? true : false;
        $tree = $this->treeUserService->show($user);
        $totalChildTrade = $tree['totalChildTrade'] ?? 0;
        return view('frontend.agency.index', compact('amount', 'is_buy_agency', 'user', 'tree', 'totalChildTrade'));
    }

    public function buyAgency()
    {
        $data = $this->agencyService->buyAgency();
        if ($data['success'] == true && $data['code'] && $data['referralCode']) {
            return response()->json([
                'success' => $data['success'],
                'code' => $data['code'],
                'referralCode' => $data['referralCode'],
                'level' => $data['level'],
                'message' => 'Buy affiliate marketing rights successful!'
            ], 200);
        } else {
            return response()->json([
                'success' => $data['success'],
                'message' => $data['message']
            ], 200);
        }
    }
}
