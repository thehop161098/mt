import AppListing from "../app-components/Listing/AppListing";

Vue.component("wallet-listing", {
    mixins: [AppListing],
    methods: {
        resetItem: function resetItem(url) {
            var _this7 = this;

            this.$modal.show("dialog", {
                title: "Warning!",
                text: "Do you really want to reset this item?",
                buttons: [
                    { title: "No, cancel." },
                    {
                        title:
                            '<span class="btn-dialog btn-danger">Yes, Reset.<span>',
                        handler: function handler() {
                            _this7.$modal.hide("dialog");

                            axios.post(url).then(
                                function(response) {
                                    _this7.loadData();
                                    _this7.$notify({
                                        type: "success",
                                        title: "Success!",
                                        text: response.data.message
                                            ? response.data.message
                                            : "Item successfully reset."
                                    });
                                },
                                function(error) {
                                    _this7.$notify({
                                        type: "error",
                                        title: "Error!",
                                        text: error.response.data.message
                                            ? error.response.data.message
                                            : "An error has occured."
                                    });
                                }
                            );
                        }
                    }
                ]
            });
        },
        transferItem: function transferItem(url) {
            var _this7 = this;

            this.$modal.show("dialog", {
                title: "Warning!",
                text: "Do you really want to transfer to admin?",
                buttons: [
                    { title: "No, cancel." },
                    {
                        title:
                            '<span class="btn-dialog btn-danger">Yes, Transfer.<span>',
                        handler: function handler() {
                            _this7.$modal.hide("dialog");

                            axios.post(url).then(
                                function(response) {
                                    _this7.$notify({
                                        type: "success",
                                        title: "Success!",
                                        text: response.data.message
                                            ? response.data.message
                                            : "Item successfully transfer."
                                    });
                                },
                                function(error) {
                                    _this7.$notify({
                                        type: "error",
                                        title: "Error!",
                                        text: error.response.data.message
                                            ? error.response.data.message
                                            : "An error has occured."
                                    });
                                }
                            );
                        }
                    }
                ]
            });
        }
    }
});
