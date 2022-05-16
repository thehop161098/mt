@extends('frontend.wallets.index')

@section('title')
    <p class="title__text">Wallet Exchange Management</p>
@endsection

@section('wallet')
    <div id="exchangeWallet">
        <div class="blockExchangeCoin">
            <div class="blockExchangeCoin__header">
                <div class="header__account">
                    <p class="account__text">Account</p>
                </div>
                <div class="header__balance">
                    <p class="balance__text">Balance</p>
                </div>
                <div class="header__tool">
                    <p class="tool__text">Tool</p>
                </div>
            </div>
            <div class="blockExchangeCoin__listAccount">
                <div class="listAccount__item">
                    <div class="item__nameAccount">
                        <div class="nameAccount__boxUnit demoAccount">
                            <p class="boxUnit__unit">$</p>
                        </div>
                        <p class="nameAccount__text">Demo Account (USDT)</p>
                    </div>
                    <div class="item__balance">
                        <p id="amountRefill" class="balance__number">{{ number_format($accountDemo, 2) }}</p>
                    </div>
                    <div class="item__listTool">
                        <div class="listTool__btn">
                            <button id="btnRefill" class="btnTool btnRefill">Refill</button>
                        </div>
                    </div>
                </div>
                <div class="listAccount__item">
                    <div class="item__nameAccount">
                        <div class="nameAccount__boxUnit liveAccount">
                            <p class="boxUnit__unit">$</p>
                        </div>
                        <p class="nameAccount__text">Live account (Bit/USDT)</p>
                    </div>
                    <div class="item__balance">
                        <p class="balance__number">{{ number_format($accountLive, 2) }}</p>
                    </div>
                    <div class="item__listTool">
                        <div class="listTool__btn">
                            {{--                                        <a href="">--}}
                            {{--                                            <button class="btnTool btnConvert">Convert</button>--}}
                            {{--                                        </a>--}}
                            <button class="btnTool btnInternalTransfer clickable" data-toggle="modal"
                                    data-target="#modalInternalTransfer">Internal Transfer
                            </button>
                        </div>
                    </div>
                </div>
                <div class="listAccount__item">
                    <div class="item__nameAccount">
                        <div class="nameAccount__boxUnit promotionAccount">
                            <p class="boxUnit__unit">$</p>
                        </div>
                        <p class="nameAccount__text">Promotion Account (USDT)</p>
                    </div>
                    <div class="item__balance">
                        <p class="balance__number">{{ number_format($accountPromotion, 2) }}</p>
                    </div>
{{--                    <div class="item__listTool">--}}
{{--                        <div class="listTool__btn">--}}
{{--                            <div class="boxInputTool">--}}
{{--                                <input class="boxInputTool__input" type="text"--}}
{{--                                       placeholder="Enter gift code"/>--}}
{{--                                <button class="boxInputTool__btn">Confirm</button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
        <div class="blockInternalTransHitory">
            <div class="blockInternalTransHitory__header">
                <div class="header__title">
                    <p class="title__text">Internal Transaction History</p>
                </div>
            </div>
            <div class="blockInternalTransHitory__table table-responsive">
                @include('frontend.wallets.pagination_data')
            </div>
        </div>
    </div>

    {{-- Modal Internal Transfer --}}
    <div class="modal fade" id="modalInternalTransfer" tabindex="-1" role="dialog">
        <div class="modal-dialog modalInternalTransfer" role="document">
            <div class="modalContent">
                <div class="modalContent__title">
                    <p class="title__text">Internal Transfer</p>
                    <button class="title__btnClose" data-dismiss="modal">
                        <img class="btnClose__img" src="{{ asset('frontend/images/icons/icClose.png') }}"/>
                    </button>
                </div>
                <div class="modalContent__form">
                    <div class="form__boxInput">
                        <p class="boxInput__label">Money BIT</p>
                        <input class="boxInput__input" value="${{number_format($wallet_main, 2)}}" readonly/>
                    </div>
                    <div class="form__boxInput">
                        <p class="boxInput__label">Account type</p>
                        <input class="boxInput__input" value="Live Account (BIT/USDT)" readonly/>
                    </div>
                    <div class="form__boxInput">
                        <p class="boxInput__label">Wallet address</p>
                        <div class="boxWalletAddress">
                            <span>Bit</span>
                            <input class="boxInput__input md-it-code" placeholder="Enter wallet address"/>
                        </div>
                    </div>
                    <div class="form__boxInput">
                        <p class="boxInput__label">Amount</p>
                        <input class="boxInput__input md-it-amount" type="text" placeholder="Enter Amount" value="0"
                               onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode == 46'
                               data-dojo-type="dojox.mobile.TextBox" type="number" pattern="\d*"
                        />
                    </div>
                    <div class="form__btn">
                        <button class="btn__text md-it-submit">Transfer</button>
                    </div>
                    @if (!$isEnabled2FA)
                        <div class="form__note">
                            <p class="note__text">You must have 2FA enabled to make a transfer.</p>
                            <a href="{{route('user.edit')}}" class="note__link">Enable Now</a>
                        </div>
                    @endif;
                </div>
            </div>
        </div>
    </div>
    {{-- /-- Modal Internal Transfer --}}
@endsection
