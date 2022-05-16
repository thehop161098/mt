@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.history-withdraw.actions.index'))

@section('body')

    <history-withdraw-listing
        :data="{{ $data->toJson() }}"
        :url="'{{ url('admin/history-withdraws') }}'"
        :status_reject="{{config('constants.status_withdraw.reject')}}"
        :status_approved="{{config('constants.status_withdraw.approved')}}"
        inline-template>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> {{ trans('admin.history-withdraw.actions.index') }}
                    </div>
                    <div class="card-body" v-cloak>
                        <div class="card-block">
                            <form @submit.prevent="">
                                <div class="row justify-content-md-between">
                                    <div class="col col-lg-7 col-xl-5 form-group">
                                        <div class="input-group">
                                            <input class="form-control"
                                                   placeholder="{{ trans('brackets/admin-ui::admin.placeholder.search') }}"
                                                   v-model="search"
                                                   @keyup.enter="filter('search', $event.target.value)"/>
                                            <span class="input-group-append">
                                                <button type="button" class="btn btn-primary"
                                                        @click="filter('search', search)"><i class="fa fa-search"></i>&nbsp; {{ trans('brackets/admin-ui::admin.btn.search') }}</button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col col-lg-3 col-xl-3 form-group">
                                        <div class="input-group">
                                            <select class="form-control" v-model="status">
                                                <option value="">---- Status ----</option>
                                                <option
                                                    value="{{config('constants.status_withdraw.pending')}}">{{config('constants.status_withdraw.pending_text')}}</option>
                                            </select>
                                        </div>
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
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th class="bulk-checkbox">
                                            <input class="form-check-input" id="enabled" type="checkbox"
                                                   v-model="isClickedAll" v-validate="''" data-vv-name="enabled"
                                                   name="enabled_fake_element"
                                                   @click="onBulkItemsClickedAllWithPagination()">
                                            <label class="form-check-label" for="enabled">
                                                #
                                            </label>
                                        </th>

                                        <th is='sortable'
                                            :column="'wallet'">Wallet</th>
                                        <th is='sortable'
                                            :column="'user_id'">{{ trans('admin.history-withdraw.columns.user_id') }}</th>
                                        <th is='sortable'
                                            :column="'coin'">{{ trans('admin.history-withdraw.columns.coin') }}</th>
                                        <th is='sortable'
                                            :column="'amount_fee'">{{ trans('admin.history-withdraw.columns.amount_fee') }}</th>
                                        <th is='sortable'
                                            :column="'amount'">{{ trans('admin.history-withdraw.columns.amount') }}</th>
                                        <th is='sortable'
                                            :column="'code'">{{ trans('admin.history-withdraw.columns.code') }}</th>
                                        <th is='sortable'
                                            :column="'status'">{{ trans('admin.history-withdraw.columns.status') }}</th>
                                        <th is='sortable'
                                            :column="'reason'">{{ trans('admin.history-withdraw.columns.reason') }}</th>
                                        <th is='sortable'
                                            :column="'tx_hash'">{{ trans('admin.history-withdraw.columns.tx_hash') }}</th>
                                        <th is='sortable'
                                            :column="'created_at'">Time
                                        </th>
                                        <th></th>
                                    </tr>
                                    <tr v-show="(clickedBulkItemsCount > 0) || isClickedAll">
                                        <td class="bg-bulk-info d-table-cell text-center" colspan="10">
                                                <span class="align-middle font-weight-light text-dark">{{ trans('brackets/admin-ui::admin.listing.selected_items') }} @{{ clickedBulkItemsCount }}.  <a
                                                        href="#" class="text-primary"
                                                        @click="onBulkItemsClickedAll('/admin/history-withdraws')"
                                                        v-if="(clickedBulkItemsCount < pagination.state.total)"> <i
                                                            class="fa"
                                                            :class="bulkCheckingAllLoader ? 'fa-spinner' : ''"></i> {{ trans('brackets/admin-ui::admin.listing.check_all_items') }} @{{ pagination.state.total }}</a> <span
                                                        class="text-primary">|</span> <a
                                                        href="#" class="text-primary"
                                                        @click="onBulkItemsClickedAllUncheck()">{{ trans('brackets/admin-ui::admin.listing.uncheck_all_items') }}</a>  </span>

                                            <span class="pull-right pr-2">
                                                    <button class="btn btn-sm btn-danger pr-3 pl-3"
                                                            @click="bulkDelete('/admin/history-withdraws/bulk-destroy')">{{ trans('brackets/admin-ui::admin.btn.delete') }}</button>
                                                </span>
                                        </td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(item, index) in collection" :key="item.id"
                                        :class="bulkItems[item.id] ? 'bg-bulk' : ''">
                                        <td class="bulk-checkbox">
                                            <input class="form-check-input" :id="'enabled' + item.id" type="checkbox"
                                                   v-model="bulkItems[item.id]" v-validate="''"
                                                   :data-vv-name="'enabled' + item.id"
                                                   :name="'enabled' + item.id + '_fake_element'"
                                                   @click="onBulkItemClicked(item.id)"
                                                   :disabled="bulkCheckingAllLoader">
                                            <label class="form-check-label" :for="'enabled' + item.id">
                                            </label>
                                        </td>

                                        <td>@{{ item.wallet_text }}</td>
                                        <td>@{{ item.user.full_name }}</td>
                                        <td>@{{ item.coin + ' - ' + item.network }}</td>
                                        <td>@{{ item.amount_fee }}</td>
                                        <td>
                                            @{{ item.amount - item.amount_fee }}
                                            @{{ ' ≈ ' + item.amount_convert + ' BNB' }}
                                        </td>
                                        <td>@{{ item.code }}</td>
                                        <td>@{{ item.status_text }}</td>
                                        <td>@{{ item.reason }}</td>
                                        <td>@{{ item.tx_hash }}</td>
                                        <td>@{{ item.created_at | formatDate }}</td>

                                        <td>
                                            <div class="row no-gutters">
                                                <div class="col-auto mr-1"
                                                     v-if="item.status === {{config('constants.status_withdraw.pending')}}"
                                                >
                                                    <button type="button" class="btn btn-sm btn-success"
                                                            @click="handleApproved('{{ route("admin/history-withdraws/history-withdraw.store") }}', item.id)"
                                                            title="View"><i
                                                            class="fa fa-check"></i></button>
                                                </div>
                                                <div class="col-auto mr-1"
                                                     v-if="item.status === {{config('constants.status_withdraw.pending')}}"
                                                >
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                            @click="openReject(item.id)"
                                                            title="View"><i
                                                            class="fa fa-times"></i></button>
                                                </div>
                                                <div class="col-auto mr-1"
                                                     v-if="item.status === {{config('constants.status_withdraw.approved')}}"
                                                >
                                                    <a class="btn btn-sm btn-spinner btn-info"
                                                       :href="item.resource_url + '/edit'"
                                                       title="Edit"
                                                       role="button"><i class="fa fa-edit"></i></a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <modal name="reject-withdraw" @before-open="beforeOpen" @before-close="beforeClose"
                                   width="30%" height="auto">
                                <h4 class="text-center mt-2">Reason reject</h4>
                                <hr/>
                                <div class="row-col m-3">
                                    <div class="form-group">
                                        <textarea class="form-control" id="reason" placeholder="Reason"
                                                  v-model="reason"></textarea>
                                    </div>
                                </div>
                                <div>
                                    <button class="btn btn-danger d-block w-100"
                                            style="height: 40px; border-radius: unset"
                                            @click="handleReject('{{ route("admin/history-withdraws/history-withdraw.store") }}')"
                                    >
                                        Reject
                                    </button>
                                </div>
                            </modal>

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
    </history-withdraw-listing>

@endsection
