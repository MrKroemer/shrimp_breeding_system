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
        <form action="{{ route('admin.preparacoes.to_update', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
    @else
        <form enctype="multipart/form-data">
    @endif
            <div class="form-group">
                <label for="ciclo_id">Ciclo:</label>
                <input type="text" name="ciclo_id" placeholder="Ciclo" class="form-control" value="{{ $ciclo->tanque->sigla }} ( Ciclo Nº {{ $ciclo->numero }} )" disabled>
            </div>
            <div class="row">
                <div class="form-group col-sm-3">
                    <label for="data_inicio">Início da preparação:</label>
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                        </div>
                        <input type="text" name="data_inicio" placeholder="Início da preparação" class="form-control pull-right" id="date_picker" value="{{ $data_inicio }}" {{ $cicloSituacao ? '' : 'disabled' }}>
                    </div>
                </div>
                <div class="form-group col-sm-3">
                    <label for="abas_inicio">Início do abastecimento:</label>
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                        </div>
                        <input type="text" name="abas_inicio" placeholder="Início do abastecimento" class="form-control pull-right" id="date_picker" value="{{ $abas_inicio }}" {{ $cicloSituacao ? '' : 'disabled' }}>
                    </div>
                </div>
                <div class="form-group col-sm-3">
                    <label for="abas_fim">Fim do abastecimento:</label>
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                        </div>
                        <input type="text" name="abas_fim" placeholder="Fim do abastecimento" class="form-control pull-right" id="date_picker" value="{{ $abas_fim }}" {{ $cicloSituacao ? '' : 'disabled' }}>
                    </div>
                </div>
                <div class="form-group col-sm-3">
                    <label for="data_fim">Fim da preparação:</label>
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                        </div>
                        <input type="text" name="data_fim" onfocus="alertaFinalizacao(this);"  placeholder="Fim da preparação" class="form-control pull-right" id="date_picker" value="{{ $data_fim }}" {{ $cicloSituacao ? '' : 'disabled' }}>
                    </div>
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
                <a href="{{ route('admin.preparacoes') }}" class="btn btn-success">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection