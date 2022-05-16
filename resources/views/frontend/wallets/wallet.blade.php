@extends('frontend.wallets.index')

@section('title')
    <p class="title__text">Wallet Management</p>
@endsection
@section('wallet')
    <div id="mainWallet">
        <div class="blockWalletCoin">
            <div class="blockWalletCoin__header">
                <div class="header__currency">
                    <p class="currency__text">Currency</p>
                </div>
                <div class="header__balance">
                    <p class="balance__text">Balance</p>
                </div>
                <div class="header__tool">
                    <p class="tool__text">Tool</p>
                </div>
            </div>
            <div class="blockWalletCoin__listCoin">
                @if(isset($wallets) && ! $wallets->isEmpty())
                    @foreach($wallets as $wallet)
                        <div class="listCoin__item">
                            <div class="item__nameCoin">
                                <img class="nameCoin__img"
                                     src="{{ $wallet->image }}"/>
                                <p class="nameCoin__text">{{ !empty($wallet->nameOther) ? $wallet->nameOther : $wallet->coin }}</p>
                            </div>
                            <div class="item__balance">
                                <p class="balance__number">{{ number_format($wallet->balance, 8) }}</p>
                            </div>
                            <div class="item__listTool">
                                <div class="listTool__btn">
                                    <button
                                        onclick="onShowModalDeposit('{{ $wallet->nameOther ?? $wallet->coin }}', '{{ $wallet->code }}')"
                                        class="btnTool btnDeposit clickable">Deposit
                                    </button>
                                    <button
                                        onclick="onShowModalWithdraw('{{ $wallet->nameOther ?? $wallet->coin }}', '{{ $wallet->balance }}', '{{ $wallet->code }}')"
                                        class="btnTool btnWithdraw clickable">Withdraw
                                    </button>
                                    {{--                                                <button class="btnTool btnConvert">Convert</button>--}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="noResult">NO RESULTS FOUND</p>
                @endif
            </div>
        </div>

        <div class="blockTransHistory">
            <div class="blockTransHistory__header">
                <div class="header__title">
                    <p class="title__text">Withdrawal History</p>
                </div>
                {{--        <div class="header__filter">--}}
                {{--            <div class="filter__currency">--}}
                {{--                <input type="checkbox" class="currency__input cbDropdown"/>--}}
                {{--                <div class="currencySelect__item">--}}
                {{--                    <p class="item__text">Currency</p>--}}
                {{--                    <img class="currency__showList"--}}
                {{--                         src="{{ asset('frontend/images/icons/icDropdown.png') }}"/>--}}
                {{--                </div>--}}
                {{--                <div class="currency__listItem">--}}
                {{--                    <div class="currency__item">--}}
                {{--                        <p class="item__text">All</p>--}}
                {{--                    </div>--}}
                {{--                    <div class="currency__item">--}}
                {{--                        <p class="item__text">BTC</p>--}}
                {{--                    </div>--}}
                {{--                    <div class="currency__item">--}}
                {{--                        <p class="item__text">ETH</p>--}}
                {{--                    </div>--}}
                {{--                    <div class="currency__item">--}}
                {{--                        <p class="item__text">USDT</p>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                {{--            </div>--}}
                {{--            <div class="filter__type">--}}
                {{--                <input type="checkbox" class="type__input cbDropdown"/>--}}
                {{--                <div class="typeSelect__item">--}}
                {{--                    <p class="item__text">Type</p>--}}
                {{--                    <img class="type__showList"--}}
                {{--                         src="{{ asset('frontend/images/icons/icDropdown.png') }}"/>--}}
                {{--                </div>--}}
                {{--                <div class="type__listItem">--}}
                {{--                    <div class="type__item">--}}
                {{--                        <p class="item__text">All</p>--}}
                {{--                    </div>--}}
                {{--                    <div class="type__item">--}}
                {{--                        <p class="item__text">Deposit</p>--}}
                {{--                    </div>--}}
                {{--                    <div class="type__item">--}}
                {{--                        <p class="item__text">Withdraw</p>--}}
                {{--                    </div>--}}
                {{--                    <div class="type__item">--}}
                {{--                        <p class="item__text">Send</p>--}}
                {{--                    </div>--}}
                {{--                    <div class="type__item">--}}
                {{--                        <p class="item__text">Receive</p>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
                {{--            </div>--}}
                {{--        </div>--}}
            </div>
            <div class="blockTransHistory__table table-responsive">
                @include('frontend.wallets.transaction')
            </div>
        </div>
    </div>

    {{-- Modal Deposit--}}
    <div class="modal fade" id="modalDeposit" tabindex="-1" role="dialog">
        <div class="modal-dialog modalDeposit" role="document">
            <div class="modalContent">
                <div class="modalContent__title">
                    <p class="title__text">Deposit</p>
                    <button class="title__btnClose" data-dismiss="modal">
                        <img class="btnClose__img" src="{{ asset('frontend/images/icons/icClose.png') }}"/>
                    </button>
                </div>
                <div class="modalContent__form">
                    <div class="form__boxInput">
                        <p class="boxInput__label">Currency</p>
                        <input class="boxInput__input" value="BTC" readonly id="md-dps-currency"/>
                    </div>
                    <div class="form__boxInput">
                        <p class="boxInput__label">Address</p>
                        <textarea class="boxInput__input ipTA" value=""
                                  id="md-dps-address"
                                  readonly></textarea>
                        <button onclick="onCopied()" class="boxInput__btnCoppy">Copy</button>
                    </div>
                    <div class="form__boxQr">
                        <img class="boxQr__img"
                             id="md-dps-qr"
                             src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d0/QR_code_for_mobile_English_Wikipedia.svg/1200px-QR_code_for_mobile_English_Wikipedia.svg.png"/>
                    </div>
                    <p class="deposit_text">Send only BNB on BEP20 to this deposit address. Sending any other
                        currency to this address may result in the loss of your deposit. The deposit may take from
                        few minutes to half of hour, please be patient.</p>
                </div>
            </div>
        </div>
    </div>
    {{-- /- Modal Deposit--}}

    {{-- Modal Withdraw--}}
    <div class="modal fade" id="modalWithdraw" tabindex="-1" role="dialog">
        <div class="modal-dialog modalWithdraw" role="document">
            <div class="modalContent">
                <div class="modalContent__title">
                    <p class="title__text">Withdraw</p>
                    <button class="title__btnClose" data-dismiss="modal">
                        <img class="btnClose__img" src="{{ asset('frontend/images/icons/icClose.png') }}"/>
                    </button>
                </div>
                <div class="modalContent__form">
                    <div class="form__boxInput">
                        <p class="boxInput__label">Wallet</p>
                        <div class="boxInput__blockSl">
                            <div class="blockSl__selectAccount">
                                <input type="hidden" value="{{config('constants.main_wallet')}}" id="type-wallet"
                                       name="wallet"/>
                                <input type="checkbox" class="selectAccount__check"/>
                                <p class="selectAccount__itemSelect selected-account-text">Bit</p>
                                <img src="{{ asset('frontend/images/icons/icDropdown.png') }}"
                                     class="selectAccount__dropdown"/>
                                <div class="selectAccount__list">
                                    <div class="list__item item-type-wallet"
                                         data-wallet="{{config('constants.main_wallet')}}"
                                         data-amount="{{$wallet_main}}">Bit
                                    </div>
                                    <div class="list__item item-type-wallet"
                                         data-wallet="{{config('constants.discount_wallet')}}"
                                         data-amount="{{$wallet_discount}}">Discount
                                    </div>
                                </div>
                            </div>
                            <input class="boxInput__inputMoney" value="${{$wallet_main}}" readonly/>
                        </div>
                    </div>
                    <div class="form__boxInput">
                        <p class="boxInput__label">Currency</p>
                        <div class="boxInput__blockSl">
                            <div class="blockSl__selectAccount">
                                <input type="hidden" value="BEP20" id="network"
                                       name="network"/>
                                <input type="checkbox" class="selectAccount__check"/>
                                <p class="selectAccount__itemSelect selected-network-text">BEP20</p>
                                <img src="{{ asset('frontend/images/icons/icDropdown.png') }}"
                                     class="selectAccount__dropdown"/>
                                <div class="selectAccount__list">
                                    <div class="list__item item-network" data-network="BEP20">BEP20</div>
                                </div>
                            </div>
                            <input class="boxInput__input text-uppercase text-right" value="BNB" readonly
                                   id="md-wr-currency"/>
                            <input type="hidden" value="" id="md-wr-code"/>
                        </div>
                    </div>
                    <div class="form__boxInput">
                        <p class="boxInput__label">Address</p>
                        <input class="boxInput__input" type="text" placeholder="Enter Address" id="md-wr-address"/>
                    </div>
                    <div class="form__boxInput">
                        <p class="boxInput__label">Amount</p>
                        <input class="boxInput__input" id="md-wr-amount" type="text" placeholder="Enter Amount"
                               value="0"
                               onkeypress='return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46'
                               data-dojo-type="dojox.mobile.TextBox" type="number" pattern="\d*"
                        />
                    </div>
                    <div class="form__boxInfoWithdraw">
                        <div class="boxInfoWithdraw__item">
                            <p class="item__label">Fee (<?= $fee_withdraw ?>%):</p>
                            <p class="item__number text-uppercase">
                                <span id="md-wr-fee">0.00</span>
                                <span class="md-wr-unit">N/A</span>
                            </p>
                        </div>
                        <div class="boxInfoWithdraw__item">
                            <p class="item__label">The remaining amount :</p>
                            <p class="item__number text-uppercase">
                                <span id="md-wr-remaining">0.00</span>
                                <span class="md-wr-unit">N/A</span>
                            </p>
                        </div>
                    </div>
                    <div class="withdraw_note">Remember us, I only accept wallets USDT (ERC20 or BEP20)</div>
                    <div class="form__btn">
                        <button class="btn__text" id="md-wr-submit">Withdraw</button>
                    </div>
                    @if (!$isEnabled2FA)
                        <div class="form__note">
                            <p class="note__text">You must have 2FA enabled to make a withdrawal.</p>
                            <a href="{{route('user.edit')}}" class="note__link">Enable Now</a>
                        </div>
                    @endif;
                </div>
            </div>
        </div>
    </div>
    {{-- /- Modal Withdraw --}}
@endsection
