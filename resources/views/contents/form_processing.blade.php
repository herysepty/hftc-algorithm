@section('title_web','Prosessing')
@extends('master_app')
@section('content') 
<?php date_default_timezone_set("Asia/Jakarta"); ?>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Klasterisasi</h2>
        <ol class="breadcrumb">
            <li><a href="{{ URL('/') }}">Beranda</a></li>
            <li><a>Prosessing</a></li>
            <li><strong>Form</strong></li>
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
                    <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    <a class="close-link"><i class="fa fa-times"></i></a>
                </div>
            </div>
            <div class="ibox-content">
                <form class="form-horizontal" role="form" method="post" action="{{ action('TweetController@processingTweets') }}" novalidate="novalidate">
                {!! csrf_field() !!}
                    <div class="form-group" id="data_keywords"><label class="col-lg-2 control-label">Tanggal</label>
                        <div class="col-lg-10">
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" class="form-control" name="date_tweet" value="{{ old('date_tweet') ? old('date_tweet') : date('m/d/Y') }}">
                        </div>
                        <span class="text-danger">{{ $errors->first('date_tweet')}}</span>
                        </div>
                    </div>
                    <div class="form-group{{ $errors->has('total_tweet') ? ' has-error' : '' }}">
                        <label class="col-lg-2 control-label">Jam</label>
                        <div class="col-lg-4">
                            <div class="input-group clockpicker" data-autoclose="true">
                                <span class="input-group-addon">
                                    <span class="fa fa-clock-o"></span>
                                </span><input type="text" class="form-control" value="{{ old('start_time_tweet') ? old('start_time_tweet') : '00:00' }}" name="start_time_tweet">
                            </div>
                            <span class="text-danger">{{ $errors->first('start_time_tweet')}}</span>
                        </div>
                        <p class="col-lg-2 text-center" style="margin-top:10px;">s/d</p>
                        <div class="col-lg-4">
                            <div class="input-group clockpicker" data-autoclose="true">
                                <span class="input-group-addon">
                                    <span class="fa fa-clock-o"></span>
                                </span><input type="text" class="form-control" value="{{ old('end_time_tweet') ? old('end_time_tweet') : '00:30' }}" name="end_time_tweet">
                            </div>
                            <span class="text-danger">{{ $errors->first('end_time_tweet')}}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <button class="btn btn-sm btn-primary btn-rounded" type="submit">Submit</button>
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