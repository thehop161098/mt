<?php

return [
    'apiCoingecko' => [
        'coinsMarkets' => env('URL_COINGECKO') . "/api/v3/coins/markets",
    ],
    'apiEtherscan' => [
        'getPriceUSD' => 'https://api.coingecko.com/api/v3/simple/price?ids=binancecoin&vs_currencies=usd',
        'account' => env('URL_ETHERSCAN') . "/api?module=account&action=tokenbalance&tag=latest&apikey=" . env('TOKEN_ETH'),
        'gasTracker' => env('URL_ETHERSCAN') . "/api?module=gastracker&action=gasoracle&apikey=" . env('TOKEN_ETH'),
        'balanceMulti' => env('URL_ETHERSCAN') . "/api?module=account&action=balancemulti&tag=latest&apikey=" . env('TOKEN_ETH') . '&address=',
    ],
    'apiChartGG' => [
        'chart' => env('URL_CHART_GOOGLEAPIS') . "/chart?chs=300&300&chld=L|2&cht=qr&chl=ethereum:"
    ]
];
