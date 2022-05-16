const {YELLOW, RED, GREEN} = require('../config/Constants');
const {randomProgress} = require('../utils');

class ProgressService {

    arrProgress = {};

    randomArrProgress(coin) {
        let progress = {
            [RED]: 0,
            [GREEN]: 0,
            [YELLOW]: 0
        };
        let arrProgress = [progress];
        for (let i = 1; i < 30; i++) {
            progress = randomProgress(progress);
            arrProgress.push(progress);
        }
        this.arrProgress[coin] = arrProgress;
    }

    getArrProgress() {
        return this.arrProgress;
    }
}

module.exports = ProgressService;
