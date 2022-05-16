@extends('layouts.app')

@section('content')
    <div class="fullLogin">
        <div class="boxLogin">
            <div class="boxLogin__logo">
                <div class="logo__icon">
                    <svg viewBox="0 0 16 16" class="circleColor">
                        <g>
                            <path d="M8,0 C12.418278,0 16,3.581722 16,8 C16,12.418278 12.418278,16 8,16 C3.581722,16 0,12.418278 0,8 C0,3.581722 3.581722,0 8,0 L8,0 Z M7.99952276,4.06943864 C10.1705754,4.06943864 11.9305614,5.82942457 11.9305614,8.00047724 C11.9305614,10.1715299 10.1705754,11.9315158 7.99952276,11.9315158 C5.82847009,11.9315158 4.06848416,10.1715299 4.06848416,8.00047724 C4.06848416,5.82942457 5.82847009,4.06943864 7.99952276,4.06943864 L7.99952276,4.06943864 Z" fill="#EF3059"></path>
                        </g>
                    </svg>
                    <svg viewBox="0 0 16 16" class="circleColor">
                        <g>
                            <path d="M8,0 C12.418278,0 16,3.581722 16,8 C16,12.418278 12.418278,16 8,16 C3.581722,16 0,12.418278 0,8 C0,3.581722 3.581722,0 8,0 L8,0 Z M7.99952276,4.06943864 C10.1705754,4.06943864 11.9305614,5.82942457 11.9305614,8.00047724 C11.9305614,10.1715299 10.1705754,11.9315158 7.99952276,11.9315158 C5.82847009,11.9315158 4.06848416,10.1715299 4.06848416,8.00047724 C4.06848416,5.82942457 5.82847009,4.06943864 7.99952276,4.06943864 L7.99952276,4.06943864 Z" fill="#FFCB00"></path>
                        </g>
                    </svg>
                    <svg viewBox="0 0 16 16" class="circleColor">
                        <g>
                            <path d="M8,0 C12.418278,0 16,3.581722 16,8 C16,12.418278 12.418278,16 8,16 C3.581722,16 0,12.418278 0,8 C0,3.581722 3.581722,0 8,0 L8,0 Z M7.99952276,4.06943864 C10.1705754,4.06943864 11.9305614,5.82942457 11.9305614,8.00047724 C11.9305614,10.1715299 10.1705754,11.9315158 7.99952276,11.9315158 C5.82847009,11.9315158 4.06848416,10.1715299 4.06848416,8.00047724 C4.06848416,5.82942457 5.82847009,4.06943864 7.99952276,4.06943864 L7.99952276,4.06943864 Z" fill="#00F264"></path>
                        </g>
                    </svg>
                    <svg viewBox="0 0 16 16" class="circleColor">
                        <g>
                            <path d="M8,0 C12.418278,0 16,3.581722 16,8 C16,12.418278 12.418278,16 8,16 C3.581722,16 0,12.418278 0,8 C0,3.581722 3.581722,0 8,0 L8,0 Z M7.99952276,4.06943864 C10.1705754,4.06943864 11.9305614,5.82942457 11.9305614,8.00047724 C11.9305614,10.1715299 10.1705754,11.9315158 7.99952276,11.9315158 C5.82847009,11.9315158 4.06848416,10.1715299 4.06848416,8.00047724 C4.06848416,5.82942457 5.82847009,4.06943864 7.99952276,4.06943864 L7.99952276,4.06943864 Z" fill="#1DD1FF"></path>
                        </g>
                    </svg>
                    <svg viewBox="0 0 16 16" class="squareColor">
                        <g>
                            <rect width="16" height="16" stroke="#EF3059" />
                        </g>
                    </svg>
                    <svg viewBox="0 0 16 16" class="squareColor">
                        <g>
                            <rect width="16" height="16" stroke="#FFCB00" />
                        </g>
                    </svg>
                    <svg viewBox="0 0 16 16" class="squareColor">
                        <g>
                            <rect width="16" height="16" stroke="#00F264" />
                        </g>
                    </svg>
                    <svg viewBox="0 0 16 16" class="squareColor">
                        <g>
                            <rect width="16" height="16" stroke="#1DD1FF" />
                        </g>
                    </svg>
                    <svg viewBox="0 0 16 16" class="plusColor">
                        <line x1="0" y1="8" x2="16" y2="8" stroke="#EF3059" ></line>
                        <line x1="8" y1="0" x2="8" y2="16" stroke="#EF3059" ></line>
                    </svg>
                    <svg viewBox="0 0 16 16" class="plusColor">
                        <line x1="0" y1="8" x2="16" y2="8" stroke="#FFCB00"></line>
                        <line x1="8" y1="0" x2="8" y2="16" stroke="#FFCB00" ></line>
                    </svg>
                    <svg viewBox="0 0 16 16" class="plusColor">
                        <line x1="0" y1="8" x2="16" y2="8" stroke="#00F264"></line>
                        <line x1="8" y1="0" x2="8" y2="16" stroke="#00F264" ></line>
                    </svg>
                    <svg viewBox="0 0 16 16" class="plusColor">
                        <line x1="0" y1="8" x2="16" y2="8" stroke="#1DD1FF"></line>
                        <line x1="8" y1="0" x2="8" y2="16" stroke="#1DD1FF"></line>
                    </svg>
                </div>
                
                <div class="logo__boxLogo">
                    @php
                        $pathLogo = isset(config('settings')['logoFE']) ? 'images/settings/'.config('settings')['logoFE'] : '';
                    @endphp
                    <img class="logo__img" src="{{asset($pathLogo)}}"/>
                </div>
            </div>
            <div class="boxLogin__form">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    @foreach ($errors->all() as $error)
                        <div class="form__error">
                            <p class="error__text">{{ $error }}</p>
                        </div>
                    @endforeach
                    <div class="form__boxInput">
                        <p class="boxInput__label">{{ __('Email') }}</p>
                        <input class="boxInput__input" placeholder="Enter your email" type="email" required
                               autocomplete="email" name="email" autofocus value="{{ old('email') }}" />
                    </div>
                    <div class="form__boxInput">
                        <p class="boxInput__label">{{ __('Password') }}</p>
                        <input class="boxInput__input" placeholder="Enter your password" type="password"
                               name="password" required autocomplete="current-password"/>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="boxInput__btnForgetPassword">
                                <img class="btnForgetPassword__img"
                                     src="{{ asset('frontend/images/icons/icForgetPassword.png')  }}"/>
                            </a>
                        @endif
                    </div>
                    <div class="form__btn">
                        <button type="submit" class="btn login">
                            {{ __('Login') }}
                        </button>
                        <a href="{{ route('register')  }}">
                            <button class="btn register" type="button">{{ __('Register') }}</button>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if(session('message'))
        <script type="text/javascript">
            $(document).ready(function(){
                toastr.warning('{{session('message')}}');
            })
        </script>
    @endif
    @if(session('success_verified'))
        <script type="text/javascript">
            $(document).ready(function(){
                toastr.success('{{session('success_verified')}}');
            })
        </script>
    @endif
@endsection
