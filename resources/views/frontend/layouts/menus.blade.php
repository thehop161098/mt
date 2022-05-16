<div class="tabNavigation">
    <ul class="tabNavigation__listItem">
        <a href="{{ route('home') }}">
            <li class="item__boxImg {{ request()->is('/') ? 'active' : '' }}">
                <div class="boxImg__icTrading"
                     style="background-image: url({{asset('frontend/images/icons/icTradingNormal.png')}});"></div>
                <div class="boxImg__note">
                    <p class="note__text">Trading</p>
                </div>
            </li>
        </a>
        <a href="{{route('history.live')}}">
            <li class="item__boxImg {{ (request()->is('history/demo') || request()->is('history/live') || request()->is('history/promotion') || request()->is('history/refund')) ? 'active' : '' }}">
                <div class="boxImg__icHistory"
                     style="background-image: url({{asset('frontend/images/icons/icHistoryNormal.png')}});">
                </div>
                <div class="boxImg__note">
                    <p class="note__text">History</p>
                </div>
            </li>
        </a>
        <a href="{{route('dashboard.index')}}">
            <li class="item__boxImg {{ request()->is('dashboard') ? 'active' : '' }}">
                <div class="boxImg__icDashboard"
                     style="background-image: url({{asset('frontend/images/icons/icDashboardNormal.png')}});">
                </div>
                <div class="boxImg__note">
                    <p class="note__text">Dashboard</p>
                </div>
            </li>
        </a>
        <a href="{{ route('wallets.index') }}">
            <li class="item__boxImg {{ request()->is('wallets/*') || request()->is('wallets') ? 'active' : '' }}">
                <div class="boxImg__icWallet"
                     style="background-image: url({{asset('frontend/images/icons/icWalletNormal.png')}});">
                </div>
                <div class="boxImg__note">
                    <p class="note__text">Wallet</p>
                </div>
            </li>
        </a>
        {{--        <a href="{{route('convert.index')}}">--}}
        {{--            <li class="item__boxImg {{ request()->is('convert') ? 'active' : '' }}">--}}
        {{--                <div class="boxImg__icConvert"--}}
        {{--                     style="background-image: url({{asset('frontend/images/icons/icConvertNormal.png')}});">--}}
        {{--                </div>--}}
        {{--                <div class="boxImg__note">--}}
        {{--                    <p class="note__text">Convert</p>--}}
        {{--                </div>--}}
        {{--            </li>--}}
        {{--        </a>--}}
        <a href="{{route('agency.index')}}">
            <li class="item__boxImg {{ request()->is('affiliate-marketing') ? 'active' : '' }}">
                <div class="boxImg__icAgency"
                     style="background-image: url({{asset('frontend/images/icons/icAgencyNormal.png')}});">
                </div>
                <div class="boxImg__note">
                    <p class="note__text">Affiliate Marketing</p>
                </div>
            </li>
        </a>
        <a href="{{route('commission.daily')}}">
            <li class="item__boxImg {{ (request()->is('commission/daily') || request()->is('commission/agency') || request()->is('commission/master-ib') || request()->is('commission/imcome')) ? 'active' : '' }}">
                <div class="boxImg__icHistory"
                     style="background-image: url({{asset('frontend/images/icons/icHistoryNormal.png')}});">
                </div>
                <div class="boxImg__note">
                    <p class="note__text">Commission</p>
                </div>
            </li>
        </a>
        <a href="{{route('faq.index')}}">
            <li class="item__boxImg {{ request()->is('faqs') ? 'active' : '' }}">
                <div class="boxImg__icPolicy"
                     style="background-image: url({{asset('frontend/images/icons/icPolicyNormal.png')}});">
                </div>
                <div class="boxImg__note">
                    <p class="note__text">Policy</p>
                </div>
            </li>
        </a>
        <a href="{{route('user.edit')}}">
            <li class="item__boxImg {{ request()->is('user') ? 'active' : '' }}">
                <div class="boxImg__icProfile"
                     style="background-image: url({{asset('frontend/images/icons/icProfileNormal.png')}});">
                </div>
                <div class="boxImg__note">
                    <p class="note__text">Profile</p>
                </div>
            </li>
        </a>
        @if (config('settings.publish_bot') === '1')
            <a href="{{route('autoBot.index')}}">
                <li class="item__boxImg {{ request()->is('auto-bots') ? 'active' : '' }}">
                    <div class="boxImg__icBots"
                         style="background-image: url({{asset('frontend/images/icons/icBotsNormal.png')}});">
                    </div>
                    <div class="boxImg__note">
                        <p class="note__text">Bots</p>
                    </div>
                </li>
            </a>
        @endif
    </ul>
</div>
