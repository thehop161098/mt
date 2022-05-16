import AppForm from '../app-components/Form/AppForm';

Vue.component('email-template-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                code:  '' ,
                subject:  '' ,
                content:  '' ,
                
            }
        }
    }

});