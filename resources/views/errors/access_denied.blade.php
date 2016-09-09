<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Access Denied</title>
        {!! HTML::style('assets/css/bootstrap.min.css') !!}
        {!! HTML::style('assets/font-awesome/css/font-awesome.css') !!}
        {!! HTML::style('assets/css/animate.css') !!}
        {!! HTML::style('assets/css/style.css') !!}
    </head>
    <body class="gray-bg">
        <div class="middle-box text-center animated fadeInDown">
            <h1>Maaf..!</h1>
            <h3 class="font-bold">Akses anda di tolak.</h3>
            <div class="error-desc">
                Maaf Akses ke halaman yang anda tuju ditolak, anda tidak di perbolehkan masuk kehalaman yang dituju.
                <a href="{{ URL('/') }}" class="btn btn-warning btn-rounded m-t">Kembali ke beranda</a>
            </div>
        </div>
        <!-- Mainly scripts -->
        {!! HTML::script('assets/js/jquery-2.1.1.js') !!}
        {!! HTML::script('assets/js/bootstrap.min.js') !!}
    </body>
</html>
