@extends('adminlte::page')

@section('title', 'Registro de analises biométricas')

@section('content_header')
<h1>Cadastro de analises biométricas</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Analises biométricas</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $ciclo_id          = !empty(session('ciclo_id'))         ? session('ciclo_id')         : old('ciclo_id');
            $data_analise      = !empty(session('data_analise'))     ? session('data_analise')     : old('data_analise');
            $moles             = !empty(session('moles'))            ? session('moles')            : old('moles');
            $semimoles         = !empty(session('semimoles'))        ? session('semimoles')        : old('semimoles');
            $flacidos          = !empty(session('flacidos'))         ? session('flacidos')         : old('flacidos');
            $opacos            = !empty(session('opacos'))           ? session('opacos')           : old('opacos');
            $deformados        = !empty(session('deformados'))       ? session('deformados')       : old('deformados');
            $caudas_vermelhas  = !empty(session('caudas_vermelhas')) ? session('caudas_vermelhas') : old('caudas_vermelhas');
            $necroses_nivel1   = !empty(session('necroses_nivel1'))  ? session('necroses_nivel1')  : old('necroses_nivel1');
            $necroses_nivel2   = !empty(session('necroses_nivel2'))  ? session('necroses_nivel2')  : old('necroses_nivel2');
            $necroses_nivel3   = !empty(session('necroses_nivel3'))  ? session('necroses_nivel3')  : old('necroses_nivel3');
            $necroses_nivel4   = !empty(session('necroses_nivel4'))  ? session('necroses_nivel4')  : old('necroses_nivel4');
            $classe0to10       = !empty(session('classe0to10'))      ? session('classe0to10')      : old('classe0to10');
            $classe10to20      = !empty(session('classe10to20'))     ? session('classe10to20')     : old('classe10to20');
            $classe20to30      = !empty(session('classe20to30'))     ? session('classe20to30')     : old('classe20to30');
            $classe30to40      = !empty(session('classe30to40'))     ? session('classe30to40')     : old('classe30to40');
            $classe40to50      = !empty(session('classe40to50'))     ? session('classe40to50')     : old('classe40to50');
            $classe50to60      = !empty(session('classe50to60'))     ? session('classe50to60')     : old('classe50to60');
            $classe60to70      = !empty(session('classe60to70'))     ? session('classe60to70')     : old('classe60to70');
            $classe70to80      = !empty(session('classe70to80'))     ? session('classe70to80')     : old('classe70to80');
            $classe80to100     = !empty(session('classe80to100'))    ? session('classe80to100')    : old('classe80to100');
            $classe100to120    = !empty(session('classe100to120'))   ? session('classe100to120')   : old('classe100to120');
            $classe120to150    = !empty(session('classe120to150'))   ? session('classe120to150')   : old('classe120to150');
            $classe150toUP     = !empty(session('classe150toUP'))    ? session('classe150toUP')    : old('classe150toUP');
            $total_animais     = !empty(session('total_animais'))    ? session('total_animais')    : old('total_animais');
            $peso_total        = !empty(session('peso_total'))       ? session('peso_total')       : old('peso_total');
            $peso_medio        = !empty(session('peso_medio'))       ? session('peso_medio')       : old('peso_medio');
            $sobrevivencia     = !empty(session('sobrevivencia'))    ? session('sobrevivencia')    : old('sobrevivencia');
        @endphp
        <form action="{{ route('admin.analises_biometricas_old.to_store') }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="ciclo_id">Ciclo:</label>

                <!-- Lista todos os ciclos ativos do sistema -->
                <select name="ciclo_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($ciclos as $ciclo)
                        <option value="{{ $ciclo->id }}" {{ ($ciclo->id == $ciclo_id) ? 'selected' : '' }}>{{ $ciclo->tanque->sigla }} ( Ciclo Nº {{ $ciclo->numero }} )</option>
                    @endforeach
                </select>
            </div>
            <!-- Pega os dados de acordo com a data selecionada-->
            <div class="form-group">
                <label for="data_analise">Data da análise:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_analise" placeholder="Data da análise" class="form-control pull-right" id="date_picker" value="{{ $data_analise }}">
                </div>
            </div>
            

            <h4>Qualidade</h4>
            <div class="form-group">
                    <!-- Campos para receber os parametros específicos de cada amostra -->
                <div class="row">
                    <div class="col-xs-6">
                        <label for="moles">Moles:</label>
                        <input type="number" step="any" name="moles" placeholder="Moles" class="form-control" value="{{ $moles }}" autocomplete="off">
                    </div>
                    <div class="col-xs-6">
                        <label for="semimoles">Semi-moles:</label>
                        <input type="number" step="any" name="semimoles" placeholder="Semi-moles" class="form-control" value="{{ $semimoles }}" autocomplete="off">
                    </div>
                    <div class="col-xs-6">
                        <label for="flacidos">Flácidos:</label>
                        <input type="number" step="any" name="flacidos" placeholder="Flácidos" class="form-control" value="{{ $flacidos }}" autocomplete="off">
                    </div>
                    <div class="col-xs-6">
                        <label for="opacos">Opacos:</label>
                        <input type="number" step="any" name="opacos" placeholder="Opacos" class="form-control" value="{{ $opacos }}" autocomplete="off">
                    </div>
                    <div class="col-xs-6">
                        <label for="deformados">Deformados:</label>
                        <input type="number" step="any" name="deformados" placeholder="Deformados" class="form-control" value="{{ $deformados }}" autocomplete="off">
                    </div>
                    <div class="col-xs-6">
                        <label for="caudas_vermelhas">Caudas vermelhas:</label>
                        <input type="number" step="any" name="caudas_vermelhas" placeholder="Caudas vermelhas" class="form-control" value="{{ $caudas_vermelhas }}" autocomplete="off">
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-3">
                        <label for="necroses_nivel1">Necrosados nivel 1:</label>
                        <input type="number" step="any" name="necroses_nivel1" placeholder="Necrosados nivel 1" class="form-control" value="{{ $necroses_nivel1 }}" autocomplete="off">
                    </div>
                    <div class="col-xs-3">
                        <label for="necroses_nivel2">Necrosados nivel 2:</label>
                        <input type="number" step="any" name="necroses_nivel2" placeholder="Necrosados nivel 2" class="form-control" value="{{ $necroses_nivel2 }}" autocomplete="off">
                    </div>
                    <div class="col-xs-3">
                        <label for="necroses_nivel3">Necrosados nivel 3:</label>
                        <input type="number" step="any" name="necroses_nivel3" placeholder="Necrosados nivel 3" class="form-control" value="{{ $necroses_nivel3 }}" autocomplete="off">
                    </div>
                    <div class="col-xs-3">
                        <label for="necroses_nivel4">Necrosados nivel 4:</label>
                        <input type="number" step="any" name="necroses_nivel4" placeholder="Necrosados nivel 4" class="form-control" value="{{ $necroses_nivel4 }}" autocomplete="off">
                    </div>
                </div>

            </div>

            <h4>Classificação</h4>
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-3">
                        <label for="classe0to10">0/10:</label>
                        <input type="number" step="any" name="classe0to10" placeholder="0/10" class="form-control" value="{{ $classe0to10 }}" autocomplete="off" oninput="onChangeAmostraPorClasse();">
                    </div>
                    <div class="col-xs-3">
                        <label for="classe10to20">10/20:</label>
                        <input type="number" step="any" name="classe10to20" placeholder="10/20" class="form-control" value="{{ $classe10to20 }}" autocomplete="off" oninput="onChangeAmostraPorClasse();">
                    </div>
                    <div class="col-xs-3">
                        <label for="classe20to30">20/30:</label>
                        <input type="number" step="any" name="classe20to30" placeholder="20/30" class="form-control" value="{{ $classe20to30 }}" autocomplete="off" oninput="onChangeAmostraPorClasse();">
                    </div>
                    <div class="col-xs-3">
                        <label for="classe30to40">30/40:</label>
                        <input type="number" step="any" name="classe30to40" placeholder="30/40" class="form-control" value="{{ $classe30to40 }}" autocomplete="off" oninput="onChangeAmostraPorClasse();">
                    </div>
                    <div class="col-xs-3">
                        <label for="classe40to50">40/50:</label>
                        <input type="number" step="any" name="classe40to50" placeholder="40/50" class="form-control" value="{{ $classe40to50 }}" autocomplete="off" oninput="onChangeAmostraPorClasse();">
                    </div>
                    <div class="col-xs-3">
                        <label for="classe50to60">50/60:</label>
                        <input type="number" step="any" name="classe50to60" placeholder="50/60" class="form-control" value="{{ $classe50to60 }}" autocomplete="off" oninput="onChangeAmostraPorClasse();">
                    </div>
                    <div class="col-xs-3">
                        <label for="classe60to70">60/70:</label>
                        <input type="number" step="any" name="classe60to70" placeholder="60/70" class="form-control" value="{{ $classe60to70 }}" autocomplete="off" oninput="onChangeAmostraPorClasse();">
                    </div>
                    <div class="col-xs-3">
                        <label for="classe70to80">70/80:</label>
                        <input type="number" step="any" name="classe70to80" placeholder="70/80" class="form-control" value="{{ $classe70to80 }}" autocomplete="off" oninput="onChangeAmostraPorClasse();">
                    </div>
                    <div class="col-xs-3">
                        <label for="classe80to100">80/100:</label>
                        <input type="number" step="any" name="classe80to100" placeholder="80/100" class="form-control" value="{{ $classe80to100 }}" autocomplete="off" oninput="onChangeAmostraPorClasse();">
                    </div>
                    <div class="col-xs-3">
                        <label for="classe100to120">100/120:</label>
                        <input type="number" step="any" name="classe100to120" placeholder="100/120" class="form-control" value="{{ $classe100to120 }}" autocomplete="off" oninput="onChangeAmostraPorClasse();">
                    </div>
                    <div class="col-xs-3">
                        <label for="classe120to150">120/150:</label>
                        <input type="number" step="any" name="classe120to150" placeholder="120/150" class="form-control" value="{{ $classe120to150 }}" autocomplete="off" oninput="onChangeAmostraPorClasse();">
                    </div>
                    <div class="col-xs-3">
                        <label for="classe150toUP">150/UP:</label>
                        <input type="number" step="any" name="classe150toUP" placeholder="150/UP" class="form-control" value="{{ $classe150toUP }}" autocomplete="off" oninput="onChangeAmostraPorClasse();">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="total_animais">Total de animais:</label>
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
                <label for="sobrevivencia">Percentual de sobrevivência:</label>
                <input type="number" step="any" name="sobrevivencia" placeholder="Percentual de sobrevivência" class="form-control" value="{{ $sobrevivencia }}">
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.analises_biometricas_old') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection