<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Wheel\BulkDestroyWheel;
use App\Http\Requests\Admin\Wheel\DestroyWheel;
use App\Http\Requests\Admin\Wheel\IndexWheel;
use App\Http\Requests\Admin\Wheel\StoreWheel;
use App\Http\Requests\Admin\Wheel\UpdateWheel;
use App\Models\Wheel;
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

class WheelsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexWheel $request
     * @return array|Factory|View
     */
    public function index(IndexWheel $request)
    {
        if (empty($request->orderBy) || $request->orderBy === 'id') {
            $request->merge(['orderBy' => 'sort', 'orderDirection' => 'asc']);
        }

        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Wheel::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'name', 'prize', 'sort'],

            // set columns to searchIn
            ['id', 'name']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.wheel.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.wheel.create');

        return view('admin.wheel.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreWheel $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreWheel $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the Wheel
        $wheel = Wheel::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/wheels'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/wheels');
    }

    /**
     * Display the specified resource.
     *
     * @param Wheel $wheel
     * @throws AuthorizationException
     * @return void
     */
    public function show(Wheel $wheel)
    {
        $this->authorize('admin.wheel.show', $wheel);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Wheel $wheel
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Wheel $wheel)
    {
        $this->authorize('admin.wheel.edit', $wheel);


        return view('admin.wheel.edit', [
            'wheel' => $wheel,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateWheel $request
     * @param Wheel $wheel
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateWheel $request, Wheel $wheel)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values Wheel
        $wheel->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/wheels'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/wheels');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyWheel $request
     * @param Wheel $wheel
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyWheel $request, Wheel $wheel)
    {
        $wheel->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyWheel $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyWheel $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Wheel::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
