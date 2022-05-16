@extends('frontend.history.index')
@section('history')
<div id="demo">
    <div class="blockDemoHistory">
        <div class="blockDemoHistory__table table-responsive">
            <table class="table" id="demoHistory">
                <thead class="table__header">
                    <tr class="header__listItem">
                        <th class="header__item">Time</th>
                        <th class="header__item">Currency</th>
                        <th class="header__item">Bet</th>
                        <th class="header__item">Orders</th>
                        <th class="header__item">Result</th>
                        <th class="header__item">Profit</th>
                    </tr>
                </thead>
                <tbody class="table__content">
                    @if(isset($orders) && ! $orders->isEmpty())
                        @foreach ($orders as $order)
                            @php
                                // Profit
                                if(!empty($order->profit)) {
                                    $classProfit = $order->profit > 0 ? 'profit__win' : 'profit__lose';
                                    $profit = '$'.number_format($order->profit, 2);
                                    $profit = str_replace('$-', '-$',$profit);
                                } else {
                                    $classProfit = '';
                                    $profit = '--';
                                }
                                // Bet
                                if($order->type == config('constants.orders.red')) {
                                    $classBet = 'low';
                                    $nameBet = config('constants.low');
                                } elseif($order->type == config('constants.orders.green')) {
                                    $classBet = 'high';
                                    $nameBet = config('constants.higher');
                                } else {
                                    $classBet = 'balance';
                                    $nameBet = config('constants.balance');
                                }
                                // Result
                                if(!empty($order->profit)) {
                                    if($order->open < $order->close) {
                                        $classResult = 'high';
                                        $nameResult = config('constants.higher');
                                    } elseif($order->open > $order->close) {
                                        $classResult = 'low';
                                        $nameResult = config('constants.low');
                                    } else {
                                        $classResult = 'balance';
                                        $nameResult = config('constants.balance');
                                    }
                                } else {
                                    $classResult = '';
                                    $nameResult = '--';
                                }
                            @endphp
                        <tr class="content__listItem">
                            <td class="content__item">{{date('Y-m-d H:i', strtotime($order->date))}}</td>
                            <td class="content__item">
                                {{$order->coinAlias ? $order->coinAlias->alias : ''}}
                            </td>
                            <td class="content__item {{$classBet}}">{{$nameBet}}</td>
                            <td class="content__item">${{number_format($order->amount, 2)}}</td>
                            <td class="content__item {{$classResult}}">{{$nameResult}}</td>
                            <td class="content__item {{$classProfit}}">{{$profit}}</td>
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
    @if(isset($orders) && ! $orders->isEmpty())
        <div class="paginate text-center blockPaginate">{{$orders->appends(Request()->all())->links()}}</div>
    @endif
</div>

@endsection
