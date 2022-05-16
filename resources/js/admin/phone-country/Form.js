import AppForm from '../app-components/Form/AppForm';

Vue.component('phone-country-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                name:  '' ,
                
            }
        }
    }

});