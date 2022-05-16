const OrderRepository = require('../repositories/OrderRepository');
const SettingRepository = require('../repositories/SettingRepository');
const {randomInt} = require('../utils');
const {PRIZE} = require('../config/Constants');

class ControlService {

    maxProfit = 0;
    maxProfitAdmin = 0;
    rangeProfitAllow = 0;
    profit = 0;
    max = 1;
    min = 1;
    prize = PRIZE;
    arrControl = {};
    arrCheckControl = {};
    maxAmountWin = 0;

    constructor() {
        this.setProfit();
        this.setControl();
    }

    setArrControl(coin) {
        if (this.arrControl[coin] === undefined) {
            console.log('Run Reset unControl', coin);
            this.arrControl[coin] = randomInt(this.min, this.max);
            this.arrCheckControl[coin] = 0;
        }
    }

    setArrCheckControl(coin) {
        if (this.arrCheckControl[coin] !== undefined) {
            let unControl = this.arrControl[coin];
            let count = this.arrCheckControl[coin] + 1;
            console.log('count', count);
            console.log('count unControl', unControl);
            console.log('is check count', count < unControl);
            if (count < unControl) {
                this.arrCheckControl[coin] = count;
                return true;
            }
            this.setArrControl(coin);
            console.log('Reset unControl', this.arrControl[coin]);
        }
        return false;
    }

    isControl(coin) {
        console.log('this.min', this.min);
        console.log('this.max', this.max);
        console.log('Current profit', this.profit);
        console.log('Max profit', this.maxProfit);
        console.log('Max profit admin', this.maxProfitAdmin);
        // dont control result
        if (this.min === 0 && this.max === 0) return false;

        // control all result
        if (this.min === 1 && this.max === 1) return true;

        if (this.profit > this.maxProfitAdmin) return true;

        // check control
        if (this.profit > this.maxProfit) return this.setArrCheckControl(coin);

        return false;
    }

    plusProfit(profit) {
        this.profit += profit;
    }

    setProfit() {
        return OrderRepository.getProfitInDay().then(profit => {
            this.profit = profit;
        });
    }

    setControl() {
        SettingRepository.getControl().then(rst => {
            this.maxProfit = rst.maxProfit;
            this.maxProfitAdmin = rst.maxProfitAdmin;
            this.rangeProfitAllow = rst.rangeProfitAllow;
            this.min = rst.min;
            this.max = rst.max;
            this.prize = rst.prize;
            this.maxAmountWin = rst.maxAmountWin;
        });
    }

    resetProfit() {
        this.profit = 0;
    }
}

module.exports = ControlService;
