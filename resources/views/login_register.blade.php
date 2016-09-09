<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title_web') - TA 2016</title>
        <link rel="icon" href="{{ URL('assets/img/favicon.png')}}">
        {!! HTML::style('assets/css/bootstrap.min.css') !!}
        {!! HTML::style('assets/font-awesome/css/font-awesome.css') !!}
        {!! HTML::style('assets/css/animate.css') !!}
        {!! HTML::style('assets/css/style.css') !!}
        {!! HTML::style('assets/css/style_hs.css') !!}
        {!! HTML::style('assets/css/plugins/iCheck/custom.css') !!}

    </head>
    <!-- <body class="gray-bg"> -->
    <body style="background-color: #ffa500;">
        @yield('content')
        
        {!! HTML::script('assets/js/jquery-2.1.1.js') !!}
        {!! HTML::script('assets/js/bootstrap.min.js') !!}
        {!! HTML::script('assets/js/plugins/iCheck/icheck.min.js') !!}
        <script>
        $(document).ready(function(){
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });
        });
    </script>
    </body>
</html>
