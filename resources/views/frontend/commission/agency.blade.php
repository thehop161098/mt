@extends('frontend.commission.index')
@section('title_page')
    <p class="title__text">Affiliate Marketing Commission</p>
@endsection
@section('commission')
<div id="demo">
    <div class="blockDemoHistory">
        <div class="blockDemoHistory__table table-responsive">
            <table class="table" id="demoHistory">
                <thead class="table__header">
                    <tr class="header__listItem">
                        <th class="header__item">Amount</th>
                        <th class="header__item">Note</th>
                        <th class="header__item">Created Date</th>
                    </tr>
                </thead>
                <tbody class="table__content">
                    @if(isset($commissions) && ! $commissions->isEmpty())
                        @foreach ($commissions as $commission)
                            @php
                                // Amount
                                if(!empty($commission->amount)) {
                                    $amount = '$'.number_format($commission->amount, 2);
                                    $amount = str_replace('$-', '-$',$amount);
                                } else {
                                    $amount = '--';
                                }
                            @endphp
                            <tr class="content__listItem">
                            <td class="content__item">{{$amount}}</td>
                            <td class="content__item">{{$commission->note}}</td>
                            <td class="content__item">{{date('Y-m-d : H:i:s', strtotime($commission->created_at))}}</td>
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
    @if(isset($commissions) && ! $commissions->isEmpty())
        <div class="paginate text-center blockPaginate">{{$commissions->appends(Request()->all())->links()}}</div>
    @endif
</div>

@endsection
