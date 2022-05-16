$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $("#imgInp").change(function () {
        readURL(this);
    });

    // Change Password
    $('#changePasswordForm').submit(function (e) {
        e.preventDefault();
        $('.form__error.removeErr').addClass("hidden");
        let Url = $(this).attr('action');
        let formData = $(this).serializeArray();
        $.ajax({
            method: "POST",
            headers: {
                Accept: "application/json"
            },
            url: Url,
            data: formData,
            success: (res) => {
                if (res.success == 200) {
                    $('#modalChangePassword').modal('hide');
                    toastr.success("Change password successfull!");
                }
            },
            error: (res) => {
                if (res.status === 422) {
                    let errors = res.responseJSON.errors;
                    Object.keys(errors).forEach(function (key) {
                        $("#" + key + "MessError").removeClass("hidden");
                        $("#" + key + "Error").text(errors[key][0]);
                    });
                } else {
                    window.location.reload();
                }
            }
        })
    });

    // 2FA Auth Step 1
    $('#show2FAStep1').click(function (e) {
        let Url = $('#url2FA').val();
        $.ajax({
            method: "POST",
            headers: {
                Accept: "application/json"
            },
            url: Url,
            success: (res) => {
                $('#modalActiveGoogleStep1').modal('hide');
                $('#modalActiveGoogleStep2').modal('show');
                $('#show2FAStep2').html(res.data);
            },
            error: (res) => {
                window.location.reload();
            }
        })
    });
    // 2FA Auth Step 2
    $(document).on("click", "#btn2faStep2", function () {
        $('#modalActiveGoogleStep2').modal('hide');
        $('#modalActiveGoogleStep3').modal('show');
    });

    $('#modalActiveGoogleStep3').on('shown.bs.modal', function () {
        $('#focus1').focus();
    })

    // 2FA Auth Step 3 Enable

    $('#formToggle2fa').submit(function (e) {
        e.preventDefault();
        $('.form__error.removeErrSecret').addClass("hidden");
        let Url = $(this).attr('action');
        let formData = $(this).serializeArray();
        $.ajax({
            method: "POST",
            headers: {
                Accept: "application/json"
            },
            url: Url,
            data: formData,
            success: (res) => {
                if (res && res.success) {
                    $("#formToggle2fa :input").val("");
                    let modalTarget = "#modalActiveGoogleStep";
                    if (res.status) {
                        $('#enable2fa').addClass('active2fa');
                        modalTarget = `${modalTarget}3`;
                    } else {
                        $('#enable2fa').removeClass('active2fa');
                        modalTarget = `${modalTarget}1`;
                    }
                    $('#enable2fa').attr("data-target", modalTarget);
                    toastr.success(res.message);
                    $('#modalActiveGoogleStep3').modal('hide');
                }
            },
            error: (res) => {
                if (res.status === 422) {
                    let errors = res.responseJSON.errors;
                    Object.keys(errors).forEach(function (key) {
                        $("#" + key + "MessError").removeClass("hidden");
                        $("#" + key + "Error").text(errors[key][0]);
                    });
                } else {
                    // window.location.reload();
                }
            }
        })
    });
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#blah').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function nextFocus(input, idNext, idPrev = null) {
    var length = input.value.length;
    var maxLength = input.getAttribute("maxlength");
    if (length == maxLength) {
        document.getElementById(idNext).focus();
    }
    var key = event.keyCode || event.charCode;
    if (idPrev && (key == 8 || key == 46)) {
        document.getElementById(idPrev).value = "";
        document.getElementById(idPrev).focus();
    }
}

//setCode2fa
function setCode2fa(classInput) {
    var secret = "";
    var classInput = classInput + ' .item__input';
    $(classInput).each(function () {
        secret += $(this).val();
    });
    $('#secret2fa').val(secret);
}
