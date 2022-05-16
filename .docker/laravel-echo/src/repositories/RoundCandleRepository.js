const db = require('../connection/Dbconnection');

const RoundCandleRepository = {
    getAll: function () {
        return db.query(`Select coin, round from round_candles`);
    },
    create: function (coin, round) {
        const sql = `INSERT INTO round_candles (coin, round) VALUES ('${coin}', ${round})`;
        return db.query(sql).then(rst => rst ? rst.insertId : null);
    },
    update: function (coin, round) {
        const sql = `UPDATE round_candles SET round = ${round} WHERE coin = '${coin}'`;
        return db.query(sql).then(rst => rst ? rst.insertId : null);
    },
};

module.exports = RoundCandleRepository;
