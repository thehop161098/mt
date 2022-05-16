@extends('frontend.history.index')
@section('history')
<div id="refund">
    <div class="blockRefundHistory">
        <div class="blockRefundHistory__table table-responsive">
            <table class="table" id="promotionHistory">
                <thead class="table__header">
                    <tr class="header__listItem">
                        <th class="header__item">Amount</th>
                        <th class="header__item">Note</th>
                        <th class="header__item">Created Date</th>
                    </tr>
                </thead>
                <tbody class="table__content">
                    @if(isset($refunds) && ! $refunds->isEmpty())
                        @foreach ($refunds as $refund)
                            @php
                                // Profit
                                if(!empty($refund->amount)) {
                                    $amount = '$'. number_format($refund->amount, 2);
                                    $amount = str_replace('$-', '-$',$amount);
                                } else {
                                    $amount = '--';
                                }
                            @endphp
                            <tr class="content__listItem">
                                <td class="content__item">{{$amount}}</td>
                                <td class="content__item">{{$refund->note}}</td>
                                <td class="content__item">
                                    {{date('Y-m-d H:i', strtotime($refund->created_at))}}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="content__listItem">
                            <td colspan="3" class="content__item text-center">No results found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @if(isset($refunds) && ! $refunds->isEmpty())
        <div class="paginate text-center blockPaginate">{{$refunds->links()}}</div>
    @endif
</div>
@endsection
