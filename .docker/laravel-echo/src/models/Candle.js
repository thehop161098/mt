class Coin {
    round;
    coin;
    open;
    close;
    high;
    low;
    date;
    prize;

    constructor(round, coin, open, close, high, low, date) {
        this.round = round;
        this.coin = coin;
        this.open = open;
        this.close = close;
        this.high = high;
        this.low = low;
        this.date = date;
        this.prize = 1;
    }
}

module.exports = Coin;
