@section('title_web','Klasterisasi')
@extends('master_app')
@section('content') 
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Klasterisasi</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ URL('/') }}">Beranda</a>
            </li>
            <li>
                <a>Klasterisasi</a>
            </li>
            <li>
                <strong>Minimum support</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">

    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-5 col-lg-offset-4">
    {!! Session::get('message') !!}
      <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5></h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="close-link">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
                <form class="form-horizontal" role="form" method="post" action="{{ action('TweetController@clustering') }}" novalidate="novalidate">
                {!! csrf_field() !!}
                    <div class="form-group{{ $errors->has('minsupp') ? ' has-error' : '' }}">
                        <label class="col-lg-2 control-label">Minsupp</label>
                        <div class="col-lg-10">
                            <input type="text" placeholder="Masukan jumlah minsupp" class="form-control" name="minsupp" value="{{ old('minsupp') }}" id="minsupp">
                            <span class="text-danger">{{ $errors->first('minsupp')}}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button id='btn_minsupp' class="btn btn-sm btn-primary btn-rounded" type="submit">Submit</button> 
                            <div class="sk-spinner sk-spinner-wave loading_minsupp" style="margin-left: 70px; margin-top: -34px; display: none;">
                                <div class="sk-rect1"></div>
                                <div class="sk-rect2"></div>
                                <div class="sk-rect3"></div>
                                <div class="sk-rect4"></div>
                                <div class="sk-rect5"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
  </div>
</div>
@stop
@section('script_js')
   <script type="text/javascript">
       $('#data_keywords .input-group.date').datepicker({
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true
            });
   </script>
@endsection