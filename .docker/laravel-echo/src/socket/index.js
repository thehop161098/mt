const AppFacade = require('../facade/AppFacade');
const {getFullTime} = require('../utils');

const appFacade = AppFacade.getInstance();
let is_new = false;

var Redis = require('ioredis');
var redis = new Redis({
    port: 6379, // Redis port
    host: "redis", // Redis host
    family: 4, // 4 (IPv4) or 6 (IPv6)
    password: "gxvRDbHUpwkgZAdR",
    db: 0,
});

// redis.get('candles:latest:bitcoin').then(function (result) {
//     console.log(result);
// });

redis.on('pmessage', function (partner, channel, message) {
    console.log(partner)
    console.log(channel)
    console.log(message)
});

async function runSocket(io) {
    // random new candle
    setInterval(function () {
        const second = parseInt(getFullTime('s'));
        // console.log('second', second);
        if (!is_new && second < 30) {
            is_new = true;
            appFacade.randomCandles();
        } else if (is_new && second > 29 && second < 60) {
            is_new = false;
        }
    }, 1000);

    // reset coins
    setInterval(function () {
        appFacade.initCoinService();
    }, 1000 * 60 * 3);

    io.sockets.on('connection', function (socket) {
        console.log('NEW CLIENT CONNECTED')
    });
}

module.exports = runSocket;
