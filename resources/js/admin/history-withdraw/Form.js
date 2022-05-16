import AppForm from '../app-components/Form/AppForm';

Vue.component('history-withdraw-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                tx_hash:  ''
            }
        }
    }

});
