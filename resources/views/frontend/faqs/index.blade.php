@extends('frontend.layouts.app')

@section('content')
<div id="policy">
    <div class="pagePolicy">
        <div class="title">
            <p class="title__text">Policy</p>
        </div>
        <div class="content">
            <div class="accordion">
                @foreach ($faqs as $faq)
                    <div class="accordion-ques">
                        <a class="content__textTitle ques">{{$faq->name}}</a>
                        <div class="accordion-ask">
                            {!!$faq->content!!}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection