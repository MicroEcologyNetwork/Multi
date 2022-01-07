<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="renderer" content="webkit">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ Multi::title() }} @if($header) | {{ $header }}@endif</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    @if(!is_null($favicon = Multi::favicon()))
    <link rel="shortcut icon" href="{{$favicon}}">
    @endif

    {!! Multi::css() !!}

    <script src="{{ Multi::jQuery() }}"></script>
    {!! Multi::headerJs() !!}
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body class="hold-transition {{config('multi.skin')}} {{join(' ', config('multi.layout'))}}">

@if($alert = config('multi.top_alert'))
    <div style="text-align: center;padding: 5px;font-size: 12px;background-color: #ffffd5;color: #ff0000;">
        {!! $alert !!}
    </div>
@endif

<div class="wrapper">

    @include('multi::partials.header')

    @include('multi::partials.sidebar')

    <div class="content-wrapper" id="pjax-container">
        {!! Multi::style() !!}
        <div id="app">
        @yield('content')
        </div>
        {!! Multi::script() !!}
        {!! Multi::html() !!}
    </div>

    @include('multi::partials.footer')

</div>

<button id="totop" title="Go to top" style="display: none;"><i class="fa fa-chevron-up"></i></button>

<script>
    function LA() {}
    LA.token = "{{ csrf_token() }}";
    LA.user = @json($_user_);
</script>

<!-- REQUIRED JS SCRIPTS -->
{!! Multi::js() !!}

</body>
</html>
