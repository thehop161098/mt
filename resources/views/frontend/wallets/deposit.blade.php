@extends('frontend.wallets.index')

@section('css')
    <link rel="stylesheet" href="{{asset('frontend/css/bootstrap-datepicker.css')}}"/>
    <link href="{{ asset('frontend/css/history.css') }}" rel="stylesheet">
@endsection

@section('title')
    <p class="title__text">Deposit History</p>
@endsection

@section('wallet')
    <div class="form-search" style="width: 100%">
        <div class="date blockFilterHistory" style="margin-bottom: 20px; overflow: hidden;">
            <form action="" method="GET">
                <div class="col-pr-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="input-group input-daterange blockFilterHistory__date">
                        <input type="text" name="from_date" id="from_date" readonly
                               class="form-control date__inputFrom" value="{{Request()->from_date ?: ''}}"/>
                        <div class="input-group-addon date__direct">to</div>
                        <input type="text" name="to_date" id="to_date" readonly
                               class="form-control date__inputTo" value="{{Request()->to_date ?: ''}}"/>
                    </div>
                </div>
                <div class="col-pr-12 col-md-3 col-sm-12 col-xs-12 col-pr blockFilterHistory__btn">
                    <input type="hidden" value="{{url()->current()}}">
                    <button type="submit" id="filter" class="btn blockFilterHistory__btnFilter">Filter
                    </button>
                    <button type="reset" id="refresh" class="btn blockFilterHistory__btnRefresh">Refresh
                    </button>
                </div>
            </form>
        </div>
        <div id="live">
            <div class="blockDemoHistory">
                <div class="blockDemoHistory__table table-responsive">
                    <table class="table" id="demoHistory">
                        <thead class="table__header">
                        <tr class="header__listItem">
                            <th class="header__item">Type</th>
                            <th class="header__item">Time</th>
                            <th class="header__item">Note</th>
                        </tr>
                        </thead>
                        <tbody class="table__content">
                        @if(isset($history) && !$history->isEmpty())
                            @foreach ($history as $model)
                                <tr class="content__listItem">
                                    <td class="content__item high">{{$model->type === config('constants.type_history.cron_deposit') ? 'DEPOSIT' : 'DISCOUNT ACCOUNT'}}</td>
                                    <td class="content__item">{{date('Y-m-d H:i', strtotime($model->created_at))}}</td>
                                    <td class="content__item">{{$model->note}}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="content__listItem">
                                <td colspan="6" class="content__item text-center">No results found</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            @if(isset($history) && ! $history->isEmpty())
                <div class="paginate text-center blockPaginate">{{$history->appends(Request()->all())->links()}}</div>
            @endif
        </div>
    </div>
@endsection

@section('js')
    <script src="{{asset('frontend/js/history.js')}}"></script>
    <script src="{{asset('frontend/js/bootstrap-datepicker.js')}}"></script>
@endsection
