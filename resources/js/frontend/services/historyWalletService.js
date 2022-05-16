import {HTTP} from "../../http-common";

export const readAllNotifications = function () {
    return HTTP.get('historyWallets/readAll').then(response => {
        if (response.data) return response.data;
        return null;
    });
}
