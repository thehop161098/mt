const Echo = require("laravel-echo-server");
const AppFacade = require("./src/facade/AppFacade");
const { getFullTime } = require("./src/utils");
const WalletService = require("./src/services/WalletService");

var Redis = require("ioredis");

var redis = new Redis({
    port: 6379,
    host: process.env.LARAVEL_ECHO_SERVER_REDIS_HOST,
    password: process.env.LARAVEL_ECHO_SERVER_REDIS_PASSWORD
});

var options = {
    authHost: process.env.LARAVEL_ECHO_SERVER_AUTH_HOST,
    authEndpoint: "/broadcasting/auth",
    clients: [],
    database: "redis",
    databaseConfig: {
        redis: {
            port: 6379,
            host: process.env.LARAVEL_ECHO_SERVER_REDIS_HOST,
            password: process.env.LARAVEL_ECHO_SERVER_REDIS_PASSWORD
        },
        sqlite: {
            databasePath: "/database/laravel-echo-server.sqlite"
        }
    },
    devMode: process.env.LARAVEL_ECHO_SERVER_DEBUG,
    host: null,
    port: "6001",
    protocol: "http",
    socketio: {},
    secureOptions: 67108864,
    sslCertPath: "",
    sslKeyPath: "",
    sslCertChainPath: "",
    sslPassphrase: "",
    subscribers: {
        http: true,
        redis: true
    },
    apiOriginAllow: {
        allowCors: false,
        allowOrigin: "",
        allowMethods: "",
        allowHeaders: ""
    },
    publishPresence: true
};
/**
 * Run the Laravel Echo Server.
 */

// init AppFacade
const appFacade = AppFacade.getInstance();
let is_new = false;
let date = getFullTime("YYYY-MM-DD").toString();
let dateTime = getFullTime("YYYY-MM-DD HH").toString();

Echo.run(options).then(echo => {
    redis.psubscribe("reset-candle-manual", function(error, count) {});

    redis.psubscribe("generate-wallet", function(error, count) {});

    redis.psubscribe("auto-transfer", function(error, count) {});

    redis.on("pmessage", function(pattern, channel, message) {
        if (channel === "reset-candle-manual") {
            const data = JSON.parse(message);
            const arrCandles = appFacade.resetCandleManual(
                data.data.coin,
                data.data.result,
                data.data.prize
            );
            if (arrCandles) {
                echo.toAll("reset-candle", {
                    event: "App\\Events\\ResetCandleEvent",
                    data: { arrCandles }
                });
            }
        } else if (channel === "generate-wallet") {
            new WalletService().autoCreate();
        } else if (channel === "auto-transfer") {
            const { data } = JSON.parse(message);
            new WalletService()
                .autoTransfer(data.wallet_id, data.address)
                .then(({ success, message }) => {
                    echo.toAll("auto-transfer", {
                        event: "App\\Events\\AutoTransferEvent",
                        data: { success, message }
                    });
                });
        }
    });
    // random new candle
    setInterval(function() {
        const second = parseInt(getFullTime("s"));
        echo.toAll("second", {
            event: "App\\Events\\SecondEvent",
            data: { second }
        });
        // console.log('second', second);
        const arrProgress = appFacade.progressService.getArrProgress();
        echo.toAll("progress", {
            event: "App\\Events\\ProgressEvent",
            data: { arrProgress, second }
        });

        if (!is_new && second < 30) {
            is_new = true;

            const dateCurrent = getFullTime("YYYY-MM-DD").toString();
            const dateTimeCurrent = getFullTime("YYYY-MM-DD HH").toString();
            if (date !== dateCurrent) {
                appFacade.controlService.resetProfit();
            }

            if (dateTime !== dateTimeCurrent) {
                dateTime = dateTimeCurrent;
                appFacade.randomYellowCandles();
            }

            const arrCandles = appFacade.randomCandles(
                parseInt(getFullTime("mm"))
            );
            appFacade.randomProgress();
            echo.toAll("new-candle", {
                event: "App\\Events\\NewCandleEvent",
                data: { arrCandles }
            });
        } else if (is_new && second > 29 && second < 60) {
            is_new = false;
            appFacade
                .controlCandles(parseInt(getFullTime("mm")))
                .then(arrCandles => {
                    echo.toAll("reset-candle", {
                        event: "App\\Events\\ResetCandleEvent",
                        data: { arrCandles }
                    });
                });
        }
    }, 1000);

    // reset coins
    setInterval(function() {
        appFacade.coinService.setCoins().then(coins => {
            appFacade.resetCoins(coins);
        });
        appFacade.controlService.setControl();
    }, 1000 * 60 * 3);
});
