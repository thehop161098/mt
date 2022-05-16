import Vue from 'vue';
import Vuex from 'vuex';
import {walletStore} from './walletStore';
import {coinStore} from './coinStore';

Vue.use(Vuex);

export const store = new Vuex.Store({
    modules: {
        wallet: walletStore,
        coin: coinStore
    }
});
