@extends('frontend.layouts.app')

@section('css')
    <link rel="stylesheet" href="{{asset('frontend/css/bootstrap-datepicker.css')}}"/>
    <link href="{{ asset('frontend/css/history.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div id="history">
        <div class="pageHistory">
            <div class="title">
                @yield('title_page')
                <div class="title__tool">
                    <ul class="tool__selectHistory">
                        <a href="{{route('commission.daily')}}">
                            <li class="selectHistory__item {{ request()->is('commission/daily') ? 'active' : '' }}">
                                <img class="item__img" src="{{asset('frontend/images/icons/icHistoryDemo.png')}}"/>
                                <p class="item__text">Daily</p>
                            </li>
                        </a>
                        <a href="{{route('commission.agency')}}">
                            <li class="selectHistory__item {{ request()->is('commission/affiliate-marketing') ? 'active' : '' }}">
                                <img class="item__img" src="{{asset('frontend/images/icons/icHistoryLive.png')}}"/>
                                <p class="item__text">Affiliate Marketing</p>
                            </li>
                        </a>
                        <a href="{{route('commission.master-ib')}}">
                            <li class="selectHistory__item {{ request()->is('commission/master-ib') ? 'active' : '' }}">
                                <img class="item__img" src="{{asset('frontend/images/icons/icHistoryPromotion.png')}}"/>
                                <p class="item__text">Masterib</p>
                            </li>
                        </a>
                        <a href="{{route('commission.imcome')}}">
                            <li class="selectHistory__item {{ request()->is('commission/imcome') ? 'active' : '' }}">
                                <img class="item__img" src="{{asset('frontend/images/icons/icHistoryRefund.png')}}"/>
                                <p class="item__text">Imcome</p>
                            </li>
                        </a>
                        <a href="{{route('commission.bot')}}">
                            <li class="selectHistory__item {{ request()->is('commission/bot') ? 'active' : '' }}">
                                <img class="item__img" src="{{asset('frontend/images/icons/icHistoryDemo.png')}}"/>
                                <p class="item__text">Auto Bot</p>
                            </li>
                        </a>
                    </ul>
                </div>
            </div>
            <div class="content tab-content form-search">
                @if (!request()->is('commission/affiliate-marketing'))
                    <div class="date blockFilterHistory commission">
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
                @endif
                @yield('commission')
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{asset('frontend/js/commission.js')}}"></script>
    <script src="{{asset('frontend/js/bootstrap-datepicker.js')}}"></script>
@endsection
