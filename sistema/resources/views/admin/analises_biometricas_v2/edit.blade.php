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
        <div class="row">
            @if ($situacao)
            <form action="{{ route('admin.analises_biometricas_v2.to_update', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class="col-xs-6">
                    <label for="ciclo_id">Ciclo:</label>
                    <select name="ciclo_id" class="form-control">
                        <option value="">..:: Selecione ::..</option>
                        @foreach($ciclos as $ciclo)
                            <option value="{{ $ciclo->id }}" {{ ($ciclo->id == $ciclo_id) ? 'selected' : '' }}>{{ $ciclo->tanque->sigla }} ( Ciclo Nº {{ $ciclo->numero }} )</option>
                        @endforeach
                    </select>
                </div>
            @else
                <form enctype="multipart/form-data">
                    <div class="col-xs-6">
                        <label for="ciclo_id">Ciclo:</label>
                        <select name="ciclo_id" class="form-control" {{ $situacao ? '' : 'disabled' }}>
                            <option value selected>{{ $tanque_sigla }} ( Ciclo Nº {{ $ciclo_id }} )</option>
                        </select>
                    </div>
            @endif
    
            <div class="col-xs-6">
                <label for="data_analise">Data da análise:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" name="data_analise" placeholder="Data da análise" class="form-control pull-right" id="date_picker" value="{{ $data_analise }}">
                </div>
            </div>
        </div>
        <br><br>
        <div class="row">
                <div class="col-xs-12" >
                    <h4>Informações Iniciais</h4>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4" id="total_amostras">
                    <label for="total_amostras">Total de Amostras:</label>
                    <input type="number" step="any" name="total_amostras" placeholder="Total de Amostras" class="form-control" value="{{ $total_amostras }}">
                </div>
                <div class="col-xs-4">
                    <label for="peso_total">Peso Total:</label>
                    <input type="number" step="any" name="peso_total" placeholder="Peso Total" class="form-control" value="{{ $peso_total}}">
                </div>
                <div class="col-xs-4">
                    <label for="sobrevivencia">Percentual de Sobrevivência:</label>
                    <input type="number" step="any" name="sobrevivencia" placeholder="Percentual de sobrevivência" class="form-control" value="{{ $sobrevivencia }}">
                </div>
            </div>
            <br>
            <br>
            <div class="form-group">
                @if ($situacao)
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                @endif
                <a href="{{ route('admin.analises_biometricas_v2') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection