const Constant = {
    URL_COINS: "https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&ids={IDS}&order=market_cap_desc&per_page=100&page=1&sparkline=false",
    URL_GOLD_COINS: "https://currencydatafeed.com/api/data.php?token=beo67zdsok8ftt6hkns3&currency={IDS}",
    GREEN: 1,
    RED: 0,
    YELLOW: 2,
    MAIN_WALLET: 'main',
    DISCOUNT_WALLET: 'discount',
    TRIAL_WALLET: 'trial',
    NUM_YELLOW_CANDLES: 5,
    PRIZE: 3,
    LIMIT: 89,
    NUM_NOT_ADJUST: 4,
    LIMIT_EMA: 89
}

module.exports = Constant;
