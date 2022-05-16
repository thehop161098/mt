<div class="modal fade" id="modalUpgradeAgency" tabindex="-1" role="dialog">
    <div class="modal-dialog modalUpgradeAgency" role="document">
        <div class="modalUpgradeAgency__boxContent">
            <input id="urlAgency" type="hidden" value="{{route('agency.buyAgency')}}">
            <div class="boxContent__title">
                <p class="title__text">Upgrade Agency</p>
                <button class="title__btnClose" data-dismiss="modal">
                    <img class="btnClose__img" src="{{asset('frontend/images/icons/icClose.png')}}" />
                </button>
            </div>
            <div class="boxContent__form">
                <div class="form__note">
                    <p class="note__text">Buy Agent Rights to receive agency commissions and transaction commissions for only ${{$amount}}</p>
                </div>
                <div class="form__btn">
                    <button id="btnConfirm" class="btnConfirm">Upgrade Now</button>
                </div>
            </div>
        </div>
    </div>
</div>