@extends('frontend.layouts.app')

@section('css')
    <link href="{{asset('frontend/css/autoBots.css')}}" rel="stylesheet">
    <link href="{{ asset('frontend/css/history.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div id="autobots">
        <div class="pageAutoBots">
            <div class="title">
                <p class="title__text">Auto Bots</p>
            </div>
            @if(!$bots->isEmpty())
                <div class="content">
                    <div class="content__listPackages">
                        @foreach($bots as $bot)
                            <div
                                class="listPackages__item <?= $currentBot && $currentBot->bot_id === $bot->id ? 'active' : '' ?>">
                                <div class="item__boxImg">
                                    <div class="boxImg__img"
                                         style="background-image: url({{$bot->image_url}})"></div>
                                </div>
                                <div class="item__info">
                                    <p class="info__name">{{$bot->name}}</p>
                                    <div class="info__price">
                                        <sup class="price__unit">$</sup>
                                        <p class="price__number">{{number_format($bot->price)}}</p>
                                    </div>
                                    <div class="info__dayReceive">
                                        @if ($bot->commission_7)
                                            <div
                                                class="dayReceive__item <?= $currentBot && $currentBot->bot_id === $bot->id && $currentBot->time === 7 ? 'active' : '' ?>"
                                                <?= $currentBot ? 'disabled' : '' ?>
                                                data-timeSelected="7"
                                                data-botId="{{$bot->id}}"
                                            >
                                                7 days
                                            </div>
                                        @endif
                                        @if ($bot->commission_21)
                                            <div
                                                class="dayReceive__item <?= $currentBot && $currentBot->bot_id === $bot->id && $currentBot->time === 21 ? 'active' : '' ?>"
                                                <?= $currentBot ? 'disabled' : '' ?>
                                                data-timeSelected="21"
                                                data-botId="{{$bot->id}}"
                                            >
                                                21 days
                                            </div>
                                        @endif
                                        @if ($bot->commission_30)
                                            <div
                                                class="dayReceive__item <?= $currentBot && $currentBot->bot_id === $bot->id && $currentBot->time === 30 ? 'active' : '' ?>"
                                                <?= $currentBot ? 'disabled' : '' ?>
                                                data-timeSelected="30"
                                                data-botId="{{$bot->id}}"
                                            >
                                                30 days
                                            </div>
                                        @endif
                                        @if ($bot->commission_90)
                                            <div
                                                class="dayReceive__item <?= $currentBot && $currentBot->bot_id === $bot->id && $currentBot->time === 90 ? 'active' : '' ?>"
                                                data-timeSelected="90"
                                                data-botId="{{$bot->id}}"
                                            <?= $currentBot ? 'disabled' : '' ?>
                                            >
                                                90 days
                                            </div>
                                        @endif
                                    </div>
                                    <div class="info__profit tab-content">
                                        <div class="profit__item">
                                            @if ($bot->risk)
                                                <p class="listProfit__text">Risk ≈
                                                    <span>{{$bot->risk * 100}}% / 90 days</span></p>
                                            @endif
                                            @if ($bot->commission_7)
                                                <p class="listProfit__text">7 days =>
                                                    <span>{{$bot->commission_7 * 100}}%</span> commission</p>
                                            @endif
                                            @if ($bot->commission_21)
                                                <p class="listProfit__text">21 days =>
                                                    <span>{{$bot->commission_21 * 100}}%</span> commission</p>
                                            @endif
                                            @if ($bot->commission_30)
                                                <p class="listProfit__text">30 days =>
                                                    <span>{{$bot->commission_30 * 100}}%</span> commission</p>
                                            @endif
                                            @if ($bot->commission_90)
                                                <p class="listProfit__text">90 days =>
                                                    <span>{{$bot->commission_90 * 100}}%</span> commission</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="info__buy">
                                        @php
                                            $unlock = 0;
                                            $disabled = '';
                                            $classActive = '';
                                            if ($currentBot) $disabled = 'disabled';
                                            if ($currentBot && $currentBot->bot_id === $bot->id && $currentBot->time_expired . ' 00:10' < Carbon\Carbon::now()->format('Y-m-d H:i')) {
                                                $disabled = '';
                                                $classActive = 'active';
                                                $unlock = 1;
                                            }
                                        @endphp
                                        <button
                                            class="buy__btn buy-{{$bot->id}} {{$classActive}} {{$currentBot && $currentBot->bot_id === $bot->id ? 'bought' : ''}}"
                                            <?= $disabled ?>
                                            data-unlock="{{$unlock}}"
                                            data-id="{{$currentBot ? $currentBot->id : ''}}"
                                            data-botId="{{$bot->id}}"
                                        >{{!$currentBot || $currentBot->bot_id !== $bot->id ? 'Lock' : 'Unlock'}}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="modal fade" id="modalBuyAutoBots" tabindex="-1" role="dialog">
        <div class="modal-dialog modalBuyAutoBots" role="document">
            <div class="modalBuyAutoBots__boxContent">
                <div class="boxContent__title">
                    <p class="title__text">Buy AutoBots</p>
                    <button class="title__btnClose" data-dismiss="modal">
                        <img class="btnClose__img" src="{{asset('frontend/images/icons/icClose.png')}}"/>
                    </button>
                </div>
                <div class="boxContent__form">
                    <div class="form__note">
                        <p class="note__text">Do you want buy this bot ?</p>
                    </div>
                    <form action="" method="POST" id="form-bot">
                        @csrf
                        <div class="form__btn">
                            <button id="btnRefuse" data-dismiss="modal" class="btn__text">Cancel</button>
                            <button id="btnConfirm" class="btn__text" type="submit">Ok</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $('.buy__btn').click(function () {
                if ($(this).attr('data-unlock') == '1') {
                    const id = $(this).attr('data-id');
                    if (id) {
                        $('.note__text').html('Do you want to unlock this bot ?');
                        $('#modalBuyAutoBots').modal('show');
                        $('#form-bot').attr('action', '/auto-bots/unLock' + '/' + id);
                    } else {
                        toastr.warning('Not found bot');
                    }
                } else {
                    if (!$(this).attr('data-timeSelected')) {
                        toastr.warning('Please select time');
                        return;
                    }
                    const botId = $(this).attr('data-botId');
                    const timeSelected = $(this).attr('data-timeSelected');
                    if (botId && timeSelected) {
                        $('.note__text').html('Do you want buy this bot ?');
                        $('#modalBuyAutoBots').modal('show');
                        $('#form-bot').attr('action', '/auto-bots/buy' + '/' + botId + '/' + timeSelected);
                    } else {
                        toastr.warning('Not found bot');
                    }
                }
            });

            $(".dayReceive__item").click(function () {
                if (!$(this).attr('disabled')) {
                    const botId = $(this).attr('data-botId');
                    $(".dayReceive__item").removeClass("active");
                    $(this).addClass("active");
                    $('.buy__btn').removeAttr('data-timeSelected');
                    $('.buy-' + botId).attr('data-timeSelected', $(this).attr('data-timeSelected'));
                    $('.buy__btn').removeClass('active');
                    $('.buy-' + botId).addClass('active');
                }
            });

            @if ($currentBot)
            // Set the date we're counting down to
            var countDownDate = new Date('{{$currentBot->time_expired.' 00:10:00'}}'.replace(/-/g, "/")).getTime();

            // Update the count down every 1 second
            var x = setInterval(function () {

                // Get today's date and time
                var now = new Date().getTime();

                // Find the distance between now and the count down date
                var distance = countDownDate - now;

                // Time calculations for days, hours, minutes and seconds
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Output the result in an element with id="demo"
                $('.bought').html("Unlock: <span>" + days + "d " + hours + "h "
                    + minutes + "m " + seconds + "s</span>");

                // If the count down is over, write some text
                if (distance < 0) {
                    clearInterval(x);
                    $('.bought').html("Unlock");
                }
            }, 1000);
            @endif
        });
    </script>
    @if(session('error_verified'))
        <script type="text/javascript">
            $(document).ready(function () {
                toastr.error('{{session('error_verified')}}');
            })
        </script>
    @endif
    @if(session('success_verified'))
        <script type="text/javascript">
            $(document).ready(function () {
                toastr.success('{{session('success_verified')}}');
            })
        </script>
    @endif
@endsection
