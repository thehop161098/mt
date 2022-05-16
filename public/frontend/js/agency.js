$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Amount
    $('#btnConfirm').click(function (e) {
        let Url = $('#urlAgency').val();
        $('#btnConfirm').addClass('disabled-click');
        $.ajax({
            method: "POST",
            headers: {
                Accept: "application/json"
            },
            url: Url,
            success: (res) => {
                if (res && res.success) {
                    toastr.success(res.message);
                    $('#codeUser').val(res.code);
                    $('#referralCodeUser').val(res.referralCode);
                    $('.infoRank__level').text('Rank #' + res.level);
                    $(".doneBuy").addClass("btnDisableBuy");
                    $('.boxInput__btnCoppy').removeClass('hidden');
                } else {
                    toastr.error(res.message);
                }
                $('#modalUpgradeAgency').modal('hide');
                $('#btnConfirm').removeClass('disabled-click');
            },
            error: (res) => {
                // window.location.reload();
            }
        })
    });
});

function onCopied(elementId) {
    var copyText = document.getElementById(elementId);
    copyText.select();
    copyText.setSelectionRange(0, 99999)
    document.execCommand("copy");
    toastr.success("Copied successfully");
}
