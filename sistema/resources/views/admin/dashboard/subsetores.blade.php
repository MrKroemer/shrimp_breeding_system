@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Dashboard <small>Painel de controle</small></h1>
<ol class="breadcrumb">
    <li><i class="fa fa-dashboard"></i> Dashboard</li>
    <li class="active"><a href="{{ route('admin.dashboard') }}">Setores</a></li>
    <li class="active">Subsetores</li>
</ol>
@endsection

@section('content')

<!-- Small boxes (Stat box) -->
<div class="row">
    @foreach ($subsetores as $subsetor)
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-blue" style="background-color: #{{ $subsetor->setor->cor }} !important;">
                <div class="inner">
                    <h3>{{ $subsetor->tanques->count() }}</h3>
                    <p>{{ $subsetor->nome }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="{{ route('admin.dashboard.tanques', ['setor_id' => $setor_id, 'subsetor_id' => $subsetor->id]) }}" class="small-box-footer">Mais informações <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    @endforeach
</div>
<!-- /.row -->

@endsection
