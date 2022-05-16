import AppForm from '../app-components/Form/AppForm';

Vue.component('discount-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                name:  '' ,
                date_show_image:  '' ,
                deposit:  '' ,
                discount:  '' ,
                from_date:  '' ,
                to_date:  '' ,
                
            },
            mediaCollections: ['image']
        }
    }

});
