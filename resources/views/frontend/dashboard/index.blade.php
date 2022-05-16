@extends('frontend.layouts.app')

@section('css')
<link href="{{asset('frontend/css/dashboard.css')}}" rel="stylesheet">
@endsection

@section('content')
<div id="dashboard">
    <div class="pageDashboard">
        <div class="title">
            <p class="title__text">Dashboard</p>
        </div>
        <div class="content">
            @include('frontend.dashboard.info')
            <div class="blockDashboardHistory">
                <div class="blockDashboardHistory__header">
                    <div class="header__title">
                        <p class="title__text">Trade History</p>
                    </div>
                </div>
                <div class="blockDashboardHistory__table table-responsive">
                    <table class="table" id="dashboardHistory">
                        <thead class="table__header">
                            <tr class="header__listItem">
                                <th class="header__item">Time</th>
                                <th class="header__item">Profit</th>
                                <th class="header__item">Amount</th>
                                <th class="header__item">Selected</th>
                                <th class="header__item">Status</th>
                                <th class="header__item">Open Price</th>
                                <th class="header__item">Close Price</th>
                                <th class="header__item">Result</th>
                            </tr>
                        </thead>
                        <tbody class="table__content">
                            @if(isset($orders) && ! $orders->isEmpty())
                                @foreach ($orders as $order)
                                    @php
                                        //profit
                                        $nameProfit = ! empty($order->profit) ? '$'.number_format($order->profit, 2) : '--';
                                        //open
                                        $nameOpen = ! empty($order->open) ? '$'.number_format($order->open, 2) : '--';
                                        //close
                                        $nameClose = ! empty($order->close) ? '$'.number_format($order->close, 2) : '--';
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
                                        // status
                                        if(!empty($order->profit)) {
                                            if($order->profit > 0) {
                                                $classStatus = 'statusSuccess';
                                                $nameStatus = 'Success';
                                            }  else {
                                                $classStatus = 'statusFail';
                                                $nameStatus = 'Fail';
                                            }
                                        } else {
                                            $classStatus = '';
                                            $nameStatus = '--';
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
                                        <td class="content__item">{{$nameProfit}}</td>
                                        <td class="content__item">
                                            ${{number_format($order->amount, 2)}}
                                        </td>
                                        <td class="content__item {{$classBet}}">{{$nameBet}}</td>
                                        <td class="content__item {{$classStatus}}">{{$nameStatus}}</td>
                                        <td class="content__item">{{$nameOpen}}</td>
                                        <td class="content__item">{{$nameClose}}</td>
                                        <td class="content__item {{$classResult}}">{{$nameResult}}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="content__listItem">
                                    <td colspan="8" class="content__item text-center">No results found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
    <script src="{{asset('frontend/js/dashboard.js')}}"></script>
@endsection
