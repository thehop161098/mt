<div class="infoTabNavigation__header" id="hdMB">
    <div class="overlayMenu"></div>
    <div class="header__menuMobi hidden-lg">
        <input type="checkbox" class="menuMobi__select"/>
        <svg width="30" height="30" class="icMenu">
            <line x1="2" y1="5" x2="28" y2="5" class="icMenu__item"></line>
            <line x1="2" y1="15" x2="16" y2="15" class="icMenu__item"></line>
            <line x1="2" y1="25" x2="28" y2="25" class="icMenu__item"></line>
        </svg>
        <div class="menuMobi__blockMenu">
            <div class="blockMenu__info">
                <div class="info__logo">
                    @php
                        $pathLogo = isset(config('settings')['logoFE']) ? 'images/settings/'.config('settings')['logoFE'] : '';
                    @endphp
                    <a href="{{ route('home') }}"><img class="logo__img" src="{{asset($pathLogo)}}"/></a>
                </div>
                <div class="info__user">
                    <img class="user__img" src="{{asset(auth()->user()->getAvatar())}}">
                    <p class="user__name">{{auth()->user()->full_name}}</p>
                </div>
            </div>
            <div class="blockMenu__listPage">
                <a href="{{ route('home') }}">
                    <div class="listPage__item {{ request()->is('/') ? 'active' : '' }}">
                        <img class="item__imgMenu" src="{{asset('frontend/images/icons/icTradingActive.png')}}"/>
                        <div class="item__textMenu">Trading</div>
                    </div>
                </a>
                <a href="{{route('history.live')}}">
                    <div
                        class="listPage__item {{ (request()->is('history/demo') || request()->is('history/live') || request()->is('history/promotion') || request()->is('history/refund')) ? 'active' : '' }}">
                        <img class="item__imgMenu" src="{{asset('frontend/images/icons/icHistoryActive.png')}}"/>
                        <div class="item__textMenu">History</div>
                    </div>
                </a>
                <a href="{{route('dashboard.index')}}">
                    <div class="listPage__item {{ request()->is('dashboard') ? 'active' : '' }}">
                        <img class="item__imgMenu" src="{{asset('frontend/images/icons/icDashboardActive.png')}}"/>
                        <div class="item__textMenu">Dashboard</div>
                    </div>
                </a>
                <a href="{{ route('wallets.index') }}">
                    <div class="listPage__item {{ request()->is('wallets') ? 'active' : '' }}">
                        <img class="item__imgMenu" src="{{asset('frontend/images/icons/icWalletActive.png')}}"/>
                        <div class="item__textMenu">Wallet</div>
                    </div>
                </a>
                {{--            <a href="{{route('convert.index')}}">--}}
                {{--                <div class="listPage__item {{ request()->is('convert') ? 'active' : '' }}">--}}
                {{--                    <img class="item__imgMenu" src="{{asset('frontend/images/icons/icConvertActive.png')}}"/>--}}
                {{--                    <div class="item__textMenu">Convert</div>--}}
                {{--                </div>--}}
                {{--            </a>--}}
                <a href="{{route('agency.index')}}">
                    <div class="listPage__item {{ request()->is('affiliate-marketing') ? 'active' : '' }}">
                        <img class="item__imgMenu" src="{{asset('frontend/images/icons/icAgencyActive.png')}}"/>
                        <div class="item__textMenu">Affiliate Marketing</div>
                    </div>
                </a>
                <a href="{{route('commission.daily')}}">
                    <div
                        class="listPage__item {{ (request()->is('commission/daily') || request()->is('commission/agency') || request()->is('commission/master-ib') || request()->is('commission/imcome')) ? 'active' : '' }}">
                        <img class="item__imgMenu" src="{{asset('frontend/images/icons/icHistoryNormal.png')}}"/>
                        <div class="item__textMenu">Commission</div>
                    </div>
                </a>
                <a href="{{route('faq.index')}}">
                    <div class="listPage__item {{ request()->is('faqs') ? 'active' : '' }}">
                        <img class="item__imgMenu" src="{{asset('frontend/images/icons/icPolicyNormal.png')}}"/>
                        <div class="item__textMenu">Policy</div>
                    </div>
                </a>
                <a href="{{route('user.edit')}}">
                    <div class="listPage__item {{ request()->is('user') ? 'active' : '' }}">
                        <img class="item__imgMenu" src="{{asset('frontend/images/icons/icProfileActive.png')}}"/>
                        <div class="item__textMenu">Profile</div>
                    </div>
                </a>
                @if (config('settings.publish_bot') === '1')
                    <a href="{{route('autoBot.index')}}">
                        <div class="listPage__item {{ request()->is('auto-bots') ? 'active' : '' }}">
                            <img class="item__imgMenu" src="{{asset('frontend/images/icons/icBotsActive.png')}}"/>
                            <div class="item__textMenu">Bots</div>
                        </div>
                    </a>
                @endif
                <a href="{{route('logout')}}"
                   onclick="event.preventDefault();
                    document.getElementById('logout-form-mobile').submit();"
                >
                    <div class="listPage__item">
                        <img class="item__imgMenu" src="{{asset('frontend/images/icons/icLogout.png')}}"/>
                        <div class="item__textMenu">Logout</div>
                    </div>
                </a>
                <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>
    @include('frontend.layouts.header.logo')
    @if(request()->is('/'))
        <Dropdown
            full-name="{{Auth::user()->full_name}}"
            route-edit="{{route('user.edit')}}"
            route-logout="{{route('logout')}}"
            csrf="{{csrf_token()}}"
            notifications="{{Auth::user()->historyNotifications}}"
        />
    @else
        <div class="header__tool">
            <div class="tool__luckyWheel">
                <img class="luckyWheel__btnShow" src="{{asset('frontend/images/icons/spinButton.png')}}"/>
            </div>
            {{-- @include('frontend.layouts.header.coins') --}}
            {{-- @include('frontend.layouts.header.wallet_games') --}}
            @include('frontend.layouts.header.notifications')
            @include('frontend.layouts.header.tool')
        </div>
    @endif
</div>
