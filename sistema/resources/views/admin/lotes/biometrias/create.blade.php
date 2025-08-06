@extends('adminlte::page')

@section('title', 'Registro de informações biométricas de pós-larvas')

@section('content_header')
<h1>Cadastro de informações biométricas de pós-larvas</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Lotes de pós-larvas</a></li>
    <li><a href="">Informações biométricas de pós-larvas</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $mm5           = !empty(session('mm5'))           ? session('mm5')           : old('mm5');
            $mm6           = !empty(session('mm6'))           ? session('mm6')           : old('mm6');
            $mm7           = !empty(session('mm7'))           ? session('mm7')           : old('mm7');
            $mm8           = !empty(session('mm8'))           ? session('mm8')           : old('mm8');
            $mm9           = !empty(session('mm9'))           ? session('mm9')           : old('mm9');
            $mm10          = !empty(session('mm10'))          ? session('mm10')          : old('mm10');
            $mm11          = !empty(session('mm11'))          ? session('mm11')          : old('mm11');
            $mm12          = !empty(session('mm12'))          ? session('mm12')          : old('mm12');
            $mm13          = !empty(session('mm13'))          ? session('mm13')          : old('mm13');
            $mm14          = !empty(session('mm14'))          ? session('mm14')          : old('mm14');
            $mm15          = !empty(session('mm15'))          ? session('mm15')          : old('mm15');
            $mm16          = !empty(session('mm16'))          ? session('mm16')          : old('mm16');
            $mm17          = !empty(session('mm17'))          ? session('mm17')          : old('mm17');
            $mm18          = !empty(session('mm18'))          ? session('mm18')          : old('mm18');
            $mm19          = !empty(session('mm19'))          ? session('mm19')          : old('mm19');
            $mm20          = !empty(session('mm20'))          ? session('mm20')          : old('mm20');
            $total_animais = !empty(session('total_animais')) ? session('total_animais') : old('total_animais');
            $estresse      = !empty(session('estresse'))      ? session('estresse')      : old('estresse');
            $sobrevivencia = !empty(session('sobrevivencia')) ? session('sobrevivencia') : old('sobrevivencia');
            $tamanho_medio = !empty(session('tamanho_medio')) ? session('tamanho_medio') : old('tamanho_medio');
            $peso_total    = !empty(session('peso_total'))    ? session('peso_total')    : old('peso_total');
            $peso_medio    = !empty(session('peso_medio'))    ? session('peso_medio')    : old('peso_medio');
        @endphp
        <form action="{{ route('admin.lotes.biometrias.to_store', ['lote_id' => $lote_id, 'redirectBack' => $redirectBack, 'povoamento_id' => $povoamento_id]) }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="estresse">Percentual de estresse:</label>
                <input type="number" step="any" name="estresse" placeholder="Percentual de estresse" class="form-control" value="{{ $estresse }}">
            </div>
            <div class="form-group">
                <label for="sobrevivencia">Percentual de sobrevivência:</label>
                <input type="number" step="any" name="sobrevivencia" placeholder="Percentual de sobrevivência" class="form-control" value="{{ $sobrevivencia }}">
            </div>
            <h4>Quantidade de animais por milimetragem</h4>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-3">
                        <label for="mm5">5mm:</label>
                        <input type="number" step="any" name="mm5" placeholder="5mm" class="form-control" value="{{ $mm5 }}" autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                    <div class="col-xs-3">
                        <label for="mm6">6mm:</label>
                        <input type="number" step="any" name="mm6" placeholder="6mm" class="form-control" value="{{ $mm6 }}" autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                    <div class="col-xs-3">
                        <label for="mm7">7mm:</label>
                        <input type="number" step="any" name="mm7" placeholder="7mm" class="form-control" value="{{ $mm7 }}" autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                    <div class="col-xs-3">
                        <label for="mm8">8mm:</label>
                        <input type="number" step="any" name="mm8" placeholder="8mm" class="form-control" value="{{ $mm8 }}" autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                    <div class="col-xs-3">
                        <label for="mm9">9mm:</label>
                        <input type="number" step="any" name="mm9" placeholder="9mm" class="form-control" value="{{ $mm9 }}" autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                    <div class="col-xs-3">
                        <label for="mm10">10mm:</label>
                        <input type="number" step="any" name="mm10" placeholder="10mm" class="form-control" value="{{ $mm10 }}" autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                    <div class="col-xs-3">
                        <label for="mm11">11mm:</label>
                        <input type="number" step="any" name="mm11" placeholder="11mm" class="form-control" value="{{ $mm11 }}" autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                    <div class="col-xs-3">
                        <label for="mm12">12mm:</label>
                        <input type="number" step="any" name="mm12" placeholder="12mm" class="form-control" value="{{ $mm12 }}" autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                    <div class="col-xs-3">
                        <label for="mm13">13mm:</label>
                        <input type="number" step="any" name="mm13" placeholder="13mm" class="form-control" value="{{ $mm13 }}" autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                    <div class="col-xs-3">
                        <label for="mm14">14mm:</label>
                        <input type="number" step="any" name="mm14" placeholder="14mm" class="form-control" value="{{ $mm14 }}" autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                    <div class="col-xs-3">
                        <label for="mm15">15mm:</label>
                        <input type="number" step="any" name="mm15" placeholder="15mm" class="form-control" value="{{ $mm15 }}" autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                    <div class="col-xs-3">
                        <label for="mm16">16mm:</label>
                        <input type="number" step="any" name="mm16" placeholder="16mm" class="form-control" value="{{ $mm16 }}" autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                    <div class="col-xs-3">
                        <label for="mm17">17mm:</label>
                        <input type="number" step="any" name="mm17" placeholder="17mm" class="form-control" value="{{ $mm17 }}" autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                    <div class="col-xs-3">
                        <label for="mm18">18mm:</label>
                        <input type="number" step="any" name="mm18" placeholder="18mm" class="form-control" value="{{ $mm18 }}" autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                    <div class="col-xs-3">
                        <label for="mm19">19mm:</label>
                        <input type="number" step="any" name="mm19" placeholder="19mm" class="form-control" value="{{ $mm19 }}" autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                    <div class="col-xs-3">
                        <label for="mm20">20mm:</label>
                        <input type="number" step="any" name="mm20" placeholder="20mm" class="form-control" value="{{ $mm20 }}" autocomplete="off" oninput="onChangeAmostraPorMm();">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="tamanho_medio">Tamanho médio (mm):</label>
                <input type="number" step="any" name="tamanho_medio" placeholder="Tamanho médio" class="form-control" value="{{ $tamanho_medio }}">
            </div>
            <div class="form-group">
                <label for="total_animais">Total de animais (amostras):</label>
                <input type="number" step="any" name="total_animais" placeholder="Total de animais" class="form-control" value="{{ $total_animais }}" oninput="onChangePesoTotal();">
            </div>
            <div class="form-group">
                <label for="peso_total">Peso total (g):</label>
                <input type="number" step="any" name="peso_total" placeholder="Peso total" class="form-control" value="{{ $peso_total }}" oninput="onChangePesoTotal();">
            </div>
            <div class="form-group">
                <label for="peso_medio">Peso médio (g):</label>
                <input type="number" step="any" name="peso_medio" placeholder="Peso médio" class="form-control" value="{{ $peso_medio }}">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                @if ($redirectBack == 'yes')
                    <a href="{{ route('admin.lotes.redirect_to.povoamentos.to_create', ['povoamento_id' => $povoamento_id]) }}" class="btn btn-default">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> Povoamento de tanques
                    </a>
                @else
                    <a href="{{ route('admin.lotes') }}" class="btn btn-success">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
