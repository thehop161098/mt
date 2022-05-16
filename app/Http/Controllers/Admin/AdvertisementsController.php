<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Advertisement\BulkDestroyAdvertisement;
use App\Http\Requests\Admin\Advertisement\DestroyAdvertisement;
use App\Http\Requests\Admin\Advertisement\IndexAdvertisement;
use App\Http\Requests\Admin\Advertisement\StoreAdvertisement;
use App\Http\Requests\Admin\Advertisement\UpdateAdvertisement;
use App\Models\Advertisement;
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

class AdvertisementsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexAdvertisement $request
     * @return array|Factory|View
     */
    public function index(IndexAdvertisement $request)
    {
        if (empty($request->orderBy) || $request->orderBy === 'id') {
            $request->merge(['orderBy' => 'sort', 'orderDirection' => 'asc']);
        }

        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Advertisement::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'name', 'sort', 'publish'],

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

        return view('admin.advertisement.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.advertisement.create');

        return view('admin.advertisement.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAdvertisement $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreAdvertisement $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the Advertisement
        $advertisement = Advertisement::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/advertisements'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/advertisements');
    }

    /**
     * Display the specified resource.
     *
     * @param Advertisement $advertisement
     * @throws AuthorizationException
     * @return void
     */
    public function show(Advertisement $advertisement)
    {
        $this->authorize('admin.advertisement.show', $advertisement);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Advertisement $advertisement
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Advertisement $advertisement)
    {
        $this->authorize('admin.advertisement.edit', $advertisement);


        return view('admin.advertisement.edit', [
            'advertisement' => $advertisement,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAdvertisement $request
     * @param Advertisement $advertisement
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateAdvertisement $request, Advertisement $advertisement)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values Advertisement
        $advertisement->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/advertisements'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/advertisements');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyAdvertisement $request
     * @param Advertisement $advertisement
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyAdvertisement $request, Advertisement $advertisement)
    {
        $advertisement->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyAdvertisement $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyAdvertisement $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Advertisement::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
