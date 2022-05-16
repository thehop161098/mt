const db = require('../connection/Dbconnection');

const CoinRepository = {
    getAll: function () {
        return db.query("Select * from coins");
    }
};

module.exports = CoinRepository;
