@extends('frontend.layouts.app')

@section('css')
<link href="{{asset('frontend/css/convert.css')}}" rel="stylesheet">
@endsection

@section('content')
<div id="convert">
    <div class="pageAgency">
        <div class="title">
            <p class="title__text">Convert Currencies</p>
        </div>
        <div class="content">
            <div class="blockConvert">
                <div class="blockConvert__valueConvert">
                    <input type="checkbox" class="valueConvert__changeCurrency" />
                    <div class="valueConvert__btnChange">
                        <img class="btnChange__img" src="{{asset('frontend/images/icons/icChangeConvert.png')}}" />
                    </div>
                    <div class="valueConvert__blockSend">
                        <div class="blockSend__send">
                            <div class="send__formSend">
                                <p class="formSend__label">Send</p>
                                <div class="formSend__inputCoin">
                                    <input placeholder="Enter money send" value="1" class="input__send">
                                    <div class="input__select">
                                        <input class="select__checkCoin cbDropdown" type="checkbox" />
                                        <p class="select__itemSelect">BTC</p>
                                        <img class="select__showList" src="{{asset('frontend/images/icons/icDropdown.png')}}">
                                        <div class="select__listSelect">
                                            <p class="listSelect__item">BTC</p>
                                            <p class="listSelect__item">ETH</p>
                                            <p class="listSelect__item">USDT</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="formSend__inputMoney">
                                    <input placeholder="Enter money send" value="1" class="input__send">
                                    <div class="input__money">
                                        <p class="money__text">USD</p>
                                    </div>
                                </div>
                            </div>
                            <div class="send__currentBalance">
                                <p class="currentBalance__text">Available : 0.00000000
                                    <span class="text__coin">BTC</span>
                                    <span class="text__money">USD</span>
                                </p>
                                <button class="currentBalance__btnAll">All</button>
                            </div>
                        </div>
                    </div>
                    <div class="valueConvert__blockReceive">
                        <div class="blockReceive__receive">
                            <div class="receive__formReceive">
                                <p class="formReceive__label">Receive</p>
                                <div class="formReceive__inputMoney">
                                    <input placeholder="Enter money receive" value="1" class="input__receive">
                                    <div class="input__money">
                                        <p class="money__text">USD</p>
                                    </div>
                                </div>
                                <div class="formReceive__inputCoin">
                                    <input placeholder="Enter money send" value="1" class="input__receive">
                                    <div class="input__select">
                                        <input class="select__checkCoin cbDropdown" type="checkbox" />
                                        <p class="select__itemSelect">BTC</p>
                                        <img class="select__showList" src="{{asset('frontend/images/icons/icDropdown.png')}}">
                                        <div class="select__listSelect">
                                            <p class="listSelect__item">BTC</p>
                                            <p class="listSelect__item">ETH</p>
                                            <p class="listSelect__item">USDT</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="receive__currentBalance">
                                <p class="currentBalance__text">Available : 0.00000000
                                    <span class="text__coin">BTC</span>
                                    <span class="text__money">USD</span>
                                </p>
                                <button class="currentBalance__btnAll">All</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="blockConvert__btnConvert">
                    <button class="btnConvert__text">
                        <p class="text__countDown">0:30 Convert</p>
                        <!-- <p class="text__refresh">Refresh</p> -->
                    </button>
                </div>
            </div>
            <div class="blockConvertHistory">
                <div class="blockConvertHistory__header">
                    <div class="header__title">
                        <p class="title__text">Convert History</p>
                    </div>
                </div>
                <div class="blockConvertHistory__table table-responsive">
                    <table class="table" id="convertHistory">
                        <thead class="table__header">
                            <tr class="header__listItem">
                                <th class="header__item">Time</th>
                                <th class="header__item">Sent</th>
                                <th class="header__item">Received</th>
                                <th class="header__item">Rate</th>
                                <th class="header__item">From</th>
                                <th class="header__item">To</th>
                            </tr>
                        </thead>
                        <tbody class="table__content">
                            <tr class="content__listItem">
                                <td class="content__item">30/10/2020 15:03</td>
                                <td class="content__item">25 USD</td>
                                <td class="content__item">0.000025 BTC</td>
                                <td class="content__item">1 : 0.0001</td>
                                <td class="content__item">4x93udxx0202</td>
                                <td class="content__item">7x7200238392</td>
                            </tr>
                            <tr class="content__listItem">
                                <td class="content__item">30/10/2020 15:03</td>
                                <td class="content__item">4 BTC</td>
                                <td class="content__item">60,324.232 USD</td>
                                <td class="content__item">1 : 15,435</td>
                                <td class="content__item">4x93udxx0202</td>
                                <td class="content__item">7x7200238392</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection