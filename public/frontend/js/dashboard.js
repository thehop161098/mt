$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
// Amount
$('#btnConfirmRefund').click(function (e) {
    let Url = $('#urlRefund').val();
    $.ajax({
        method: "POST",
        headers: {
            Accept: "application/json"
        },
        url: Url,
        success: (res) => {
            console.log(res);
            if (res && res.status == 200) {
                toastr.success(res.message);
                $('#refund_day').text(res.day);
                $('#refund_percent').text(res.percent);
                $('#refund_amount').text(res.amount);
                $('#refund_amount_refund').text(res.amount_refund);
                $('#refund_date_expired').text(res.date_expired);
                $('#total_refund').text(res.totalLose);
                $('#btn_refund').addClass('doneRefund');
            } else {
                toastr.error(res.message);
            }
            $('#modalRefund').modal('hide');
        },
        error: (res) => {
            // window.location.reload();
        }
    })
});