@extends('frontend.layouts.app')

@section('css')
    <link href="{{ asset('frontend/css/profile.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div id="profile">
        @include('frontend.profile.change-password')
        @include('frontend.profile.modal2fa')
        <form action="{{route('user.update')}}" method="POST" role="form" enctype="multipart/form-data">
            @csrf
            <div class="pageProfile">
                <div class="title">
                    <p class="title__text">Profile</p>
                    <div class="title__tool">
                        <button type="submit" class="tool__btnSave">Save</button>
                    </div>
                </div>
                <div class="content">
                    <div class="content__blockInfoUser">
                        <div class="blockInfoUser__boxAvatar">
                            <div class="boxAvatar__avatar {{$user->class_kyc}}">
                                <img id="blah" class="avatar__img" src="{{asset($user->getAvatar())}}"/>
                                <div class="avatar__tool">
                                    <input id="imgInp" name="avatar" class="tool__btnEditAvatar" type="file"/>
                                    <img class="btnEditAvatar__img"
                                         src="{{asset('frontend/images/icons/icCamera.png')}}"/>
                                </div>
                                <div class="avatar__verifyCheck">
                                    <img class="verifyCheck__img" src="{{$user->icon_kyc}}" />
                                </div>
                                @error('avatar'))
                                <script type="text/javascript">
                                    $(document).ready(function () {
                                        toastr.error('{{$message}}');
                                    })
                                </script>
                                @enderror
                            </div>
                        </div>
                        <div class="blockInfoUser__boxInfo">
                            <div class="boxInfo__form">
                                <div class="form__boxInput">
                                    <p class="boxInput__label">Full Name</p>
                                    <input name="full_name" class="boxInput__input" placeholder="Enter name"
                                           value="{{$user->full_name}}"/>
                                    @error('full_name')
                                    <div class="form__error">
                                        <img class="error__img"
                                             src="{{ asset('frontend/images/icons/icError.png')  }}"/>
                                        <p class="error__text">{{$message}}</p>
                                    </div>
                                    @enderror
                                </div>
                                <div class="form__boxInput">
                                    <p class="boxInput__label">Phone Number</p>
                                    @if(isset($phoneCountries) && !empty($phoneCountries))
                                        <div class="codeArea">
                                            <input type="checkbox" class="codeArea__check"/>
                                            <p class="codeArea__select">{{$user->phone_country}}</p>
                                            <input name="phone_country" type="hidden" id="phone_country"
                                                   value="{{$user->phone_country}}"/>
                                            <img class="codeArea__icon"
                                                 src="{{ asset('frontend/images/icons/icDropdown.png')  }}"/>

                                            <div class="codeArea__list">
                                                <input type="text" id="searchPhone" class="codeArea__searchList" placeholder="Search" autocomplete="none"/>
                                                @foreach($phoneCountries as $phone)
                                                    <div class="list__item"
                                                         data-phone-country="{{$phone->name}}">{{$phone->name}}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    <input name="phone" class="boxInput__input inputPD"
                                           placeholder="Enter phone"
                                           value="{{$user->phone}}"/>
                                    @error('phone')
                                    <div class="form__error">
                                        <img class="error__img"
                                             src="{{ asset('frontend/images/icons/icError.png')  }}"/>
                                        <p class="error__text">{{$message}}</p>
                                    </div>
                                    @enderror
                                </div>
                                <div class="form__boxInput">
                                    <p class="boxInput__label">Email</p>
                                    <input class="boxInput__input" value="{{$user->email}}" disabled/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="content__blockSettingUser">
                        <div class="blockSettingUser__form">
                            <div class="form__setting">
                                <div class="setting__title">
                                    <p class="title__text">KYC</p>
                                    <p class="title__note">
                                        Uploading your ID helps as ensure the safety and security of your founds</p>
                                </div>
                                <div class="setting__content">
                                    <a href="{{route('identityCard')}}" class="content__link">
                                        Verification
                                    </a>
                                </div>
                            </div>
                            <div class="form__setting">
                                <div class="setting__title">
                                    <p class="title__text">Password</p>
                                </div>
                                <div class="setting__content">
                                    <button type="button" class="content__btn" data-toggle="modal"
                                            data-target="#modalChangePassword">
                                        Change Password
                                    </button>
                                </div>
                            </div>
                            <div class="form__setting">
                                <div class="setting__title">
                                    <p class="title__text">Email Verification</p>
                                    <p class="title__note">
                                        For login, withdrawals, password retrieval, change of security settings</p>
                                </div>
                                <div class="setting__content">
                                    <p class="content__text">{{Auth::user()->email}}</p>
                                </div>
                            </div>
                            <div class="form__setting">
                                <div class="setting__title">
                                    <p class="title__text">Google Authentication</p>
                                    <p class="title__note">
                                        For login, withdrawals, password retrieval, change of security settings</p>
                                </div>
                                <div class="setting__content">
                                    @if(Auth::user()->google2fa_enable)
                                        <div id="enable2fa" class="content__toogle active2fa" data-toggle="modal"
                                             data-target="#modalActiveGoogleStep3">
                                            <input class="toogle__check" type="checkbox"/>
                                            <div class="toogle__bgToogle"></div>
                                            <div class="toogle__circle"></div>
                                        </div>
                                    @else
                                        <div id="enable2fa" class="content__toogle" data-toggle="modal"
                                             data-target="#modalActiveGoogleStep1">
                                            <input class="toogle__check" type="checkbox"/>
                                            <div class="toogle__bgToogle"></div>
                                            <div class="toogle__circle"></div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script src="{{ asset('frontend/js/profile/profile.js') }}"></script>
@endsection
