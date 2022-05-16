<template>
    <div class="tool__changeWallet">
        <input type="checkbox" class="changeWallet__input" id="cbox2"/>
        <div class="changeWallet__walletSelect" v-if="walletSelected">
            <div class="walletSelect__infoWallet">
                <p class="infoWallet__text">{{getName(walletSelected)}}</p>
                <div class="infoWallet__money">
                    <p class="money__text"><span
                        class="money__unit">$</span>{{walletSelected.amount | formatNumber}}</p>
                </div>
            </div>
            <img class="wallet__showList" :src="'frontend/images/icons/icDropdown.png'"/>
        </div>
        <div class="changeWallet__listWallet" v-if="wallets.length">
            <div class="wallet__infoWallet" v-for="wallet in wallets" @click="changeWalletSelected(wallet.type)">
                <p class="infoWallet__text">{{getName(wallet)}}</p>
                <div class="infoWallet__money">
                    <p class="money__text"><span class="money__unit">$</span>{{wallet.amount | formatNumber}}</p>
                </div>
                <button class="btnRefreshDemo" v-if="wallet.type === TRIAL_WALLET ? true : false">
                    <img class="infoCoin__img" :src="'frontend/images/icons/icRefreshDemo.png'"/>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import {createNamespacedHelpers} from 'vuex';

    const {mapState, mapMutations} = createNamespacedHelpers('wallet');
    import {changeWalletSelected, getWallets, getWalletSelected} from "../services/walletService";
    import {MAIN_WALLET, DISCOUNT_WALLET, TRIAL_WALLET} from '../../constants';

    export default {
        name: "Wallets",
        data: function () {
            return {
                TRIAL_WALLET: TRIAL_WALLET
            }
        },
        created() {
            getWallets().then(wallets => {
                this.setWallets(wallets);
            });
            getWalletSelected().then(walletSelected => {
                this.setWalletSelected(walletSelected);
            });
        },
        computed: {
            ...mapState({
                wallets: state => state.wallets,
                walletSelected: state => state.walletSelected
            })
        },
        methods: {
            ...mapMutations([
                'setWallets',
                'setWalletSelected'
            ]),
            getName: function (wallet) {
                if (wallet.type === MAIN_WALLET) return 'Live Account';
                if (wallet.type === DISCOUNT_WALLET) return 'Discount Account';
                if (wallet.type === TRIAL_WALLET) return 'Demo Account';
            },
            changeWalletSelected: function (type) {
                changeWalletSelected(type).then(res => {
                    if (res.success && res.walletSelected) {
                        this.setWalletSelected(res.walletSelected);
                    }
                });
            }
        }
    }
</script>
