<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Discount\BulkDestroyDiscount;
use App\Http\Requests\Admin\Discount\DestroyDiscount;
use App\Http\Requests\Admin\Discount\IndexDiscount;
use App\Http\Requests\Admin\Discount\StoreDiscount;
use App\Http\Requests\Admin\Discount\UpdateDiscount;
use App\Models\Discount;
use Brackets\AdminListing\Facades\AdminListing;
use Core\Repositories\Eloquents\DiscountRepository;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DiscountsController extends Controller
{
    private $discountRepository;

    public function __construct(DiscountRepository $discountRepository)
    {
        $this->discountRepository = $discountRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @param IndexDiscount $request
     * @return array|Factory|View
     */
    public function index(IndexDiscount $request)
    {
        if (empty($request->orderBy) || $request->orderBy === 'id') {
            $request->merge(['orderBy' => 'created_at', 'orderDirection' => 'desc']);
        }

        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Discount::class)->processRequestAndGet(
        // pass the request with params
            $request,

            // set columns to query
            ['id', 'name', 'date_show_image', 'deposit', 'discount', 'from_date', 'to_date'],

            // set columns to searchIn
            ['id', 'name', 'date_show_image', 'from_date', 'to_date'],

            function ($query) use ($request) {
                $query->with(['history']);

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

        return view('admin.discount.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|View
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('admin.discount.create');

        return view('admin.discount.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreDiscount $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreDiscount $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the Discount
        $discount = Discount::create($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/discounts'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded')
            ];
        }

        return redirect('admin/discounts');
    }

    /**
     * Display the specified resource.
     *
     * @param Discount $discount
     * @return void
     * @throws AuthorizationException
     */
    public function show(Discount $discount)
    {
        $this->authorize('admin.discount.show', $discount);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Discount $discount
     * @return Factory|View
     * @throws AuthorizationException
     */
    public function edit(Discount $discount)
    {
        if (!$discount->history->isEmpty()) {
            return redirect('admin/discounts');
        }

        $this->authorize('admin.discount.edit', $discount);

        return view('admin.discount.edit', [
            'discount' => $discount,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateDiscount $request
     * @param Discount $discount
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateDiscount $request, Discount $discount)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values Discount
        $discount->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/discounts'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/discounts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyDiscount $request
     * @param Discount $discount
     * @return ResponseFactory|RedirectResponse|Response
     * @throws Exception
     */
    public function destroy(DestroyDiscount $request, Discount $discount)
    {
        if (!$discount->history->isEmpty()) {
            return redirect()->back();
        }
        $discount->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyDiscount $request
     * @return Response|bool
     * @throws Exception
     */
    public function bulkDestroy(BulkDestroyDiscount $request): Response
    {
        $arrAllowDelete = $this->discountRepository->checkArrDelete($request->data['ids']);
        DB::transaction(static function () use ($arrAllowDelete) {
            collect($arrAllowDelete)
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Discount::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
