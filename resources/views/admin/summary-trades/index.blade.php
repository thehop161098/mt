@extends('brackets/admin-ui::admin.layout.default')

@section('title', 'List')
@section('body')

@section('styles')
<link rel="stylesheet" href="{{asset('frontend/css/bootstrap-datepicker.css')}}" />
@endsection
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Summary Trade
                </div>
                <div class="card-body">
                    <div class="card-block">
                        <form action="" method="GET">
                            <div class="row justify-content-md-between">
                                <div class="col-md-3 form-group">
                                    <div class="input-group">
                                        <input autocomplete="off" type="text" name="datetime" class="form-control" id="todaySummaryTrade" value="{{Request()->datetime ?: ''}}">
                                        <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                    </div>
                                </div>
                                <div class="col-md-5 form-group">
                                    <div class="input-group">
                                        <input name="user" class="form-control" placeholder="fullname, code, phone" value="{{Request()->user ?: ''}}"/>
                                        <span class="input-group-append">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;Search</button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 form-group">
                                   <p style="float: left">Total Profit: {{str_replace('$-', '-$',$totalProfit)}}</p>
                                   <p style="float: right">Total Amount: {{str_replace('$-', '-$',$totalAmount)}}</p>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">FullName</th>
                                        <th class="text-center">Code</th>
                                        <th class="text-center">SDT</th>
                                        <th class="text-center">Amount</th>
                                        <th class="text-center">Profit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($summaryTrades) && ! $summaryTrades->isEmpty())
                                        @foreach($summaryTrades as $summaryTrade)
                                            @php
                                                // Amount
                                                if(!empty($summaryTrade->amount)) {
                                                    $amount = '$'.number_format($summaryTrade->amount, 2);
                                                    $amount = str_replace('$-', '-$',$amount);
                                                } else {
                                                    $profit = '--';
                                                }
                                                // Profit
                                                if(!empty($summaryTrade->profit)) {
                                                    $profit = '$'.number_format($summaryTrade->profit, 2);
                                                    $profit = str_replace('$-', '-$',$profit);
                                                } else {
                                                    $profit = '--';
                                                }
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{$summaryTrade->full_name}}</td>
                                                <td class="text-center">{{$summaryTrade->code}}</td>
                                                <td class="text-center">{{$summaryTrade->phone}}</td>
                                                <td class="text-center">{{$amount}}</td>
                                                <td class="text-center">{{$profit}}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="content__listItem">
                                            <td style="display: table-cell" colspan="5" class="content__item text-center">No results found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if(isset($summaryTrades) && ! $summaryTrades->isEmpty())
                    <div class="paginate text-center blockPaginate">{{$summaryTrades->appends(Request()->all())->links()}}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection

@section('bottom-scripts')
<script src="{{asset('backend/js/summarytrade.js')}}"></script>
<script src="{{asset('frontend/js/bootstrap-datepicker.js')}}"></script>
@endsection
