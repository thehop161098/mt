export const coinStore = {
    namespaced: true,
    state: {
        coins: [],
        goldCoins: [],
        coinSelected: null
    },
    getters: {
        coins: state => state.coins,
        goldCoins: state => state.goldCoins,
        coinSelected: state => state.coinSelected
    },
    mutations: {
        setCoins(state, coins) {
            let coinsState = [];
            let goldCoinsState = [];
            let coinSelected = null;
            if (coins.length > 0) {
                coins.forEach(coin => {
                    if (coin.is_gold) {
                        goldCoinsState.push({...coin});
                    } else {
                        if (!coinSelected) coinSelected = {...coin};
                        coinsState.push({...coin});
                    }
                });
                state.coinSelected = coinSelected;
            }
            state.coins = coinsState;
            state.goldCoins = goldCoinsState;
        },
        setCoinSelected(state, coinSelected) {
            state.coinSelected = coinSelected;
        },
    }
}
