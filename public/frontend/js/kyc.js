$(function () {

    $("#portrait, #identity_card_font_side, #identity_card_back_side").change(function () {
        readURL(this);
    });

});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        var id = $(input).attr('id') + 'Img';
        reader.onload = function (e) {
            $('#' + id).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

