<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Level\BulkDestroyLevel;
use App\Http\Requests\Admin\Level\DestroyLevel;
use App\Http\Requests\Admin\Level\IndexLevel;
use App\Http\Requests\Admin\Level\StoreLevel;
use App\Http\Requests\Admin\Level\UpdateLevel;
use App\Models\Level;
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

class LevelsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexLevel $request
     * @return array|Factory|View
     */
    public function index(IndexLevel $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Level::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'name', 'amount', 'commission_f1', 'total_f1', 'total_trade', 'master_ib'],

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

        return view('admin.level.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.level.create');

        return view('admin.level.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreLevel $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreLevel $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the Level
        $level = Level::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/levels'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/levels');
    }

    /**
     * Display the specified resource.
     *
     * @param Level $level
     * @throws AuthorizationException
     * @return void
     */
    public function show(Level $level)
    {
        $this->authorize('admin.level.show', $level);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Level $level
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Level $level)
    {
        $this->authorize('admin.level.edit', $level);


        return view('admin.level.edit', [
            'level' => $level,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateLevel $request
     * @param Level $level
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateLevel $request, Level $level)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values Level
        $level->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/levels'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/levels');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyLevel $request
     * @param Level $level
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyLevel $request, Level $level)
    {
        $level->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyLevel $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyLevel $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Level::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
