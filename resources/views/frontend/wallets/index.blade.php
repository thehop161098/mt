@extends('frontend.layouts.app')

@section('css')
    <link href="{{ asset('frontend/css/wallet.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div id="wallet">
        <div class="pageWallet">
            <div class="title">
                @yield('title')
                <div class="title__tool">
                    <ul class="tool__selectWallet">
                        <a href="{{route('wallets.index')}}">
                            <li class="selectWallet__item {{ request()->is('wallets') ? 'active' : '' }}">
                                <img class="item__img" src="{{ asset('frontend/images/icons/icMainWallet.png') }}"/>
                                <p class="item__text">Main</p>
                            </li>
                        </a>
                        <a href="{{route('wallets.exchange')}}">
                            <li class="selectWallet__item {{ request()->is('wallets/exchange') ? 'active' : '' }}">
                                <img class="item__img" src="{{ asset('frontend/images/icons/icExchangeWallet.png') }}"/>
                                <p class="item__text">Exchange</p>
                            </li>
                        </a>
                        <a href="{{route('wallets.deposit')}}">
                            <li class="selectWallet__item {{ request()->is('wallets/deposit') ? 'active' : '' }}">
                                <img class="item__img" src="{{ asset('frontend/images/icons/icHistoryDemo.png') }}"/>
                                <p class="item__text">Deposit</p>
                            </li>
                        </a>
                    </ul>
                </div>
            </div>
            <div class="content tab-content">
                @yield('wallet')
            </div>
        </div>
    </div>

    <script type="application/javascript">
        const urlQrCode = "<?= config('api.apiChartGG.chart') ?>";
        const feeWithdraw = "<?= $fee_withdraw ?>";
        const amountWithdrawMin = "<?= isset($amountWithdrawMin) && !empty($amountWithdrawMin) ? $amountWithdrawMin : 0  ?>";

        function toFixed(num = 0, fixed = 8) {
            const parseValue = num || 0;
            return Number(parseValue).toFixed(fixed);
        }

        function getDataGasPrice() {
            let result = {fast: 0, safeLow: 0};
            return new Promise((resolve, reject) => {
                $.ajax({
                    type: "GET",
                    url: "<?= route('wallets.getGasPrice') ?>",
                    dataType: 'json',
                    success: function (res) {
                        if (Number(res.success) === 1) {
                            resolve(res.data)
                        } else {
                            resolve(result)
                        }
                    },
                    error: function (e) {
                        resolve(result)
                    }
                })
            })
        }

        function onShowModalDeposit(symbol, code) {
            $("#md-dps-currency").val(symbol || "N/A");
            $("#md-dps-qr").attr("src", `${urlQrCode}${code}`);
            $("#md-dps-address").val(code || "N/A")

            $("#modalDeposit").modal("show");
        }

        function onShowModalWithdraw(symbol, balance, code) {
            const newSymbol = symbol || "N/A";
            // const gasPrice = await getDataGasPrice();
            $("#md-wr-currency").val(newSymbol);
            $("#md-wr-code").val(code);
            $(".md-wr-unit").text('BIT');
            $("#md-wr-balance").text(toFixed(balance));
            $("#md-wr-amount-min").text(toFixed(amountWithdrawMin));
            // $("#md-wr-fee").text(toFixed(gasPrice?.fee?.safeLow));
            $("#modalWithdraw").modal("show");
        }

        function myFunction() {
            var copyText = document.getElementById("myInput");
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            alert("Text copied successfully");
        }

        function onCopied() {
            var copyText = document.getElementById("md-dps-address");
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            toastr.success("Copied successfully");
        }

        function fetchDataInternal(page) {
            $.ajax({
                url: "<?= route('wallets.ajaxGetDataInternal') ?>?page=" + page,
                success: function (data) {
                    $('.blockInternalTransHitory__table').html(data);
                }
            });
        }

        function fetchDataWithdraw(page) {
            $.ajax({
                url: "<?= route('wallets.ajaxGetDataWithdraw') ?>?page=" + page,
                success: function (data) {
                    $('.blockTransHistory__table').html(data);
                }
            });
        }

        $(document).ready(function () {
            $(document).on('keyup', '#md-wr-amount', function (e) {
                const value = $("#md-wr-amount").val();
                const calValue = parseFloat(value || 0) * parseFloat(feeWithdraw) / 100;
                const remaining = parseFloat(value) - calValue;
                $("#md-wr-fee").text(toFixed(calValue, 2));
                $("#md-wr-remaining").text(toFixed(remaining, 2));
            });

            $(document).on('click', '#md-wr-submit', function (e) {
                const coin = $("#md-wr-currency").val();
                const code = $("#md-wr-code").val();
                const address = $("#md-wr-address").val();
                const amount = $("#md-wr-amount").val();
                const wallet = $("#type-wallet").val();
                const network = $("#network").val();
                $('#md-wr-submit').addClass('blockClick');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "<?= route('wallets.withdraw') ?>",
                    data: {coin, address, amount, code, wallet, network},
                    dataType: 'json',
                    success: function (res) {
                        if (Number(res.success) === 1) {
                            $("#md-wr-currency").val("");
                            $("#md-wr-code").val("");
                            $("#md-wr-address").val("");
                            $("#md-wr-amount").val(0);
                            toastr.success(res.msg);
                            $("#modalWithdraw").modal('hide');
                        } else {
                            toastr.error(res.msg || "");
                        }
                        $('#md-wr-submit').removeClass('blockClick');
                    },
                    error: function (e) {
                        console.log(e, "error e")
                    }
                })
            });

            $(document).on('click', '.md-it-submit', function (e) {
                const code = $(".md-it-code").val();
                const amount = $(".md-it-amount").val();
                $('.md-it-submit').addClass('blockClick');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "<?= route('wallets.ajaxInternalTransfer') ?>",
                    data: {amount, code},
                    dataType: 'json',
                    success: function (res) {
                        if (Number(res.success) === 1) {
                            $(".md-it-code").val("");
                            $(".md-it-amount").val(0);
                            fetchDataInternal(1);
                            toastr.success(res.msg);
                            $("#modalInternalTransfer").modal('hide');
                        } else {
                            toastr.error(res.msg || "");
                        }
                        $('.md-it-submit').removeClass('blockClick');
                    },
                    error: function (e) {
                        console.log(e, "error e")
                    }
                })
            });

            $(document).on('click', '.blockTransHistory__table .pagination a', function (event) {
                event.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                fetchDataWithdraw(page);
            });

            $(document).on('click', '.blockInternalTransHitory__table .pagination a', function (event) {
                event.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                fetchDataInternal(page);
            });

            $(document).on('click', '#btnRefill', function (e) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "<?= route('wallets.refill') ?>",
                    dataType: 'json',
                    success: function (res) {
                        if (res && res.success) {
                            $("#amountRefill").text(res.amount);
                            toastr.success(res.message);
                        } else {
                            toastr.error(res.message);
                        }
                    },
                    error: function (e) {
                        console.log(e, "error e")
                    }
                })
            });

            $(document).on('click', '.selectAccount__list .item-type-wallet', function() {
                $('.selected-account-text').html($(this).html());
                $('.boxInput__inputMoney').val($(this).data('amount'));
                $('#type-wallet').val($(this).data('wallet'));
            });

            $(document).on('click', '.selectAccount__list .item-network', function() {
                $('.selected-network-text').html($(this).html());
                $('#network').val($(this).data('network'));
            });
        });
    </script>
@endsection
