<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HistoryBot\BulkDestroyHistoryBot;
use App\Http\Requests\Admin\HistoryBot\DestroyHistoryBot;
use App\Http\Requests\Admin\HistoryBot\IndexHistoryBot;
use App\Http\Requests\Admin\HistoryBot\StoreHistoryBot;
use App\Http\Requests\Admin\HistoryBot\UpdateHistoryBot;
use App\Models\HistoryBot;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HistoryBotsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexHistoryBot $request
     * @return array|Factory|View
     */
    public function index(IndexHistoryBot $request)
    {
        if (empty($request->orderBy) || $request->orderBy === 'id') {
            $request->merge(['orderBy' => 'status', 'orderDirection' => 'desc']);
        }

        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(HistoryBot::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'user_id', 'bot_id', 'time', 'time_expired', 'status'],

            // set columns to searchIn
            ['id', 'time_expired'],
            function ($query) use ($request) {
                $query->with(['user', 'bot']);
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

        return view('admin.history-bot.index', ['data' => $data]);
    }
}
