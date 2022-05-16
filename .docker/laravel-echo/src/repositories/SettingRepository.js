const db = require('../connection/Dbconnection');
const {PRIZE} = require('../config/Constants');

const SettingRepository = {
    getControl: function () {
        let where = `\`key\` IN ('max_profit', 'max_profit_admin', 'min', 'max', 'prize', 'max_amount_win')`;
        return db.query(`SELECT value, \`key\` FROM settings WHERE ${where}`).then(rows => {
            let result = {
                maxProfit: 0,
                maxProfitAdmin: 0,
                rangeProfitAllow: 0,
                min: 2,
                max: 2,
                prize: PRIZE,
                maxAmountWin: 0
            };
            if (rows.length > 0) {
                rows.forEach(row => {
                    const value = parseFloat(row.value);
                    if (!isNaN(value)) {
                        if (row.key === 'max_profit') result.maxProfit = parseFloat(row.value);
                        else if (row.key === 'max_profit_admin') result.maxProfitAdmin = parseFloat(row.value);
                        else if (row.key === 'max_amount_win') result.maxAmountWin = parseFloat(row.value);
                        else result[row.key] = parseFloat(row.value);
                    }
                });
                result.rangeProfitAllow = Math.abs(Math.abs(result.maxProfitAdmin) - Math.abs(result.maxProfit));
            }
            return result;
        });
    }
};

module.exports = SettingRepository;
