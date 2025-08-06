@extends('adminlte::page')

@section('title', 'Registro de informações biométricas de pós-larvas')

@section('content_header')
<h1>Edição de informações biométricas de pós-larvas</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Lotes de pós-larvas</a></li>
    <li><a href="">Informações biométricas de pós-larvas</a></li>
    <li><a href="">Edição</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        <form action="{{ route('admin.lotes.biometrias.to_update', ['lote_id' => $lote->id, 'id' => $id]) }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="estresse">Percentual de estresse:</label>
                <input type="number" step="any" name="estresse" placeholder="Percentual de estresse" class="form-control" value="{{ $estresse }}" {{ $povoado ? 'disabled' : '' }}>
            </div>
            <div class="form-group">
                <label for="sobrevivencia">Percentual de sobrevivência:</label>
                <input type="number" step="any" name="sobrevivencia" placeholder="Percentual de sobrevivência" class="form-control" value="{{ $sobrevivencia }}" {{ $povoado ? 'disabled' : '' }}>
            </div>
            <h4>Quantidade de animais por milimetragem</h4>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-3">
                        <label for="mm5">5mm:</label>
                        <input type="number" step="any" name="mm5" placeholder="5mm" class="form-control" value="{{ $mm5 }}" {{ $povoado ? 'disabled' : '' }} autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                    <div class="col-xs-3">
                        <label for="mm6">6mm:</label>
                        <input type="number" step="any" name="mm6" placeholder="6mm" class="form-control" value="{{ $mm6 }}" {{ $povoado ? 'disabled' : '' }} autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                    <div class="col-xs-3">
                        <label for="mm7">7mm:</label>
                        <input type="number" step="any" name="mm7" placeholder="7mm" class="form-control" value="{{ $mm7 }}" {{ $povoado ? 'disabled' : '' }} autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                    <div class="col-xs-3">
                        <label for="mm8">8mm:</label>
                        <input type="number" step="any" name="mm8" placeholder="8mm" class="form-control" value="{{ $mm8 }}" {{ $povoado ? 'disabled' : '' }} autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                    <div class="col-xs-3">
                        <label for="mm9">9mm:</label>
                        <input type="number" step="any" name="mm9" placeholder="9mm" class="form-control" value="{{ $mm9 }}" {{ $povoado ? 'disabled' : '' }} autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                    <div class="col-xs-3">
                        <label for="mm10">10mm:</label>
                        <input type="number" step="any" name="mm10" placeholder="10mm" class="form-control" value="{{ $mm10 }}" {{ $povoado ? 'disabled' : '' }} autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                    <div class="col-xs-3">
                        <label for="mm11">11mm:</label>
                        <input type="number" step="any" name="mm11" placeholder="11mm" class="form-control" value="{{ $mm11 }}" {{ $povoado ? 'disabled' : '' }} autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                    <div class="col-xs-3">
                        <label for="mm12">12mm:</label>
                        <input type="number" step="any" name="mm12" placeholder="12mm" class="form-control" value="{{ $mm12 }}" {{ $povoado ? 'disabled' : '' }} autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                    <div class="col-xs-3">
                        <label for="mm13">13mm:</label>
                        <input type="number" step="any" name="mm13" placeholder="13mm" class="form-control" value="{{ $mm13 }}" {{ $povoado ? 'disabled' : '' }} autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                    <div class="col-xs-3">
                        <label for="mm14">14mm:</label>
                        <input type="number" step="any" name="mm14" placeholder="14mm" class="form-control" value="{{ $mm14 }}" {{ $povoado ? 'disabled' : '' }} autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                    <div class="col-xs-3">
                        <label for="mm15">15mm:</label>
                        <input type="number" step="any" name="mm15" placeholder="15mm" class="form-control" value="{{ $mm15 }}" {{ $povoado ? 'disabled' : '' }} autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                    <div class="col-xs-3">
                        <label for="mm16">16mm:</label>
                        <input type="number" step="any" name="mm16" placeholder="16mm" class="form-control" value="{{ $mm16 }}" {{ $povoado ? 'disabled' : '' }} autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                    <div class="col-xs-3">
                        <label for="mm17">17mm:</label>
                        <input type="number" step="any" name="mm17" placeholder="17mm" class="form-control" value="{{ $mm17 }}" {{ $povoado ? 'disabled' : '' }} autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                    <div class="col-xs-3">
                        <label for="mm18">18mm:</label>
                        <input type="number" step="any" name="mm18" placeholder="18mm" class="form-control" value="{{ $mm18 }}" {{ $povoado ? 'disabled' : '' }} autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                    <div class="col-xs-3">
                        <label for="mm19">19mm:</label>
                        <input type="number" step="any" name="mm19" placeholder="19mm" class="form-control" value="{{ $mm19 }}" {{ $povoado ? 'disabled' : '' }} autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                    <div class="col-xs-3">
                        <label for="mm20">20mm:</label>
                        <input type="number" step="any" name="mm20" placeholder="20mm" class="form-control" value="{{ $mm20 }}" {{ $povoado ? 'disabled' : '' }} autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="tamanho_medio">Tamanho médio (mm):</label>
                <input type="number" step="any" name="tamanho_medio" placeholder="Tamanho médio" class="form-control" value="{{ $tamanho_medio }}" {{ $povoado ? 'disabled' : '' }}>
            </div>
            <div class="form-group">
                <label for="total_animais">Total de animais (amostras):</label>
                <input type="number" step="any" name="total_animais" placeholder="Total de animais" class="form-control" value="{{ $total_animais }}" {{ $povoado ? 'disabled' : '' }} oninput="onChangePesoTotal();">
            </div>
            <div class="form-group">
                <label for="peso_total">Peso total (g):</label>
                <input type="number" step="any" name="peso_total" placeholder="Peso total" class="form-control" value="{{ $peso_total }}" {{ $povoado ? 'disabled' : '' }} oninput="onChangePesoTotal();">
            </div>
            <div class="form-group">
                <label for="peso_medio">Peso médio (g):</label>
                <input type="number" step="any" name="peso_medio" placeholder="Peso médio" class="form-control" value="{{ $peso_medio }}" {{ $povoado ? 'disabled' : '' }}>
            </div>
            <div class="form-group">
                @if(! $povoado)
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save" aria-hidden="true"></i> Salvar
                    </button>
                    <button type="button" onclick="onActionForRequest('{{ route('admin.lotes.biometrias.to_remove', ['lote_id' => $lote->id, 'id' => $id]) }}');" class="btn btn-danger">
                        <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                    </button>
                @endif
                <a href="{{ route('admin.lotes') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection