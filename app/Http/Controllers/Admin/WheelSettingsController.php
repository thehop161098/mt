<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WheelSetting\BulkDestroyWheelSetting;
use App\Http\Requests\Admin\WheelSetting\DestroyWheelSetting;
use App\Http\Requests\Admin\WheelSetting\IndexWheelSetting;
use App\Http\Requests\Admin\WheelSetting\StoreWheelSetting;
use App\Http\Requests\Admin\WheelSetting\UpdateWheelSetting;
use App\Models\WheelSetting;
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

class WheelSettingsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexWheelSetting $request
     * @return array|Factory|View
     */
    public function index(IndexWheelSetting $request)
    {
        if (empty($request->orderBy) || $request->orderBy === 'id') {
            $request->merge(['orderBy' => 'amount', 'orderDirection' => 'asc']);
        }

        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(WheelSetting::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'amount', 'prize'],

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

        return view('admin.wheel-setting.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.wheel-setting.create');

        return view('admin.wheel-setting.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreWheelSetting $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreWheelSetting $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the WheelSetting
        $wheelSetting = WheelSetting::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/wheel-settings'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/wheel-settings');
    }

    /**
     * Display the specified resource.
     *
     * @param WheelSetting $wheelSetting
     * @throws AuthorizationException
     * @return void
     */
    public function show(WheelSetting $wheelSetting)
    {
        $this->authorize('admin.wheel-setting.show', $wheelSetting);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param WheelSetting $wheelSetting
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(WheelSetting $wheelSetting)
    {
        $this->authorize('admin.wheel-setting.edit', $wheelSetting);


        return view('admin.wheel-setting.edit', [
            'wheelSetting' => $wheelSetting,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateWheelSetting $request
     * @param WheelSetting $wheelSetting
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateWheelSetting $request, WheelSetting $wheelSetting)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values WheelSetting
        $wheelSetting->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/wheel-settings'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/wheel-settings');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyWheelSetting $request
     * @param WheelSetting $wheelSetting
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyWheelSetting $request, WheelSetting $wheelSetting)
    {
        $wheelSetting->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyWheelSetting $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyWheelSetting $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    WheelSetting::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
