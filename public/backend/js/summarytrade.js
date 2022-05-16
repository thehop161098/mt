$(document).ready(function () {
    $('#todaySummaryTrade').datepicker({
        todayBtn: 'linked',
        format: 'dd-mm-yyyy',
        todayHighlight: true,
        autoclose: true
    });

    // get current day
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();
    today = dd + '-' + mm + '-' + yyyy;
    // set current day
    if ($('#todaySummaryTrade').val() == '') {
        $('#todaySummaryTrade').val(today);
    }
});