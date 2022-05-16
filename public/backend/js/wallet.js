$(function() {
    $(document).on("click", ".autoCreate", autoCreate);

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });

    window.Echo.channel("auto-transfer").listen("AutoTransferEvent", function({
        success,
        message
    }) {
        if (success !== undefined) {
            if (success) {
                toastr.success(message || "Transfer successfully");
            } else {
                toastr.error("Transfer Failed");
            }
        }
    });
});

function autoCreate() {
    let urlRequest = $(this).data("url");
    $.ajax({
        type: "GET",
        headers: {
            Accept: "application/json"
        },
        url: urlRequest,
        success: res => {
            if (res.success) {
                toastr.success(res.message);
                setTimeout(function() {
                    location.reload();
                }, 3000);
            } else {
                toastr.error(res.message);
            }
        }
    });
}
