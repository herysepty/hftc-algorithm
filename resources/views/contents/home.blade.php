@section('title_web','Beranda')
@extends('master_app')
@section('content') 
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row m-b-lg m-t-lg" style="margin-top:-30px;">
        <h1 class="text-center">APLIKASI KLASTERISASI BENCANA ALAM</h1>
        <div class="col-md-8 col-md-offset-2" style="margin-bottom:20px; margin-top:30px;">
            <img alt="image" class="img-responsive" src="{{ URL('assets/img/bnpb_banner.png') }}">
        </div>
        <div class="col-md-8 col-md-offset-2">
            <img alt="image" class="img-responsive" src="{{ URL('assets/img/bnpb_images.jpg') }}">
        </div>
        <!-- <div class="col-md-8 col-md-offset-2">
            <div class="carousel slide" id="carousel1">
                <div class="carousel-inner">
                    <div class="item active">
                        <img alt="image" class="img-responsive" src="{{ URL('assets/img/bnpb_banner.png') }}">
                    </div>
                    <div class="item">
                        <img alt="image" class="img-responsive" src="{{ URL('assets/img/slider2.jpg') }}">
                    </div>
                </div>
                <a data-slide="prev" href="#carousel1" class="left carousel-control">
                    <span class="icon-prev"></span>
                </a>
                <a data-slide="next" href="#carousel1" class="right carousel-control">
                    <span class="icon-next"></span>
                </a>
            </div>
        </div> -->
    </div>
</div>
@stop