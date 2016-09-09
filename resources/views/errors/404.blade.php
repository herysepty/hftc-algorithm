<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Halaman tidak ditemukan</title>
        <link rel="icon" href="{{ URL('assets/img/favicon.png')}}">
        {!! HTML::style('assets/css/bootstrap.min.css') !!}
        {!! HTML::style('assets/font-awesome/css/font-awesome.css') !!}
        {!! HTML::style('assets/css/animate.css') !!}
        {!! HTML::style('assets/css/style.css') !!}
    </head>
    <body class="gray-bg">
        <div class="middle-box text-center animated fadeInDown">
            <h1>404</h1>
            <h3 class="font-bold">HALAMAN TIDAK DITEMUKAN.</h3>
            <div class="error-desc">
            <p>Maaf alamat yang kamu tuju tidak ditemukan, periksa kembali alamat yang dituju</p>
                <a href="{{ URL('/') }}" class="btn btn-warning btn-rounded m-t">Kembali ke Beranda</a>
            </div>
        </div>
        {!! HTML::script('assets/js/jquery-2.1.1.js') !!}
        {!! HTML::script('assets/js/bootstrap.min.js') !!}
    </body>
</html>
