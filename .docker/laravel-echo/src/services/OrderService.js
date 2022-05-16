const OrderRepository = require('../repositories/OrderRepository');
const {YELLOW, RED, GREEN} = require('../config/Constants');

class OrderService {

    coinOrders = {};
    coinProfit = {};

    setCoinOrders() {
        return OrderRepository.getAll().then(rows => {
            if (rows.length > 0) {
                let temp = {};
                rows.forEach(row => {
                    if (!temp[row.coin]) temp[row.coin] = {[YELLOW]: 0, [RED]: 0, [GREEN]: 0};
                    temp[row.coin][row.type] = row.amount;
                });
                this.coinProfit = temp;

                let coinOrders = {};
                for (const coin in temp) {
                    if (temp.hasOwnProperty(coin)) {
                        const elm = temp[coin];
                        let max;
                        let tempType = [];
                        for (const type in elm) {
                            if (elm.hasOwnProperty(type)) {
                                const amount = elm[type];
                                if (max === undefined) {
                                    max = amount;
                                    tempType = [parseInt(type)];
                                } else {
                                    if (max === amount) {
                                        tempType.push(parseInt(type));
                                    } else if (max > amount) {
                                        max = amount;
                                        tempType = [parseInt(type)];
                                    }
                                }
                            }
                        }
                        coinOrders[coin] = tempType;
                    }
                }
                this.coinOrders = coinOrders;
                return coinOrders;
            }
        });
    }

    resetCoinOrders() {
        this.coinOrders = {};
    }

    getCoinOrders() {
        return this.coinOrders;
    }

    getCoinProfit() {
        return this.coinProfit;
    }
}

module.exports = OrderService;
