@extends('frontend.commission.index')
@section('title_page')
    <p class="title__text">Auto Bot Commission</p>
@endsection
@section('commission')
    <div id="demo">
        <div class="blockDemoHistory">
            <div class="blockDemoHistory__table table-responsive">
                <table class="table" id="demoHistory">
                    <thead class="table__header">
                    <tr class="header__listItem">
                        <th class="header__item">Note</th>
                        <th class="header__item">Created Date</th>
                    </tr>
                    </thead>
                    <tbody class="table__content">
                    @if(isset($commissions) && ! $commissions->isEmpty())
                        @foreach ($commissions as $commission)
                            <tr class="content__listItem">
                                <td class="content__item">{!! str_replace('$'.$commission->amount, '<span style="color: #02C076">$ '.number_format($commission->amount, 2).'</span>', $commission->note) !!}</td>
                                <td class="content__item">{{date('Y-m-d : H:i:s', strtotime($commission->created_at))}}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="content__listItem">
                            <td colspan="2" class="content__item text-center">No results found</td>
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
