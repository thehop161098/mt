const config = {
    database: {
        host: "db",
        user: process.env.DB_USERNAME,
        password: process.env.DB_PASSWORD,
        database: process.env.DB_DATABASE
    }
};

module.exports = config;
