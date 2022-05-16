@if(!empty($wheels) && Auth::user()->is_spin && config('settings.publish_wheel') === '1')
    <div class="luckyWheelBlock">
        <div class="luckyWheelWeb">
            <div class="luckywheel">
                <div class="luckywheel__spin">
                    <button class="spinBtn" onClick="startSpin()">Spin</button>
                </div>
                <div class="luckywheel__select">
                    <img class="select__img" src="{{ asset('frontend/images/icons/spinArrow.svg') }}"/>
                </div>
                <div class="luckywheel__bgSmall"></div>
                <div class="luckywheel__bgBig"></div>
                <canvas id="canvas"></canvas>
            </div>
            <div class="luckywheel__form">
                <div class="form__content">
                    <div class="content__title">
                        <p class="title__big">Lucky Spin Everyday</p>
                        <p class="title__small">Have a try now</p>
                    </div>
                    <div class="content__img">
                        <img class="img__item" src="{{ asset('frontend/images/icons/diamond.png') }}"/>
                    </div>
                </div>
                <div class="form__history">
                    <div class="history__title">
                        <p class="title__text">Top High Prize</p>
                    </div>
                    <div class="history__list">
                        @forelse ($historyWheels as $history)
                            <div class="list__item">
                                <p class="item__name">{{$history['user']['full_name']}}</p>
                                <div class="item__coin">
                                    <img class="coin__img"
                                         src="{{ asset('frontend/images/icons/icCoinSpin.png') }}"/>
                                    <p class="coin__number">{{number_format($history['amount'])}}</p>
                                </div>
                            </div>
                        @empty
                            <div class="list__noItem">
                                <img class="noItem__img" src="{{ asset('frontend/images/icons/noItemSpin.png') }}">
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="btnCloseLuckyWheel">
            <img class="imgCloseLuckyWheel" src="{{ asset('frontend/images/icons/icCloseSlide.svg') }}"/>
        </div>
        <div class="luckyWheelResult hidden">
            <div class="luckyWheelResult__boxResult">
                <div class="box__resultImg">
                    <img class="resultImg__img" src="{{ asset('frontend/images/icons/getCoinSpin.gif') }}"/>
                </div>
                <div class="box__resultContent">
                    <p class="resultContent__text">Prize 10 MIN</p>
                    <p class="resultContent__ok">OK</p>
                </div>
            </div>
        </div>
    </div>

    <script type="application/javascript">
        var theWheel;
        var durationComplete = 3;
        $(document).ready(function () {
            const arrColors = ['#FBCE44', '#D32D7C', '#1AC485', '#7649F6'];
            let wheels = <?= json_encode($wheels) ?>;
            let keyColor = 0;
            wheels = wheels.map(wheel => {
                if (keyColor > arrColors.length - 1) {
                    keyColor = 0;
                }
                const newWheel = {...wheel, fillStyle: arrColors[keyColor]};
                keyColor++;
                return newWheel;
            });
        
            var canvas = document.getElementById('canvas');
            canvas.width = 340;
            canvas.height = 340;
            // Custom Wheel
            theWheel = new Winwheel({
                'numSegments': wheels.length,
                'outerRadius': 165,
                'pointerAngle': 90,
                'textFontSize': 15,
                'textFontFamily': 'Exo, sans-serif',
                'textAlignment': 'outer',
                'textMargin': 30,
                'textFontWeight': '700',
                'strokeStyle': 'rgba(255,255,255,0.1)',
                'lineWidth': 0.25,
                'textFillStyle': '#FFF',
                'segments': wheels,
                'animation': {
                    'type': 'spinToStop',
                    'duration': durationComplete,
                    'spins': 5,
                    'callbackFinished': notifySpin
                }
            });
        });
    </script>
@endif
