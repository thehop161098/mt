<input id="url2FA" type="hidden" value="{{route('generate2faSecret')}}">
<div class="modal fade" id="modalActiveGoogleStep1" tabindex="-1" role="dialog">
    <div class="modal-dialog modalActiveGoogleStep1" role="document">
        <div class="modalContent">
            <div class="modalContent__title">
                <p class="title__text">Active Google Authenticator</p>
                <button class="title__btnClose" data-dismiss="modal">
                    <img class="btnClose__img" src="{{asset('frontend/images/icons/icClose.png')}}"/>
                </button>
            </div>
            <div class="modalContent__form">
                <div class="form__boxLinkDownload">
                    <div class="boxLinkDownload__note">
                        <p class="note__text">Install the Google Authenticator App from the App Store or Google Play</p>
                    </div>
                    <div class="boxLinkDownload__listLink">
                        <a href="https://apps.apple.com/vn/app/google-authenticator/id388497605?l=vi" target="_blank">
                            <div class="listLink__item">
                                <img class="item__img" src="{{asset('frontend/images/icons/icApple.png')}}"/>
                                <p class="item__text">Apple Store</p>
                            </div>
                        </a>
                        <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=vi&gl=US"
                           target="_blank">
                            <div class="listLink__item">
                                <img class="item__img" src="{{asset('frontend/images/icons/icCHPlay.png')}}"/>
                                <p class="item__text">Google Play</p>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="form__btn">
                    <button id="show2FAStep1" class="btn__text">Next Step</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalActiveGoogleStep2" tabindex="-1" role="dialog">
    <div class="modal-dialog modalActiveGoogleStep2" role="document">
        <div class="modalContent">
            <div class="modalContent__title">
                <p class="title__text">Active Google Authenticator</p>
                <button class="title__btnClose" data-dismiss="modal">
                    <img class="btnClose__img" src="{{asset('frontend/images/icons/icClose.png')}}"/>
                </button>
            </div>
            <div id="show2FAStep2" class="modalContent__form"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalActiveGoogleStep3" tabindex="-1" role="dialog">
    <div class="modal-dialog modalActiveGoogleStep3" role="document">
        <div class="modalContent">
            <div class="modalContent__title">
                <p class="title__text">Active Google Authenticator</p>
                <button class="title__btnClose" data-dismiss="modal">
                    <img class="btnClose__img" src="{{asset('frontend/images/icons/icClose.png')}}"/>
                </button>
            </div>

            <div class="modalContent__form">
                <div id="current_password_secretMessError" class="form__error removeErrSecret hidden">
                    <img class="error__img" src="{{ asset('frontend/images/icons/icError.png')  }}"/>
                    <p class="error__text" id="current_password_secretError"></p>
                </div>
                <div id="secretMessError" class="form__error removeErrSecret hidden">
                    <img class="error__img" src="{{ asset('frontend/images/icons/icError.png')  }}"/>
                    <p class="error__text" id="secretError"></p>
                </div>
                <form id="formToggle2fa" action="{{route('toggle2fa')}}" method="POST">
                    <div class="form__boxLinkDownload">
                        <div class="boxLinkDownload__note">
                            <img class="note__img" src="{{asset('frontend/images/icons/icGoogleAuthen.png')}}"/>
                            <p class="note__text">Enter the 6-digit code in your Google Authenticator app</p>
                        </div>
                        <div class="boxLinkDownload__listInputCode">
                            <input id="secret2fa" type="hidden" name="secret">
                            <div class="listInputCode__item">
                                <input id="focus1" autocomplete="off" class="item__input" maxlength="1"
                                       inputmode="numeric" pattern="[0-9]*"
                                       onkeyup="nextFocus(this,'focus2')"/>
                            </div>
                            <div class="listInputCode__item">
                                <input id="focus2" autocomplete="off" class="item__input" maxlength="1"
                                       inputmode="numeric" pattern="[0-9]*"
                                       onkeyup="nextFocus(this,'focus3', 'focus1')"/>
                            </div>
                            <div class="listInputCode__item">
                                <input id="focus3" autocomplete="off" class="item__input" maxlength="1"
                                       inputmode="numeric" pattern="[0-9]*"
                                       onkeyup="nextFocus(this,'focus4', 'focus2')"/>
                            </div>
                            <div class="listInputCode__item">
                                <input id="focus4" autocomplete="off" class="item__input" maxlength="1"
                                       inputmode="numeric" pattern="[0-9]*"
                                       onkeyup="nextFocus(this,'focus5', 'focus3')"/>
                            </div>
                            <div class="listInputCode__item">
                                <input id="focus5" autocomplete="off" class="item__input" maxlength="1"
                                       inputmode="numeric" pattern="[0-9]*"
                                       onkeyup="nextFocus(this,'focus6', 'focus4')"/>
                            </div>
                            <div class="listInputCode__item">
                                <input id="focus6" autocomplete="off" class="item__input" maxlength="1"
                                       inputmode="numeric" pattern="[0-9]*"
                                       onkeyup="nextFocus(this,'pass', 'focus5')"/>
                            </div>
                        </div>
                        <div class="boxLinkDownload__boxInput">
                            <p class="boxInput__label">Password</p>
                            <input id="pass" name="current_password_secret" class="boxInput__input"
                                   placeholder="Enter your password" type="password"/>
                        </div>
                    </div>
                    <div class="form__btn">
                        <button onclick="setCode2fa('.listInputCode__item')" class="btn__text">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
