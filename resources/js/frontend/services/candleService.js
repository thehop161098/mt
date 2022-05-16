import {HTTP} from "../../http-common";

export const getConfigs = function () {
    return HTTP.get('candles/getConfigs').then(response => {
        if (response.data) return response.data;
        return null;
    });
}

export const getCandles = function (coin) {
    const coin_str = coin ? coin.replace("/", ".") : '';
    return HTTP.get(`candles/getCandles/${coin_str}`).then(response => {
        if (response.data) return response.data;
        return null;
    });
}

export const getCirCleHistory = function (coin) {
    const coin_str = coin ? coin.replace("/", ".") : '';
    return HTTP.get(`candles/getCirCleHistory/${coin_str}`).then(response => {
        if (response.data) return response.data;
        return null;
    });
}
