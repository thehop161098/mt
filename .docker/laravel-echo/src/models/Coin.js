class Coin {
    name;
    range;
    max;
    min;
    price;
    is_gold;

    constructor(coin) {
        this.name = coin.name;
        this.range = coin.range;
        this.max = coin.max;
        this.min = coin.min;
        this.price = coin.price;
        this.is_gold = coin.is_gold;
    }
}

module.exports = Coin;
