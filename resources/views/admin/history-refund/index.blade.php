@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.history-refund.actions.index'))

@section('body')

    <history-refund-listing
        :data="{{ $data->toJson() }}"
        :url="'{{ url('admin/history-refunds') }}'"
        inline-template>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> {{ trans('admin.history-refund.actions.index') }}
                    </div>
                    <div class="card-body" v-cloak>
                        <div class="card-block">
                            <form @submit.prevent="">
                                <div class="row justify-content-md-between">
                                    <div class="col col-lg-7 col-xl-5 form-group">
{{--                                        <div class="input-group">--}}
{{--                                            <input class="form-control"--}}
{{--                                                   placeholder="{{ trans('brackets/admin-ui::admin.placeholder.search') }}"--}}
{{--                                                   v-model="search"--}}
{{--                                                   @keyup.enter="filter('search', $event.target.value)"/>--}}
{{--                                            <span class="input-group-append">--}}
{{--                                                <button type="button" class="btn btn-primary"--}}
{{--                                                        @click="filter('search', search)"><i class="fa fa-search"></i>&nbsp; {{ trans('brackets/admin-ui::admin.btn.search') }}</button>--}}
{{--                                            </span>--}}
{{--                                        </div>--}}
                                    </div>
                                    <div class="col-sm-auto form-group ">
                                        <select class="form-control" v-model="pagination.state.per_page">

                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                </div>
                            </form>

                            <table class="table table-hover table-listing">
                                <thead>
                                <tr>
                                    <th>Email
                                    </th>
                                    <th is='sortable'
                                        :column="'amount'">Amount
                                    </th>
                                    <th is='sortable'
                                        :column="'note'">Note
                                    </th>
                                    <th is='sortable'
                                        :column="'created_at'">Time
                                    </th>
                                    <th></th>
                                </tr>
                                <tr v-show="(clickedBulkItemsCount > 0) || isClickedAll">
                                    <td class="bg-bulk-info d-table-cell text-center" colspan="2">
                                            <span class="align-middle font-weight-light text-dark">{{ trans('brackets/admin-ui::admin.listing.selected_items') }} @{{ clickedBulkItemsCount }}.  <a
                                                    href="#" class="text-primary"
                                                    @click="onBulkItemsClickedAll('/admin/history-refunds')"
                                                    v-if="(clickedBulkItemsCount < pagination.state.total)"> <i
                                                        class="fa"
                                                        :class="bulkCheckingAllLoader ? 'fa-spinner' : ''"></i> {{ trans('brackets/admin-ui::admin.listing.check_all_items') }} @{{ pagination.state.total }}</a> <span
                                                    class="text-primary">|</span> <a
                                                    href="#" class="text-primary"
                                                    @click="onBulkItemsClickedAllUncheck()">{{ trans('brackets/admin-ui::admin.listing.uncheck_all_items') }}</a>  </span>

                                        <span class="pull-right pr-2">
                                                <button class="btn btn-sm btn-danger pr-3 pl-3"
                                                        @click="bulkDelete('/admin/history-refunds/bulk-destroy')">{{ trans('brackets/admin-ui::admin.btn.delete') }}</button>
                                            </span>

                                    </td>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(item, index) in collection" :key="item.id"
                                    :class="bulkItems[item.id] ? 'bg-bulk' : ''">
                                    <td>@{{ item.user.email }}</td>
                                    <td>$@{{ item.amount }}</td>
                                    <td>@{{ item.note }}</td>
                                    <td>@{{ item.created_at | formatDate }}</td>
                                    <td></td>
                                </tr>
                                </tbody>
                                </tbody>
                            </table>

                            <div class="row" v-if="pagination.state.total > 0">
                                <div class="col-sm">
                                    <span
                                        class="pagination-caption">{{ trans('brackets/admin-ui::admin.pagination.overview') }}</span>
                                </div>
                                <div class="col-sm-auto">
                                    <pagination></pagination>
                                </div>
                            </div>

                            <div class="no-items-found" v-if="!collection.length > 0">
                                <i class="icon-magnifier"></i>
                                <h3>{{ trans('brackets/admin-ui::admin.index.no_items') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </history-refund-listing>

@endsection
