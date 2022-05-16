import AppForm from '../app-components/Form/AppForm';

Vue.component('coin-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                name:  '' ,
                image:  '' ,
                alias:  '' ,
                range:  '' ,
                min:  '' ,
                max:  '' ,
                is_gold: false,
                publish: true
            }
        }
    }

});
