<template>
    <div class="tool__changeCoin" v-bind:class="{'bgDisabledCoin': !coinSelected || coinSelected.is_gold}">
        <input type="checkbox" class="changeCoin__input" id="cbox1"/>
        <div class="changeCoin__coinSelect" v-if="coinSelected">
            <div class="coinSelect__infoCoin">
                <img class="infoCoin__img" :src="getSrc()"/>
                <p class="infoCoin__text" v-if="!coinSelected.is_gold">{{coinSelected.alias}}</p>
            </div>
            <img class="coin__showList" :src="'frontend/images/icons/icDropdown.png'"/>
        </div>
        <div class="changeCoin__listCoin" v-if="coins.length">
            <div class="coin__infoCoin" v-for="coin in coins" @click="changeCoinSelected(coin)">
                <img class="infoCoin__img" :src="coin.image_url"/>
                <p class="infoCoin__text">{{coin.alias}}</p>
            </div>
        </div>
    </div>
</template>

<script>
    import {createNamespacedHelpers} from 'vuex';

    const {mapState, mapMutations} = createNamespacedHelpers('coin');
    import {getCoins} from "../services/coinService";

    export default {
        name: "Coins",
        created() {
            getCoins().then(wallets => {
                this.setCoins(wallets);
            });
        },
        computed: {
            ...mapState({
                coins: state => state.coins,
                coinSelected: state => state.coinSelected
            })
        },
        methods: {
            ...mapMutations([
                'setCoins',
                'setCoinSelected'
            ]),
            changeCoinSelected: function (coin) {
                this.setCoinSelected(coin);
            },
            getSrc: function () {
                if (!this.coinSelected || this.coinSelected.is_gold) return 'frontend/images/icons/noCoinSelect.svg';
                return this.coinSelected.image_url;
            }
        }
    }
</script>
