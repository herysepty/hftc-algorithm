@section('title_web','Hasil Klasterisasi')
@extends('master_app')
@section('content') 
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Hasil Klasterisasi</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ URL('/') }}">Beranda</a>
            </li>
            <li>
                <a>Hasil Klasterisasi</a>
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
      <div class="col-lg-5 col-lg-offset-3">  
         {!! Session::get('message') !!}
      </div>
  </div>
    <div class="row">
        <!-- <div class="col-lg-5 col-lg-offset-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>Term</h5>
                    <div style="height: 300px; overflow: scroll;">
                    <table class="table table-stripped small m-t-md">
                        <tbody>
                        <?php $i = 0; ?>
                        @foreach($terms as $key_term => $value_term)
                        <tr>
                            <td class="no-borders">
                                {{  ++$i }}
                            </td>
                            <td class="no-borders">
                                {{ $key_term }}
                            </td>
                            <td class="no-borders">
                                {{ $value_term }}
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div> -->
        <div class="col-lg-12">
            <div class="widget style1 lazur-bg">
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <h3 class="font-bold"><i class="fa fa-tasks"></i> Daftar Klaster terakhir</h3>
                    </div>
                </div>
            </div>
        </div>
        @foreach($tweets as $key_last_cluster => $value_last_cluster)
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <h5>Klaster terakhir : {{ $key_last_cluster }}</h5>
                    <h5>Jumlah tweet : {{ count($value_last_cluster) }}</h5>
                    <div style="height: 500px; overflow: scroll;">
                        <table class="table table-stripped small m-t-md">
                        <tbody >
                        @foreach($value_last_cluster as $key_tweet => $value_tweet)
                        <tr>
                            <td class="no-borders">
                                <i class="fa fa-circle text-navy"></i>
                            </td>
                            <!-- <td class="no-borders">
                                {{ $value_tweet['tweet'] }}
                            </td> -->
                            <td class="no-borders">
                                {{ $value_tweet['clear_tweet'] }}
                            </td>
                            <!-- <td class="no-borders">
                                {{ $value_tweet['date_tweet'] }}
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
       <!--  -->
        <div class="row">
            <div class="col-lg-12">
            <div class="widget style1 lazur-bg">
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <h3 class="font-bold"><i class="fa fa-tasks"></i> Trending Topik</h3>
                    </div>
                </div>
            </div>
            </div>
        </div>
      <div class="row" style="margin-bottom:50px;">
            @foreach($rank as $r)
                @foreach($r as $slot_time => $rank_terms)
                <div class="col-lg-3" style="margin-bottom:10px;">
                    <div class="ibox-content">
                        <div class="tab-content">
                            <h4>Jam : {{ $slot_time }}</h4>
                            <table class="table small m-b-xs">
                                <tbody>
                                @foreach($rank_terms as $term => $skor)
                                <tr>
                                    <td>{{ '#'.$term }}</td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>                            
                    </div>
                </div>
                @endforeach
            @endforeach
        </div>
        <!--  -->
        <div class="row">
        @foreach($trend as $trend_slot_time => $trend_tweets)
            <div class="col-lg-12">
                <h3>Jam {{ $trend_slot_time }}</h3>
            </div>
            @foreach($trend_tweets as $key_last_cluster => $value_last_cluster)
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <h5 style="color:blue;">
                            <?php  
                                $trend_term = explode(' ',$key_last_cluster);
                                if(in_array('banjir',$trend_term))
                                    echo '#banjir';
                                else if(in_array('beliung',$trend_term))
                                    echo '#puting';
                                else if(in_array('gempa',$trend_term))
                                    echo '#gempa';
                                else if(in_array('gunung',$trend_term))
                                    echo '#gunung';
                                else if(in_array('meletus',$trend_term))
                                    echo '#meletus';
                                else if(in_array('tsunami',$trend_term))
                                    echo '#tsunami';
                                 else if(in_array('longsor',$trend_term))
                                    echo '#longsor';
                                else if(in_array('pasang',$trend_term))
                                    echo '#pasang';
                                else if(in_array('gelombang',$trend_term))
                                    echo '#gelombang';
                                else
                                    echo '#'.$trend_term[0];
                            ?>
                        </h5>
                        <div style="height: 500px; overflow: scroll;">
                            <table class="table table-stripped small m-t-md">
                            <tbody >
                            @foreach($value_last_cluster as $key_tweet => $value_tweet)
                            <tr>
                                <td class="no-borders">
                                    <i class="fa fa-circle text-navy"></i>
                                </td>
                                <td class="no-borders">
                                    {{ $value_tweet['clear_tweet'] }}
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endforeach
        </div>
  </div>
@stop