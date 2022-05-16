const CandleRepository = require('../repositories/CandleRepository');
const Candle = require('../models/Candle');
const {YELLOW, RED, GREEN, NUM_NOT_ADJUST} = require('../config/Constants');
const {getFullTime, subtractTime, randomRangeCandle, randomInt} = require('../utils');

class CandleService {

    round;
    lastCandle;
    coin;
    arr_candles = [];
    isLastTrade = true;
    numNotAdjust = 0;
    isAllowWin = false;

    constructor(coin, lastCandle, round) {
        this.round = round;
        this.coin = coin;
        this.lastCandle = lastCandle;
    }

    getNewCandle() {
        let round = this.round + 1;
        if (this.lastCandle) {
            if (this.lastCandle.date === subtractTime()) {
                const {coin, close} = this.lastCandle;
                return new Candle(round, coin, close, close, close, close, getFullTime());
            }
        }
        const price = this.coin.price;
        return new Candle(round, this.coin.name, price, price, price, price, getFullTime());
    }

    randomCandle(is_yellow, prize) {
        let arr_candles = [];
        let candle = this.getNewCandle();
        arr_candles.push({...candle});
        const {max, min, range} = this.coin;
        let priceCoin = this.coin ? this.coin.price : candle.close;
        let rangeMin = priceCoin - range;
        let rangeMax = priceCoin + Math.floor(range / 4);
        let wrongPrice = false;
        if (rangeMin > candle.close || candle.close > rangeMax) {
            if (this.isLastTrade) {
                if (this.numNotAdjust < NUM_NOT_ADJUST) {
                    rangeMin = candle.close - range;
                    let tempRangeMax = candle.close;
                    for (let i = 0; i < 3; i++) {
                        tempRangeMax = randomRangeCandle(candle.close, max, min, 'plus');
                    }
                    rangeMax = tempRangeMax;
                    if (rangeMin > candle.close) {
                        rangeMin = candle.close - range / 4;
                    }
                    this.numNotAdjust++;
                    console.log('Not Adjust Price ' + candle.coin + ' - ' + this.numNotAdjust, candle.close + ' - Min: ' + rangeMin + ' - Max : ' + rangeMax);
                } else {
                    console.log('Allow Adjust Price' + candle.coin, candle.close + ' - ' + rangeMin + ' - ' + rangeMax);
                    this.numNotAdjust = 0;
                    this.isAllowWin = true;
                    wrongPrice = true;
                }
            } else {
                console.log('Adjust Price', candle.coin);
                this.numNotAdjust = 0;
                this.isAllowWin = false;
                wrongPrice = true;
            }
        } else {
            this.numNotAdjust = 0;
            this.isAllowWin = false;
        }

        for (let i = 1; i < 60; i++) {
            let close = randomRangeCandle(candle.close, max, min);
            if (close < rangeMin) {
                close = randomRangeCandle(rangeMin, max, min, 'plus');
            } else if (close > rangeMax) {
                close = randomRangeCandle(rangeMax, max, min, 'minus');
            }

            if (i === 59 && is_yellow && !wrongPrice) {
                close = candle.open;
                candle.prize = prize;
            }
            candle.close = close;
            candle.high = Math.max(candle.high, close);
            candle.low = Math.min(candle.low, close);
            arr_candles.push({...candle});
        }
        this.saveCandle(arr_candles);
        this.arr_candles = arr_candles;
        this.isLastTrade = false;
    }

    getArrCandles() {
        return this.arr_candles;
    }

    saveCandle(arr_candles) {
        if (!arr_candles[59]) return;

        CandleRepository.create(arr_candles[59], this.lastCandle);
        this.setRound(arr_candles[59].round);

        this.setLastCandle(arr_candles[59]);
    }

    setLastCandle(lastCandle) {
        this.lastCandle = lastCandle;
    }

    setRound(round) {
        this.round = round;
    }

    controlCandle(candles, result, prize, isAllowWinOld = false) {
        if (this.isAllowWin && isAllowWinOld) {
            this.numNotAdjust = 0;
            this.isLastTrade = false;
            return this.arr_candles;
        }
        this.isLastTrade = true;

        let {range} = this.coin;
        const candle = {...candles[59]};
        const second_begin = randomInt(35, 42);
        let rangeMin = candle.open - range;
        let rangeMax = candle.open + range;

        if (result === RED) rangeMax = candle.open;
        else if (result === GREEN) rangeMin = candle.open;

        let newCandles = candles;
        for (let i = second_begin; i <= 59; i++) {
            let min = this.coin.min;
            let max = this.coin.max;
            let close;
            if (result === YELLOW) {
                if (newCandles[i - 1].close < candle.open) {
                    close = randomRangeCandle(newCandles[i - 1].close, max, min, 'plus');
                } else {
                    close = randomRangeCandle(newCandles[i - 1].close, max, min, 'minus');
                }
            } else {
                if (newCandles[i - 1].close < rangeMin) {
                    let distance = rangeMin - newCandles[i - 1].close;
                    let newMax;
                    if (distance > 60) {
                        newMax = 0.006;
                    } else if (distance > 50) {
                        newMax = 0.005;
                    } else if (distance > 40) {
                        newMax = 0.004;
                    } else if (distance > 30) {
                        newMax = 0.003;
                    } else if (distance > 20) {
                        newMax = 0.002;
                    } else {
                        newMax = 0.001;
                    }
                    if (newMax > min) {
                        max = newMax;
                    }
                    close = randomRangeCandle(newCandles[i - 1].close, max, min, 'plus');
                } else if (newCandles[i - 1].close > rangeMax) {
                    let distance = newCandles[i - 1].close - rangeMax;
                    let newMax;
                    if (distance > 60) {
                        newMax = 0.006;
                    } else if (distance > 50) {
                        newMax = 0.005;
                    } else if (distance > 40) {
                        newMax = 0.004;
                    } else if (distance > 30) {
                        newMax = 0.003;
                    } else if (distance > 20) {
                        newMax = 0.002;
                    } else {
                        newMax = 0.001;
                    }
                    if (newMax > min) {
                        max = newMax;
                    }
                    close = randomRangeCandle(newCandles[i - 1].close, max, min, 'minus');
                } else {
                    close = randomRangeCandle(newCandles[i - 1].close, max, min);
                }
            }

            if (i === 59) {
                if (result === YELLOW) close = candle.open;
                else {
                    if (newCandles[i - 1].close < rangeMin) {
                        close = randomRangeCandle(rangeMin, max, min, 'plus');
                    } else if (newCandles[i - 1].close > rangeMax) {
                        close = randomRangeCandle(rangeMax, max, min, 'minus');
                    }
                }
            }

            newCandles[i].close = close;
            newCandles[i].high = Math.max(newCandles[i - 1].high, close);
            newCandles[i].low = Math.min(newCandles[i - 1].low, close);
            if (i === 59) {
                newCandles[i].prize = prize;
                const newCandleUpdate = {...newCandles[i]};
                CandleRepository.update(newCandleUpdate);
                this.setLastCandle(newCandleUpdate);
            }
        }
        this.arr_candles = newCandles;
        return this.arr_candles;
    }

    setCoin(coin) {
        this.coin = coin;
    }
}

module.exports = CandleService;
