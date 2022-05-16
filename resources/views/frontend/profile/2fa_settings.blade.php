<div class="form__boxLinkDownload">
    <div class="boxLinkDownload__note">
        <p class="note__text">Scan QR codes with the Google Authenticator app</p>
    </div>
    <div class="boxLinkDownload__qr">
        <img class="qr__img" src="{{ $data['google2fa_url'] }}" />
    </div>
    <div class="boxLinkDownload__code">
        <p class="note__code">{{ $data['secret'] }}</p>
    </div>
</div>
<div class="form__btn">
    <button class="btn__text cancel" data-dismiss="modal">Cancel</button>
    <button id="btn2faStep2" class="btn__text">Next Step</button>
</div>