const WalletRepository = require("../repositories/WalletRepository");
const {
    loadMnemonic,
    createWallet,
    loadWalletWithPrivateKey,
    sendTransaction
} = require("../utils/wallet");
const ethers = require("ethers");

class WalletService {
    async autoCreate() {
        const total = await WalletRepository.count();
        const currentIndex = total > 0 ? total : 0;
        const mnemonic = await loadMnemonic();
        const data = [];
        for (let index = currentIndex; index < currentIndex + 100; index++) {
            const account = createWallet(index, mnemonic);
            if (account) {
                data.push({
                    address: account.address,
                    private_key: account.privateKey
                });
            }
        }
        if (data.length > 0) {
            WalletRepository.create(data);
        }
    }
    async autoTransfer(wallet_id, to) {
        const model = await WalletRepository.findById(wallet_id);
        if (model.length > 0) {
            const provider = new ethers.providers.JsonRpcProvider(
                process.env.RPC
            );
            let wallet = loadWalletWithPrivateKey(model[0].private_key);

            if (provider) {
                wallet = wallet.connect(provider);
                return Promise.all([
                    provider.getGasPrice(),
                    provider.estimateGas({
                        to,
                        value: 1
                    }),
                    wallet.getBalance(),
                    provider.getTransactionCount(wallet.address, "latest")
                ]).then(async ([gasUsed, gasLimit, balance, nonce]) => {
                    if (gasUsed && gasLimit && balance) {
                        const fee = gasUsed.mul(gasLimit);

                        if (balance.gt(fee)) {
                            const value = balance.sub(fee);
                            const value_ether = ethers.utils.formatEther(value);
                            try {
                                console.log(
                                    `Sending ${value_ether} to ${to} from ${wallet.address}`
                                );
                                return await sendTransaction(wallet, {
                                    to,
                                    value,
                                    nonce
                                }).then(transferResult => {
                                    if (transferResult && transferResult.hash) {
                                        console.log(
                                            `Transfer successfully ${value_ether} to ${to} from ${wallet.address}`
                                        );
                                        return {
                                            success: true,
                                            message: `Transfer successfully ${value_ether} BNB to ${to}`
                                        };
                                    }
                                    return { success: false };
                                });
                            } catch (error) {
                                console.error(
                                    "Transfer " +
                                        wallet.address +
                                        " to " +
                                        to +
                                        " failed",
                                    error
                                );
                            }
                        }
                        return { success: false };
                    }
                });
            }
            return { success: false };
        }
    }
}

module.exports = WalletService;
