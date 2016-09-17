<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="@yield('title_web') | Tugas Akhir 2016 | Data Mining | Algoritma HFTC - Herarchy Text Clustering">
        <meta name="base_url" content="{{ URL('/') }}"/>
        <meta name="token" content="{{ base64_encode(md5(URL('/'))) }}"/>
        <title>@yield('title_web') - TA 2016</title>
        <!-- 
        Author      : Hery Septyadi
        Email       : heryspty@gmail.com
        Copyright   : 2016
        -->
        <link rel="icon" href="{{ URL('assets/img/favicon.png')}}">
        {!! HTML::style('assets/css/bootstrap.min.css') !!}
        {!! HTML::style('assets/font-awesome/css/font-awesome.css') !!}
        <!-- Toastr style -->
        {!! HTML::style('assets/css/plugins/toastr/toastr.min.css') !!}
        <!-- Gritter -->
        {!! HTML::style('assets/js/plugins/gritter/jquery.gritter.css') !!}
        {!! HTML::style('assets/css/animate.css') !!}
        {!! HTML::style('assets/css/style.css') !!}
        {!! HTML::style('assets/css/style_hs.css') !!}
        <!--  -->
        {!! HTML::style('assets/css/plugins/codemirror/codemirror.css') !!}
        {!! HTML::style('assets/css/plugins/codemirror/ambiance.css') !!}
        <!--  -->
        {!! HTML::style('assets/css/plugins/datapicker/datepicker3.css') !!}
        {!! HTML::style('assets/css/plugins/clockpicker/clockpicker.css') !!}
        <link href="{{ URL('assets/css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
        @yield('script_css')
    </head>
    <body class="fixed-sidebar skin-3">
        <div id="wrapper">
            @include('layouts.admin_sidebar')
            <div id="page-wrapper" class="gray-bg dashbard-1">
                @include('layouts.admin_navbar')
                @yield('content')
                @include('layouts.admin_footer')
            </div>
        </div>
        {!! HTML::script('assets/js/jquery-2.1.1.js') !!}
        <!-- Mainly scripts -->
        {!! HTML::script('assets/js/bootstrap.min.js') !!}
        {!! HTML::script('assets/js/plugins/metisMenu/jquery.metisMenu.js') !!}
        {!! HTML::script('assets/js/plugins/slimscroll/jquery.slimscroll.min.js') !!}
        <!-- CodeMirror -->
        {!! HTML::script('assets/js/plugins/codemirror/codemirror.js') !!}
        {!! HTML::script('assets/js/plugins/codemirror/mode/javascript/javascript.js') !!}
        <!-- Custom and plugin javascript -->
        {!! HTML::script('assets/js/inspinia.js') !!}
        {!! HTML::script('assets/js/plugins/pace/pace.min.js') !!}
        <!-- jQuery UI -->
        {!! HTML::script('assets/js/plugins/jquery-ui/jquery-ui.min.js') !!}
        <!-- GITTER -->
        {!! HTML::script('assets/js/plugins/gritter/jquery.gritter.min.js') !!}
        <!-- Toastr -->
        {!! HTML::script('assets/js/plugins/toastr/toastr.min.js') !!}
        {!! HTML::script('assets/js/custom.js') !!}
        <!--  -->
        {!! HTML::script('assets/js/plugins/datapicker/bootstrap-datepicker.js') !!}
        {!! HTML::script('assets/js/plugins/clockpicker/clockpicker.js') !!}
        @yield('script_js')
        @yield('script_js_chats')
        <script type="text/javascript">
            $(document).ready(function(){
                // $("#btn_minsupp").click(function() {
                //     var minsupp = $("input[name='minsupp']").val();
                //     if(minsupp == "")
                //     {
                //         $("#isi_chat").focus();
                //     }
                //     else
                //     {
                        
                //         $("#minsupp").prop("readonly",true);
                //         $(".loading_minsupp").show();
                //     }
                // });
                $('.clockpicker').clockpicker();
            });
        </script>
        {!! $AppController::scure('PHNjcmlwdD4NCiAgICAgICAgKGZ1bmN0aW9uKGkscyxvLGcscixhLG0pe2lbJ0dvb2dsZUFuYWx5dGljc09iamVjdCddPXI7aVtyXT1pW3JdfHxmdW5jdGlvbigpew0KICAgICAgICAoaVtyXS5xPWlbcl0ucXx8W10pLnB1c2goYXJndW1lbnRzKX0saVtyXS5sPTEqbmV3IERhdGUoKTthPXMuY3JlYXRlRWxlbWVudChvKSwNCiAgICAgICAgICBtPXMuZ2V0RWxlbWVudHNCeVRhZ05hbWUobylbMF07YS5hc3luYz0xO2Euc3JjPWc7bS5wYXJlbnROb2RlLmluc2VydEJlZm9yZShhLG0pDQogICAgICAgICAgfSkod2luZG93LGRvY3VtZW50LCdzY3JpcHQnLCdodHRwczovL3d3dy5nb29nbGUtYW5hbHl0aWNzLmNvbS9hbmFseXRpY3MuanMnLCdnYScpOw0KICAgICAgICAgIGdhKCdjcmVhdGUnLCAnVUEtNzg0NDYxODEtMScsICdhdXRvJyk7DQogICAgICAgICAgZ2EoJ3NlbmQnLCAncGFnZXZpZXcnKTsNCiAgICAgICAgPC9zY3JpcHQ+') !!}
    </body>
</html>
