@extends('layouts.app')

@section('content')
    <style>
        .boxLogin {
            min-height: auto;
            padding: 20px 15px 0;
        }
    </style>
    <div class="fullLogin">
        <div class="boxOverlay"></div>
        <div class="boxLogin">
            <p class="boxInput__label">{{ isset($message) ? $message : "" }}</p>
            <div class="form__btn">
                <a href="{{ route('home') }}">
                    <button class="btn register" type="button">Back To Home</button>
                </a>
            </div>
        </div>
    </div>
@endsection
