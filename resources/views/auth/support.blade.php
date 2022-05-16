@extends('layouts.app')

@section('css')
    <link href="{{ asset('frontend/css/google2fa.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="fullSupport">
        <div class="boxSupport">
            <div class="boxSupport__form">
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="form__error">
                            <p class="error__text">{{ $error }}</p>
                        </div>
                    @endforeach
                @endif
                <form action="{{ route('support/store') }}" method="POST">
                    @csrf
                    <div class="form__boxInput">
                        <div class="boxInput__listInput">
                            <div class="listInput__item">
                                <p class="item__label">Name</p>
                                <input name="full_name" type="text" class="item__input" placeholder="Enter your name"/>
                            </div>
                            <div class="listInput__item">
                                <p class="item__label">Email</p>
                                <input name="email" type="email" class="item__input" placeholder="Enter your email"/>
                            </div>
                            <div class="listInput__item">
                                <p class="item__label">Phone Number</p>
                                <input name="phone" inputmode="numeric" pattern="[0-9]*" class="item__input"
                                       inputmode="numeric" pattern="[0-9]"
                                       placeholder="Enter your phone number"/>
                            </div>
                            <div class="listInput__item">
                                <p class="item__label">Content</p>
                                <textarea name="content" class="item__inputContent" rows="3"
                                          placeholder="Enter content"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form__btn">
                        <button class="btn confirm" type="submit">Submit a support request</button>
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
