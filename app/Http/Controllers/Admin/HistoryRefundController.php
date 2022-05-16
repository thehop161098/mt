<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HistoryRefund\BulkDestroyHistoryRefund;
use App\Http\Requests\Admin\HistoryRefund\DestroyHistoryRefund;
use App\Http\Requests\Admin\HistoryRefund\IndexHistoryRefund;
use App\Http\Requests\Admin\HistoryRefund\StoreHistoryRefund;
use App\Http\Requests\Admin\HistoryRefund\UpdateHistoryRefund;
use App\Models\HistoryWallet;
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

class HistoryRefundController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexHistoryRefund $request
     * @return array|Factory|View
     */
    public function index(IndexHistoryRefund $request)
    {
        if (empty($request->orderBy) || $request->orderBy === 'id') {
            $request->merge(['orderBy' => 'created_at', 'orderDirection' => 'desc']);
        }

        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(HistoryWallet::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'user_id', 'amount', 'created_at', 'note'],

            // set columns to searchIn
            ['id', 'user_id'],

            function ($query) use ($request) {
                $query->where('type', config('constants.type_history.refund'));
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

        return view('admin.history-refund.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.history-refund.create');

        return view('admin.history-refund.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreHistoryRefund $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreHistoryRefund $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the HistoryRefund
        $historyRefund = HistoryRefund::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/history-refunds'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/history-refunds');
    }

    /**
     * Display the specified resource.
     *
     * @param HistoryRefund $historyRefund
     * @throws AuthorizationException
     * @return void
     */
    public function show(HistoryRefund $historyRefund)
    {
        $this->authorize('admin.history-refund.show', $historyRefund);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param HistoryRefund $historyRefund
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(HistoryRefund $historyRefund)
    {
        $this->authorize('admin.history-refund.edit', $historyRefund);


        return view('admin.history-refund.edit', [
            'historyRefund' => $historyRefund,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateHistoryRefund $request
     * @param HistoryRefund $historyRefund
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateHistoryRefund $request, HistoryRefund $historyRefund)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values HistoryRefund
        $historyRefund->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/history-refunds'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/history-refunds');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyHistoryRefund $request
     * @param HistoryRefund $historyRefund
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyHistoryRefund $request, HistoryRefund $historyRefund)
    {
        $historyRefund->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyHistoryRefund $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyHistoryRefund $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    HistoryRefund::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
