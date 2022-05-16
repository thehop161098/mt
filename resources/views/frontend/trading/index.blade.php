@extends('frontend.layouts.app')

@section('css')
    <link href="{{ asset('frontend/css/trade.css') }}" rel="stylesheet">
@endsection

@section('content')
    <trading/>
@endsection

@section('js')
    <script src="{{ asset('js/app.js') }}"></script>
@endsection
