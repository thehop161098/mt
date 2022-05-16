import {HTTP} from "../../http-common";

export const getWallets = function () {
    return HTTP.get('walletGames/getWallets').then(response => {
        if (response.data) return response.data;
        return [];
    });
}
export const getWalletSelected = function () {
    return HTTP.get('walletGames/getWalletSelected').then(response => {
        if (response.data) return response.data;
        return null;
    });
}

export const changeWalletSelected = function (type) {
    return HTTP.get(`walletGames/changeWalletSelected/${type}`).then(response => {
        if (response.data) return response.data;
        return {success: false};
    });
}
