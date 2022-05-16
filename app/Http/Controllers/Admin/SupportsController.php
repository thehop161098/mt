<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Support\BulkDestroySupport;
use App\Http\Requests\Admin\Support\DestroySupport;
use App\Http\Requests\Admin\Support\IndexSupport;
use App\Http\Requests\Admin\Support\StoreSupport;
use App\Http\Requests\Admin\Support\UpdateSupport;
use App\Models\Support;
use Brackets\AdminListing\Facades\AdminListing;
use Core\Factories\NotificationFactory;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SupportsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexSupport $request
     * @return array|Factory|View
     */
    public function index(IndexSupport $request)
    {
        if (empty($request->orderBy) || $request->orderBy === 'id') {
            $request->merge(['orderBy' => 'created_at', 'orderDirection' => 'desc']);
        }

        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Support::class)->processRequestAndGet(
        // pass the request with params
            $request,

            // set columns to query
            ['id', 'full_name', 'email', 'phone', 'status'],

            // set columns to searchIn
            ['id', 'full_name', 'email', 'phone', 'content', 'response']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.support.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|View
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('admin.support.create');

        return view('admin.support.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSupport $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreSupport $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the Support
        $support = Support::create($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/supports'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded')
            ];
        }

        return redirect('admin/supports');
    }

    /**
     * Display the specified resource.
     *
     * @param Support $support
     * @return void
     * @throws AuthorizationException
     */
    public function show(Support $support)
    {
        $this->authorize('admin.support.show', $support);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Support $support
     * @return Factory|View
     * @throws AuthorizationException
     */
    public function edit(Support $support)
    {
        $this->authorize('admin.support.edit', $support);


        return view('admin.support.edit', [
            'support' => $support,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSupport $request
     * @param Support $support
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateSupport $request, Support $support)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values Support
        $support->update($sanitized);

        $notify = new NotificationFactory();
        $notify->produceNotification(config('constants.email.supported'), [
            'email' => $sanitized['email'],
            'full_name' => $sanitized['full_name'],
        ]);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/supports'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/supports');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroySupport $request
     * @param Support $support
     * @return ResponseFactory|RedirectResponse|Response
     * @throws Exception
     */
    public function destroy(DestroySupport $request, Support $support)
    {
        $support->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroySupport $request
     * @return Response|bool
     * @throws Exception
     */
    public function bulkDestroy(BulkDestroySupport $request): Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Support::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
