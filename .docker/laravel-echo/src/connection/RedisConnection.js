var Redis = require('ioredis');
var redis = new Redis({
    "port": 6379,
    "host": process.env.LARAVEL_ECHO_SERVER_REDIS_HOST,
    "password": process.env.LARAVEL_ECHO_SERVER_REDIS_PASSWORD
});

module.exports = redis;
