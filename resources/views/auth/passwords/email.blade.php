@extends('layouts.app')

@section('content')
<div class="fullForgetPassword">
    <div class="boxForgetPassword">
        <div class="boxForgetPassword__form">
            @if (session('status'))
            <div class="form__error">
                <img class="error__img" src="{{ asset('frontend/images/icons/icError.png') }}" />
                <p class="error__text">{{ session('status') }}</p>
            </div>
            @endif
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                @error('email')
                <div class="form__error">
                    <p class="error__text">Email not exits</p>
                </div>
                @enderror
                <div class="form__boxInput">
                    <p class="boxInput__label">Email<sup class="boxInput__required">*</sup></p>
                    <input id="email" type="email" class="boxInput__input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Enter your email" required autocomplete="email" autofocus>
                </div>
                <div class="form__btn">
                    <button type="submit" class="btn confirm">Reset Password</button>
                    <a href="{{route('login')}}">
                        <button type="button" class="btn login">Back to Login</button>
                    </a>
                </div>
                <!-- <div class="form__boxNote">
                <p class="boxNote__text">A new password has been sent to you. Please check your email and login again.</p>
            </div>
            <div class="form__btn">
                <a href="login.php">
                    <button class="btn login">Back to Login</button>
                </a>
            </div> -->
            </form>
        </div>
    </div>
</div>

<!-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> -->

@endsection