@extends('frontend.layouts.app')

@section('css')
    <link href="{{asset('frontend/css/agency.css')}}" rel="stylesheet">
    <link href="{{ asset('frontend/css/history.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div id="agency">
        @include('frontend.agency.modal')

        <div class="pageAgency">
            <div class="title">
                <p class="title__text">Affiliate Marketing</p>
                <div class="title__tool">
                    <button class="tool__btnUpgrade doneBuy {{ !$is_buy_agency ? 'btnDisableBuy' : ''}}"
                            data-toggle="modal" data-target="#modalUpgradeAgency">
                        <img class="btnUpgrade__img" src="{{asset('frontend/images/icons/icUpgradeAgency.png')}}"/>
                        Upgrade with {{$amount}} Bit
                    </button>
                </div>
            </div>
            <div class="content">
                <div class="content__blockRank">
                    <div class="blockRank__infoRank">
                        <p class="infoRank__text">Affiliate Marketing level</p>
                        <p class="infoRank__level">Rank #{{$user->level}}</p>
                        <a href="">
                            <p class="infoRank__policy">Affiliate Marketing Policy</p>
                        </a>
                        <img class="infoRank__img" src="{{$user->icon_level}}"/>
                    </div>
                    <div class="blockRank__rose">
                        <p class="rose__note">The commission received will be calculated on the trading volume of your
                            subordinates</p>
                    </div>
                    <div class="blockRank__upgrade">
                        <button class="updrage__btnUpgrade doneBuy {{ !$is_buy_agency ? 'btnDisableBuy' : ''}}"
                                data-toggle="modal" data-target="#modalUpgradeAgency">
                            <img class="btnUpgrade__img" src="{{asset('frontend/images/icons/icUpgradeAgency.png')}}"/>
                            Buy Affiliate Marketing Rights For Only {{$amount}} Bit
                        </button>
                    </div>
                </div>
                <div class="content__blockReferralCode">
                    <div class="blockReferralCode_n_code">
                        <div class="code__title">
                            <p class="title__text">Introduce Now !</p>
                        </div>
                        <div class="code__listInfo">
                            <div class="listInfo__item">
                                <div class="title-horizontal">
                                    <p class="item__label">Invitation link</p>
                                    <button onclick="onCopied('referralCodeUser')"
                                            class="boxInput__btnCoppy {{ !$is_buy_agency ? '' : 'hidden'}}">
                                        Copied
                                    </button>
                                </div>
                                <input id="referralCodeUser" class="item__code"
                                       value="{{ !$is_buy_agency ? $user->referral_url : '************************'}}"
                                       readonly/>
                                <p class="item__note">Buy affiliate Marketing to get invitation link</p>
                            </div>
                            <div class="listInfo__item">
                                <div class="title-horizontal">
                                    <p class="item__label">Invitation Code</p>
                                    <button onclick="onCopied('codeUser')"
                                            class="boxInput__btnCoppy {{ !$is_buy_agency ? '' : 'hidden'}}">Copied
                                    </button>
                                </div>
                                <input id="codeUser" class="item__code"
                                       value="{{ !$is_buy_agency ? $user->code : '************'}}"
                                       readonly/>
                                <p class="item__note">Buy affiliate Marketing to get invitation code</p>
                            </div>
                        </div>
                    </div>
                    <div class="blockReferralCode__friend">
                        <div class="friend__title">
                            <p class="title__text">Friend Referral</p>
                        </div>
                        <div class="friend__tutorial">
                            <div class="tutorial__item">
                                <div class="item__boxImg">
                                    <img class="boxImg__img" src="{{asset('frontend/images/icons/icRFStep1.png')}}"/>
                                </div>
                                <div class="item__text">
                                    <p class="text__title">Send referral link to friends</p>
                                    <p class="text__note">Introduce your friends to join ByBit through referral link</p>
                                </div>
                            </div>
                            <div class="tutorial__item">
                                <div class="item__boxImg">
                                    <img class="boxImg__img" src="{{asset('frontend/images/icons/icRFStep2.png')}}"/>
                                </div>
                                <div class="item__text">
                                    <p class="text__title">Register</p>
                                    <p class="text__note">Referred people have successfully registered via your referral
                                        link</p>
                                </div>
                            </div>
                            <div class="tutorial__item">
                                <div class="item__boxImg">
                                    <img class="boxImg__img" src="{{asset('frontend/images/icons/icRFStep3.png')}}"/>
                                </div>
                                <div class="item__text">
                                    <p class="text__title">Receive commissions</p>
                                    <p class="text__note">Receive commissions according to the referral's
                                        transaction</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="blockTree">
                <div class="blockTree__title">
                    <p class="title__text">List Of Subordinates</p>
                    <p class="title__totalTrade">Total Trade : ${{number_format($totalChildTrade, 2)}}</p>
                </div>
                <div class="blockTree__content">
                    <div id="treeList">
                        @if(isset($tree) && !empty($tree))
                            <div class="form-group">
                                <input class="form-control" id="listSearch" type="search"
                                       placeholder="Search email ..."/>
                            </div>
                            <div class="filter__list-wrap">
                                {!! $tree['view'] !!}

                            </div>
                        @else
                            <p class="content__item text-center">No results found</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@section('js')
    <script src="{{asset('frontend/js/agency.js')}}"></script>
@endsection

@endsection
