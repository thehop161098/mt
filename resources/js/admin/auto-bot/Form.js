import AppForm from '../app-components/Form/AppForm';

Vue.component('auto-bot-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                name:  '' ,
                price:  '' ,
                commission_f1:  '' ,
                commission_7:  '' ,
                commission_21:  '' ,
                commission_30:  '' ,
                commission_90:  '' ,
                risk:  '' ,

            },
            mediaCollections: ['image']
        }
    }

});
