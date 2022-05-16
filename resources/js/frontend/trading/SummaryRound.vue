<template>
    <div>
        <div class="trading__title">
        <div class="title__info">
            <div class="info__item">
                <p class="item__label">Total Invesment</p>
                <p class="item__number">${{getTotal() | formatNumber }}</p>
            </div>
            <div class="info__item">
                <p class="item__label">Expected Income</p>
                <p class="item__number clExpected">${{getExpected()}}</p>
            </div>
            <div class="info__item">
                <p class="item__label">Current Order: {{getTotal() | formatNumber}}</p>
                <div class="item__boxContent">
                    <div class="boxContent__value">
                        <p class="value__circle up"></p>
                        <p class="value__number">{{getAmount(GREEN) | formatNumberInt }} <span
                            class="value__numberUnit type_txt">: BUY</span></p>
                    </div>
                    <div class="boxContent__value">
                        <p class="value__circle down"></p>
                        <p class="value__number">{{getAmount(RED) | formatNumberInt }} <span
                            class="value__numberUnit type_txt">: SELL</span></p>
                    </div>
                    <div class="boxContent__value">
                        <p class="value__circle balance"></p>
                        <p class="value__number">{{getAmount(YELLOW) | formatNumberInt }} <span
                            class="value__numberUnit type_txt">: BALANCE x7</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="chartShowHiddenMB">
            <div class="boxOC">
                <input type="checkbox" class="toogleShowHideOpenClose">
                <div class="bgToogle">
                    <div class="circleToogle">
                        <img class="imgToogle" :src="'frontend/images/icons/icOC.svg'"/>
                    </div>
                </div>
           </div>
            <div class="boxRS">
                <input type="checkbox" class="toogleShowHideResult">
                <div class="bgToogle">
                    <div class="circleToogle">
                        <img class="imgToogle" :src="'frontend/images/icons/icRS.svg'"/>
                    </div>
                </div>
            </div>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
            <div class="boxPF">
                <input type="checkbox" class="toogleShowHideProfit">
                <div class="bgToogle">
                    <div class="circleToogle">
                        <img class="imgToogle" :src="'frontend/images/icons/icPF.svg'"/>
                    </div>
                </div>
            </div>
            <div class="boxChat">
                <input type="checkbox" class="toogleShowHideChat">
                <div class="bgToogle">
                    <div class="circleToogle">
                        <img class="imgToogle" :src="'frontend/images/icons/icChat.svg'"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {createNamespacedHelpers} from 'vuex';

    const {mapMutations} = createNamespacedHelpers('wallet');
    import {HTTP} from '../../http-common';
    import {GREEN, RED, YELLOW} from '../../constants';

    const numeral = require("numeral");

    export default {
        name: "SummaryRound",
        props: ['orderEventName', 'lastCandle', 'coinSelected'],
        data: function () {
            return {
                GREEN: GREEN,
                RED: RED,
                YELLOW: YELLOW,
                summary: {},
                errors: []
            }
        },
        computed: {
            total: function () {
                let total = 0;
                return total;
            },
            coin: function () {
                return this.coinSelected ? this.coinSelected.name : '';
            }
        },
        created() {
            this.getSummary();
        },
        methods: {
            ...mapMutations([
                'setWallets',
                'setWalletSelected'
            ]),
            getSummary: function () {
                let $this = this;
                HTTP.get('orders/getOrdersInMinutes').then(response => {
                    const res = [...response.data.summary];
                    let summary = {};
                    res.forEach(elm => {
                        if (!summary[elm.coin]) summary[elm.coin] = {};
                        summary[elm.coin][elm.type] = elm.amount;
                    });
                    $this.summary = summary;
                }).catch(e => {
                    $this.errors.push(e);
                });
            },
            getAmount: function (type) {
                if (this.summary[this.coin]) return this.summary[this.coin][type];
                return 0;
            },
            getTotal: function () {
                if (!this.summary[this.coin]) return 0;
                let total = 0;
                for (const type in this.summary[this.coin]) {
                    if (this.summary[this.coin].hasOwnProperty(type)) {
                        total += parseFloat(this.summary[this.coin][type]);
                    }
                }
                return total;
            },
            getExpected: function () {
                if (!this.summary[this.coin]) return 0.00;
                if (!this.summary[this.coin][GREEN] && !this.summary[this.coin][RED]) {
                    const amount = this.summary[this.coin][YELLOW];
                    const x3 = amount * 0.95 * 3 + amount;
                    const x7 = amount * 0.95 * 7 + amount;
                    return numeral(x3).format("0,0.00") + ' - $' + numeral(x7).format("0,0.00")
                } else {
                    return numeral(this.getTotal() * 1.95).format("0,0.00");
                }
            }
        },
        watch: {
            orderEventName: function () {
                let $this = this;
                if ($this.orderEventName) {
                    window.Echo.private($this.orderEventName)
                        .listen('OrderEvent', function (res) {
                            if (res.response && res.response.status === 201) {
                                const {order, walletSelected} = res.response;
                                let summary = {...$this.summary};
                                if (!summary[order.coin]) summary[order.coin] = {};
                                if (!summary[order.coin][order.type]) summary[order.coin][order.type] = 0;
                                summary[order.coin][order.type] += parseFloat(order.amount);
                                $this.summary = summary;
                                if (walletSelected) {
                                    $this.setWallets(walletSelected);
                                    $this.setWalletSelected(walletSelected);
                                }
                            } else {
                                toastr.error(res.response.message);
                            }
                        });
                }
            },
            lastCandle: function (lastCandle, oldLastCandle) {
                if (oldLastCandle && lastCandle && lastCandle.round !== oldLastCandle.round) {
                    this.summary = {};
                }
            }
        }
    }
</script>

