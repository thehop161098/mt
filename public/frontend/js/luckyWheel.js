// Function Start
let wheelSpinning = false;
let spinDirect = document.getElementsByClassName('select__img');
let spinButton = document.getElementsByClassName('spinBtn');
let wheelButton = document.getElementsByClassName('luckywheel__spin');

let xhr = new XMLHttpRequest();
xhr.onreadystatechange = validSpin;

function validSpin() {
    if (xhr.readyState < 4) {
        return;
    }

    if (xhr.status !== 200) {
        return;
    }

    // The request has completed.
    if (xhr.readyState === 4) {
        let spinWheel = JSON.parse(xhr.responseText);
        if (spinWheel && spinWheel.status === 200) {
            let segment = theWheel.getRandomForSegment(spinWheel.spin);
            for (var i = 0; i < spinDirect.length; i++) {
                spinDirect[0].classList.add("arrowSpinRunning");
            }
            for (var i = 0; i < spinButton.length; i++) {
                spinButton[0].classList.add("spinBtnOff");
            }
            for (var i = 0; i < wheelButton.length; i++) {
                wheelButton[0].classList.add("luckywheel__spinOff");
            }
            theWheel.animation.stopAngle = segment;
            theWheel.startAnimation(true);
            wheelSpinning = true;
            setTimeout(function () {
                for (var i = 0; i < spinDirect.length; i++) {
                    spinDirect[0].classList.remove("arrowSpinRunning");
                }
            }, durationComplete * 1000 - 600);
        } else {
            spinWheel && toastr.warning(spinWheel.message);
        }
    }
}

function startSpin() {
    if (wheelSpinning == false) {
        xhr.open('GET', "/ajax/luckyWheel/validSpin", true);
        xhr.send('');
    }
}

// Function Alert
function notifySpin(indicatedSegment) {
    $('.luckyWheelResult').removeClass('hidden');
    $('.luckyWheelResult .resultContent__text').html("You received " + indicatedSegment.text);
    setTimeout(() => {
        $('.luckyWheelResult').addClass('hidden');
    }, 2000);
}
