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
        <form action="{{ route('admin.analises_biometricas_old.to_update', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
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
            <div class="form-group">
                <label for="ciclo_id">Ciclo:</label>
                <select name="ciclo_id" class="form-control" {{ $situacao ? '' : 'disabled' }}>
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
                    <input type="text" name="data_analise" placeholder="Data da análise" class="form-control pull-right" id="date_picker" value="{{ $data_analise }}" {{ $situacao ? '' : 'disabled' }}>
                </div>
            </div>

            <h4>Qualidade</h4>
            <div class="form-group">

                <div class="row">
                    <div class="col-xs-6">
                        <label for="moles">Moles:</label>
                        <input type="number" step="any" name="moles" placeholder="Moles" class="form-control" value="{{ $moles }}" autocomplete="off" {{ $situacao ? '' : 'disabled' }}>
                    </div>
                    <div class="col-xs-6">
                        <label for="semimoles">Semi-moles:</label>
                        <input type="number" step="any" name="semimoles" placeholder="Semi-moles" class="form-control" value="{{ $semimoles }}" autocomplete="off" {{ $situacao ? '' : 'disabled' }}>
                    </div>
                    <div class="col-xs-6">
                        <label for="flacidos">Flácidos:</label>
                        <input type="number" step="any" name="flacidos" placeholder="Flácidos" class="form-control" value="{{ $flacidos }}" autocomplete="off" {{ $situacao ? '' : 'disabled' }}>
                    </div>
                    <div class="col-xs-6">
                        <label for="opacos">Opacos:</label>
                        <input type="number" step="any" name="opacos" placeholder="Opacos" class="form-control" value="{{ $opacos }}" autocomplete="off" {{ $situacao ? '' : 'disabled' }}>
                    </div>
                    <div class="col-xs-6">
                        <label for="deformados">Deformados:</label>
                        <input type="number" step="any" name="deformados" placeholder="Deformados" class="form-control" value="{{ $deformados }}" autocomplete="off" {{ $situacao ? '' : 'disabled' }}>
                    </div>
                    <div class="col-xs-6">
                        <label for="caudas_vermelhas">Caudas vermelhas:</label>
                        <input type="number" step="any" name="caudas_vermelhas" placeholder="Caudas vermelhas" class="form-control" value="{{ $caudas_vermelhas }}" autocomplete="off" {{ $situacao ? '' : 'disabled' }}>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-3">
                        <label for="necroses_nivel1">Necrosados nivel 1:</label>
                        <input type="number" step="any" name="necroses_nivel1" placeholder="Necrosados nivel 1" class="form-control" value="{{ $necroses_nivel1 }}" autocomplete="off" {{ $situacao ? '' : 'disabled' }}>
                    </div>
                    <div class="col-xs-3">
                        <label for="necroses_nivel2">Necrosados nivel 2:</label>
                        <input type="number" step="any" name="necroses_nivel2" placeholder="Necrosados nivel 2" class="form-control" value="{{ $necroses_nivel2 }}" autocomplete="off" {{ $situacao ? '' : 'disabled' }}>
                    </div>
                    <div class="col-xs-3">
                        <label for="necroses_nivel3">Necrosados nivel 3:</label>
                        <input type="number" step="any" name="necroses_nivel3" placeholder="Necrosados nivel 3" class="form-control" value="{{ $necroses_nivel3 }}" autocomplete="off" {{ $situacao ? '' : 'disabled' }}>
                    </div>
                    <div class="col-xs-3">
                        <label for="necroses_nivel4">Necrosados nivel 4:</label>
                        <input type="number" step="any" name="necroses_nivel4" placeholder="Necrosados nivel 4" class="form-control" value="{{ $necroses_nivel4 }}" autocomplete="off" {{ $situacao ? '' : 'disabled' }}>
                    </div>
                </div>

            </div>

            <h4>Classificação</h4>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-3">
                        <label for="classe0to10">0/10:</label>
                        <input type="number" step="any" name="classe0to10" placeholder="0/10" class="form-control" value="{{ $classe0to10 }}" autocomplete="off" oninput="onChangeAmostraPorClasse();" {{ $situacao ? '' : 'disabled' }}>
                    </div>
                    <div class="col-xs-3">
                        <label for="classe10to20">10/20:</label>
                        <input type="number" step="any" name="classe10to20" placeholder="10/20" class="form-control" value="{{ $classe10to20 }}" autocomplete="off" oninput="onChangeAmostraPorClasse();" {{ $situacao ? '' : 'disabled' }}>
                    </div>
                    <div class="col-xs-3">
                        <label for="classe20to30">20/30:</label>
                        <input type="number" step="any" name="classe20to30" placeholder="20/30" class="form-control" value="{{ $classe20to30 }}" autocomplete="off" oninput="onChangeAmostraPorClasse();" {{ $situacao ? '' : 'disabled' }}>
                    </div>
                    <div class="col-xs-3">
                        <label for="classe30to40">30/40:</label>
                        <input type="number" step="any" name="classe30to40" placeholder="30/40" class="form-control" value="{{ $classe30to40 }}" autocomplete="off" oninput="onChangeAmostraPorClasse();" {{ $situacao ? '' : 'disabled' }}>
                    </div>
                    <div class="col-xs-3">
                        <label for="classe40to50">40/50:</label>
                        <input type="number" step="any" name="classe40to50" placeholder="40/50" class="form-control" value="{{ $classe40to50 }}" autocomplete="off" oninput="onChangeAmostraPorClasse();" {{ $situacao ? '' : 'disabled' }}>
                    </div>
                    <div class="col-xs-3">
                        <label for="classe50to60">50/60:</label>
                        <input type="number" step="any" name="classe50to60" placeholder="50/60" class="form-control" value="{{ $classe50to60 }}" autocomplete="off" oninput="onChangeAmostraPorClasse();" {{ $situacao ? '' : 'disabled' }}>
                    </div>
                    <div class="col-xs-3">
                        <label for="classe60to70">60/70:</label>
                        <input type="number" step="any" name="classe60to70" placeholder="60/70" class="form-control" value="{{ $classe60to70 }}" autocomplete="off" oninput="onChangeAmostraPorClasse();" {{ $situacao ? '' : 'disabled' }}>
                    </div>
                    <div class="col-xs-3">
                        <label for="classe70to80">70/80:</label>
                        <input type="number" step="any" name="classe70to80" placeholder="70/80" class="form-control" value="{{ $classe70to80 }}" autocomplete="off" oninput="onChangeAmostraPorClasse();" {{ $situacao ? '' : 'disabled' }}>
                    </div>
                    <div class="col-xs-3">
                        <label for="classe80to100">80/100:</label>
                        <input type="number" step="any" name="classe80to100" placeholder="80/100" class="form-control" value="{{ $classe80to100 }}" autocomplete="off" oninput="onChangeAmostraPorClasse();" {{ $situacao ? '' : 'disabled' }}>
                    </div>
                    <div class="col-xs-3">
                        <label for="classe100to120">100/120:</label>
                        <input type="number" step="any" name="classe100to120" placeholder="100/120" class="form-control" value="{{ $classe100to120 }}" autocomplete="off" oninput="onChangeAmostraPorClasse();" {{ $situacao ? '' : 'disabled' }}>
                    </div>
                    <div class="col-xs-3">
                        <label for="classe120to150">120/150:</label>
                        <input type="number" step="any" name="classe120to150" placeholder="120/150" class="form-control" value="{{ $classe120to150 }}" autocomplete="off" oninput="onChangeAmostraPorClasse();" {{ $situacao ? '' : 'disabled' }}>
                    </div>
                    <div class="col-xs-3">
                        <label for="classe150toUP">150/UP:</label>
                        <input type="number" step="any" name="classe150toUP" placeholder="150/UP" class="form-control" value="{{ $classe150toUP }}" autocomplete="off" oninput="onChangeAmostraPorClasse();" {{ $situacao ? '' : 'disabled' }}>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="total_animais">Total de animais:</label>
                <input type="number" step="any" name="total_animais" placeholder="Total de animais" class="form-control" value="{{ $total_animais }}" oninput="onChangePesoTotal();" {{ $situacao ? '' : 'disabled' }}>
            </div>
            <div class="form-group">
                <label for="peso_total">Peso total (g):</label>
                <input type="number" step="any" name="peso_total" placeholder="Peso total" class="form-control" value="{{ $peso_total }}" oninput="onChangePesoTotal();" {{ $situacao ? '' : 'disabled' }}>
            </div>
            <div class="form-group">
                <label for="peso_medio">Peso médio (g):</label>
                <input type="number" step="any" name="peso_medio" placeholder="Peso médio" class="form-control" value="{{ $peso_medio }}" {{ $situacao ? '' : 'disabled' }}>
            </div>
            <div class="form-group">
                <label for="sobrevivencia">Percentual de sobrevivência:</label>
                <input type="number" step="any" name="sobrevivencia" placeholder="Percentual de sobrevivência" class="form-control" value="{{ $sobrevivencia }}" {{ $situacao ? '' : 'disabled' }}>
            </div>

            <div class="form-group">
                @if ($situacao)
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                @endif
                <a href="{{ route('admin.analises_biometricas_old') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection