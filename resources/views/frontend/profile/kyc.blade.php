@extends('frontend.layouts.app')

@section('css')
    <link href="{{ asset('frontend/css/kyc.css') }}" rel="stylesheet">
@endsection

@section('content')
    @php
        $note = 'Allow jpg, png, jpeg image up to 5MB!';
    @endphp
    <div id="kyc">
        <div class="pageKYC">
            <div class="title" style="justify-content: flex-start">
                <a href="{{route('user.edit')}}"><img src="{{asset('frontend/images/icons/icBack.png')}}" width="25"
                                                      style="margin-right: 10px;"/></a>
                <p class="title__text">KYC - Please use your ID / passport</p>
            </div>
            <div class="content">
                <form action="{{route('postIdentityCard')}}" method="POST" enctype="multipart/form-data"
                      style="width: 100%">
                    @csrf
                    <div class="modalContent__form">
                        <div class="form__upload">
                            <div class="upload__item">
                                <div class="item__boxImg">
                                    <img id="portraitImg" class="boxImg__img img-thumbnail"
                                         src="{{asset($user->images['portrait'])}}"/>
                                </div>
                                <div class="item__note">
                                    <p class="note__text">Selfie</p>
                                </div>
                                @if(auth()->user()->verify == config('constants.not_verify_user'))
                                    <p class="upload__warning">{{$note}}</p>
                                    <div class="item__upload">
                                        <input id="portrait" class="upload__btnUp" name="portrait" type="file"/>
                                        <p class="upload__text">Upload</p>

                                    </div>
                                @endif
                                @error('portrait')
                                <div class="form__error">
                                    <img class="error__img" src="{{ asset('frontend/images/icons/icError.png')  }}"/>
                                    <p class="error__text">{{$message}}</p>
                                </div>
                                @enderror
                            </div>
                            <div class="upload__item">
                                <div class="item__boxImg">
                                    <img id="identity_card_back_sideImg" class="boxImg__img img-thumbnail"
                                         src="{{asset($user->images['identity_card_before'])}}"/>
                                </div>
                                <div class="item__note">
                                    <p class="note__text">Front Side</p>
                                </div>
                                @if(auth()->user()->verify == config('constants.not_verify_user'))
                                    <p class="upload__warning">{{$note}}</p>
                                    <div class="item__upload">
                                        <input id="identity_card_back_side" class="upload__btnUp"
                                               name="identity_card_before" type="file"/>
                                        <p class="upload__text">Upload</p>
                                    </div>
                                @endif
                                @error('identity_card_before')
                                <div class="form__error">
                                    <img class="error__img" src="{{ asset('frontend/images/icons/icError.png')  }}"/>
                                    <p class="error__text">{{$message}}</p>
                                </div>
                                @enderror
                            </div>
                            <div class="upload__item">
                                <div class="item__boxImg">
                                    <img id="identity_card_font_sideImg" class="boxImg__img img-thumbnail"
                                         src="{{asset($user->images['identity_card_after'])}}"/>
                                </div>
                                <div class="item__note">
                                    <p class="note__text">Back side</p>
                                </div>
                                @if(auth()->user()->verify == config('constants.not_verify_user'))
                                    <p class="upload__warning">{{$note}}</p>
                                    <div class="item__upload">
                                        <input id="identity_card_font_side" class="upload__btnUp"
                                               name="identity_card_after" type="file"/>
                                        <p class="upload__text">Upload</p>
                                    </div>
                                @endif
                                @error('identity_card_after')
                                <div class="form__error">
                                    <img class="error__img" src="{{ asset('frontend/images/icons/icError.png')  }}"/>
                                    <p class="error__text">{{$message}}</p>
                                </div>
                                @enderror
                            </div>
                        </div>
                        @if(auth()->user()->verify == config('constants.pending_verify_user'))
                            <div class="form__warning">
                                <p class="error__text">You are in the validation process!</p>
                            </div>
                        @endif
                        @if(auth()->user()->verify == config('constants.verify_user'))
                            <div class="form__success">
                                <p class="error__text">KYC has been verified!</p>
                            </div>
                        @endif
                        @if(auth()->user()->verify == config('constants.not_verify_user'))
                            <div class="form__btn">
                                <button type="submit" class="btn__text">Confirm</button>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('frontend/js/kyc.js') }}"></script>
@endsection
