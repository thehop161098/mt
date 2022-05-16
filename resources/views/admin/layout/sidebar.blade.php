<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
           {{-- Do not delete me :) I'm used for auto-generation menu items --}}
            <li class="nav-title">Summary</li>
            {{-- User --}}
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/users') }}"><i
                        class="nav-icon icon-umbrella"></i> User</a></li>
            {{-- summary trade --}}
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/summary-trades') }}"><i
                        class="nav-icon icon-energy"></i> Trade</a></li>
            {{-- control candles --}}
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/control-candles') }}"><i
                        class="nav-icon icon-energy"></i> Control</a></li>

            <li class="nav-title">History</li>
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/history-withdraws') }}"><i
                        class="nav-icon icon-energy"></i> Withdraw</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/history-deposits') }}"><i
                        class="nav-icon icon-energy"></i> Deposit</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/transfer') }}"><i
                        class="nav-icon icon-globe"></i> Transfer</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/history-refunds') }}"><i
                        class="nav-icon icon-diamond"></i>Refund</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/supports') }}"><i
                        class="nav-icon icon-plane"></i> {{ trans('admin.support.title') }}</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/luckyWheelHistory') }}"><i
                        class="nav-icon icon-globe"></i> Lucky Wheel</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/history-bots') }}"><i class="nav-icon icon-compass"></i> {{ trans('admin.history-bot.title') }}</a></li>


            <li class="nav-title">{{ trans('brackets/admin-ui::admin.sidebar.settings') }}</li>
            {{-- Settings --}}
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/settings') }}"><i
                        class="nav-icon icon-settings"></i> {{ __('Configuration') }}</a></li>
            {{-- Wallet --}}
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/wallets') }}"><i
                        class="nav-icon icon-globe"></i> Wallet </a></li>
            {{-- email templates --}}
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/email-templates') }}"><i
                        class="nav-icon icon-book-open"></i> Email Templates</a></li>
            {{-- Coins --}}
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/coins') }}"><i
                        class="nav-icon icon-magnet"></i> Coins </a></li>
            {{-- Levels --}}
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/levels') }}"><i
                        class="nav-icon icon-globe"></i> Levels</a></li>
            {{-- setting --}}
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/setting-refunds') }}"><i
                        class="nav-icon icon-energy"></i> Setting Refund</a></li>
            {{-- faq --}}
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/faqs') }}"><i
                        class="nav-icon icon-umbrella"></i> FAQ</a></li>

            <li class="nav-item"><a class="nav-link" href="{{ url('admin/phone-countries') }}"><i
                        class="nav-icon icon-graduation"></i> {{ trans('admin.phone-country.title') }}</a></li>

            <li class="nav-item"><a class="nav-link" href="{{ url('admin/discounts') }}"><i
                        class="nav-icon icon-energy"></i> {{ trans('admin.discount.title') }}</a></li>

            <li class="nav-item"><a class="nav-link" href="{{ url('admin/wheels') }}"><i
                        class="nav-icon icon-puzzle"></i> {{ trans('admin.wheel.title') }}</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/wheel-settings') }}"><i
                        class="nav-icon icon-ghost"></i> {{ trans('admin.wheel-setting.title') }}</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/advertisements') }}"><i
                        class="nav-icon icon-graduation"></i> {{ trans('admin.advertisement.title') }}</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/auto-bots') }}"><i class="nav-icon icon-drop"></i> {{ trans('admin.auto-bot.title') }}</a></li>

            {{-- <li class="nav-item"><a class="nav-link" href="{{ url('admin/admin-users') }}"><i class="nav-icon icon-user"></i> {{ __('Manage access') }}</a></li> --}}
            {{-- <li class="nav-item"><a class="nav-link" href="{{ url('admin/translations') }}"><i class="nav-icon icon-location-pin"></i> {{ __('Translations') }}</a></li> --}}

            {{-- Do not delete me :) I'm also used for auto-generation menu items --}}
        </ul>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>
