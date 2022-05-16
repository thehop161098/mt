const {randomInt} = require('../utils');
const {NUM_YELLOW_CANDLES} = require('../config/Constants');

class YellowCandleService {

    arrYellowCandles = {};

    randomArrYellowCandles(coin) {
        const numCandle = randomInt(1, NUM_YELLOW_CANDLES);
        let candles = [];
        for (let i = 0; i < numCandle; i++) {
            let minutes = randomInt(0, 59);
            let count = 0;
            while (candles.includes(minutes)) {
                minutes = randomInt(0, 59);
                count++;
                if (count === 20) break;
            }
            candles.push(minutes);
        }
        this.arrYellowCandles[coin] = candles;
    }

    getArrYellowCandles(coin) {
        return this.arrYellowCandles[coin] ? this.arrYellowCandles[coin] : [];
    }
}

module.exports = YellowCandleService;
