export const walletStore = {
    namespaced: true,
    state: {
        wallets: [],
        walletSelected: null
    },
    getters: {
        wallets: state => state.wallets,
        walletSelected: state => state.walletSelected
    },
    mutations: {
        setWallets(state, wallets) {
            if (wallets[0]) state.wallets = wallets;
            else {
                const newWallets = state.wallets.map(wallet => {
                    if (wallet.type === wallets.type) return wallets;
                    return wallet;
                });
                state.wallets = newWallets;
            }
        },
        setWallet(state, wallet) {
            const wallets = state.map(s => {
                if (s.type === wallet.type) return {...wallet, amount: wallet.amount};
                return s;
            });
            state.wallets = wallets
        },
        setWalletSelected(state, walletSelected) {
            state.walletSelected = walletSelected;
        },
        resetWalletSelected(state, wallets) {
            if (wallets[state.walletSelected.type] && wallets[state.walletSelected.type] > 0) {
                const amount = state.walletSelected.amount + wallets[state.walletSelected.type];
                state.walletSelected = {...state.walletSelected, amount};
            }
        },
        resetWallets(state, wallets) {
            const newWallets = state.wallets.map(wallet => {
                if (wallets[wallet.type] && wallets[wallet.type] > 0) {
                    const amount = wallet.amount + wallets[wallet.type];
                    return {...wallet, amount};
                }
                return wallet;
            });
            state.wallets = newWallets;
        }
    }
}
