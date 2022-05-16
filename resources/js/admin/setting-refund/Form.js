import AppForm from '../app-components/Form/AppForm';

Vue.component('setting-refund-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                day:  '' ,
                amount:  '' ,
                percent:  '' ,
                
            }
        }
    }

});