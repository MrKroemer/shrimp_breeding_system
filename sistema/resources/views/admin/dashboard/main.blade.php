@extends('adminlte::page')
@php
    use Carbon\Carbon;
@endphp
@section('title', 'Dashboard')

@section('content_header')
<h1>Dashboard</h1>
<ol class="breadcrumb">
    <li class="active"><i class="fa fa-dashboard"></i> Dashboard</li>
</ol>
@endsection

@section('content')

<!-- Boxes dos setores -->
<div class="row">
    @foreach ($setores as $setor)
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-blue" style="background-color: #{{ $setor->cor }} !important;">
                <div class="inner">
                    <h3>{{ $setor->tanques->count() }}</h3>
                    <p>{{ $setor->nome }}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="{{ route('admin.dashboard.tanques', ['setor_id' => $setor->id]) }}" class="small-box-footer">Mais informações <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    @endforeach
</div>
<!-- /.Boxes dos setores -->

<!-- Listagem dos últimos cultivos iniciados -->
<div class="box box-success">
    <div class="box-header ui-sortable-handle" style="cursor: move;">
        <i class="ion ion-clipboard"></i>
        <h3 class="box-title">Últimos cultivos iniciados</h3>
    </div>
    <div class="box-body">
        <ul class="todo-list ui-sortable">
            @foreach ($ciclos as $ciclo)
            <li>
                <span class="handle ui-sortable-handle">
                    <i class="fa fa-ellipsis-v"></i>
                    <i class="fa fa-ellipsis-v"></i>
                </span>
                <a href="{{ route('admin.dashboard.informacoes', ['setor_id' => $ciclo->tanque->setor_id, 'tanque_id' => $ciclo->tanque->id]) }}">
                    <span class="label label-success" style="font-size: 3mm;"><i class="fa fa-clock-o"></i> {{ Carbon::parse($ciclo->data_inicio)->diffInDays(date('Y-m-d')) }} dias atrás</span>
                </a>
                <span class="text">Tanque {{ $ciclo->tanque->sigla }} ciclo Nº {{ $ciclo->numero }} em {{ $ciclo->data_inicio() }}</span>
            </li>
            @endforeach
        </ul>
    </div>
</div>

<!-- Listagem das últimas biometrias -->
<div class="box box-primary">
    <div class="box-header ui-sortable-handle" style="cursor: move;">
        <i class="ion ion-clipboard"></i>
        <h3 class="box-title">Últimas biometrias realizadas</h3>
    </div>
    <div class="box-body">
        <ul class="todo-list ui-sortable">
            @foreach ($biometrias as $biometria)
                <li>
                    <span class="handle ui-sortable-handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                    </span>
                    <a href="{{ route('admin.dashboard.informacoes', ['setor_id' => $biometria->ciclo->tanque->setor_id, 'tanque_id' => $biometria->ciclo->tanque->id]) }}">
                        <span class="label label-primary" style="font-size: 3mm;"><i class="fa fa-clock-o"></i> {{ Carbon::parse($biometria->data_analise)->diffInDays(date('Y-m-d')) }} dias atrás</span>
                    </a>
                    <span class="text">Tanque {{ $biometria->ciclo->tanque->sigla }} ciclo Nº {{ $biometria->ciclo->numero }} em {{ $biometria->data_analise() }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
<!-- /.Listagem das últimas despescas -->

<!-- Listagem das últimas despescas -->
<div class="box box-warning">
    <div class="box-header ui-sortable-handle" style="cursor: move;">
        <i class="ion ion-clipboard"></i>
        <h3 class="box-title">Últimas depescas realizadas</h3>
    </div>
    <div class="box-body">
        <ul class="todo-list ui-sortable">
            @foreach ($despescas as $despesca)
                <li>
                    <span class="handle ui-sortable-handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                    </span>
                    <a href="{{ route('admin.dashboard.informacoes', ['setor_id' => $despesca->ciclo->tanque->setor_id, 'tanque_id' => $despesca->ciclo->tanque->id]) }}">
                        <span class="label label-warning" style="font-size: 3mm;"><i class="fa fa-clock-o"></i> {{ Carbon::parse($despesca->data_fim)->diffInDays(date('Y-m-d')) }} dias atrás</span>
                    </a>
                    <span class="text">Tanque {{ $despesca->ciclo->tanque->sigla }} ciclo Nº {{ $despesca->ciclo->numero }} em {{ $despesca->data_fim() }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
<!-- /.Listagem das últimas despescas -->

@endsection