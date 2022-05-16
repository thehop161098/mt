import AppForm from '../app-components/Form/AppForm';

Vue.component('history-bot-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                user_id:  '' ,
                bot_id:  '' ,
                time:  '' ,
                time_expired:  '' ,
                status:  false ,
                
            }
        }
    }

});