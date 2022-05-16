import AppForm from '../app-components/Form/AppForm';

Vue.component('wheel-setting-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                amount:  '' ,
                prize:  '' ,
                
            }
        }
    }

});