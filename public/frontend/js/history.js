// $("#demoHistory").DataTable({});
// $("#liveHistory").DataTable({});
// $("#promotionHistory").DataTable({});

$(document).ready(function () {
    $('.input-daterange').datepicker({
        todayBtn: 'linked',
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        autoclose: true
    });
    
    // get current day
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();
    today = yyyy + '-' + mm + '-' + dd;

    // set current day
    if ($('#from_date').val() == '' && $('#to_date').val() == '') {
        $('#from_date').val(today);
        $('#to_date').val(today);
    }
    
    // refresh
    $('#refresh').click(function () {
        window.location = window.location.pathname;
    });
})
