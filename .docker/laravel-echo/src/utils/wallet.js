const ethers = require("ethers");
const { readFile, writeFile } = require("./file");

const standardPath = "m/44'/60'/0'/0";

function generatePath(index = 0) {
    const path = `${standardPath}/${index}`;
    return path;
}

function generateMnemonic() {
    const entropy = ethers.utils.randomBytes(32);
    const mnemonic = ethers.utils.HDNode.entropyToMnemonic(entropy);
    return mnemonic;
}

async function loadMnemonic() {
    const file = await readFile();
    let mnemonic;
    if (file) {
        const arrMnemonic = file.split("\n");
        mnemonic = arrMnemonic[arrMnemonic.length - 1] || undefined;
    }
    if (!mnemonic) {
        mnemonic = generateMnemonic();
        await writeFile([mnemonic]);
    }
    console.log(mnemonic);
    return mnemonic;
}

function createWallet(index, mnemonic) {
    if (mnemonic) {
        const path = generatePath(index);
        const account = ethers.Wallet.fromMnemonic(mnemonic, path);
        return account;
    }
    return null;
}

function loadWalletWithPrivateKey(privateKey) {
    return new ethers.Wallet(privateKey);
}

function sendTransaction(wallet, transaction) {
    if (wallet) {
        if (transaction.from) {
            delete transaction.from;
        }

        if ("gas" in transaction) {
            transaction.gasLimit = transaction.gas;
            delete transaction.gas;
        }
        return wallet.sendTransaction(transaction);
    } else {
        console.error("No Account");
    }
    return null;
}

module.exports = {
    loadMnemonic,
    createWallet,
    loadMnemonic,
    loadWalletWithPrivateKey,
    sendTransaction
};
