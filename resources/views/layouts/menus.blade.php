<div class="tabNavigation">
    <ul class="tabNavigation__listItem">
        <a href="{{ route('trading.index') }}">
            <li class="item__boxImg {{ request()->is('trading') ? 'active' : '' }}">
                <div class="boxImg__icTrading"
                     style="background-image: url(frontend/images/icons/icTradingNormal.png);"></div>
                <div class="boxImg__note">
                    <p class="note__text">Trading</p>
                </div>
            </li>
        </a>
        <a href="/History">
            <li class="item__boxImg">
                <div class="boxImg__icHistory"
                     style="background-image: url(frontend/images/icons/icHistoryNormal.png);">
                </div>
                <div class="boxImg__note">
                    <p class="note__text">History</p>
                </div>
            </li>
        </a>
        <a href="/Dashboard">
            <li class="item__boxImg">
                <div class="boxImg__icDashboard"
                     style="background-image: url(frontend/images/icons/icDashboardNormal.png);">
                </div>
                <div class="boxImg__note">
                    <p class="note__text">Dashboard</p>
                </div>
            </li>
        </a>
        <a href="{{ route('wallets.index') }}">
            <li class="item__boxImg">
                <div class="boxImg__icWallet" style="background-image: url(frontend/images/icons/icWalletNormal.png);">
                </div>
                <div class="boxImg__note">
                    <p class="note__text">Wallet</p>
                </div>
            </li>
        </a>
{{--        <a href="/Convert">--}}
{{--            <li class="item__boxImg">--}}
{{--                <div class="boxImg__icConvert"--}}
{{--                     style="background-image: url(frontend/images/icons/icConvertNormal.png);">--}}
{{--                </div>--}}
{{--                <div class="boxImg__note">--}}
{{--                    <p class="note__text">Convert</p>--}}
{{--                </div>--}}
{{--            </li>--}}
{{--        </a>--}}
        <a href="/Agency">
            <li class="item__boxImg">
                <div class="boxImg__icAgency" style="background-image: url(frontend/images/icons/icAgencyNormal.png);">
                </div>
                <div class="boxImg__note">
                    <p class="note__text">Agency</p>
                </div>
            </li>
        </a>
        <a href="{{route('user.edit')}}">
            <li class="item__boxImg {{ request()->is('user') ? 'active' : '' }}">
                <div class="boxImg__icProfile"
                     style="background-image: url(frontend/images/icons/icProfileNormal.png);">
                </div>
                <div class="boxImg__note">
                    <p class="note__text">Profile</p>
                </div>
            </li>
        </a>
    </ul>
</div>
