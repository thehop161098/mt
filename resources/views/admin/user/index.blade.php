@extends('brackets/admin-ui::admin.layout.default')

@section('title', 'User')

@section('body')

    <user-listing :data="{{ $data->toJson() }}" :url="'{{ url('admin/users') }}'" inline-template>

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> User
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
                                            <select class="form-control" v-model="verify">
                                                <option value="">---- Status ----</option>
                                                <option
                                                    value="{{config('constants.pending_verify_user')}}">{{config('constants.pending_verify_user_text')}}</option>
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

                                        <th is='sortable' :column="'full_name'">Fullname</th>
                                        <th is='sortable' :column="'email'">Verify</th>
                                        <th is='sortable' :column="'total_withdrawal'">Total Withdrawal</th>
                                        <th is='sortable' :column="'total_deposit'">Total Deposit</th>
                                        <th is='sortable' :column="'total_deposit'">Total Transfer</th>
                                        <th is='sortable' :column="'total_deposit'">Total Order</th>
                                        <th is='sortable' :column="'total_deposit'">Total Profit</th>

                                        <th></th>
                                    </tr>
                                    <tr v-show="(clickedBulkItemsCount > 0) || isClickedAll">
                                        <td class="bg-bulk-info d-table-cell text-center" colspan="8">
                                        <span class="align-middle font-weight-light text-dark">{{ trans('brackets/admin-ui::admin.listing.selected_items') }} @{{ clickedBulkItemsCount }}. <a
                                                href="#" class="text-primary"
                                                @click="onBulkItemsClickedAll('/admin/users')"
                                                v-if="(clickedBulkItemsCount < pagination.state.total)"> <i class="fa"
                                                                                                            :class="bulkCheckingAllLoader ? 'fa-spinner' : ''"></i> {{ trans('brackets/admin-ui::admin.listing.check_all_items') }} @{{ pagination.state.total }}</a> <span
                                                class="text-primary">|</span> <a href="#" class="text-primary"
                                                                                 @click="onBulkItemsClickedAllUncheck()">{{ trans('brackets/admin-ui::admin.listing.uncheck_all_items') }}</a> </span>

                                            <span class="pull-right pr-2">
                                            <button class="btn btn-sm btn-danger pr-3 pl-3"
                                                    @click="bulkDelete('/admin/users/bulk-destroy')">{{ trans('brackets/admin-ui::admin.btn.delete') }}</button>
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

                                        <td>
                                            <p v-if="item.level > 0"><b>Buy Affiliate Marketing</b></p>
                                            <p v-if="item.full_name"><b>Name</b>: @{{ item.full_name }}</p>
                                            <p v-if="item.phone"><b>Phone</b>: @{{ item.phone }}</p>
                                            <p v-if="item.email"><b>Email</b>: @{{ item.email }}</p>
                                            <p v-if="item.wallets" v-for="wallet in item.wallets"><b>Wallets</b>: @{{
                                                wallet.code }}</p>
                                            <p v-if="item.wallets" v-for="wallet in item.wallets"><b>Current Money</b>:
                                                $@{{ item.wallet_main.amount | formatNumber }}</p>
                                        </td>
                                        <td>@{{ item.verify_text }}</td>
                                        <td class="text-right">$@{{ item.total_withdrawal | formatNumber }}</td>
                                        <td class="text-right">$@{{ item.total_deposit | formatNumber }}</td>
                                        <td class="text-right">
                                            <p>Received: $@{{ item.total_transfer | formatNumber }}</p>
                                            <p>To: $@{{ item.total_transfer_to | formatNumber }}</p>
                                        </td>
                                        <td class="text-right">$@{{ item.total_order | formatNumber }}</td>
                                        <td class="text-right">$@{{ item.total_profit | formatNumber }}</td>

                                        <td>
                                            <div class="row no-gutters">
                                                {{--                                                <button type="button" class="btn btn-sm btn-primary mr-1"--}}
                                                {{--                                                        @click="viewItem(item.images, item.id, item.verify)"--}}
                                                {{--                                                        title="View"><i--}}
                                                {{--                                                        class="fa fa-list"></i></button>--}}
                                                <button type="button" class="btn btn-sm btn-primary mr-1"
                                                        v-if="item.is_view"
                                                        @click="viewItem(item.images, item.id, item.verify)"
                                                        title="View"><i
                                                        class="fa fa-eye"></i></button>
                                                <form class="col" @submit.prevent="deleteItem(item.resource_url)">
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                        <i
                                                            class="fa fa-trash-o"></i></button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <modal name="thumb-user" @before-open="beforeOpen" @before-close="beforeClose" width="50%"
                                   height="auto">
                                <h3 class="text-center mt-2">Verify KYC User</h3>
                                <hr/>
                                <div class="row text-center text-lg-left m-3">
                                    <div class="col-sm">
                                        <div class="d-block mt-2 text-center" style="max-width: 190px">
                                            <img class="img-fluid img-thumbnail" :src="portrait" alt="portrait">
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <div class="d-block mt-2 text-center" style="max-width: 190px">
                                            <img class="img-fluid img-thumbnail" :src="identity_card_before"
                                                 alt="identity_card_before">
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <div class="d-block mt-2 text-center" style="max-width: 190px">
                                            <img class="img-fluid img-thumbnail" :src="identity_card_after"
                                                 alt="identity_card_after">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-inline" v-if="status === {{config('constants.pending_verify_user')}}">
                                    <button class="btn btn-danger d-block w-50 pull-left"
                                            style="height: 40px; border-radius: unset"
                                            @click="handleVerify('{{url('admin/users/reject')}}', id)">
                                        Reject
                                    </button>
                                    <button class="btn btn-success d-block w-50 pull-right"
                                            style="height: 40px; border-radius: unset"
                                            @click="handleVerify('{{url('admin/users/approve')}}', id)">
                                        Verify
                                    </button>
                                </div>
                            </modal>

                            <modal name="list-children" @before-open="beforeOpen" @before-close="beforeClose"
                                   width="50%"
                                   height="auto">
                                <h3 class="text-center mt-2">Children</h3>
                                <hr/>
                                <div class="row text-center text-lg-left m-3">
                                    <div class="col-sm">
                                        <div class="d-block mt-2 text-center" style="max-width: 190px">
                                            <img class="img-fluid img-thumbnail" :src="portrait" alt="">
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <div class="d-block mt-2 text-center" style="max-width: 190px">
                                            <img class="img-fluid img-thumbnail" :src="identity_card_before" alt="">
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <div class="d-block mt-2 text-center" style="max-width: 190px">
                                            <img class="img-fluid img-thumbnail" :src="identity_card_after" alt="">
                                        </div>
                                    </div>
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
                                <p>{{ trans('brackets/admin-ui::admin.index.try_changing_items') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </user-listing>

@endsection
