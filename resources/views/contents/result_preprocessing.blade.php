@section('title_web','Hasil preprocessing')
@extends('master_app')
@section('content') 
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Hasil preprocessing</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ URL('/') }}">Beranda</a>
            </li>
            <li>
                <a>Hasil preprocessing</a>
            </li>
            <li class="active">
                <strong>Daftar</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">
    </div>
</div>
  <div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
      <div class="col-lg-12">  
         {!! Session::get('message') !!}
      </div>
  </div>
    <div class="row">
        <div class="col-lg-6" style="margin-bottom:10px;">
            <div class="ibox-content">
                <div class="tab-content">
                    <h4>Term</h4>
                    <p>Jumlah term : {{ count(json_decode(json_encode($terms),true)) }}</p>
                    <div style="height: 300px; overflow: scroll;">
                    <table class="table small m-b-xs">
                        <tbody>
                        <?php $i = 0;?>
                        @foreach($terms as $term => $skor)
                        <tr>
                            <td>{{ $term }}</td>
                            <td>{{ $skor }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>                            
            </div>
        </div>
        <div class="col-lg-6" style="margin-bottom:10px;">
            <div class="ibox-content">
                <div class="tab-content">
                    <h4>Tweet preprocessing </h4>
                    <p>Jumlah tweet : {{ count($clear_tweets) }} </p>
                    <div style="height: 300px; overflow: scroll;">
                    <table class="table small m-b-xs">
                        <tbody>
                        @foreach($clear_tweets as $tweet)
                        <tr>
                            <td>{{ $tweet->clear_tweet }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>                            
            </div>
        </div>

        <div class="col-lg-12">
            <div class="widget style1 lazur-bg">
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <h3 class="font-bold"><i class="fa fa-clock-o"></i> Daftar pengelompokan waktu</h3>
                    </div>
                </div>
            </div>
        </div>
        @foreach($time_aggregations as $time => $tweets)
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        
                        <h4>Slot time : <small>{{ $time }}</small></h4>
                        <div style="height: 300px; overflow: scroll;">
                            <table class="table table-stripped small m-t-md">
                            <tbody >
                            @foreach($tweets as $value_tweet_agregation)
                            <tr>
                                <td class="no-borders">
                                    <i class="fa fa-circle text-navy"></i>
                                </td>
                                <!-- <td class="no-borders">
                                    {{ $value_tweet_agregation['tweet'] }}
                                </td> -->
                                <td class="no-borders">
                                    {{ $value_tweet_agregation['clear_tweet'] }}
                                </td>
                                <!-- <td class="no-borders">
                                    {{ date('d M Y H:i:s',strtotime($value_tweet_agregation['date_tweet'])) }}
                                </td> -->
                            </tr>
                            @endforeach
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
  </div>
@stop