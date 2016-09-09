@section('title_web','Bantuan')
@extends('master_app')
@section('content') 
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row m-b-lg m-t-lg">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-info-circle"></i> Bagaimana cara klasterisasi?
                </div>
                <div class="panel-body">
                    <p>1. Klik menu <b>Unduh tweet</b> atau klik <a href="{{ URL('tweet/get') }}">disini</a> kemudian set tanggal tweet yang ingin di unduh lalu klik tombol unduh</p>
                    <p>2. Selanjutnya Klik menu <b>Preprocessing</b> atau klik <a href="{{ URL('processing') }}">klik disini</a> kemudian set tanggal untuk mem-preprocessing lalu klik submit.</p>
                    <p>2. Selanjutnya Klik menu <b>Klasterisasi</b> atau klik <a href="{{ URL('clustering') }}">klik disini</a> masukan nilai minimum support atau minsupp kemudian klik tombol submit.</p>
                </div>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <i class="fa fa-info-circle"></i> Bagaimana cara menambah/edit/hapus stopword?
                </div>
                <div class="panel-body">
                    <p>1. Menambah stopword, anda harus masuk ke menu <b> Stopword</b> atau klik <a href="{{ URL('tweet/get') }}">disini</a> kemudian masukan stopword di form stopword setelah itu klik simpan</p>
                    <p>2. mengedit stopword, anda harus pilih terlebih dahulu stopword yang ingin dihapus lalu klik icon edit, kemudian masukan stopword yang ingin diubah stelah itu klik ubah</p>
                    <p>3. Menghapus stopword, anda harus pilih terlebih dahulu stopword yang ingin di hapus kemudian klik icon hapus</p>
                </div>
            </div>
        </div>
    </div>
</div>
@stop