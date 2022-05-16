<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, target-densityDpi=device-dpi">
    <meta name="description" content=" {{ isset(config('settings')['meta_description']) ? config('settings')['meta_description'] : '' }}" />
    <link rel="shortcut icon" href="{{asset('images/settings/'.config('settings')['favicon'])}}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ByBit') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Exo:wght@400;500;600;700;900&display=swap" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('frontend/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/reset.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/style.css') }}" rel="stylesheet">
    @yield('css')
    <link href="{{ asset('frontend/css/responsive.css') }}" rel="stylesheet">
    
    <!-- Jquery -->
    <script src="{{ asset('frontend/js/jquery.min.js') }}"></script>
    @toastr_css

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-0KCN6HKJKC"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-0KCN6HKJKC');
    </script>
</head>

<body>

    <div id="app">
        <div id="root">
            @yield('content')
        </div>
    </div>

    <script src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('frontend/js/script.js') }}"></script>

    @toastr_js
    @toastr_render

    @yield('js')

</body>

</html>
