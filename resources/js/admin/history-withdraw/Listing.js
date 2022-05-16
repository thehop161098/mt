import AppListing from '../app-components/Listing/AppListing';

Vue.component('history-withdraw-listing', {
    mixins: [AppListing],
    data: function () {
        return {
            id: '',
            reason: '',
            status: ''
        }
    },
    methods: {
        openReject: function openReject(id) {
            var _this7 = this;
            _this7.$modal.show('reject-withdraw', {id});
        },
        beforeOpen(event) {
            this.id = event.params.id;
        },
        beforeClose() {
            this.id = '';
            this.reason = '';
        },
        handleReject: function (url) {
            var _this = this;
            if (!_this.reason) {
                _this.$notify({
                    type: 'error',
                    title: 'Error!',
                    text: 'Reason cannot be blank!'
                });
                return;
            }

            axios.post(url, {
                id: _this.id,
                status: _this.$attrs.status_reject,
                reason: _this.reason
            }).then(function (response) {
                _this.loadData();
                _this.$notify({
                    type: 'success',
                    title: 'Success!',
                    text: response.data.message ? response.data.message : 'Reject successfully.'
                });
                _this.$modal.hide('reject-withdraw');
            }, function (error) {
                _this.$notify({
                    type: 'error',
                    title: 'Error!',
                    text: error.response.data.message ? error.response.data.message : 'An error has occured.'
                });
            });
        },
        handleApproved: function (url, id) {
            var _this = this;

            this.$modal.show('dialog', {
                title: 'Warning!',
                text: 'Do you really want to approve this item?',
                buttons: [{title: 'No, cancel.'}, {
                    title: '<span class="btn-dialog btn-danger">Yes, Approve.<span>',
                    handler: function handler() {
                        _this.$modal.hide('dialog');

                        axios.post(url, {
                            id: id,
                            status: _this.$attrs.status_approved
                        }).then(function (response) {
                            _this.loadData();
                            _this.$notify({
                                type: 'success',
                                title: 'Success!',
                                text: response.data.message ? response.data.message : 'Item successfully approved.'
                            });
                        }, function (error) {
                            _this.$notify({
                                type: 'error',
                                title: 'Error!',
                                text: error.response.data.message ? error.response.data.message : 'An error has occured.'
                            });
                        });
                    }
                }]
            });
        },
    },
    watch: {
        status: function (status) {
            this.status = status;
            this.filter('status', this.status);
        }
    }
});
