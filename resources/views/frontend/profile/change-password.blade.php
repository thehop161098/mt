<div class="modal fade" id="modalChangePassword" tabindex="-1" role="dialog">
    <div class="modal-dialog modalChangePassword" role="document">
        <div class="modalChangePassword__boxChangePassword">
            <div class="boxChangePassword__title">
                <p class="title__text">Change Password</p>
                <button class="title__btnClose" data-dismiss="modal">
                    <img class="btnClose__img" src="{{asset('frontend/images/icons/icClose.png')}}"/>
                </button>
            </div>
            <form action="{{route('user.change-password')}}" id="changePasswordForm" method="POST" role="form"
                  enctype="multipart/form-data">
                <div class="boxChangePassword__form">
                    <div id="current_passwordMessError" class="form__error removeErr hidden">
                        <img class="error__img" src="{{ asset('frontend/images/icons/icError.png')  }}"/>
                        <p class="error__text" id="current_passwordError"></p>
                    </div>
                    <div class="form__boxInput inputPassword">
                        <p class="boxInput__label">Password</p>
                        <input name="current_password" class="boxInput__input" placeholder="Enter your password"
                               type="password"/>
                    </div>
                    <div id="new_passwordMessError" class="form__error removeErr hidden">
                        <img class="error__img" src="{{ asset('frontend/images/icons/icError.png')  }}"/>
                        <p class="error__text" id="new_passwordError"></p>
                    </div>
                    <div class="form__boxInput inputPassword">
                        <p class="boxInput__label">New Password</p>
                        <input name="new_password" class="boxInput__input" placeholder="Enter new password"
                               type="password"/>
                    </div>
                    <div id="new_confirm_passwordMessError" class="form__error removeErr hidden">
                        <img class="error__img" src="{{ asset('frontend/images/icons/icError.png')  }}"/>
                        <p class="error__text" id="new_confirm_passwordError"></p>
                    </div>
                    <div class="form__boxInput inputPassword">
                        <p class="boxInput__label">Re-New Password</p>
                        <input name="new_confirm_password" class="boxInput__input" placeholder="Enter re-new password"
                               type="password"/>
                    </div>
                    <div class="form__btn">
                        <button type="submit" class="btn confirm">Confirm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
