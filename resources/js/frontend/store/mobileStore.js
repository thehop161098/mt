export const mobileStore = {
    namespaced: true,
    state: {
        isMobile: false
    },
    getters: {
        coins: state => state.coins,
        coinSelected: state => state.coinSelected
    },
    mutations: {
        isMobile() {
            if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                return true
            } else {
                return false
            }
        },
        setCoins(state, coins) {
            state.coins = coins;
            if (coins.length > 0) {
                state.coinSelected = {...coins[0]};
            }
        },
        setCoinSelected(state, coinSelected) {
            state.coinSelected = coinSelected;
        },
    }
}
