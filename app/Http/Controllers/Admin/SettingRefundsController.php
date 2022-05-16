<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingRefund\BulkDestroySettingRefund;
use App\Http\Requests\Admin\SettingRefund\DestroySettingRefund;
use App\Http\Requests\Admin\SettingRefund\IndexSettingRefund;
use App\Http\Requests\Admin\SettingRefund\StoreSettingRefund;
use App\Http\Requests\Admin\SettingRefund\UpdateSettingRefund;
use App\Models\SettingRefund;
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

class SettingRefundsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexSettingRefund $request
     * @return array|Factory|View
     */
    public function index(IndexSettingRefund $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(SettingRefund::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'day', 'amount', 'percent'],

            // set columns to searchIn
            ['id']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.setting-refund.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.setting-refund.create');

        return view('admin.setting-refund.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSettingRefund $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreSettingRefund $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the SettingRefund
        $settingRefund = SettingRefund::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/setting-refunds'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/setting-refunds');
    }

    /**
     * Display the specified resource.
     *
     * @param SettingRefund $settingRefund
     * @throws AuthorizationException
     * @return void
     */
    public function show(SettingRefund $settingRefund)
    {
        $this->authorize('admin.setting-refund.show', $settingRefund);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param SettingRefund $settingRefund
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(SettingRefund $settingRefund)
    {
        $this->authorize('admin.setting-refund.edit', $settingRefund);


        return view('admin.setting-refund.edit', [
            'settingRefund' => $settingRefund,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSettingRefund $request
     * @param SettingRefund $settingRefund
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateSettingRefund $request, SettingRefund $settingRefund)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values SettingRefund
        $settingRefund->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/setting-refunds'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/setting-refunds');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroySettingRefund $request
     * @param SettingRefund $settingRefund
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroySettingRefund $request, SettingRefund $settingRefund)
    {
        $settingRefund->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroySettingRefund $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroySettingRefund $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    SettingRefund::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
