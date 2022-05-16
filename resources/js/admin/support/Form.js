import AppForm from '../app-components/Form/AppForm';

Vue.component('support-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                full_name:  '' ,
                email:  '' ,
                phone:  '' ,
                content:  '' ,
                response:  '' ,
                status:  false ,
                
            }
        }
    }

});