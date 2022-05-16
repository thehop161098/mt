<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PhoneCountry\BulkDestroyPhoneCountry;
use App\Http\Requests\Admin\PhoneCountry\DestroyPhoneCountry;
use App\Http\Requests\Admin\PhoneCountry\IndexPhoneCountry;
use App\Http\Requests\Admin\PhoneCountry\StorePhoneCountry;
use App\Http\Requests\Admin\PhoneCountry\UpdatePhoneCountry;
use App\Models\PhoneCountry;
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

class PhoneCountriesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexPhoneCountry $request
     * @return array|Factory|View
     */
    public function index(IndexPhoneCountry $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(PhoneCountry::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'name'],

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

        return view('admin.phone-country.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.phone-country.create');

        return view('admin.phone-country.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePhoneCountry $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StorePhoneCountry $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the PhoneCountry
        $phoneCountry = PhoneCountry::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/phone-countries'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/phone-countries');
    }

    /**
     * Display the specified resource.
     *
     * @param PhoneCountry $phoneCountry
     * @throws AuthorizationException
     * @return void
     */
    public function show(PhoneCountry $phoneCountry)
    {
        $this->authorize('admin.phone-country.show', $phoneCountry);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param PhoneCountry $phoneCountry
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(PhoneCountry $phoneCountry)
    {
        $this->authorize('admin.phone-country.edit', $phoneCountry);


        return view('admin.phone-country.edit', [
            'phoneCountry' => $phoneCountry,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePhoneCountry $request
     * @param PhoneCountry $phoneCountry
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdatePhoneCountry $request, PhoneCountry $phoneCountry)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values PhoneCountry
        $phoneCountry->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/phone-countries'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/phone-countries');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyPhoneCountry $request
     * @param PhoneCountry $phoneCountry
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyPhoneCountry $request, PhoneCountry $phoneCountry)
    {
        $phoneCountry->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyPhoneCountry $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyPhoneCountry $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    PhoneCountry::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
