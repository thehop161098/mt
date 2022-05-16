<template>
    <div id="trading">
        <div class="pageTrading">
            <toggleChat/>
            <div class="pageTrading__trading">
                <summaryRound
                    :orderEventName="orderEventName"
                    :lastCandle="lastCandle"
                    :coinSelected="coinSelected"
                />
                <div class="trading__content">
                    <div class="item__boxResult">
                        <div class="boxResult__value">
                            <p class="value__number">HIGH : <span class="value__numberUnit">{{getCurrentValue('high') | formatNumber}}</span>
                            </p>
                        </div>
                        <div class="boxResult__value">
                            <p class="value__number">LOW : <span class="value__numberUnit">{{getCurrentValue('low') | formatNumber}}</span>
                            </p>
                        </div>
                        <div class="boxResult__value">
                            <p class="value__number">OPEN : <span class="value__numberUnit">{{getCurrentValue('open') | formatNumber}}</span>
                            </p>
                        </div>
                        <div class="boxResult__value">
                            <p class="value__number">CLOSE: <span class="value__numberUnit">{{getCurrentValue('close') | formatNumber}}</span>
                            </p>
                        </div>
                    </div>
                    <div class="content__chartTrading">
                        <chart 
                            :second="second" 
                            :candles="candles" 
                            :limit="limit" 
                            :limit_mobile="limit_mobile" 
                            :isMobile="isMobile" 
                            :arrEma="arrEma"
                        />
                        <history :lastCandle="lastCandle" :coinSelected="coinSelected"/>
                    </div>
                </div>
                <div class="trading__bet">
                    <amountBox :amount="amount" v-on:changeAmount="changeAmount"/>
                    <div class="bet__blockSelectBet">
                        <buttonBox
                            :second="second"
                            :amount="amount"
                            :coinSelected="coinSelected"
                            :walletSelected="walletSelected"
                        />
                        <progressSummary :amount="amount" :coinSelected="coinSelected"/>
                    </div>
                </div>
                <div class="blockFooterWeb hidden-lg hidden-md"></div>
            </div>
            <chat/>
        </div>
        <modalResult
            v-if="profit !== null"
            :profit="profit"
            :isYellow="isYellow"
            :prize="prize"
            v-on:resetProfit="resetProfit"
        />
    </div>
</template>

