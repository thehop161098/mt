@extends('frontend.layouts.app')

@section('css')
    <link rel="stylesheet" href="{{asset('frontend/css/bootstrap-datepicker.css')}}"/>
    <link href="{{ asset('frontend/css/history.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div id="history">
        <div class="pageHistory">
            <div class="title">
                <p class="title__text">History</p>
                <div class="title__tool">
                    <ul class="tool__selectHistory">
                        <a href="{{route('history.demo')}}">
                            <li class="selectHistory__item {{ request()->is('history/demo') ? 'active' : '' }}">
                                <img class="item__img" src="{{asset('frontend/images/icons/icHistoryDemo.png')}}"/>
                                <p class="item__text">Demo</p>
                            </li>
                        </a>
                        <a href="{{route('history.live')}}">
                            <li class="selectHistory__item {{ request()->is('history/live') ? 'active' : '' }}">
                                <img class="item__img" src="{{asset('frontend/images/icons/icHistoryLive.png')}}"/>
                                <p class="item__text">Live</p>
                            </li>
                        </a>
                        <a href="{{route('history.promotion')}}">
                            <li class="selectHistory__item {{ request()->is('history/promotion') ? 'active' : '' }}">
                                <img class="item__img" src="{{asset('frontend/images/icons/icHistoryPromotion.png')}}"/>
                                <p class="item__text">Promotion</p>
                            </li>
                        </a>
                        <a href="{{route('history.refund')}}">
                            <li class="selectHistory__item {{ request()->is('history/refund') ? 'active' : '' }}">
                                <img class="item__img" src="{{asset('frontend/images/icons/icHistoryRefund.png')}}"/>
                                <p class="item__text">Refund</p>
                            </li>
                        </a>
                    </ul>
                </div>
            </div>
            <div class="content tab-content form-search">
                <div class="date blockFilterHistory">
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
                        @if(! request()->is('history/refund'))
                            <div class="col-pr-6 col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group blockFilterHistory__select">
                                    <div class="select__icDrop"></div>
                                    <select class="form-control select__formSelect" name="currency" id="currency">
                                        <option value="">--Select--</option>
                                        @foreach ($coins as $coin)
                                            <option
                                                {{$coin->name == Request()->currency ? 'selected' : ''}} value="{{$coin->name}}">{{$coin->alias}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="col-pr-12 col-md-3 col-sm-12 col-xs-12 col-pr blockFilterHistory__btn">
                            <input type="hidden" value="{{url()->current()}}">
                            <button type="submit" id="filter" class="btn blockFilterHistory__btnFilter">Filter
                            </button>
                            <button type="reset" id="refresh" class="btn blockFilterHistory__btnRefresh">Refresh
                            </button>
                        </div>
                    </form>
                </div>
                @yield('history')
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{asset('frontend/js/history.js')}}"></script>
    <script src="{{asset('frontend/js/bootstrap-datepicker.js')}}"></script>
@endsection
