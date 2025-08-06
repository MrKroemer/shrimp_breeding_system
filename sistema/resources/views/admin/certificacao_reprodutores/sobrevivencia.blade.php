@extends('adminlte::page')

@section('title', 'Registro de analises biométricas')

@section('content_header')

<h1>{{ $situacao ? 'Edição' : 'Visualização' }} de analises biométricas</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Analises biométricas</a></li>
    <li><a href="">{{ $situacao ? 'Edição' : 'Visualização' }}</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
    @if ($situacao)
        <form action="{{ route('admin.analises_biometricas.sobrevivencia.to_update', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="ciclo_id">Ciclo:</label>
                <select disabled name="ciclo_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($ciclos as $ciclo)
                        <option value="{{ $ciclo->id }}" {{ ($ciclo->id == $ciclo_id) ? 'selected' : '' }}>{{ $ciclo->tanque->sigla }} ( Ciclo Nº {{ $ciclo->numero }} )</option>
                    @endforeach
                </select>
            </div>
    @else
        <form enctype="multipart/form-data">
            <div class="form-group">
                <label for="ciclo_id">Ciclo:</label>
                <select disabled name="ciclo_id" class="form-control" {{ $situacao ? '' : 'disabled' }}>
                    <option value selected>{{ $tanque_sigla }} ( Ciclo Nº {{ $ciclo_id }} )</option>
                </select>
            </div>
    @endif
            <div class="form-group">
                <label for="data_analise">Data da análise:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input disabled type="text" name="data_analise" placeholder="Data da análise" class="form-control pull-right" id="date_picker" value="{{ $data_analise }}">
                </div>
            </div>
            <div class="form-group">
                <label for="sobrevivencia">Percentual de sobrevivência:</label>
                <input type="number" step="any" name="sobrevivencia" placeholder="Percentual de sobrevivência" class="form-control" value="{{ $sobrevivencia }}">
            </div>
            <div class="form-group">
                @if ($situacao)
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                @endif
                <a href="{{ route('admin.analises_biometricas') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection