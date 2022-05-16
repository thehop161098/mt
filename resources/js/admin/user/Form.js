import AppForm from '../app-components/Form/AppForm';

Vue.component('user-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                full_name:  '' ,
                email:  '' ,
                email_verified_at:  '' ,
                avatar:  '' ,
                intro:  '' ,
                referral_code:  '' ,
                password:  '' ,
            }
        }
    }

});
