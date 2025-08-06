@extends('adminlte::page')

@section('title', 'Registro de despescas de tanques')

@section('content_header')


<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Despescas de tanques</a></li>
    
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
            <div class="form-group">
                <label for="ciclo_id">Ciclo:</label>
                <select name="ciclo_id" class="form-control" disabled>
                    <option value="{{ $ciclo->id }}" selected>{{ $ciclo->tanque->sigla }} ( Ciclo Nº {{ $ciclo->numero }} )</option>
                </select>
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
                <label for="data_fim">Data de fim:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_fim" placeholder="Data de fim" class="form-control pull-right" id="datetime_picker" value="{{ $data_fim }}" disabled>
                </div>
            </div>
            <div class="form-group">
                <label for="qtd_prevista">Quantidade prevista (Kg):</label>
                <input type="number" step="any" name="qtd_prevista" placeholder="Quantidade prevista" class="form-control" value="{{ $qtd_prevista }}" disabled>
            </div>
            <div class="form-group">
                <label for="qtd_despescada">Quantidade despescada (Kg):</label>
                <input type="number" step="any" name="qtd_despescada" placeholder="Quantidade despescada" class="form-control" value="{{ $qtd_despescada }}" disabled>
            </div>
            <div class="form-group">
                <label for="peso_medio">Peso médio (g):</label>
                <input type="number" step="any" name="peso_medio" placeholder="Peso médio" class="form-control" value="{{ $peso_medio }}" disabled>
            </div>
            <div class="form-group">
                <label for="tipo">Tipo de despesca:</label>
                <input type="hidden" name="tipo_despesca">
                <ul class="list">
                    <li><input type="radio"  name="radio_button01" id="tipo_despesca01" {{ $tipo == 1 ? 'checked' : 'disabled' }}> Despesca completa</li>
                    <li><input type="radio"  name="radio_button01" id="tipo_despesca02" {{ $tipo == 2 ? 'checked' : 'disabled' }}> Despesca parcial</li>
                    <li><input type="radio"  name="radio_button01" id="tipo_despesca03" {{ $tipo == 3 ? 'checked' : 'disabled' }}> Descarte pré povoamento</li>
                    <li><input type="radio"  name="radio_button01" id="tipo_despesca04" {{ $tipo == 4 ? 'checked' : 'disabled' }}> Descarte pós povoamento</li>
                </ul>
            </div>
            <div class="form-group">
                <label for="observacoes">Observações:</label>
                <textarea rows="3" name="observacoes" placeholder="Observações" class="form-control" disabled>{{ $observacoes }}</textarea>
            </div>
            <div class="form-group">
                <a href="{{ route('admin.despescas') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
