@section('title_web','Daftar Stopword')
@extends('master_app')
@section('content') 
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Daftar Stopword</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ URL('/') }}">Beranda</a>
            </li>
            <li><a>stopword</a></li>
            <li class="active">
                <strong>Daftar Stopword</strong>
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
    <div class="col-lg-7">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>
                <!-- <a class="btn btn-primary btn-xs" href="{{ URL('stopword/add') }}"><i class="fa fa-plus"></i> Tambah Stopword</a> -->
                Daftar Stopwords
                </h5>
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
                <!-- <form action="{{ action('StopwordController@search') }}" method="GET" class="form-horizontal">
                    <div class="input-group {{ $errors->has('q') ? ' has-error' : '' }}">
                        <input type="text" placeholder="Masukan kata kunci" class=" form-control" name="q">
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-white"> Cari</button>
                        </span>
                    </div>
                     <span class="text-danger">{{ $errors->first('q')}}</span>
                </form> -->
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Stopwords</th>
                        <th style="width:50px;">Ubah</th> 
                        <th style="width:50px;">Hapus</th>
                    </tr>
                    </thead>
                    <?php  $i = ($stopwords->currentPage()-1) * 10; ?>
                    @foreach($stopwords as $stopword)
                    <tbody>
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $stopword->stopword }}</td>
                        <td class="text-center">
                            <a href="{{ URL('stopword/edit/'.$stopword->id) }}" class="badge bg-red"><span class="fa fa-edit"></span></a>
                        </td>
                        <td class="text-center">
                            <a href="{{ URL('stopword/delete/'.$stopword->id) }}" class="badge bg-red"><span class="fa fa-trash"></span></a>
                        </td>
                    </tr>
                    @endforeach
                    @if(count($stopwords) == 0)
                    <tr>
                        <td colspan="3" class="text-center">
                            Stopword tidak ada.
                        </td>
                    </tr>
                    @endif
                    </tbody>
                </table>
                @if(isset($q))
                    {!! $stopwords->appends(['q' => $q])->render() !!}
                @else
                    {!! $stopwords->render() !!}
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-5">
      <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>{{ isset($stopword_edit) ? 'Ubah Stopword' : 'Tambah Stopword' }}</h5>
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
                <form class="form-horizontal" role="form" method="post" action="{{ isset($stopword_edit) ? action('StopwordController@update') : action('StopwordController@store') }}" novalidate="novalidate">
                {!! csrf_field() !!}
                    <input type="hidden" name="id" value="{{ isset($stopword_edit) ? $stopword_edit->id : '' }}">
                    <div class="form-group{{ $errors->has('stopword') ? ' has-error' : '' }}">
                        <label class="col-lg-1 control-label" style="margin-bottom: 10px;">Stopword: </label>
                        <div class="col-lg-12 {{ isset($stopword_edit) ? 'has-warning' : '' }}">
                            <input type="text" placeholder="Masukan Stopword" class="form-control" name="stopword" value="{{ isset($stopword_edit) ? $stopword_edit->stopword : old('stopword') }}">
                            <span class="text-danger">{{ $errors->first('stopword')}}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-4 col-lg-8">
                            <button class="btn btn-sm btn-primary btn-rounded" type="submit">{{ isset($stopword_edit) ? 'Ubah' : 'Simpan' }}</button>
                            @if(isset($stopword_edit))
                                <a class="btn btn-sm btn-white btn-rounded" href="{{ URL('stopword/view') }}">Batal</a>
                            @else
                                <button class="btn btn-sm btn-white btn-rounded" type="reset">Batal</button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
  </div>
</div>
@stop