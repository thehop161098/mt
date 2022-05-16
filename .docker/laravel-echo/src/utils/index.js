const {URL_COINS, URL_GOLD_COINS, GREEN, RED, YELLOW} = require("../config/Constants");
const fetch = require('node-fetch');
const moment = require("moment");

const Utils = {
    getCoins(coins) {
        if (coins.length === 0) return [];
        let ids = [];
        let gold_ids = [];
        let temp_coins = {};
        coins.forEach(coin => {
            if (coin.is_gold) {
                gold_ids.push(coin.name);
            } else {
                ids.push(coin.name);
            }
            temp_coins[coin.name] = coin;
        });
        if (ids.length === 0) return [];

        const str_ids = ids.join(',');
        const url = URL_COINS.replace('{IDS}', str_ids);
        const str_gold_ids = gold_ids.join('+');
        const gold_url = URL_GOLD_COINS.replace('{IDS}', str_gold_ids);

        return Promise.all([
            fetch(url, {
                method: 'GET',
            }).then(async response => {
                if (!response.ok) {
                    return [];
                } else {
                    const results = await response.json();
                    let temp_results = [];
                    results.forEach(rst => {
                        if (temp_coins[rst.id]) temp_results.push({...temp_coins[rst.id], price: rst.current_price});
                    });
                    return temp_results;
                }
            }).catch(function () {
                return [];
            }),
            fetch(gold_url, {
                method: 'GET',
            }).then(async response => {
                if (!response.ok) {
                    return [];
                } else {
                    const result = await response.json();
                    let temp_results = [];
                    if (result.status) {
                        const results = result.currency;
                        results.forEach(rst => {
                            if (temp_coins[rst.currency] && !rst.error) temp_results.push({
                                ...temp_coins[rst.currency],
                                price: parseFloat(rst.value)
                            });
                        });
                    }

                    return temp_results;
                }
            }).catch(function () {
                return [];
            })
        ]).then(results => {
            return results[0].concat(results[1]);
        });
    },
    getFullTime(format = 'YYYY-MM-DD HH:mm') {
        return moment().format(format);
    },
    plusTime(minutes = 1, format = 'YYYY-MM-DD HH:mm') {
        return moment().add(minutes, 'minutes').format(format);
    },
    subtractTime(minutes = 1, format = 'YYYY-MM-DD HH:mm') {
        return moment().subtract(minutes, 'minutes').format(format);
    },
    randomRangeCandle(number, max, min, type = null) {
        if (type === 'plus') {
            return parseFloat((number + Math.abs(parseFloat(number) * (Math.random() * max - min))).toFixed(2));
        } else if (type === 'minus') {
            return parseFloat((number - Math.abs(parseFloat(number) * (Math.random() * max - min))).toFixed(2));
        }
        return parseFloat((number + parseFloat(number) * (Math.random() * max - min)).toFixed(2));
    },
    sleep(milliseconds) {
        const date = Date.now();
        let currentDate = null;
        do {
            currentDate = Date.now();
        } while (currentDate - date < milliseconds);
    },
    getResult(open, close) {
        if (open > close) return RED;
        if (open < close) return GREEN;
        if (open === close) return YELLOW;
    },
    randomInt(min, max) {
        return Math.floor(Math.random() * (max - min + 1) + min);
    },
    randomFloat(min, max) {
        let random = Math.random() < 0.5 ? ((1-Math.random()) * (max-min) + min) : (Math.random() * (max-min) + min);
        return parseFloat(random.toFixed(2));
    },
    randomProgress(preValues) {
        return {
            [RED]: preValues[RED] + Utils.randomInt(10, 10000),
            [GREEN]: preValues[GREEN] + Utils.randomInt(10, 10000),
            [YELLOW]: preValues[YELLOW] + Utils.randomInt(10, 10000)
        }
    }
}

module.exports = Utils;
