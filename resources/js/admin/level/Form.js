import AppForm from '../app-components/Form/AppForm';

Vue.component('level-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                name:  '' ,
                amount:  '' ,
                commission_f1:  '' ,
                total_f1:  '' ,
                total_trade:  '' ,
                master_ib:  '' ,
                
            }
        }
    }

});