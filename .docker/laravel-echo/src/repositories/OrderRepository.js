const db = require('../connection/Dbconnection');
const {getFullTime} = require('../utils');
const {TRIAL_WALLET} = require('../config/Constants');

const OrderRepository = {
    getAll: function () {
        let where = `wallet != '${TRIAL_WALLET}' AND date='${getFullTime()}'`;
        return db.query(`SELECT coin, type, SUM(amount) AS amount FROM orders WHERE ${where} GROUP BY coin, type`);
    },
    getProfitInDay: function () {
        let where = `profit IS NOT NULL AND wallet != '${TRIAL_WALLET}' AND date>='${getFullTime('YYYY-MM-DD 00:00')}' AND date<='${getFullTime('YYYY-MM-DD 23:59')}'`;
        const sql = `SELECT SUM(profit) AS profit FROM orders WHERE ${where}`;
        return db.query(sql).then(rows => {
            if (rows[0] && rows[0].profit !== null) return rows[0].profit;
            return 0;
        });
    },
};

module.exports = OrderRepository;
