<table class="table" id="mainWalletHistory">
    <thead class="table__header">
    <tr class="header__listItem">
        <th class="header__item">Wallet</th>
        <th class="header__item">Time</th>
        <th class="header__item">Currency</th>
        <th class="header__item">Amount Bit/USDT</th>
        <th class="header__item">Fee</th>
        <th class="header__item">Type</th>
        <th class="header__item">To</th>
        <th class="header__item">Status</th>
        <th class="header__item">TxHash</th>
    </tr>
    </thead>
    <tbody class="table__content">
    @if(isset($transactions) && !$transactions->isEmpty())
        @foreach($transactions as $val)
            <tr class="content__listItem">
                <td class="content__item">{{$val->wallet_text}}</td>
                <td class="content__item">{{ date('Y/m/d H:i', strtotime($val->created_at)) }}</td>
                <td class="content__item">{{$val->coin}}</td>
                <td class="content__item">{{ number_format($val->amount, 2) . ' BIT ≈ ' . number_format($val->amount_convert, 4) . ' BNB' }}</td>
                <td class="content__item">{{ number_format($val->amount_fee, 2) }}</td>
                <td class="content__item">Withdraw</td>
                <td class="content__item">{{$val->code}}</td>
                <td class="content__item">{{$val->status_text}}</td>
                <td class="content__item">{{$val->tx_hash}}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="9"><p class="noResult">NO RESULTS FOUND</p></td>
        </tr>
    @endif


    </tbody>
</table>
@if(isset($transactions))
    <div class="row">
        <div class="col-lg-12">
            <div class="paginate text-center blockPaginate">
                {!! $transactions->links() !!}
            </div>
        </div>
    </div>
@endif

