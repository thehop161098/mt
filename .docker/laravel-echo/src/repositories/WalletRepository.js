const db = require("../connection/Dbconnection");
const { getFullTime } = require("../utils");

const WalletRepository = {
    count: async function() {
        const rst = await db.query(`Select COUNT(id) as total FROM wallets`);
        return rst[0] && rst[0].total ? rst[0].total : 0;
    },
    findById: async function(id) {
        return db.query(`Select private_key FROM wallets WHERE id = '${id}'`);
    },
    isExists: function(address) {
        return db.query(
            `Select id as total FROM wallets WHERE code = '${address}'`
        );
    },
    create: async function(data) {
        const created_at = getFullTime("YYYY-MM-DD HH:mm:ss");
        let sql = `INSERT INTO wallets (cate_id, coin, code, private_key, amount, amount_bsc, user_id, created_at, updated_at) VALUES `;
        const rows = await Promise.all(
            data.map(async row => {
                const isExists = await this.isExists(row.address).then(
                    rst => rst.length > 0
                );
                if (!isExists) {
                    return `(1, 'binancecoin', '${row.address}', '${row.private_key}', 0, 0, NULL, '${created_at}', '${created_at}')`;
                }
            })
        ).then(rst => rst.filter(r => r));
        if (rows.length > 0) {
            return db
                .query(sql + rows.join(","))
                .then(rst => (rst ? rst.insertId : null));
        }
        return null;
    }
};

module.exports = WalletRepository;
