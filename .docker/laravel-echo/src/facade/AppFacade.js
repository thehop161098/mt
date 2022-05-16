const CoinService = require('../services/CoinService');
const CandleService = require('../services/CandleService');
const HistoryService = require('../services/HistoryService');
const OrderService = require('../services/OrderService');
const RoundCandleService = require('../services/RoundCandleService');
const ControlService = require('../services/ControlService');
const ProgressService = require('../services/ProgressService');
const YellowCandleService = require('../services/YellowCandleService');
const NewCandleRepository = require('../repositories/NewCandleRepository');
const {getFullTime, getResult} = require('../utils');
const {YELLOW, GREEN, RED} = require('../config/Constants');

class AppFacade {
    static INSTANCE = new AppFacade();
    coinService;
    historyService;
    roundCandleService;
    orderService;
    controlService;
    progressService;
    yellowCandleService;
    arr_candle_service = [];
    arr_candle_service_object = {};
    arrCandles = {};

    constructor() {
        this.coinService = new CoinService();
        this.historyService = new HistoryService();
        this.roundCandleService = new RoundCandleService();
        this.orderService = new OrderService();
        this.controlService = new ControlService();
        this.progressService = new ProgressService();
        this.yellowCandleService = new YellowCandleService();
        this.initCoinService();
    }

    static getInstance() {
        return this.INSTANCE;
    }

    initCoinService() {
        Promise.all([
            this.coinService.setCoins(),
            this.historyService.setHistories(),
            this.roundCandleService.setRoundCandles()
        ]).then(() => {
            this.initCandleService();
            this.randomYellowCandles();
        });
    }

    initCandleService() {
        const coins = this.coinService.getCoins();
        if (coins.length > 0) {
            let arr_candle_service = [];
            let arr_candle_service_object = {};
            const histories = this.historyService.getHistories();
            const roundCandles = this.roundCandleService.getRoundCandles();
            coins.forEach(coin => {
                const lastCandle = histories[coin.name] ? histories[coin.name] : null;
                const round = roundCandles[coin.name] ? roundCandles[coin.name] : 0;
                const candleService = new CandleService(coin, lastCandle, round);
                arr_candle_service.push(candleService);
                arr_candle_service_object[coin.name] = candleService;

                this.controlService.setArrControl(coin.name);
            });
            this.arr_candle_service = arr_candle_service;
            this.arr_candle_service_object = arr_candle_service_object;
        }
    }

    randomCandles(minutes) {
        this.orderService.resetCoinOrders();
        this.controlService.setProfit();
        if (this.arr_candle_service.length === 0) return;
        let arrCandles = {};
        this.arr_candle_service.forEach(candleService => {
            if (!candleService.lastCandle || candleService.lastCandle.date < getFullTime()) {
                const arrYellowCandles = this.yellowCandleService.getArrYellowCandles(candleService.coin.name);
                const is_yellow = arrYellowCandles.includes(minutes);
                const prize = this.controlService.prize;
                candleService.randomCandle(is_yellow, prize);
                const candles = candleService.getArrCandles();
                NewCandleRepository.save(candleService.coin.name, candles);
                if (candles[59]) {
                    arrCandles[candleService.coin.name] = candles;
                    this.roundCandleService.setRoundCandle(candles[59].coin, candles[59].round);
                    this.historyService.setHistory(candles[59]);
                }
            }
        });
        this.arrCandles = arrCandles;
        return arrCandles;
    }

