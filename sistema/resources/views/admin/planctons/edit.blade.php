@extends('adminlte::page')

@section('title', 'Registro de Análises de Planctôns')

@section('content_header')
<h1>Cadastro de Análises de Planctôns</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Análises de Planctôns</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<form action="{{ route('admin.planctons.to_update', ['id' => $id]) }}" id="form_planctons" method="post" enctype="multipart/form-data">
    <div class="box">
        <div class="box-header">Cabeçalho</div>
        <div class="box-body">
            {!! csrf_field() !!}
            <div class="container-fluid">    
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="ciclo_id">Ciclos:</label>
                            <select name="ciclo_id" id="ciclo_id" class="form-control" disabled>
                                <option value="">..:: Selecione ::..</option>
                                @foreach($ciclos as $ciclo)
                                    <option value="{{ $ciclo->id }}" {{ ($ciclo->id == $planctons['ciclo_id']) ? 'selected' : '' }}>{{ $ciclo->sigla }}({{$ciclo->numero}})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="data_analise">Data da análise:</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                                </div>
                                <input type="text" name="data" placeholder="Data da análise" class="form-control pull-right" id="date_picker" value="{{ $planctons->data_analise() }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @php
        $ND = ""; //Não Detectado
        $DB = ""; //Detectado em Baixa Concentração
        $DM = ""; //Detectado em Média Concentração
        $DA = ""; //Detectado em Alta Concentração
    @endphp
    <div class="container-fluid">
        <div class="row"> 
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="box box-danger">
                    <div class="box-header">
                        <h3 class="box-title">Fitoplâncton</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="cloroficeas">Cloroficeas (Cel./ml)</label>
                            <select name="cloroficeas" class="form-control">
                                @switch($planctons['cloroficeas'])
                                    @case(1)
                                        @php
                                            $ND = "selected"; 
                                            $DB = ""; 
                                            $DM = ""; 
                                            $DA = "";  
                                        @endphp
                                        @break
                                    @case(2)
                                        @php
                                        $ND = ""; 
                                        $DB = "selected";
                                        $DM = ""; 
                                        $DA = "";  
                                        @endphp
                                        @break
                                    @case(3)
                                        @php
                                        $ND = ""; 
                                        $DB = ""; 
                                        $DM = "selected"; 
                                        $DA = "";  
                                        @endphp
                                        @break
                                    @case(4)
                                        @php
                                        $ND = ""; 
                                        $DB = ""; 
                                        $DM = ""; 
                                        $DA = "selected";  
                                        @endphp
                                        @break
                                @endswitch
                                <option value="1" {{$ND}}>Não Detectado</option>
                                <option value="2" {{$DB}}>Detectado em Baixa Concentração</option>
                                <option value="3" {{$DM}}>Detectado em Média Concentração</option>
                                <option value="4" {{$DA }}>Detectado em Alta Concentração</option>
                            </select>
                            <!--input type="text" onkeyup="onKeyupSomaCampos('cloroficeas,cianoficeas,diatomaceas,euglenoficeas,total_algas')" name="cloroficeas" id="cloroficeas" class="form-control" value="{{ $planctons['cloroficeas'] }}"-->
                        </div>  
                        <div class="form-group">
                            <label for="cloroficeas">Cianoficeas (Cel./ml)</label>
                            <select name="cloroficeas" class="form-control">
                                @switch($planctons['cianoficeas'])
                                @case(1)
                                @php
                                    $ND = "selected"; 
                                    $DB = ""; 
                                    $DM = ""; 
                                    $DA = "";  
                                @endphp
                                @break
                                @case(2)
                                    @php
                                    $ND = ""; 
                                    $DB = "selected";
                                    $DM = ""; 
                                    $DA = "";  
                                    @endphp
                                    @break
                                @case(3)
                                    @php
                                    $ND = ""; 
                                    $DB = ""; 
                                    $DM = "selected"; 
                                    $DA = "";  
                                    @endphp
                                    @break
                                @case(4)
                                    @php
                                    $ND = ""; 
                                    $DB = ""; 
                                    $DM = ""; 
                                    $DA = "selected";  
                                    @endphp
                                    @break
                            @endswitch
                            <option value="1" {{$ND}}>Não Detectado</option>
                            <option value="2" {{$DB}}>Detectado em Baixa Concentração</option>
                            <option value="3" {{$DM}}>Detectado em Média Concentração</option>
                            <option value="4" {{$DA }}>Detectado em Alta Concentração</option>
                        </select>
                        <!--input type="text" onkeyup="onKeyupSomaCampos('cloroficeas,cianoficeas,diatomaceas,euglenoficeas,total_algas')" name="cianoficeas" id="cianoficeas" class="form-control" value="{{ $planctons['cianoficeas'] }}"-->
                        </div>  
                        <div class="form-group">
                            <label for="diatomaceas">Diatomaceas (Cel./ml)</label>
                            <select name="diatomaceas" class="form-control">
                                @switch($planctons['diatomaceas'])
                                @case(1)
                                @php
                                    $ND = "selected"; 
                                    $DB = ""; 
                                    $DM = ""; 
                                    $DA = "";  
                                @endphp
                                @break
                                @case(2)
                                    @php
                                    $ND = ""; 
                                    $DB = "selected";
                                    $DM = ""; 
                                    $DA = "";  
                                    @endphp
                                    @break
                                @case(3)
                                    @php
                                    $ND = ""; 
                                    $DB = ""; 
                                    $DM = "selected"; 
                                    $DA = "";  
                                    @endphp
                                    @break
                                @case(4)
                                    @php
                                    $ND = ""; 
                                    $DB = ""; 
                                    $DM = ""; 
                                    $DA = "selected";  
                                    @endphp
                                    @break
                            @endswitch
                            <option value="1" {{$ND}}>Não Detectado</option>
                            <option value="2" {{$DB}}>Detectado em Baixa Concentração</option>
                            <option value="3" {{$DM}}>Detectado em Média Concentração</option>
                            <option value="4" {{$DA }}>Detectado em Alta Concentração</option>
                        </select>
                        <!--input type="text" onkeyup="onKeyupSomaCampos('cloroficeas,cianoficeas,diatomaceas,euglenoficeas,total_algas')" name="diatomaceas" id="diatomaceas" class="form-control" value="{{ $planctons['diatomaceas'] }}"-->
                        </div>  
                        <div class="form-group">
                            <label for="euglenoficeas">Euglenoficeas (Cel./ml)</label>
                            <select name="euglenoficeas" class="form-control">
                                @switch($planctons['euglenoficeas'])
                                @case(1)
                                @php
                                    $ND = "selected"; 
                                    $DB = ""; 
                                    $DM = ""; 
                                    $DA = "";  
                                @endphp
                                @break
                                @case(2)
                                    @php
                                    $ND = ""; 
                                    $DB = "selected";
                                    $DM = ""; 
                                    $DA = "";  
                                    @endphp
                                    @break
                                @case(3)
                                    @php
                                    $ND = ""; 
                                    $DB = ""; 
                                    $DM = "selected"; 
                                    $DA = "";  
                                    @endphp
                                    @break
                                @case(4)
                                    @php
                                    $ND = ""; 
                                    $DB = ""; 
                                    $DM = ""; 
                                    $DA = "selected";  
                                    @endphp
                                    @break
                            @endswitch
                            <option value="1" {{$ND}}>Não Detectado</option>
                            <option value="2" {{$DB}}>Detectado em Baixa Concentração</option>
                            <option value="3" {{$DM}}>Detectado em Média Concentração</option>
                            <option value="4" {{$DA }}>Detectado em Alta Concentração</option>
                        </select>
                        <!--input type="text" onkeyup="onKeyupSomaCampos('cloroficeas,cianoficeas,diatomaceas,euglenoficeas,total_algas')" name="euglenoficeas" id="euglenoficeas" class="form-control" value="{{ $planctons['euglenoficeas'] }}"-->
                        </div>  
                        <!--div class="form-group">
                            <label for="descricao">Total de Algas (Cel./ml)</label>
                            <input type="text" name="total_algas" id="total_algas" class="form-control" value="{{ $total_algas }}">
                        </div-->  
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title">Zooplâncton</h3>
                    </div>
                    <div class="box-body">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="protozoarios_ciliados">Protozoarios Ciliados (Ind./ml)</label>
                                <select name="protozoarios_ciliados" class="form-control">
                                    @switch($planctons['protozoarios_ciliados'])
                                    @case(1)
                                    @php
                                        $ND = "selected"; 
                                        $DB = ""; 
                                        $DM = ""; 
                                        $DA = "";  
                                    @endphp
                                    @break
                                    @case(2)
                                        @php
                                        $ND = ""; 
                                        $DB = "selected";
                                        $DM = ""; 
                                        $DA = "";  
                                        @endphp
                                        @break
                                    @case(3)
                                        @php
                                        $ND = ""; 
                                        $DB = ""; 
                                        $DM = "selected"; 
                                        $DA = "";  
                                        @endphp
                                        @break
                                    @case(4)
                                        @php
                                        $ND = ""; 
                                        $DB = ""; 
                                        $DM = ""; 
                                        $DA = "selected";  
                                        @endphp
                                        @break
                                @endswitch
                                <option value="1" {{$ND}}>Não Detectado</option>
                                <option value="2" {{$DB}}>Detectado em Baixa Concentração</option>
                                <option value="3" {{$DM}}>Detectado em Média Concentração</option>
                                <option value="4" {{$DA }}>Detectado em Alta Concentração</option>
                            </select>
                            <!--input type="text" name="protozoarios_ciliados" id="protozoarios_ciliados" class="form-control" value="{{ $planctons['protozoarios_ciliados'] }}"-->
                            </div>  
                            <div class="form-group">
                                <label for="nematoide">Nematóides (Ind/ml)</label>
                                <select name="nematoide" class="form-control">
                                    @switch($planctons['nematoide'])
                                    @case(1)
                                    @php
                                        $ND = "selected"; 
                                        $DB = ""; 
                                        $DM = ""; 
                                        $DA = "";  
                                    @endphp
                                    @break
                                    @case(2)
                                        @php
                                        $ND = ""; 
                                        $DB = "selected";
                                        $DM = ""; 
                                        $DA = "";  
                                        @endphp
                                        @break
                                    @case(3)
                                        @php
                                        $ND = ""; 
                                        $DB = ""; 
                                        $DM = "selected"; 
                                        $DA = "";  
                                        @endphp
                                        @break
                                    @case(4)
                                        @php
                                        $ND = ""; 
                                        $DB = ""; 
                                        $DM = ""; 
                                        $DA = "selected";  
                                        @endphp
                                        @break
                                @endswitch
                                <option value="1" {{$ND}}>Não Detectado</option>
                                <option value="2" {{$DB}}>Detectado em Baixa Concentração</option>
                                <option value="3" {{$DM}}>Detectado em Média Concentração</option>
                                <option value="4" {{$DA }}>Detectado em Alta Concentração</option>
                            </select>
                            <!--input type="text" name="nematoides" id="nematoides" class="form-control" value="{{ $planctons['nematoides'] }}"-->
                            </div>  
                            <div class="form-group">
                                <label for="rotiferos">Rotíferos (Ind./ml)</label>
                                <select name="rotiferos" class="form-control">
                                    @switch($planctons['rotiferos'])
                                    @case(1)
                                    @php
                                        $ND = "selected"; 
                                        $DB = ""; 
                                        $DM = ""; 
                                        $DA = "";  
                                    @endphp
                                    @break
                                    @case(2)
                                        @php
                                        $ND = ""; 
                                        $DB = "selected";
                                        $DM = ""; 
                                        $DA = "";  
                                        @endphp
                                        @break
                                    @case(3)
                                        @php
                                        $ND = ""; 
                                        $DB = ""; 
                                        $DM = "selected"; 
                                        $DA = "";  
                                        @endphp
                                        @break
                                    @case(4)
                                        @php
                                        $ND = ""; 
                                        $DB = ""; 
                                        $DM = ""; 
                                        $DA = "selected";  
                                        @endphp
                                        @break
                                @endswitch
                                <option value="1" {{$ND}}>Não Detectado</option>
                                <option value="2" {{$DB}}>Detectado em Baixa Concentração</option>
                                <option value="3" {{$DM}}>Detectado em Média Concentração</option>
                                <option value="4" {{$DA }}>Detectado em Alta Concentração</option>
                            </select>
                            <!--input type="text" name="rotiferos" id="rotiferos" class="form-control" value="{{ $planctons['rotiferos'] }}"-->
                            </div>  
                            <div class="form-group">
                                <label for="copepodas">Copépodas (Ind./ml)</label>
                                <select name="copepodas" class="form-control">
                                    @switch($planctons['copepodas'])
                                    @case(1)
                                    @php
                                        $ND = "selected"; 
                                        $DB = ""; 
                                        $DM = ""; 
                                        $DA = "";  
                                    @endphp
                                    @break
                                    @case(2)
                                        @php
                                        $ND = ""; 
                                        $DB = "selected";
                                        $DM = ""; 
                                        $DA = "";  
                                        @endphp
                                        @break
                                    @case(3)
                                        @php
                                        $ND = ""; 
                                        $DB = ""; 
                                        $DM = "selected"; 
                                        $DA = "";  
                                        @endphp
                                        @break
                                    @case(4)
                                        @php
                                        $ND = ""; 
                                        $DB = ""; 
                                        $DM = ""; 
                                        $DA = "selected";  
                                        @endphp
                                        @break
                                @endswitch
                                <option value="1" {{$ND}}>Não Detectado</option>
                                <option value="2" {{$DB}}>Detectado em Baixa Concentração</option>
                                <option value="3" {{$DM}}>Detectado em Média Concentração</option>
                                <option value="4" {{$DA }}>Detectado em Alta Concentração</option>
                            </select>
                            <!--<input type="text" name="copepodas" id="copepodas" class="form-control" value="{{ $planctons['copepodas'] }}"-->
                            </div>  
                            <div class="form-group">
                                <label for="dinoflagelados">Dinoflagelados (Ind./ml)</label>
                                <select name="dinoflagelados" class="form-control">
                                    @switch($planctons['dinoflagelados'])
                                    @case(1)
                                    @php
                                        $ND = "selected"; 
                                        $DB = ""; 
                                        $DM = ""; 
                                        $DA = "";  
                                    @endphp
                                    @break
                                    @case(2)
                                        @php
                                        $ND = ""; 
                                        $DB = "selected";
                                        $DM = ""; 
                                        $DA = "";  
                                        @endphp
                                        @break
                                    @case(3)
                                        @php
                                        $ND = ""; 
                                        $DB = ""; 
                                        $DM = "selected"; 
                                        $DA = "";  
                                        @endphp
                                        @break
                                    @case(4)
                                        @php
                                        $ND = ""; 
                                        $DB = ""; 
                                        $DM = ""; 
                                        $DA = "selected";  
                                        @endphp
                                        @break
                                @endswitch
                                <option value="1" {{$ND}}>Não Detectado</option>
                                <option value="2" {{$DB}}>Detectado em Baixa Concentração</option>
                                <option value="3" {{$DM}}>Detectado em Média Concentração</option>
                                <option value="4" {{$DA }}>Detectado em Alta Concentração</option>
                            </select>
                            <!--input type="text" name="dinoflagelados" id="dinoflagelados" class="form-control" value="{{ $planctons['dinoflagelados'] }}"-->
                            </div> 
                            <div class="form-group">
                                <label for="outros">Outros (Ind./ml)</label>
                                <select name="outros" class="form-control">
                                    @switch($planctons['outros'])
                                    @case(1)
                                    @php
                                        $ND = "selected"; 
                                        $DB = ""; 
                                        $DM = ""; 
                                        $DA = "";  
                                    @endphp
                                    @break
                                    @case(2)
                                        @php
                                        $ND = ""; 
                                        $DB = "selected";
                                        $DM = ""; 
                                        $DA = "";  
                                        @endphp
                                        @break
                                    @case(3)
                                        @php
                                        $ND = ""; 
                                        $DB = ""; 
                                        $DM = "selected"; 
                                        $DA = "";  
                                        @endphp
                                        @break
                                    @case(4)
                                        @php
                                        $ND = ""; 
                                        $DB = ""; 
                                        $DM = ""; 
                                        $DA = "selected";  
                                        @endphp
                                        @break
                                @endswitch
                                <option value="1" {{$ND}}>Não Detectado</option>
                                <option value="2" {{$DB}}>Detectado em Baixa Concentração</option>
                                <option value="3" {{$DM}}>Detectado em Média Concentração</option>
                                <option value="4" {{$DA }}>Detectado em Alta Concentração</option>
                            </select>
                            <!--input type="text" name="outros" id="outros" class="form-control" value="{{ $planctons['outros'] }}"-->
                            </div> 
                            <div class="form-group">
                                <label for="descricao">Outros Tratamentos</label>
                                <input type="text" name="outros_tratamentos" id="outros_tratamentos" class="form-control" value="{{ $planctons['outros_tratamentos'] }}">
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Observação</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                                <textarea name="observacao" id="observacao" class="form-control" >{{ $planctons['observacao'] }} </textarea>
                        </div>       
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                <div class="form-group">
                    <button type="submit" id="salvar" class="btn btn-primary">Salvar</button>
                    <a href="{{ route('admin.planctons') }}" class="btn btn-success">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection