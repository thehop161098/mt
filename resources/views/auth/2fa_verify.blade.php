@extends('layouts.app')

@section('css')
    <link href="{{ asset('frontend/css/google2fa.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="fullVerifyGoogle">
        <div class="boxVerifyGoogle">
            <div class="boxVerifyGoogle__form">
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="form__error">
                            <p class="error__text">{{ $error }}</p>
                        </div>
                    @endforeach
                @endif
                <form action="{{ route('verify2fa') }}" method="POST">
                    @csrf
                    <div class="form__boxInput">
                        <p class="boxInput__label">Enter Google Code</p>
                        <div class="boxInput__listInput">
                            <input name="one_time_password" id="secret2fa" type="hidden" name="secret">
                            <div class="listInput__item">
                                <input id="focus1" autofocus autocomplete="off" class="item__input" maxlength="1"
                                       inputmode="numeric" pattern="[0-9]*"
                                       onkeyup="nextFocus(this,'focus2')"/>
                            </div>
                            <div class="listInput__item">
                                <input id="focus2" autocomplete="off" class="item__input" maxlength="1"
                                       inputmode="numeric" pattern="[0-9]*"
                                       onkeyup="nextFocus(this,'focus3', 'focus1')"/>
                            </div>
                            <div class="listInput__item">
                                <input id="focus3" autocomplete="off" class="item__input" maxlength="1"
                                       inputmode="numeric" pattern="[0-9]*"
                                       onkeyup="nextFocus(this,'focus4', 'focus2')"/>
                            </div>
                            <div class="listInput__item">
                                <input id="focus4" autocomplete="off" class="item__input" maxlength="1"
                                       inputmode="numeric" pattern="[0-9]*"
                                       onkeyup="nextFocus(this,'focus5', 'focus3')"/>
                            </div>
                            <div class="listInput__item">
                                <input id="focus5" autocomplete="off" class="item__input" maxlength="1"
                                       inputmode="numeric" pattern="[0-9]*"
                                       onkeyup="nextFocus(this,'focus6', 'focus4')"/>
                            </div>
                            <div class="listInput__item">
                                <input id="focus6" autocomplete="off" class="item__input" maxlength="1"
                                       inputmode="numeric" pattern="[0-9]*"
                                       onkeyup="nextFocus(this,'confirm', 'focus5')"/>
                            </div>
                        </div>
                    </div>
                    <div class="form__btn">
                        <button id="confirm" onclick="setCode2fa(' .listInput__item')" class="btn confirm">Confirm
                        </button>
                        <a href="{{route('support/index')}}" class="btn support">
                            Support
                        </a>
                        <a href="{{ route('logout') }}" class="btn login" onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();
                                            ">
                            Back to Login
                        </a>
                    </div>
                </form>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('frontend/js/profile/profile.js') }}"></script>
@endsection
