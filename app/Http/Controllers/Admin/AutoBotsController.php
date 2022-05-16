<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AutoBot\BulkDestroyAutoBot;
use App\Http\Requests\Admin\AutoBot\DestroyAutoBot;
use App\Http\Requests\Admin\AutoBot\IndexAutoBot;
use App\Http\Requests\Admin\AutoBot\StoreAutoBot;
use App\Http\Requests\Admin\AutoBot\UpdateAutoBot;
use App\Models\AutoBot;
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

class AutoBotsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexAutoBot $request
     * @return array|Factory|View
     */
    public function index(IndexAutoBot $request)
    {
        if (empty($request->orderBy) || $request->orderBy === 'id') {
            $request->merge(['orderBy' => 'price', 'orderDirection' => 'desc']);
        }

        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(AutoBot::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'price', 'commission_7', 'commission_21', 'commission_30', 'commission_90', 'name', 'commission_f1', 'risk'],

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

        return view('admin.auto-bot.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.auto-bot.create');

        return view('admin.auto-bot.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreAutoBot $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreAutoBot $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the AutoBot
        $autoBot = AutoBot::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/auto-bots'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/auto-bots');
    }

    /**
     * Display the specified resource.
     *
     * @param AutoBot $autoBot
     * @throws AuthorizationException
     * @return void
     */
    public function show(AutoBot $autoBot)
    {
        $this->authorize('admin.auto-bot.show', $autoBot);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param AutoBot $autoBot
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(AutoBot $autoBot)
    {
        $this->authorize('admin.auto-bot.edit', $autoBot);


        return view('admin.auto-bot.edit', [
            'autoBot' => $autoBot,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAutoBot $request
     * @param AutoBot $autoBot
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateAutoBot $request, AutoBot $autoBot)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values AutoBot
        $autoBot->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/auto-bots'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/auto-bots');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyAutoBot $request
     * @param AutoBot $autoBot
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyAutoBot $request, AutoBot $autoBot)
    {
        $autoBot->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyAutoBot $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyAutoBot $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    AutoBot::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
