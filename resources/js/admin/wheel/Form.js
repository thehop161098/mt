import AppForm from '../app-components/Form/AppForm';

Vue.component('wheel-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                name:  '' ,
                prize:  '' ,
                sort:  '' ,
                
            }
        }
    }

});