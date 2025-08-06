@extends('adminlte::page')

@section('title', 'Registro de analises biométricas')

@section('content_header')
<h1>{{ $situacao_ciclo ? 'Edição' : 'Visualização' }} de analises biométricas</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Analises biométricas</a></li>
    <li><a href="">{{ $situacao_ciclo ? 'Edição' : 'Visualização' }}</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
    @if ($situacao_ciclo)
        <form action="{{ route('admin.analises_biometricas.to_update', array_merge(['id' => $id], $redirectParam)) }}" method="POST">
            {!! csrf_field() !!}
    @else
        <form>
    @endif
            <div class="form-group">
                <label for="data_analise">Data da análise:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_analise" placeholder="Data da análise" class="form-control pull-right" id="date_picker" value="{{ $analise_biometrica->data_analise() }}" disabled>
                </div>
            </div>
            <div class="form-group">
                <label for="ciclo_id">Ciclo:</label>
                <select name="ciclo_id" onchange="onChangeBioTipoCultivo();" class="form-control" disabled>
                    <option value="">..:: Selecione ::..</option>
                    @foreach($ciclos as $ciclo)
                        <option value="{{ $ciclo->id }}" {{ ($ciclo->id == $analise_biometrica->ciclo_id) ? 'selected' : '' }}>{{ $ciclo->tanque->sigla }} ( Ciclo Nº {{ $ciclo->numero }} )</option>
                    @endforeach
                </select>
            </div>
            <hr>
            <h4>Informações iniciais</h4>
            <div class="form-group" id="total_animais">
                <label for="total_animais">Total de animais:</label>
                <input type="number" step="any" name="total_animais" placeholder="Total de animais" class="form-control" value="{{ $analise_biometrica->total_animais }}" oninput="onChangePesoTotal();" {{ $situacao_ciclo && $analise_biometrica->situacao < 3 ? '' : 'disabled' }}>
            </div>
            <div class="form-group">
                <label for="peso_total">Peso total (g):</label>
                <input type="number" step="any" name="peso_total" placeholder="Peso total" class="form-control" value="{{ $analise_biometrica->peso_total }}" oninput="onChangePesoTotal();" {{ $situacao_ciclo && $analise_biometrica->situacao < 3 ? '' : 'disabled' }}>
            </div>
            <div class="form-group">
                <label for="peso_medio">Peso médio (g):</label>
                <input type="number" step="any" name="peso_medio" placeholder="Peso médio" class="form-control" value="{{ $analise_biometrica->peso_medio }}" {{ $situacao_ciclo && $analise_biometrica->situacao < 3 ? '' : 'disabled' }}>
            </div>
            <div class="form-group" id="sobrevivencia">
                <label for="sobrevivencia">Percentual de sobrevivência:</label>
                <input type="number" step="any" name="sobrevivencia" placeholder="Percentual de sobrevivência" class="form-control" value="{{ $analise_biometrica->sobrevivencia }}" {{ $situacao_ciclo ? '' : 'disabled' }}>
            </div>
            <div class="form-group">
                @if($situacao_ciclo)
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                @endif
                <a href="{{ route('admin.analises_biometricas.to_search', $redirectParam) }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection