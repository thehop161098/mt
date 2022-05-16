import AppForm from '../app-components/Form/AppForm';

Vue.component('wallet-form', {
    mixins: [AppForm],
    data: function () {
        return {
            form: {
                coin: '',
                code: '',
                private_key: '',
                amount: '',
                cate_id: ''
            }
        }
    }

});