<script>
    import {createNamespacedHelpers} from 'vuex';
    import {HTTP} from '../../http-common';

    const {mapMutations, mapGetters} = createNamespacedHelpers('wallet');
    const coin = createNamespacedHelpers('coin');

    import SummaryRound from "./SummaryRound";
    import Chart from "./Chart";
    import History from "./History";
    import ToggleChat from "./ToggleChat";
    import AmountBox from "./AmountBox";
    import ButtonBox from "./ButtonBox";
    import ProgressSummary from "./ProgressSummary";
    import Chat from "./Chat";
    import ModalResult from "./ModalResult";
    import {getCandles, getConfigs} from "../services/candleService";

    export default {
        name: "Trading",
        data: function () {
            return {
                limit: 89,
                limit_mobile: 39,
                second: "",
                candles: [],
                newCandles: [],
                arrEma: [],
                lastCandle: null,
                amount: 10,
                orderEventName: "",
                orderResultEventName: "",
                profit: null,
                prize: 1,
                isYellow: false,
                errors: [],
                isMobile: false
            }
        },
        components: {
            SummaryRound,
            Chart,
            History,
            ToggleChat,
            AmountBox,
            ButtonBox,
            ProgressSummary,
            Chat,
            ModalResult
        },
        computed: {
            ...mapGetters([
                'walletSelected'
            ]),
            ...coin.mapGetters([
                'coinSelected'
            ]),
        },
        mounted() {
            this.onResize()
            window.addEventListener('resize', this.onResize, {passive: true});
        },
        created() {
            let $this = this;

            getConfigs().then(res => {
                if (res) {
                    $this.limit = res.limit;
                    // if ($this.isMobile) {
                    //     $this.limit = res.limit_mobile;
                    // }
                    $this.limit_mobile = res.limit_mobile;
                    $this.orderEventName = res.orderEventName;
                    $this.orderResultEventName = res.orderResultEventName;
                }
            }).catch(e => {
                $this.errors.push(e);
            });

            window.Echo.channel('second')
                .listen('SecondEvent', function (res) {
                    const {second} = res;
                    $this.second = second;
                    if (second && $this.coinSelected && $this.newCandles[$this.coinSelected.name] &&
                        $this.newCandles[$this.coinSelected.name][second]) {
                        const newCandle = {...$this.newCandles[$this.coinSelected.name][second]};
                        const lastCandle = {...$this.candles[$this.candles.length - 1]};

                        if (!lastCandle || lastCandle.date !== newCandle.date) {
                            let candles = [...$this.candles];
                            candles.push(newCandle);
                            $this.lastCandle = lastCandle;
                            let arrEma = [...$this.arrEma];
                            arrEma.push({close: candles[0].close});
                            if (candles.length > $this.limit) candles.shift();
                            if (arrEma.length > $this.limit) arrEma.shift();
                            $this.candles = candles;
                            $this.arrEma = arrEma;
                        } else {
                            $this.candles[$this.candles.length - 1] = newCandle;
                        }
                    }
                });

            window.Echo.channel('new-candle')
                .listen('NewCandleEvent', function (res) {
                    if (res.arrCandles) {
                        $this.newCandles = res.arrCandles;
                        $this.summaryLast = {...$this.summary};
                    }
                });

            window.Echo.channel('reset-candle')
                .listen('ResetCandleEvent', function (res) {
                    if (res.arrCandles) {
                        $this.newCandles = res.arrCandles;
                    }
                });

            window.Echo.join('online');
        },
        methods: {
            ...mapMutations([
                'resetWallets',
                'resetWalletSelected'
            ]),
            changeAmount: function (amount) {
                if (amount === "") this.amount = amount;
                else this.amount = parseFloat(amount);
            },
            resetProfit: function () {
                this.profit = null;
                this.prize = 1;
                this.isYellow = false;
            },
            getCandles: function (coin = null) {
                let $this = this;
                getCandles(coin).then(res => {
                    if (res) {
                        $this.candles = [...res.candles].reverse();
                        $this.newCandles = {...res.newCandles};
                        $this.arrEma = [...res.arrEma];
                    }
                }).catch(e => {
                    $this.errors.push(e);
                });
            },
            getCurrentValue: function (type) {
                const $this = this;
                const {newCandles, coinSelected, second} = $this;
                if (coinSelected && newCandles[coinSelected.name] && newCandles[coinSelected.name][second]) {
                    return newCandles[coinSelected.name][second][type];
                }
                return 0;
            },
            onResize() {
                const isMobile = window.innerWidth < 600;
                this.isMobile = isMobile;
                // if (isMobile) {
                //     this.limit = this.limit_mobile;
                // }
            }
        },
        watch: {
            orderResultEventName: function (orderResultEventName) {
                if (orderResultEventName) {
                    const $this = this;
                    window.Echo.private(orderResultEventName)
                        .listen('OrderResultEvent', function (res) {
                            if (res.wallets[$this.coinSelected.name] && res.wallets[$this.coinSelected.name][$this.walletSelected.type]) {
                                $this.profit = res.wallets[$this.coinSelected.name][$this.walletSelected.type]['profit'];
                                if (res.arrOrders[$this.coinSelected.name] && res.arrOrders[$this.coinSelected.name].isYellow) {
                                    $this.prize = res.arrOrders[$this.coinSelected.name].prize;
                                    $this.isYellow = true;
                                }
                            }
                            setTimeout(function () {
                                $this.resetProfit();
                            }, 4000);
                            if (res.wallets) {
                                let wallets = {};
                                for (const coin in res.wallets) {
                                    if (res.wallets.hasOwnProperty.call(res.wallets, coin)) {
                                        const wallet = res.wallets[coin];
                                        for (const type in wallet) {
                                            if (wallet.hasOwnProperty.call(wallet, type)) {
                                                if (!wallets[type]) wallets[type] = 0;
                                                wallets[type] += parseFloat(wallet[type]['money']);
                                            }
                                        }
                                    }
                                }
                                $this.resetWallets(wallets);
                                $this.resetWalletSelected(wallets);
                            }
                        });
                }
            },
            coinSelected: function (coinSelected) {
                this.lastCandle = null;
                this.getCandles(coinSelected.name);
            }
        },
        beforeDestroy() {
            if (typeof window !== 'undefined') {
                window.removeEventListener('resize', this.onResize, {passive: true});
            }
        },
    }
</script>
