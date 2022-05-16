const db = require('../connection/Dbconnection');
const redis = require('../connection/RedisConnection');
const {getFullTime} = require('../utils');
const {GREEN, YELLOW, RED, LIMIT, LIMIT_EMA} = require('../config/Constants');

const CandleRepository = {
    getAll: function (conditions) {
        let where = '1 = 1';
        if (conditions.length > 0) where = conditions.join(' AND ');
        return db.query(`Select * from candles WHERE ${where}`);
    },
    create: function (candle, lastCandle) {
        const {round, coin, open, close, high, low, date, prize} = candle;
        const created_at = getFullTime("YYYY-MM-DD HH:mm:ss");
        const sql = `INSERT INTO candles (round, coin, open, close, high, low, date, prize, created_at, updated_at) 
                        VALUES (${round}, '${coin}', ${open}, ${close}, ${high}, ${low}, '${date}', ${prize}, '${created_at}', '${created_at}')`;
        const key = `candles:latest:${coin}`;
        redis.get(key).then(function (result) {
            let candles = result ? [...JSON.parse(result)] : [];
            candles = [lastCandle].concat(candles);
            if (candles.length > LIMIT) candles.pop();
            redis.set(key, JSON.stringify(candles));
        });
        const keyCircle = `circleCandles:latest:${coin}`;
        redis.get(keyCircle).then(function (result) {
            let circle = result ? [...JSON.parse(result)] : [];
            if (lastCandle) {
                let rst = {result: "", round: lastCandle.round};
                if (lastCandle.open === lastCandle.close) rst.result = YELLOW;
                else if (lastCandle.open < lastCandle.close) rst.result = GREEN;
                else if (lastCandle.open > lastCandle.close) rst.result = RED;
                circle.push(rst);
            }

            if (circle.length > 100) circle = [];
            redis.set(keyCircle, JSON.stringify(circle));
        });

        // const keyEma = `ema:${coin}`;
        // redis.get(keyEma).then(function (result) {
        //     let arrEma = result ? [...JSON.parse(result)] : [];
        //     if (lastCandle.close) arrEma.push({close: lastCandle.close});
        //     if (arrEma.length > LIMIT_EMA) arrEma.shift();
        //     redis.set(keyEma, JSON.stringify(arrEma));
        // });

        return db.query(sql).then(rst => rst ? rst.insertId : null);
    },
    update: function (candle) {
        const sql = `UPDATE candles SET close=${candle.close}, prize=${candle.prize}, high=${candle.high}, low=${candle.low} WHERE date = '${candle.date}' AND coin = '${candle.coin}'`;
        return db.query(sql);
    }
};

module.exports = CandleRepository;
