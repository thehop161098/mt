const CandleRepository = require('../repositories/CandleRepository');
const Candle = require('../models/Candle');
const {getFullTime} = require('../utils');

class HistoryService {

    histories = {};

    setHistories(coins = []) {
        let conditions = [`date = '${getFullTime()}'`];
        if (coins.length > 0) {
            let coins_str = coins.join("','");
            conditions.push(`coin IN ('${coins_str}')`)
        }
        return CandleRepository.getAll(conditions).then(rows => {
            if (rows.length > 0) {
                let histories = {};
                rows.forEach(row => {
                    const {round, coin, open, close, high, low, date} = row;
                    histories[row.coin] = new Candle(round, coin, open, close, high, low, date);
                });
                this.histories = histories;
                return histories;
            }
        });
    }

    getHistories() {
        return this.histories;
    }

    setHistory(history) {
        this.histories[history.coin] = {...history};
    }
}

module.exports = HistoryService;
