import AppListing from '../app-components/Listing/AppListing';

Vue.component('user-listing', {
    mixins: [AppListing],
    data: function () {
        return {
            id: '',
            verify: '',
            status: '',
            identity_card_after: '',
            identity_card_before: '',
            portrait: '',
        }
    },
    methods: {
        viewItem: function viewItem(images, id, status) {
            var _this7 = this;
            _this7.$modal.show('thumb-user', {
                ...images,
                id,
                status
            });
        },
        beforeOpen(event) {
            this.id = event.params.id;
            this.status = event.params.status;
            this.identity_card_after = event.params.identity_card_after;
            this.identity_card_before = event.params.identity_card_before;
            this.portrait = event.params.portrait;
        },
        beforeClose() {
            this.id = '';
            this.status = '';
            this.identity_card_after = '';
            this.identity_card_before = '';
            this.portrait = '';
        },
        handleVerify: function (url, id) {
            var _this = this;
            _this.$modal.hide('thumb-user');

            axios.get(url + '/' + id).then(function (response) {
                _this.loadData();
                _this.$notify({
                    type: 'success',
                    title: 'Success!',
                    text: response.data.message ? response.data.message : 'Item successfully reset.'
                });
            }, function (error) {
                _this.$notify({
                    type: 'error',
                    title: 'Error!',
                    text: error.response.data.message ? error.response.data.message : 'An error has occured.'
                });
            });
        }
    },
    watch: {
        verify: function (verify) {
            this.verify = verify;
            this.filter('verify', this.verify);
        }
    }
});
