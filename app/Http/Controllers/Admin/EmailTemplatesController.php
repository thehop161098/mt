<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EmailTemplate\BulkDestroyEmailTemplate;
use App\Http\Requests\Admin\EmailTemplate\DestroyEmailTemplate;
use App\Http\Requests\Admin\EmailTemplate\IndexEmailTemplate;
use App\Http\Requests\Admin\EmailTemplate\StoreEmailTemplate;
use App\Http\Requests\Admin\EmailTemplate\UpdateEmailTemplate;
use App\Models\EmailTemplate;
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

class EmailTemplatesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexEmailTemplate $request
     * @return array|Factory|View
     */
    public function index(IndexEmailTemplate $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(EmailTemplate::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'code', 'subject'],

            // set columns to searchIn
            ['code', 'subject', 'content']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.email-template.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.email-template.create');

        return view('admin.email-template.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreEmailTemplate $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreEmailTemplate $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the EmailTemplate
        $emailTemplate = EmailTemplate::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/email-templates'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/email-templates');
    }

    /**
     * Display the specified resource.
     *
     * @param EmailTemplate $emailTemplate
     * @throws AuthorizationException
     * @return void
     */
    public function show(EmailTemplate $emailTemplate)
    {
        $this->authorize('admin.email-template.show', $emailTemplate);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param EmailTemplate $emailTemplate
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(EmailTemplate $emailTemplate)
    {
        $this->authorize('admin.email-template.edit', $emailTemplate);


        return view('admin.email-template.edit', [
            'emailTemplate' => $emailTemplate,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateEmailTemplate $request
     * @param EmailTemplate $emailTemplate
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateEmailTemplate $request, EmailTemplate $emailTemplate)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values EmailTemplate
        $emailTemplate->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/email-templates'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/email-templates');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyEmailTemplate $request
     * @param EmailTemplate $emailTemplate
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyEmailTemplate $request, EmailTemplate $emailTemplate)
    {
        $emailTemplate->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyEmailTemplate $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyEmailTemplate $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    EmailTemplate::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
