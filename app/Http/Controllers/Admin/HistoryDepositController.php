<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HistoryDeposit\IndexHistoryDeposit;
use App\Models\HistoryWallet;
use App\Models\User;
use Brackets\AdminListing\Facades\AdminListing;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class HistoryDepositController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexHistoryDeposit $request
     * @return array|Factory|View
     */
    public function index(IndexHistoryDeposit $request)
    {
//        $arrUserId = [];
//        if ($request->has('search')) {
//            $users = User::select('id')->where('email', 'like', '%' . $request->search . '%')->get();
//            if (!empty($users)) {
//                foreach ($users as $user) {
//                    $arrUserId[] = $user['id'];
//                }
//            }
//        }
        if (empty($request->orderBy) || $request->orderBy === 'id') {
            $request->merge(['orderBy' => 'created_at', 'orderDirection' => 'desc']);
        }

        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(HistoryWallet::class)
            ->processRequestAndGet(
            // pass the request with params
                $request,

                // set columns to query
                ['id', 'user_id', 'amount', 'created_at', 'note'],

                // set columns to searchIn
                ['id', 'user_id'],

                function ($query) use ($request) {
                    $query->where('type', config('constants.type_history.cron_deposit'));
//                if ($request->has('search')) {
//                    $query->whereIn('user_id', $arrUserId);
//                }
                    $query->with(['user']);
                }
            );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.history-deposit.index', ['data' => $data]);
    }
}
