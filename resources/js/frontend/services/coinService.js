import {HTTP} from "../../http-common";

export const getCoins = function () {
    return HTTP.get('coins/getCoins').then(response => {
        if (response.data) return response.data;
        return [];
    });
}
