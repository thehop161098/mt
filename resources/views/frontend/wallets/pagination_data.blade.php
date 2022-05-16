<table class="table" id="exchangeWalletHistory">
    <thead class="table__header">
    <tr class="header__listItem">
        <th class="header__item">Time</th>
        <th class="header__item">Amount</th>
        <th class="header__item">Note</th>
        <th class="header__item">Status</th>
    </tr>
    </thead>
    <tbody class="table__content">
    @if(!empty($internalWithdraws) && $internalWithdraws->count())
        @foreach($internalWithdraws as $val)
            <tr class="content__listItem">
                <td class="content__item">{{ date('Y/m/d H:i', strtotime($val->created_at)) }}</td>
                <td class="content__item">{{ number_format($val->amount, 2) }}</td>
                <td class="content__item">{{ $val->note }}</td>
                <td class="content__item">Success</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="4"><p class="noResult">NO RESULTS FOUND</p></td>
        </tr>
    @endif
    </tbody>
</table>

<div class="row">
    <div class="col-lg-12">
        <div class="paginate text-center blockPaginate">
            {!! $internalWithdraws->links() !!}
        </div>
    </div>
</div>

