const RoundCandleRepository = require('../repositories/RoundCandleRepository');

class RoundCandleService {

    roundCandles = {};

    setRoundCandles() {
        return RoundCandleRepository.getAll().then(rows => {
            if (rows.length > 0) {
                let roundCandles = {};
                rows.forEach(row => {
                    roundCandles[row.coin] = row.round;
                });
                this.roundCandles = roundCandles;
                return roundCandles;
            }
        });
    }

    setRoundCandle(coin, round) {
        if (!this.roundCandles[coin]) {
            RoundCandleRepository.create(coin, round);
        } else {
            RoundCandleRepository.update(coin, round);
        }
        this.roundCandles[coin] = round;
    }

    getRoundCandles() {
        return this.roundCandles;
    }
}

module.exports = RoundCandleService;