    controlCandles(minutes) {
        return this.orderService.setCoinOrders().then(() => {
            const coinOrders = this.orderService.getCoinOrders();
            console.log('coinOrders', coinOrders);
            const coinProfit = this.orderService.getCoinProfit();
            // let profit = 0;
            let arrCandles = {...this.arrCandles};
            let countCoin = coinOrders.length > 0 ? coinOrders.length : 1;
            let maxProfitWinEachCoin = this.controlService.rangeProfitAllow / countCoin;
            if (this.controlService.maxProfitAdmin < 0 && this.controlService.maxProfitAdmin > this.controlService.profit) {
                maxProfitWinEachCoin = Math.abs(this.controlService.profit) - Math.abs(this.controlService.maxProfitAdmin);
            }

            for (const coin in coinOrders) {
                if (coinOrders.hasOwnProperty(coin)) {
                    const arrYellowCandles = this.yellowCandleService.getArrYellowCandles(coin);
                    const is_yellow = arrYellowCandles.includes(minutes);
                    const controlResults = coinOrders[coin];
                    if (arrCandles[coin] && arrCandles[coin][59]) {
                        const {open, close} = arrCandles[coin][59];
                        let result = getResult(open, close);
                        let prize = 1;
                        if (!controlResults.includes(result)) {
                            const isControl = this.controlService.isControl(coin);
                            console.log('isControl', isControl);
                            if (this.arr_candle_service_object[coin]) {
                                let tempProfitWinByCoin = this.getProfitRoundByCoin(coinProfit[coin], result, prize);
                                console.log('isControlSecond', tempProfitWinByCoin > maxProfitWinEachCoin);
                                if (isControl || tempProfitWinByCoin > maxProfitWinEachCoin) {
                                    const amountWinOld = coinProfit[coin][result];
                                    const isAllowWinOld = this.controlService.maxAmountWin > amountWinOld ? true : false;

                                    if (result === GREEN && controlResults.includes(RED)) {
                                        result = RED;
                                    } else if (result === RED && controlResults.includes(GREEN)) {
                                        result = GREEN;
                                    } else {
                                        result = controlResults[Math.floor(Math.random() * controlResults.length)];
                                    }
                                    if (is_yellow && controlResults.includes(YELLOW)) {
                                        result = YELLOW;
                                        prize = this.controlService.prize;
                                    }

                                    const newCandles = this.arr_candle_service_object[coin].controlCandle(arrCandles[coin], result, prize, isAllowWinOld);
                                    if (newCandles) {
                                        arrCandles[coin] = [...newCandles];
                                        NewCandleRepository.save(coin, newCandles);
                                    }
                                }
                            }
                        }
                        // profit += this.getProfitRoundByCoin(coinProfit[coin], result, prize);
                    }
                }
            }
            // console.log('Profit', profit);
            // this.controlService.plusProfit(profit);
            // console.log('New Profit', this.controlService.profit);
            this.arrCandles = {...arrCandles};
            return arrCandles;
        });
    }

    randomProgress() {
        const coins = this.coinService.getCoins();
        if (coins.length > 0) {
            coins.forEach(coin => {
                this.progressService.randomArrProgress(coin.name);
            });
        }
    }

    randomYellowCandles() {
        const coins = this.coinService.getCoins();
        if (coins.length > 0) {
            coins.forEach(coin => {
                this.yellowCandleService.randomArrYellowCandles(coin.name);
            });
        }
    }

    resetLastCandle(coin, newClose) {
        if (this.arr_candle_service.length === 0) return;
        this.arr_candle_service.forEach(candleService => {
            if (candleService.coin.name === coin && candleService.lastCandle) {
                const lastCandle = {...candleService.lastCandle, close: newClose};
                candleService.setLastCandle(lastCandle);
            }
        });
    }

    resetCoins(coins) {
        if (coins.length > 0) {
            let arrCoins = [];
            coins.forEach(coin => {
                if (!this.arr_candle_service_object[coin.name]) {
                    arrCoins.push(coin);
                }
            });
            const histories = arrCoins.length > 0 ? this.historyService.setHistories(arrCoins) : {};
            const roundCandles = this.roundCandleService.getRoundCandles();

            let arr_candle_service = [...this.arr_candle_service];
            let arr_candle_service_object = {...this.arr_candle_service_object};
            coins.forEach(coin => {
                if (!this.arr_candle_service_object[coin.name]) {
                    const lastCandle = histories[coin.name] ? histories[coin.name] : null;
                    const round = roundCandles[coin.name] ? roundCandles[coin.name] : 0;
                    const candleService = new CandleService(coin, lastCandle, round);
                    arr_candle_service.push(candleService);
                    arr_candle_service_object[coin.name] = candleService;

                    this.controlService.setArrControl(coin.name);
                } else {
                    arr_candle_service.forEach((oldCandleService, index) => {
                        if (oldCandleService.coin.name === coin.name) {
                            arr_candle_service[index].setCoin(coin);
                        }
                    });
                    arr_candle_service_object[coin.name].setCoin(coin);
                }
            });
            this.arr_candle_service = arr_candle_service;
            this.arr_candle_service_object = arr_candle_service_object;
        }
    }

    getProfitRoundByCoin(arrProfit, result, prize = 1) {
        let profit = 0;
        for (const type in arrProfit) {
            if (arrProfit.hasOwnProperty(type)) {
                const tempProfit = arrProfit[type];
                if (result === parseInt(type)) {
                    profit += tempProfit * 0.95 * prize;
                } else {
                    profit -= tempProfit;
                }
            }
        }
        return profit;
    }

    resetCandleManual(coin, result, prize) {
        if (this.arr_candle_service_object[coin]) {
            let arrCandles = {...this.arrCandles};
            const newCandles = this.arr_candle_service_object[coin].controlCandle(arrCandles[coin], result, prize);
            if (newCandles) {
                arrCandles[coin] = [...newCandles];
                NewCandleRepository.save(coin, newCandles);
            }
            this.arrCandles = {...arrCandles};
            // this.controlService.setProfit().then(() => {
            //     console.log('New Profit cause reset manual', this.controlService.profit);
            // });
            return arrCandles;
        }
        return null;
    }
}

module.exports = AppFacade;
