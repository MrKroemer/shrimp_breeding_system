@extends('adminlte::page')

@section('title', 'Registro de preparações de cultivos')

@section('content_header')
<h1>{{ $cicloSituacao ? 'Edição' : 'Visualização' }} de preparações de cultivos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Preparações de cultivos</a></li>
    <li><a href="">{{ $cicloSituacao ? 'Edição' : 'Visualização' }}</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
    @if ($cicloSituacao)
        <form action="{{ route('admin.preparacoes_v2.to_update', ['id' => $id]) }}" method="POST">
            {!! csrf_field() !!}
    @else
        <form>
    @endif  
            <div class="form-group">
                <label for="ciclo_id">Ciclo:</label>
                <input type="text" name="ciclo_id" placeholder="Ciclo" class="form-control" value="{{ $tanque_sigla }} ( Ciclo Nº {{ $ciclo_id }} )" disabled>
            </div>
            <div class="form-group">
                <label for="data_inicio">Data de início:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_inicio" placeholder="Data de início" class="form-control pull-right" id="datetime_picker" value="{{ $data_inicio }}" disabled>
                </div>
            </div>
            <div class="form-group">
                <label for="bandejas">Bandejas:</label>
                <input type="number" step="any" name="bandejas" placeholder="Bandejas" class="form-control" value="{{ $bandejas }}" {{ $cicloSituacao ? '' : 'disabled' }}>
            </div>
            <div class="form-group">
                <label for="ph_solo">pH do solo:</label>
                <input type="number" step="any" name="ph_solo" placeholder="pH do solo" class="form-control" value="{{ $ph_solo }}" {{ $cicloSituacao ? '' : 'disabled' }}>
            </div>
            <div class="form-group">
                <label for="observacoes">Observações:</label>
                <textarea rows="3" name="observacoes" placeholder="Observações" class="form-control" {{ $cicloSituacao ? '' : 'disabled' }}>{{ $observacoes }}</textarea>
            </div>
            <div class="form-group">
                @if ($cicloSituacao)
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check" aria-hidden="true"></i> Salvar</button>
                @endif
                <a href="{{ route('admin.preparacoes_v2') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection