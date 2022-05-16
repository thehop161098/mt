<div class="tool__changeWallet">
    <input type="checkbox" class="changeWallet__input" id="cbox2" onclick="selectOnlyCbox(this.id)" />
    <div class="changeWallet__walletSelect">
        <div class="walletSelect__infoWallet">
            <p class="infoWallet__text">Demo Account</p>
            <div class="infoWallet__money">
                <p class="money__text"><span class="money__unit">$</span> 1,000,000.00</p>
            </div>
        </div>
        <img class="wallet__showList" src="{{asset('frontend/images/icons/icDropdown.png')}}" />
    </div>
    <div class="changeWallet__listWallet">
        <div class="wallet__infoWallet">
            <p class="infoWallet__text">Demo Account </p>
            <div class="infoWallet__money">
                <p class="money__text"><span class="money__unit">$</span> 1,000.00</p>
            </div>
            <button class="btnRefreshDemo">
                <img class="infoCoin__img" src="{{asset('frontend/images/icons/icRefreshDemo.png')}}" />
            </button>
        </div>
        <div class="wallet__infoWallet">
            <p class="infoWallet__text">Live Account</p>
            <div class="infoWallet__money">
                <p class="money__text"><span class="money__unit">$</span> 0.00</p>
            </div>
        </div>
        <div class="wallet__infoWallet">
            <p class="infoWallet__text">Promotion Account</p>
            <div class="infoWallet__money">
                <p class="money__text"><span class="money__unit">$</span> 0.00</p>
            </div>
        </div>
    </div>
</div>
