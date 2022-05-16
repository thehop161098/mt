<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Coin\BulkDestroyCoin;
use App\Http\Requests\Admin\Coin\DestroyCoin;
use App\Http\Requests\Admin\Coin\IndexCoin;
use App\Http\Requests\Admin\Coin\StoreCoin;
use App\Http\Requests\Admin\Coin\UpdateCoin;
use App\Models\Coin;
use Brackets\AdminListing\Facades\AdminListing;
use Core\Repositories\Contracts\CoinInterface;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CoinsController extends Controller
{
    private $coinRepository;

    public function __construct(CoinInterface $coinRepository)
    {
        $this->coinRepository = $coinRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @param IndexCoin $request
     * @return array|Factory|View
     */
    public function index(IndexCoin $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Coin::class)->processRequestAndGet(
        // pass the request with params
            $request,

            // set columns to query
            ['id', 'name', 'image', 'alias', 'range', 'min', 'max', 'publish'],

            // set columns to searchIn
            ['id', 'name', 'image', 'alias']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.coin.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Factory|View
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('admin.coin.create');

        return view('admin.coin.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCoin $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreCoin $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();
        $sanitized['is_gold'] = $request->is_gold;
        $sanitized['publish'] = $request->publish;

        // Store the Coin
        $coin = Coin::create($sanitized);
        $this->coinRepository->update();

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/coins'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded')
            ];
        }

        return redirect('admin/coins');
    }

    /**
     * Display the specified resource.
     *
     * @param Coin $coin
     * @return void
     * @throws AuthorizationException
     */
    public function show(Coin $coin)
    {
        $this->authorize('admin.coin.show', $coin);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Coin $coin
     * @return Factory|View
     * @throws AuthorizationException
     */
    public function edit(Coin $coin)
    {
        $this->authorize('admin.coin.edit', $coin);


        return view('admin.coin.edit', [
            'coin' => $coin,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCoin $request
     * @param Coin $coin
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateCoin $request, Coin $coin)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();
        $sanitized['is_gold'] = $request->is_gold;
        $sanitized['publish'] = $request->publish;

        // Update changed values Coin
        $coin->update($sanitized);
        $this->coinRepository->update();

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/coins'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/coins');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyCoin $request
     * @param Coin $coin
     * @return ResponseFactory|RedirectResponse|Response
     * @throws Exception
     */
    public function destroy(DestroyCoin $request, Coin $coin)
    {
        $coin->delete();
        $this->coinRepository->update();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyCoin $request
     * @return Response|bool
     * @throws Exception
     */
    public function bulkDestroy(BulkDestroyCoin $request): Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Coin::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });
        $this->coinRepository->update();

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
