$(document).ready(function () {
    // WARNING WEB
    if (!sessionStorage.getItem("closeWarning")) {
        setTimeout(function () {
            $('.warningWeb').css("visibility", "visible")
            $('.warningWeb').css("opacity", "1")
        }, 1000);
    }
    $('.closeWarning').click(function () {
        sessionStorage.setItem("closeWarning", true);
        $('.warningWeb').css("visibility", "hidden")
        $('.warningWeb').css("opacity", "0")
    });
    $('.imgCheckShowContent').click(function () {
        if ($(this).prop("checked") == true) {
            $('.warningWeb__blockContent').toggleClass('active').slideToggle(400);
        }
        if ($(this).prop("checked") == false) {
            $('.warningWeb__blockContent').removeClass('active').slideToggle(400);
        }
    });


    // SHOW HIDE RESULT
    $('.toogleShowHideResult').click(function () {
        if ($(this).prop("checked") == true) {
            $('.chartTrading__listResult').css("display", "flex");
        }
        if ($(this).prop("checked") == false) {
            $('.chartTrading__listResult').css("display", "none")
        }
    });
    // SHOW HIDE OPEN CLOSE
    const btnShowOC = document.getElementsByClassName("toogleShowHideOpenClose");
    const listOC = document.getElementsByClassName("item__boxResult");
    $(btnShowOC).click(function () {
        if ($(this).prop("checked") == true) {
            $(listOC).css("display", "flex");
        }
        if ($(this).prop("checked") == false) {
            $(listOC).css("display", "none")
        }
    });

    // SHOW HIDE OPEN PROFIT
    const btnShowPF = document.getElementsByClassName("toogleShowHideProfit");
    const listPF = document.getElementsByClassName("blockSelectBet__infoTrans");
    $(btnShowPF).click(function () {
        if ($(this).prop("checked") == true) {
            $(listPF).css("display", "block");
        }
        if ($(this).prop("checked") == false) {
            $(listPF).css("display", "none")
        }
    });

    // SHOW HIDE CHAT MOBILE
    const boxToogleChat = document.getElementsByClassName("boxChat");
    const btnShowChat = document.getElementsByClassName("toogleShowHideChat");
    const pageChat = document.getElementsByClassName("pageTrading__chat");
    const boxChat = document.getElementsByClassName("chat__boxChat");
    $(btnShowChat).click(function () {
        if ($(this).prop("checked") == true) {
            $(pageChat).addClass('chatActive');
            $(boxChat).addClass('boxChatActive');
            $(boxToogleChat).addClass('toogleChatActive');
        }
        if ($(this).prop("checked") == false) {
            $(pageChat).removeClass('chatActive');
            $(boxChat).removeClass('boxChatActive');
            $(boxToogleChat).removeClass('toogleChatActive');
        }
    });

    // SHOW HIDE SEARCH PHONE
    const btnArea = document.getElementsByClassName("codeArea__check");
    const boxSearchArea = document.getElementsByClassName("codeArea__searchList");
    $(btnArea).click(function () {
        if ($(this).prop("checked") == true) {
            $(boxSearchArea).css("display", "block");
        }
        if ($(this).prop("checked") == false) {
            $(boxSearchArea).css("display", "none");
        }
    });

    // HIDDEN LINK CHART
    var linkChart = document.querySelector('[aria-labelledby="id-61-title"]');
    $(linkChart).css("display", "none");
    // STICKY 
    $(window).on('scroll', function () {
        stickyHeader();
    });
    //  CLICK ANYWHERE UNCHECKED DROPDOWN
    $(document).on('click', function (e) {
        if (e.target.type != "checkbox") {
            $('.header__tool , .header__filter, .valueConvert__blockSend, .valueConvert__blockReceive, .blockSl__selectAccount').find('input[type="checkbox"]').each((index, elm) => {
                $(elm).prop('checked', false)
            });
            $('.header__menuMobi').find('input[type="checkbox"]');
            {
                $('.menuMobi__select').prop('checked', false);
                $(".overlayMenu").css("display", "none");
                $(".header__menuMobi").css("width", "40px");
                $("body").css("overflow", "unset");
                $(".pageTrading__boxShowChat ").css("z-index", "12");
            }
            if (e.target.id !== 'searchPhone') {
                $('.codeArea__check').prop('checked', false);
            }
        }
    });
    // MENU MOBI
    $(".menuMobi__select").click(function () {
        $(".overlayMenu").css("display", "block");
        $(".header__menuMobi").css("width", "100%");
        $("body").css("overflow", "hidden");
        $(".pageTrading__boxShowChat ").css("z-index", "11");
    });
    //  FUNCTION ONLY ONE CHECKED DROPDOWN IN WEB
    $('.cbDropdown').click(function () {
        $('.cbDropdown').not(this).each(function (index, elm) {
            $(elm).prop('checked', false)
        });
    });

    $('.content__textTitle').click(function () {
        $(this).toggleClass('active');
        $(this).next('.accordion-ask').slideToggle(400);
    });

    $('.codeArea .list__item').click(function () {
        $('#phone_country').val($(this).data('phone-country'));
        $('.codeArea .codeArea__select').html($(this).data('phone-country'));
        $('.codeArea__check').prop('checked', false);
    });

    $('#searchPhone').keyup(function () {
        const search = $(this).val().replace(/([.?*+^$[\]\\(){}|-])/g, "");
        $(".codeArea__list .list__item").each(function () {
            if ($(this).text().search(new RegExp(search, "i")) < 0) {
                $(this).hide();
            } else {
                $(this).show()
            }
        });
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.tool__showChild').click(function () {
        $('.listItem__itemChild').toggleClass('active').slideToggle(600);
    });


    // TREE LIST
    const listSearch = document.querySelector("#listSearch");
    listSearch && listSearch.addEventListener("keyup", filter);

    $('.filter__link').click(function () {
        $(this).siblings('.filter__tree').slideToggle(250);
        $(this).siblings('.filter__tree').children('.filter__tree-item').children('.filter__tree').hide();
        $('.filter__link').removeClass('active');
        $(this).addClass('active');
    });

    // SLIDE ADS
    if (!sessionStorage.getItem("closeAds")) {
        setTimeout(function () {
            $('.slideAds').css("visibility", "visible");
            $('.slideAds').css("opacity", "1");
        }, 1000);
    }
    $('.imgCloseSlide').click(function () {
        sessionStorage.setItem("closeAds", true);
        $('.slideAds').css("visibility", "hidden");
        $('.slideAds').css("opacity", "0");
    });

    if ($('.swiper-container').length) {
        var mySwiper = new Swiper('.swiper-container', {
            direction: 'horizontal',
            loop: true,
            pagination: {
                el: '.swiper-pagination',
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    }
    // CLOSE LUCKY SPIN
    $('.btnCloseLuckyWheel').click(function () {
        $('.luckyWheelBlock').css("visibility", "hidden");
        $('.luckyWheelBlock').css("opacity", "0");
        $('.luckyWheelBlock').css("display", "none");
    });
    $('.resultContent__ok').click(function () {
        $('.luckyWheelBlock').css("visibility", "hidden");
        $('.luckyWheelBlock').css("opacity", "0");
        $('.luckyWheelBlock').css("display", "none");
    });

    $('.tool__luckyWheel').click(function () {
        $('.luckyWheelBlock').css("visibility", "visible");
        $('.luckyWheelBlock').css("opacity", "1");
        $('.luckyWheelBlock').css("display", "flex");
    });
});

function stickyHeader() {
    var header = document.getElementById("hdMB");
    var content = document.getElementsByClassName("infoTabNavigation__content");
    if (header && content) {
        var sticky = header.offsetTop;
        if (window.pageYOffset > sticky) {
            $(header).addClass('sticky');
            $(content).addClass('contentSticky');
        } else {
            $(header).removeClass('sticky');
            $(content).removeClass('contentSticky');
        }
    }
}

function readAllNotifications(url) {
    console.log('url', url);
    $.ajax({
        url,
        success: (res) => {
            if (res && res.success) {
                $('.tool__noti .noti__listNoti').remove();
                $('.tool__noti .count__number').html(0);
            }
        }
    })
}

// FILTER TREE LIST
function filter() {
    var term = document.querySelector("#listSearch").value.toLowerCase();
    var tag = document.querySelectorAll(".filter__link");
    for (i = 0; i < tag.length; i++) {
        if (tag[i].innerHTML.toLowerCase().indexOf(term) !== -1) {
            $(tag[i]).show();
            $(tag[i]).parent().parent().show();
        } else {
            $(tag[i]).hide();
        }
    }
}
