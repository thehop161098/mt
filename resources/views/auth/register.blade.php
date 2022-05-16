@extends('layouts.app')

@section('content')
<div class="fullRegister">
    <div class="boxRegister">
        <div class="boxRegister__form">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                @foreach ($errors->all() as $error)
                <div class="form__error">
                    <p class="error__text">{{ $error }}</p>
                </div>
                @break
                @endforeach

                <div class="form__boxInput">
                    <p class="boxInput__label">Full Name <sup class="boxInput__required">*</sup></p>
                    <input class="boxInput__input" placeholder="Enter your fullname" type="text" required autocomplete="fullname" autofocus name="fullname" value="{{ old('fullname') }}" />
                </div>
                
                <div class="form__boxInput">
                    <p class="boxInput__label">{{ __('Email') }} <sup class="boxInput__required">*</sup></p>
                    <input class="boxInput__input" placeholder="Enter your email" type="email" required autocomplete="email" autofocus name="email" value="{{ old('email') }}" />
                </div>

                <div class="form__boxInput">
                    <p class="boxInput__label">{{ __('Password') }} <sup class="boxInput__required">*</sup></p>
                    <input class="boxInput__input" placeholder="Enter your password" type="password" name="password" required autocomplete="new-password" />
                </div>
                <div class="form__boxInput">
                    <p class="boxInput__label">{{ __('Confirm Password') }} <sup class="boxInput__required">*</sup>
                    </p>
                    <input class="boxInput__input" placeholder="Enter your password confirm" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>

                <div class="form__boxInput">
                    <p class="boxInput__label">{{ __('Referral Code') }} <sup class="boxInput__required">*</sup></p>
                    <input required class="boxInput__input" placeholder="Enter your referral code" type="text" name="referral_code" value="{{ old('referral_code', null) != null ? old('referral_code') : app('request')->input('referral_code') }}" />
                </div>
                <div class="form__acceptPolicy">
                    <input class="acceptPolicy__checked" type="checkbox" required />
                    <label for="policy" class="acceptPolicy__text">I agree to <a href="javascript:void(0)" class="acceptPolicy__link">the
                            terms of use</a> & <a href="javascript:void(0)" class="acceptPolicy__link">privacy policy</a></label>
                </div>
                <div class="form__btn">
                    <button class="btn register" type="submit">{{ __('Register') }}</button>
                    <a href="{{ route('login') }}">
                        <button class="btn login" type="button">Back to Login</button>
                    </a>
                </div>
        </div>
    </div>
</div>
</div>
@endsection
