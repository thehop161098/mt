@extends('brackets/admin-ui::admin.layout.default')

@section('title', 'List')
@section('body')

@section('styles')
    <link rel="stylesheet" href="{{asset('backend/css/control-candles.css')}}"/>
    @toastr_css
@endsection

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-align-justify"></i> Control Candles
                <a class="btn btn-primary btn-spinner btn-sm m-b-0" href="{{ url('admin/control-candles') }}"
                   role="button"><i class="fa fa-refresh"></i>&nbsp; Refresh</a>
            </div>
            <div class="card-body">
                <div class="card-block">
                    <div class="row justify-content-md-between">
                        <div class="col-md-5 form-group">
                            Datetime: {{date('d-m-Y H:i', strtotime($time))}}:<span id="second"
                                                                                    style="font-size: 30px; font-weight: bold">00</span>
                        </div>
                        <div class="col-md-7 text-right">
                            <p>Users Online: <b id="num-users-online">0</b></p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="controlCandles">
                            <thead>
                            <tr>
                                <th class="text-center">Coin</th>
                                <th class="text-center">Open</th>
                                <th class="text-center">Close</th>
                                <th class="text-center">Result</th>
                                <th class="text-center">Order</th>
                                <th class="text-center">Prize</th>
                                <th class="text-center">Tool</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($controlCandles) && ! $controlCandles->isEmpty())
                                @foreach($controlCandles as $controlCandle)
                                    @php
                                        // Result
                                        if($controlCandle->open < $controlCandle->close) {
                                            $result =  config('constants.higher');
                                        } elseif($controlCandle->open > $controlCandle->close) {
                                            $result =  config('constants.low');
                                        } else {
                                            $result =  config('constants.balance');
                                        }
                                        $summary = $controlCandle->summary($controlCandle->coin);
                                    @endphp
                                    <tr id="borderTop">
                                        <td class="text-center">{{$controlCandle->coin}}</td>
                                        <td class="text-center">{{$controlCandle->open}}</td>
                                        <td id="updateClose-{{$controlCandle->id}}"
                                            class="text-center">{{$controlCandle->close}}</td>
                                        <td id="updateResult-{{$controlCandle->id}}"
                                            class="text-center">{{$result}}</td>
                                        <td class="text-center">
                                            @if(!empty($summary->toArray()))
                                                <p class="lower">
                                                    Sell: ${{$summary->amount_sell ?? 0}}
                                                </p>
                                                <p class="higher">
                                                    Buy: ${{$summary->amount_higher ?? 0}}
                                                </p>
                                                <p class="balance">
                                                    Balance: ${{$summary->amount_balance ?? 0}}
                                                </p>
                                            @endif
                                        </td>
                                        <td style="width: 14%" class="text-center">
                                            <input id="prize-{{$controlCandle->id}}" min="1" class="form-control"
                                                   type="number"
                                                   value="{{$controlCandle->prize}}">
                                        </td>
                                        <td class="text-center display-block">
                                            <a data-bitcoin="Higher"
                                               data-url="{{ route("admin/control-candles/rangeCandle", ['id' => $controlCandle->id, 'type' => 'plus']) }}"
                                               data-id="{{$controlCandle->id}}"
                                               class="btn-controlCandles btn btn-sm btn-higher action_candles"><i
                                                    class="fa fa-bitcoin"></i></a>

                                            <a data-bitcoin="Balance"
                                               data-url="{{ route("admin/control-candles/rangeCandle", ['id' => $controlCandle->id, 'type' => 'equal']) }}"
                                               data-id="{{$controlCandle->id}}"
                                               class="btn-controlCandles btn btn-sm btn-balance action_candles"><i
                                                    class="fa fa-bitcoin"></i></a>

                                            <a data-bitcoin="Lower"
                                               data-url="{{ route("admin/control-candles/rangeCandle", ['id' => $controlCandle->id, 'type' => 'minus']) }}"
                                               data-id="{{$controlCandle->id}}"
                                               class="btn-controlCandles btn btn-sm btn-lower action_candles"><i
                                                    class="fa fa-bitcoin"></i></a>
                                        </td>
                                    </tr>
                                    @if (!$controlCandle->orders()->isEmpty())
                                        <tr>
                                            <td colspan="10">
                                                <h4 class="text-center">Order Details</h4>
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center">Email</th>
                                                        <th class="text-center">Buy</th>
                                                        <th class="text-center">Balance</th>
                                                        <th class="text-center">Sell</th>
                                                    </tr>
                                                    </thead>
                                                    @foreach($controlCandle->orders($controlCandle->coin) as $order)
                                                        <tr>
                                                            <td>Email: {{$order->users->email}}</td>
                                                            <td class="text-center"><p class="lower">
                                                                    Sell: ${{$order->amount_sell}}
                                                                </p></td>
                                                            <td class="text-center"><p class="higher">
                                                                    Buy: ${{$order->amount_higher}}
                                                                </p></td>
                                                            <td class="text-center"><p class="balance">
                                                                    Balance: ${{$order->amount_balance}}
                                                                </p></td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                <tr class="content__listItem">
                                    <td style="display: table-cell" colspan="8" class="content__item text-center">No
                                        results
                                        found
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('bottom-scripts')
    <script src="{{asset('backend/js/control-candles.js')}}"></script>
    <script src="{{ asset('backend/js/sweetalert2@9.js') }}"></script>
    @toastr_js
    @toastr_render
@endsection
