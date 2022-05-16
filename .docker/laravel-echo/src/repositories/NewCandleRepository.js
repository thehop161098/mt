const redis = require('../connection/RedisConnection');

const NewCandleRepository = {
    save: function (coin, newCandle) {
        return redis.set(`newCandle:${coin}`, JSON.stringify(newCandle), 'EX', 59);
    }
};

module.exports = NewCandleRepository;
