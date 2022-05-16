@extends('brackets/admin-ui::admin.layout.default')

@section('title', 'List')
@section('body')

    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Transfer
                </div>
                <div class="card-body">
                    <div class="card-block">
                        <form method="GET">
                            <div class="row justify-content-md-between">
                                <div class="col col-lg-7 col-xl-5 form-group">
                                    <div class="input-group">
                                        <input class="form-control"
                                               placeholder="{{ trans('brackets/admin-ui::admin.placeholder.search') }}"
                                               name="search" value="{{$_GET['search'] ?? ''}}"/>
                                        <span class="input-group-append">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp; {{ trans('brackets/admin-ui::admin.btn.search') }}</button>
                                        <a href="{{route('admin/transfer/transfer.index')}}" class="btn btn-warning ml-1"><i class="fa fa-refresh"></i></a>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-hover" id="controlCandles">
                                <thead>
                                <tr>
                                    <th class="text-center">User</th>
                                    <th class="text-center">Note</th>
                                    <th class="text-center">Created Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($transfers) && ! $transfers->isEmpty())
                                    @foreach($transfers as $transfer)
                                        <tr>
                                            <td class="text-center">{{$transfer->user->email}}</td>
                                            <td class="text-center">{{$transfer->note}}</td>
                                            <td class="text-center">
                                                {{date('Y-m-d H:i', strtotime($transfer->created_at))}}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="content__listItem">
                                        <td style="display: table-cell" colspan="3" class="content__item text-center">No
                                            results found
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                        @if(isset($transfers) && ! $transfers->isEmpty())
                            <div style="margin: 0 auto; display: table"
                                 class="paginate text-center">{{$transfers->links()}}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
