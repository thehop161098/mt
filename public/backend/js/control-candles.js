$(function () {
    $(document).on('click', '.action_candles', actionCandles);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    window.Echo.channel('second')
        .listen('SecondEvent', function (res) {
            const second = parseInt(res.second) < 10 ? '0' + parseInt(res.second) : parseInt(res.second);
            $('#second').html(second);
        });


    let numOnline = 0;
    window.Echo.join('online').here((users) => {
        numOnline = users.length;
        $('#num-users-online').html(numOnline);
    }).joining((user) => {
        numOnline++;
        $('#num-users-online').html(numOnline);
        // this.users.push(user);
        // this.usersCount = this.usersCount+1;
    }).leaving((user) => {
        numOnline--;
        $('#num-users-online').html(numOnline);
    });
});

function actionCandles(event) {
    event.preventDefault();
    let id = $(this).data('id');
    let urlRequest = $(this).data('url');
    let bitcoin = $(this).data('bitcoin');
    let prize = $(`#prize-${id}`).val();
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to change the " + bitcoin,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, do it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: 'POST',
                headers: {
                    Accept: "application/json"
                },
                url: urlRequest,
                data: {prize: prize},
                success: (res) => {
                    if (res.success) {
                        // $(`#updateClose-${res.id}`).text(res.close);
                        // $(`#updateResult-${res.id}`).text(res.result);
                        // $(`#prize-${res.id}`).val(res.prize);
                        toastr.success(res.message);
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(res.message);
                    }
                }
            });
        }
    })

}
