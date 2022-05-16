@php
    $notifications = Auth::user()->historyNotifications;

@endphp
<div class="tool__noti">
    <input type="checkbox" class="noti__input" id="cbox4"/>
    <div class="noti__infoNoti">
        <img class="infoNoti__img" src="{{asset('frontend/images/icons/icNoti.png')}}"/>
        <div class="infoNoti__count">
            <div class="count__number">{{count($notifications) > 99 ? '99+' : count($notifications)}}</div>
        </div>
    </div>
    @if(!$notifications->isEmpty())
        <div class="noti__listNoti">
            <button class="headerNoti__readAll"
                    onclick="readAllNotifications('{{route('historyWallets/historyWallets.readAll')}}')">
                <img class="readAll__img" src="{{asset('frontend/images/icons/icCheckAll.png')}}"/>
            </button>
            @foreach($notifications as $noti)
                <a href="{{$noti->route_url}}">
                    <div class="listNoti__itemNoti">
                        <p class="itemNoti__text">{{$noti->note}}</p>
                        <p class="itemNoti__time">{{date('Y-m-d H:i', strtotime($noti->created_at))}}</p>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
