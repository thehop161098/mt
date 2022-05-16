const CoinRepository = require('../repositories/CoinRepository');
const Coin = require('../models/Coin');
const { getCoins } = require('../utils');

class CoinService {
    coins = [];

    setCoins() {
        return CoinRepository.getAll().then(coinsDb => {
            let coins = [];
            coinsDb.forEach(coin => {
                coins.push(new Coin(coin));
            });
            return getCoins(coins).then(results => {
                this.coins = results;
                return results;
            });
        });
    }

    getCoins() {
        return this.coins;
    }
}

module.exports = CoinService;

