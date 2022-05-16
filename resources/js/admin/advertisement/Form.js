import AppForm from '../app-components/Form/AppForm';

Vue.component('advertisement-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                name:  '' ,
                sort:  '' ,
                publish:  false ,
                
            },
            mediaCollections: ['image']
        }
    }

});
