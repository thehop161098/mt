@extends('layouts.app')

@section('css')
    <link href="{{ asset('frontend/css/verifyemail.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="fullVerifyEmail">
        <div class="boxOverlay"></div>
        <div class="boxVerifyEmail">
            <div class="boxVerifyEmail__form">
                @if (session('resent'))
                    <div class="form__boxSuccess" role="alert">
                        {{ __('A fresh verification link has been sent to your email address.') }}
                    </div>
                @endif
                <div class="form__boxNote">
                    <p class="boxNote__text">{{ __('Verify Your Email Address') }},</p>
                    <p class="boxNote__text">{{ __('Before proceeding, please check your email for a verification link.') }}
                        {{ __('If you did not receive the email') }},</p>
                </div>
                <div class="form__btn">
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn confirm">{{ __('click here to request another') }}</button>
                        <a href="{{route('support/index')}}" class="btn support">
                            Support
                        </a>
                        <a href="{{ route('logout') }}" class="btn login" onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();
                                            ">
                            Back to Login
                        </a>
                    </form>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
