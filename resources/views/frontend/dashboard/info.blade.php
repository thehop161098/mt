<div class="blockDashboardInfo">
    <div class="blockDashboardInfo__totalMoney">
        <div class="totalMoney__listItem">
            <div class="listItem__itemForm">
                <p class="itemForm__label">Wallet Bit/USDT</p>
                <p class="itemForm__number">$ {{number_format($mainWallet, 2)}}</p>
            </div>
            <div class="listItem__itemForm">
                <p class="itemForm__label">Net profit</p>
                <p class="itemForm__number">$ {{number_format($totalRevenue, 2)}}</p>
            </div>
            <div class="listItem__itemForm">
                <p class="itemForm__label">Total trade personal</p>
                <p class="itemForm__number">$ {{number_format($totalTradeAmount, 2)}}</p>
            </div>
            <div class="listItem__itemForm">
                <p class="itemForm__label">Total Team trade</p>
                <p class="itemForm__number">$ {{number_format($totalChildTrade + $totalTradeAmount, 2)}}</p>
            </div>
        </div>
    </div>
    <div class="blockDashboardInfo__totalResult">
        <div class="totalResult__trade">
            <p class="trade__label">Total Trade</p>
            <p class="trade__number">{{$totalTrade}}</p>
        </div>
        <div class="totalResult__listResult">
            <div class="listResult__item">
                <div class="item__square win">
                    <p class="item__percent">{{number_format($winRound / $totalWinRound * 100, 1)}}%</p>
                </div>
                <div class="item__info">
                    <p class="info__label">Buy win</p>
                    <p class="info__number">{{$winRound}}</p>
                </div>
            </div>
            <div class="listResult__item">
                <div class="item__square draw">
                    <p class="item__percent">{{number_format($drawRound / $totalWinRound * 100, 1)}}%</p>
                </div>
                <div class="item__info">
                    <p class="info__label">Balance win</p>
                    <p class="info__number">{{$drawRound}}</p>
                </div>
            </div>
            <div class="listResult__item">
                <div class="item__square lose">
                    <p class="item__percent">{{number_format($loseRound / $totalWinRound * 100, 1)}}%</p>
                </div>
                <div class="item__info">
                    <p class="info__label">Sell win</p>
                    <p class="info__number">{{$loseRound}}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRefund" tabindex="-1" role="dialog">
    <div class="modal-dialog modalRefund" role="document">
        <div class="modalRefund__boxContent">
            <input id="urlRefund" type="hidden" value="{{route('refund.postRefund')}}">
            <div class="boxContent__title">
                <p class="title__text">Refund Money</p>
                <button class="title__btnClose" data-dismiss="modal">
                    <img class="btnClose__img" src="{{asset('frontend/images/icons/icClose.png')}}"/>
                </button>
            </div>
            <div class="boxContent__form">
                <div class="form__note">
                    <p class="note__text">Are you sure the refund?</p>
                </div>
                <div class="form__btn">
                    <button id="btnConfirmRefund" class="btnConfirm">Refund Now</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="blockDashboardAmount">
    <div class="blockDashboardAmount__info">
        <div class="info__item">
            <p class="item__label">Day</p>
            <p id="refund_day" class="item__content">{{!empty($refund->day) ? $refund->day : 0}}</p>
        </div>
        <div class="info__item">
            <p class="item__label">Percent</p>
            <p id="refund_percent" class="item__content">{{!empty($refund->percent) ? $refund->percent : 0}}%</p>
        </div>
        <div class="info__item">
            <p class="item__label">Amount</p>
            <p id="refund_amount" class="item__content">
                ${{!empty($refund->amount) ? number_format($refund->amount, 2) : 0}}</p>
        </div>
        <div class="info__item">
            <p class="item__label">Amount Refund</p>
            <p id="refund_amount_refund" class="item__content">
                ${{!empty($refund->amount_refund) ? number_format($refund->amount_refund, 2) : 0}}</p>
        </div>
        <div class="info__item">
            <p class="item__label">Date Expired</p>
            <p id="refund_date_expired"
               class="item__content">{{!empty($refund->date_expired) ? date('Y-m-d', strtotime($refund->date_expired)) : '--'}}</p>
        </div>
        <div class="info__item">
            <p class="item__label">Total Refund</p>
            @php
                if(!empty($totalLose)) {
                    $totalLose = '$' . number_format($totalLose, 2);
                    $totalLose = str_replace('$-', '-$',$totalLose);
                } else {
                    $totalLose = '$'. 0;
                }
                $isRefund = !empty(auth()->user()->refund) ? true : false;
            @endphp
            <p id="total_refund" class="item__content">{{$totalLose}}</p>
        </div>
        <div class="info__btn">
            <button id="btn_refund" data-toggle="modal" data-target="#modalRefund"
                    class="btn__itemBtn {{$isRefund ? 'doneRefund' : ''}}">Refund
            </button>
            <a href="{{route('history.refund')}}" class="btn__itemLink">
                <p class="link__text">History</p>
            </a>
        </div>
    </div>
</div>
