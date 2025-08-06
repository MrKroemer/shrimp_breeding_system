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

<form action="{{ route('admin.planctons.to_store')}}" id="form_planctons" method="post" enctype="multipart/form-data">
    <div class="box">
        <div class="box-header">Cabeçalho</div>
        <div class="box-body">
            {!! csrf_field() !!}
            @php
                $data_analise  = !empty(session('data_analise'))  ? session('data_analise')  : old('data_analise');
                $ciclo_id      = !empty(session('ciclo_id'))      ? session('ciclo_id')      : old('ciclo_id');
            @endphp
            <div class="container-fluid">    
                <div class="row">
                    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="ciclo_id">Ciclo:</label>
                            <select name="ciclo_id" class="form-control">
                                <option value="">..:: Selecione ::..</option>
                                @foreach($ciclos as $ciclo)
                                    <option value="{{ $ciclo->ciclo_id }}" {{ ($ciclo->ciclo_id == $ciclo_id) ? 'selected' : '' }}>{{ $ciclo->tanque_sigla }} ( Ciclo Nº {{ $ciclo->ciclo_numero }} )</option>
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
                                <input type="text" name="data" placeholder="Data da análise" class="form-control pull-right" id="date_picker" value="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                                <option value="1" >Não Detectado</option>
                                <option value="2" >Detectado em Baixa Concentração</option>
                                <option value="3" >Detectado em Média Concentração</option>
                                <option value="4" >Detectado em Alta Concentração</option>
                            </select>
                            <!--input type="text" onkeyup="onKeyupSomaCampos('cloroficeas,cianoficeas,diatomaceas,euglenoficeas,total_algas')" name="cloroficeas" class="form-control" value=""--->
                        </div>  
                        <div class="form-group">
                            <label for="cianoficeas">Cianoficeas (Cel./ml)</label>
                            <select name="cianoficeas" class="form-control">
                                <option value="1" >Não Detectado</option>
                                <option value="2" >Detectado em Baixa Concentração</option>
                                <option value="3" >Detectado em Média Concentração</option>
                                <option value="4" >Detectado em Alta Concentração</option>
                            </select>
                            <!--input type="text" onkeyup="onKeyupSomaCampos('cloroficeas,cianoficeas,diatomaceas,euglenoficeas,total_algas')" name="cianoficeas" class="form-control" value=""--->
                        </div>  
                        <div class="form-group">
                            <label for="diatomaceas">Diatomaceas (Cel./ml)</label>
                            <select name="diatomaceas" class="form-control">
                                <option value="1" >Não Detectado</option>
                                <option value="2" >Detectado em Baixa Concentração</option>
                                <option value="3" >Detectado em Média Concentração</option>
                                <option value="4" >Detectado em Alta Concentração</option>
                            </select>
                            <!--input type="text" onkeyup="onKeyupSomaCampos('cloroficeas,cianoficeas,diatomaceas,euglenoficeas,total_algas')" name="diatomaceas" class="form-control" value=""-->
                        </div>  
                        <div class="form-group">
                            <label for="euglenoficeas">Euglenoficeas (Cel./ml)</label>
                            <select name="euglenoficeas" class="form-control">
                                <option value="1" >Não Detectado</option>
                                <option value="2" >Detectado em Baixa Concentração</option>
                                <option value="3" >Detectado em Média Concentração</option>
                                <option value="4" >Detectado em Alta Concentração</option>
                            </select>
                            <!--input type="text" onkeyup="onKeyupSomaCampos('cloroficeas,cianoficeas,diatomaceas,euglenoficeas,total_algas')" name="euglenoficeas" class="form-control" value=""-->
                        </div>  
                        <!--div class="form-group">
                            <label for="descricao">Total de Algas (Cel./ml)</label>
                            <select name="euglenoficeas" class="form-control">
                                <option value="">..:: Selecione ::..</option>
                                <option value="1" >Não Detectado</option>
                                <option value="2" >Detectado em Baixa Concentração</option>
                                <option value="3" >Detectado em Média Concentração</option>
                                <option value="4" >Detectado em Alta Concentração</option>
                            </select>
                            <input type="text" name="total_algas" id="total_algas" class="form-control" value="" disabled>
                        </div--->  
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
                                    <option value="1" >Não Detectado</option>
                                    <option value="2" >Detectado em Baixa Concentração</option>
                                    <option value="3" >Detectado em Média Concentração</option>
                                    <option value="4" >Detectado em Alta Concentração</option>
                                </select>
                                <!--input type="text" name="protozoarios_ciliados" id="protozoarios_ciliados" class="form-control" value=""-->
                            </div>  
                            <div class="form-group">
                                <label for="nematoides">Nematóides (Ind/ml)</label>
                                <select name="nematoides" class="form-control">
                                    <option value="1" >Não Detectado</option>
                                    <option value="2" >Detectado em Baixa Concentração</option>
                                    <option value="3" >Detectado em Média Concentração</option>
                                    <option value="4" >Detectado em Alta Concentração</option>
                                </select>
                                <!--input type="text" name="nematoides" id="nematoides" class="form-control" value=""-->
                            </div>  
                            <div class="form-group">
                                <label for="rotiferos">Rotíferos (Ind./ml)</label>
                                <select name="rotiferos" class="form-control">
                                    <option value="1" >Não Detectado</option>
                                    <option value="2" >Detectado em Baixa Concentração</option>
                                    <option value="3" >Detectado em Média Concentração</option>
                                    <option value="4" >Detectado em Alta Concentração</option>
                                </select>
                                <!--input type="text" name="rotiferos" id="rotiferos" class="form-control" value=""-->
                            </div>  
                            <div class="form-group">
                                <label for="copepodas">Copépodas (Ind./ml)</label>
                                <select name="copepodas" class="form-control">
                                    <option value="1" >Não Detectado</option>
                                    <option value="2" >Detectado em Baixa Concentração</option>
                                    <option value="3" >Detectado em Média Concentração</option>
                                    <option value="4" >Detectado em Alta Concentração</option>
                                </select>
                                <!--input type="text" name="copepodas" id="copepodas" class="form-control" value=""-->
                            </div>  
                            <div class="form-group">
                                <label for="dinoflagelados">Dinoflagelados (Ind./ml)</label>
                                <select name="dinoflagelados" class="form-control">
                                    <option value="1" >Não Detectado</option>
                                    <option value="2" >Detectado em Baixa Concentração</option>
                                    <option value="3" >Detectado em Média Concentração</option>
                                    <option value="4" >Detectado em Alta Concentração</option>
                                </select>
                                <!--input type="text" name="dinoflagelados" id="dinoflagelados" class="form-control" value=""-->
                            </div> 
                            <div class="form-group">
                                <label for="outros">Outros (Ind./ml)</label>
                                <select name="outros" class="form-control">
                                    <option value="1" >Não Detectado</option>
                                    <option value="2" >Detectado em Baixa Concentração</option>
                                    <option value="3" >Detectado em Média Concentração</option>
                                    <option value="4" >Detectado em Alta Concentração</option>
                                </select>
                                <!--input type="text" name="outros" id="outros" class="form-control" value=""-->
                            </div> 
                            <div class="form-group">
                                <label for="outros_tratamentos">Outros Tratamentos</label>
                                <input type="text" name="outros_tratamentos" id="outros_tratamentos" class="form-control" value=""-->
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
                                <textarea name="observacao" id="observacao" class="form-control" > </textarea>
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