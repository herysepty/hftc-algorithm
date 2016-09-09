@section('title_web','Daftar tweet')
@extends('master_app')
@section('content') 
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Daftar Tweet  <small>( {{ $count }} )</small></h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ URL('/') }}">Beranda</a>
            </li>
            <li>
                <a>Tweet</a>
            </li>
            <li class="active">
                <strong>Daftar Tweet</strong>
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
                <div class="row">
                  <div class="col-md-12 table-responsive">
                      <table class="table table-striped">
                        <thead>
                        <tr>
                          <th style="width: 10px">#</th>
                          <th>Username</th>
                          <th>Tweet</th>
                          <th style="width: 150px">Tanggal Tweet</th>
                          <th style="width: 180px">Tanggal Unduh tweet</th>
                        </tr>
                        </thead>
                        <?php  $i = ($tweets->currentPage()-1) * 100; ?>
                        @foreach($tweets as $tweet)
                        <tbody>
                        <tr>
                          <td>
                            <?php
                              echo ++$i;
                            ?>
                          </td>
                          <td>{{$tweet->username}}</td>
                          <td>{{ $tweet->tweet }}</td>
                          <td>
                            <?php
                                $date = $tweet->date_tweet;
                                 // echo date('d M Y H:i:s',strtotime(str_replace('+0000', '+0700', $date)));
                                echo date('d M Y H:i',strtotime($date));
                            ?> 
                          </td>
                           <td><!-- <span class="badge bg-red">55%</span> -->{{ date('d M Y',strtotime($tweet->date_get_tweet)) }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <span class="pull-right">{!! $tweets->render() !!}</span>
                  </div>
                </div>             
                </div>
            </div>
        </div>
      </div>
  </div>
@stop