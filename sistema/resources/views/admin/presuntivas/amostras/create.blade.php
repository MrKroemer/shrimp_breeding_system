@extends('adminlte::page')

@section('title', 'Registro de Análises Presuntivas')

@section('content_header')
<h1>Cadastro de Análises Presuntivas</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Análises Presuntivas</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<form action="{{ route('admin.presuntivas.amostras.to_store', ['presuntiva_id' => $id]) }}" id="form_presuntiva" method="post" enctype="multipart/form-data" onchange="submitByAjax(this.id);">
    <div class="box">
        <div class="box-header">Cabeçalho</div>
        <div class="box-body">
            {!! csrf_field() !!}
            <div class="container-fluid">    
                <div class="row">
                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                        <div class="form-group">
                            <label for="tanque">Ciclos:</label>
                            <input value="{{ $ciclo->tanque->sigla }} - Ciclo({{$ciclo->numero}})" name="tanque" type="text" class="form-control" placeholder="Tanque" disabled>
                            <input type="hidden" value="{{$ciclo->id}}" name="ciclo_id">
                        </div>
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                        <div class="form-group">
                            <label for="gramatura">Gramatura:</label>
                            <input type="text" name="gramatura" id="gramatura" class="form-control" value="{{ $ciclo->peso_medio() }}" disabled>
                        </div>
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                        <div class="form-group">
                            <label for="dias_cultivo">Dias de Cultivo:</label>
                            <input type="text" name="dias_cultivo" id="dias_cultivo" class="form-control" value="{{ $ciclo->dias_cultivo() }}" disabled>
                        </div>
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                        <div class="form-group">
                            <label for="data_povoamento">Data Povoamento:</label>
                            <input type="text" name="data_povoamento" id="data_povoamento" class="form-control" value="{{ $ciclo->povoamento->data_fim() }}" disabled>
                        </div>       
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                        <div class="form-group">
                            <label for="densidade">Densidade:</label>
                            <input type="text" name="densidade" id="densidade" class="form-control" value="{{ $ciclo->densidade() }}" disabled>
                        </div>
                    </div>
                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                        <div class="form-group">
                            <label for="data_analise">Data de análise:</label>     
                            <input type="text" name="data_analise" id="data_analise" class="form-control" value="{{ $data_analise }}" disabled>
                            <input type="hidden" value="{{ $dataanalise }}" name="dataanalise">
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
                        <h3 class="box-title">Analises Mácroscopica</h3>
                    </div>
                    <div class="box-body">   
                        <div class="form-group">
                            <label for="descricao">Cor do Animal:</label><br />
                            <select name="cor_animal" id="cor_animal" style="width:100%;">
                                <option value="0" {{ empty($presuntivasmacroscopica->cor_animal) ? 'selected' : '' }}>Selecione a Cor</option>
                                <option value="Normal" {{ ! empty($presuntivasmacroscopica->cor_animal) ? ($presuntivasmacroscopica->cor_animal == "Normal") ? 'selected' : '' : '' }}>Normal</option>
                                <option value="Cromatóforos Expandidos" {{ ! empty($presuntivasmacroscopica->cor_animal) ? ($presuntivasmacroscopica->cor_animal == "Cromatóforos Expandidos") ? 'selected' : '' : '' }}>Cromatóforos Expandidos</option>
                                <option value="Vermelho" {{ ! empty($presuntivasmacroscopica->cor_animal) ? ($presuntivasmacroscopica->cor_animal == "Vermelho") ? 'selected' : '' : '' }}>Vermelho</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="descricao">1</label>
                            <input type="checkbox" name="chk_cor_animal_1" id="chk_cor_animal_1">
                            <label for="descricao">2</label>
                            <input type="checkbox" name="chk_cor_animal_2" id="chk_cor_animal_2">
                            <label for="descricao">3</label>
                            <input type="checkbox" name="chk_cor_animal_3" id="chk_cor_animal_3">
                            <label for="descricao">4</label>
                            <input type="checkbox" name="chk_cor_animal_4" id="chk_cor_animal_4">
                            <label for="descricao">5</label>
                            <input type="checkbox" name="chk_cor_animal_5" id="chk_cor_animal_5">
                            <label for="descricao">6</label>
                            <input type="checkbox" name="chk_cor_animal_6" id="chk_cor_animal_6">
                            <label for="descricao">7</label>
                            <input type="checkbox" name="chk_cor_animal_7" id="chk_cor_animal_7">
                            <label for="descricao">8</label>
                            <input type="checkbox" name="chk_cor_animal_8" id="chk_cor_animal_8">
                            <label for="descricao">9</label>
                            <input type="checkbox" name="chk_cor_animal_9" id="chk_cor_animal_9">
                            <label for="descricao">10</label>
                            <input type="checkbox" name="chk_cor_animal_10" id="chk_cor_animal_10"> 
                            <label for="descricao">%</label>
                            <input type="text" name="tt_cor_animal" id="tt_cor_animal" style="position:absolute;float:left;width:10%;" value="{{ isset($presuntivasmacroscopica->total_cor_animal) ? $presuntivasmacroscopica->total_cor_animal : '' }}" disabled>  
                            <input type="hidden" name="total_cor_animal" id="total_cor_animal" value="{{ isset($presuntivasmacroscopica->total_cor_animal) ? $presuntivasmacroscopica->total_cor_animal : '' }}">
                        </div>
                        <div class="form-group">
                            <label for="descricao">Antenas:</label><br />
                            <select name="antenas" id="antenas" onblur="" style="width:100%;">
                                <option value="0" {{ empty($presuntivasmacroscopica->antenas) ? 'selected' : '' }}>Selecione o Tipo</option>
                                <option value="Normal" {{ ! empty($presuntivasmacroscopica->antenas) ? ($presuntivasmacroscopica->antenas) == "Normal" ? 'selected' : '' : '' }}>Normal</option>
                                <option value="Pontos Esbranquiçados" {{ ! empty($presuntivasmacroscopica->antenas) ? ($presuntivasmacroscopica->antenas) == "Pontos Esbranquiçados" ? 'selected' : '' : '' }}>Pontos Esbranquiçados</option>
                                <option value="Rugosa" {{ ! empty($presuntivasmacroscopica->antenas) ? ($presuntivasmacroscopica->antenas) == "Rugosa" ? 'selected' : '' : '' }}>Rugosa</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="descricao">1</label>
                            <input type="checkbox" name="chk_antenas_1" id="chk_antenas_1">
                            <label for="descricao">2</label>
                            <input type="checkbox" name="chk_antenas_2" id="chk_antenas_2">
                            <label for="descricao">3</label>
                            <input type="checkbox" name="chk_antenas_3" id="chk_antenas_3">
                            <label for="descricao">4</label>
                            <input type="checkbox" name="chk_antenas_4" id="chk_antenas_4">
                            <label for="descricao">5</label>
                            <input type="checkbox" name="chk_antenas_5" id="chk_antenas_5">
                            <label for="descricao">6</label>
                            <input type="checkbox" name="chk_antenas_6" id="chk_antenas_6">
                            <label for="descricao">7</label>
                            <input type="checkbox" name="chk_antenas_7" id="chk_antenas_7">
                            <label for="descricao">8</label>
                            <input type="checkbox" name="chk_antenas_8" id="chk_antenas_8">
                            <label for="descricao">9</label>
                            <input type="checkbox" name="chk_antenas_9" id="chk_antenas_9">
                            <label for="descricao">10</label>
                            <input type="checkbox" name="chk_antenas_10" id="chk_antenas_10"> 
                            <label for="descricao">%</label>
                            <input type="text" name="tt_antenas" id="tt_antenas" value="{{ isset($presuntivasmacroscopica->total_antenas) ? $presuntivasmacroscopica->total_antenas : '' }}" style="position: absolute;float:left;width:10%;" disabled>  
                            <input type="hidden" name="total_antenas" id="total_antenas" value="{{ isset($presuntivasmacroscopica->total_antenas) ? $presuntivasmacroscopica->total_antenas : '' }}">
                        </div>
                        <div class="form-group">
                            <label for="descricao">Aparência Musculatura:</label><br />
                            <select name="aparencia_musculatura" id="aparencia_musculatura" onblur="" style="width:100%;">
                                <option value="0" {{ empty($presuntivasmacroscopica->aparencia_musculatura) ? 'selected' : '' }}>Selecione a Aparência</option>
                                <option value="Normal" {{ ! empty($presuntivasmacroscopica->aparencia_musculatura) ? ($presuntivasmacroscopica->aparencia_musculatura) == "Normal" ? 'selected' : '' : '' }}>Normal</option>
                                <option value="Opaca" {{ ! empty($presuntivasmacroscopica->aparencia_musculatura) ? ($presuntivasmacroscopica->aparencia_musculatura) == "Opaca" ? 'selected' : '' : '' }}>Opaca</option>
                                <option value="Flacidez" {{ ! empty($presuntivasmacroscopica->aparencia_musculatura) ? ($presuntivasmacroscopica->aparencia_musculatura) == "Flacidez" ? 'selected' : '' : '' }}>Flacidez</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="descricao">1</label>
                            <input type="checkbox" name="chk_aparencia_musculatura_1" id="chk_aparencia_musculatura_1">
                            <label for="descricao">2</label>
                            <input type="checkbox" name="chk_aparencia_musculatura_2" id="chk_aparencia_musculatura_2">
                            <label for="descricao">3</label>
                            <input type="checkbox" name="chk_aparencia_musculatura_3" id="chk_aparencia_musculatura_3">
                            <label for="descricao">4</label>
                            <input type="checkbox" name="chk_aparencia_musculatura_4" id="chk_aparencia_musculatura_4">
                            <label for="descricao">5</label>
                            <input type="checkbox" name="chk_aparencia_musculatura_5" id="chk_aparencia_musculatura_5">
                            <label for="descricao">6</label>
                            <input type="checkbox" name="chk_aparencia_musculatura_6" id="chk_aparencia_musculatura_6">
                            <label for="descricao">7</label>
                            <input type="checkbox" name="chk_aparencia_musculatura_7" id="chk_aparencia_musculatura_7">
                            <label for="descricao">8</label>
                            <input type="checkbox" name="chk_aparencia_musculatura_8" id="chk_aparencia_musculatura_8">
                            <label for="descricao">9</label>
                            <input type="checkbox" name="chk_aparencia_musculatura_9" id="chk_aparencia_musculatura_9">
                            <label for="descricao">10</label>
                            <input type="checkbox" name="chk_aparencia_musculatura_10" id="chk_aparencia_musculatura_10"> 
                            <label for="descricao">%</label>
                            <input type="text" name="tt_aparencia_musculatura" id="tt_aparencia_musculatura" value="{{ isset($presuntivasmacroscopica->total_aparencia_musculatura) ? $presuntivasmacroscopica->total_aparencia_musculatura : '' }}" style="position: absolute;float:left;width:10%;" disabled>  
                            <input type="hidden" name="total_aparencia_musculatura"  id="total_aparencia_musculatura" value="{{ isset($presuntivasmacroscopica->total_aparencia_musculatura) ? $presuntivasmacroscopica->total_aparencia_musculatura : '' }}">
                        </div>
                        <div class="form-group">
                            <label for="descricao">Aparência da Carapaça:</label><br />
                            <select name="aparencia_carapaca" id="aparencia_carapaca" onblur="" style="width:100%;">
                                <option value="0" {{ empty($presuntivasmacroscopica->aparencia_carapaca) ? 'selected' : '' }}>Selecione a Cor</option>
                                <option value="Normal" {{ ! empty($presuntivasmacroscopica->aparencia_carapaca) ? ($presuntivasmacroscopica->aparencia_carapaca) == "Normal" ? 'selected' : '' : '' }}>Normal</option>
                                <option value="Flacidez" {{ ! empty($presuntivasmacroscopica->aparencia_carapaca) ? ($presuntivasmacroscopica->aparencia_carapaca) == "Flacidez" ? 'selected' : '' : '' }}>Flacidez</option>
                                <option value="Necrose" {{ ! empty($presuntivasmacroscopica->aparencia_carapaca) ? ($presuntivasmacroscopica->aparencia_carapaca) == "Necrose" ? 'selected' : '' : '' }}>Necrose</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="descricao">1</label>
                            <input type="checkbox" name="chk_aparencia_carapaca_1" id="chk_aparencia_carapaca_1">
                            <label for="descricao">2</label>
                            <input type="checkbox" name="chk_aparencia_carapaca_2" id="chk_aparencia_carapaca_2">
                            <label for="descricao">3</label>
                            <input type="checkbox" name="chk_aparencia_carapaca_3" id="chk_aparencia_carapaca_3">
                            <label for="descricao">4</label>
                            <input type="checkbox" name="chk_aparencia_carapaca_4" id="chk_aparencia_carapaca_4">
                            <label for="descricao">5</label>
                            <input type="checkbox" name="chk_aparencia_carapaca_5" id="chk_aparencia_carapaca_5">
                            <label for="descricao">6</label>
                            <input type="checkbox" name="chk_aparencia_carapaca_6" id="chk_aparencia_carapaca_6">
                            <label for="descricao">7</label>
                            <input type="checkbox" name="chk_aparencia_carapaca_7" id="chk_aparencia_carapaca_7">
                            <label for="descricao">8</label>
                            <input type="checkbox" name="chk_aparencia_carapaca_8" id="chk_aparencia_carapaca_8">
                            <label for="descricao">9</label>
                            <input type="checkbox" name="chk_aparencia_carapaca_9" id="chk_aparencia_carapaca_9">
                            <label for="descricao">10</label>
                            <input type="checkbox" name="chk_aparencia_carapaca_10" id="chk_aparencia_carapaca_10"> 
                            <label for="descricao">%</label>
                            <input type="text" name="tt_aparencia_carapaca" id="tt_aparencia_carapaca" value="{{ isset($presuntivasmacroscopica->total_aparencia_carapaca) ? $presuntivasmacroscopica->total_aparencia_carapaca : '' }}" style="position: absolute;float:left;width:10%;" disabled>  
                            <input type="hidden" name="total_aparencia_carapaca" id="total_aparencia_carapaca" value="{{ isset($presuntivasmacroscopica->total_aparencia_carapaca) ? $presuntivasmacroscopica->total_aparencia_carapaca : '' }}">
                        </div>
                        <div class="form-group">
                            <label for="descricao">Edema nos Urópodos:</label><br />
                            <select name="edema_uropodos" id="edema_uropodos" onblur="" style="width:100%;">
                                <option value="0" {{ empty($presuntivasmacroscopica->edema_uropodos) ? 'selected' : '' }}>Selecione uma Opção Cor</option>
                                <option value="Não" {{ ! empty($presuntivasmacroscopica->edema_uropodos) ? ($presuntivasmacroscopica->edema_uropodos) == "Não" ? 'selected' : '' : '' }}>Não</option>
                                <option value="Sim" {{ ! empty($presuntivasmacroscopica->edema_uropodos) ? ($presuntivasmacroscopica->edema_uropodos) == "Sim" ? 'selected' : '' : '' }}>Sim</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="descricao">Inclusão ou reforço de Aditivo da Ração:</label><br />
                            <select name="aditivo" id="aditivo" onblur="" style="width:100%;">
                                <option value="0" {{ empty($presuntivashepatopancreas->aditivo) ? 'selected' : '' }}>Selecione uma Opção Cor</option>
                                <option value="Não" {{ ! empty($presuntivashepatopancreas->aditivo) ? ($presuntivashepatopancreas->aditivo) == 'Não' ? 'selected' : '' : '' }}>Não</option>
                                <option value="Sim" {{ ! empty($presuntivashepatopancreas->aditivo) ? ($presuntivashepatopancreas->aditivo) == 'Sim' ? 'selected' : '' : '' }}>Sim</option>
                            </select>
                            <input type="hidden" name="aditivomsg" id="aditivomsg" value="0" />
                        </div>   
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="box box-danger">
                    <div class="box-header">
                        <h3 class="box-title">Análises Hepatopâncreas</h3>
                    </div>
                    <div class="box-body">
                        <label for="descricao">Lipideos Amostras:</label>  
                        <div class="container-fluid">
                            <div class="row ">
                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                    <label for="descricao">1</label>
                                    <input type="text" class="form-control" style="width:30px;padding:0px" onkeyup="totallipideos();" onblur="checkvalue('lipideos1')" name="lipideos1" id="lipideos1" value="{{ isset($presuntivashepatopancreas->lipideos1) ? $presuntivashepatopancreas->lipideos1 : '' }}" >
                                </div>
                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                    <label for="descricao">2</label>
                                    <input type="text" class="form-control" style="width:30px;padding:0px" onkeyup="totallipideos();" onblur="checkvalue('lipideos2')" name="lipideos2" id="lipideos2" value="{{ isset($presuntivashepatopancreas->lipideos2) ? $presuntivashepatopancreas->lipideos2 : '' }}" >
                                </div>
                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                    <label for="descricao">3</label>
                                    <input type="text" class="form-control" style="width:30px;padding:0px" onkeyup="totallipideos();" onblur="checkvalue('lipideos3')" name="lipideos3" id="lipideos3" value="{{ isset($presuntivashepatopancreas->lipideos3) ? $presuntivashepatopancreas->lipideos3 : '' }}" >
                                </div>
                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                    <label for="descricao">4</label>
                                    <input type="text" class="form-control" style="width:30px;padding:0px" onkeyup="totallipideos();" onblur="checkvalue('lipideos4')" name="lipideos4" id="lipideos4" value="{{ isset($presuntivashepatopancreas->lipideos4) ? $presuntivashepatopancreas->lipideos4 : '' }}" >
                                </div>
                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                    <label for="descricao">5</label>
                                    <input type="text" class="form-control" style="width:30px;padding:0px" onkeyup="totallipideos();" onblur="checkvalue('lipideos5')" name="lipideos5" id="lipideos5" value="{{ isset($presuntivashepatopancreas->lipideos5) ? $presuntivashepatopancreas->lipideos5 : '' }}" >
                                </div>
                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                    <label for="descricao">6</label>
                                    <input type="text" class="form-control" style="width:30px;padding:0px" onkeyup="totallipideos();" onblur="checkvalue('lipideos6')" name="lipideos6" id="lipideos6" value="{{ isset($presuntivashepatopancreas->lipideos6) ? $presuntivashepatopancreas->lipideos6 : '' }}" >
                                </div>
                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                    <label for="descricao">7</label>
                                    <input type="text" class="form-control" style="width:30px;padding:0px" onkeyup="totallipideos();" onblur="checkvalue('lipideos7')" name="lipideos7" id="lipideos7" value="{{ isset($presuntivashepatopancreas->lipideos7) ? $presuntivashepatopancreas->lipideos7 : '' }}" >
                                </div>
                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                    <label for="descricao">8</label>
                                    <input type="text" class="form-control" style="width:30px;padding:0px" onkeyup="totallipideos();" onblur="checkvalue('lipideos8')" name="lipideos8" id="lipideos8" value="{{ isset($presuntivashepatopancreas->lipideos8) ? $presuntivashepatopancreas->lipideos8 : '' }}" >
                                </div>
                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                    <label for="descricao">9</label>
                                    <input type="text" class="form-control" style="width:30px;padding:0px" onkeyup="totallipideos();" onblur="checkvalue('lipideos9')" name="lipideos9" id="lipideos9" value="{{ isset($presuntivashepatopancreas->lipideos9) ? $presuntivashepatopancreas->lipideos9 : '' }}" >
                                </div>
                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                    <label for="descricao">10</label>
                                    <input type="text" class="form-control" style="width:30px;padding:0px" onkeyup="totallipideos();" onblur="checkvalue('lipideos10')" name="lipideos10" id="lipideos10" value="{{ isset($presuntivashepatopancreas->lipideos10) ?  $presuntivashepatopancreas->lipideos10 : '' }}" >
                                </div>
                                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                    <label for="descricao">Média</label>
                                <input type="text" class="form-control" value="{{ ($media != 0) ?  $media : '' }}" name="medialipideos" id="medialipideos" disabled>
                                </div>
                            </div>
                        </div>
                        <label for="descricao">Coloração Associada:</label><br />
                        <div class="form-group">
                            <label for="descricao">1</label>
                            <input type="checkbox" name="chk_coloracao_associada_1" id="chk_coloracao_associada_1">
                            <label for="descricao">2</label>
                            <input type="checkbox" name="chk_coloracao_associada_2" id="chk_coloracao_associada_2">
                            <label for="descricao">3</label>
                            <input type="checkbox" name="chk_coloracao_associada_3" id="chk_coloracao_associada_3">
                            <label for="descricao">4</label>
                            <input type="checkbox" name="chk_coloracao_associada_4" id="chk_coloracao_associada_4">
                            <label for="descricao">5</label>
                            <input type="checkbox" name="chk_coloracao_associada_5" id="chk_coloracao_associada_5">
                            <label for="descricao">6</label>
                            <input type="checkbox" name="chk_coloracao_associada_6" id="chk_coloracao_associada_6">
                            <label for="descricao">7</label>
                            <input type="checkbox" name="chk_coloracao_associada_7" id="chk_coloracao_associada_7">
                            <label for="descricao">8</label>
                            <input type="checkbox" name="chk_coloracao_associada_8" id="chk_coloracao_associada_8">
                            <label for="descricao">9</label>
                            <input type="checkbox" name="chk_coloracao_associada_9" id="chk_coloracao_associada_9">
                            <label for="descricao">10</label>
                            <input type="checkbox" name="chk_coloracao_associada_10" id="chk_coloracao_associada_10"> 
                            <label for="descricao">%</label>
                            <input type="text" name="tt_coloracao_associada" id="tt_coloracao_associada" value="{{ isset($presuntivashepatopancreas->total_coloracao_associada) ? $presuntivashepatopancreas->total_coloracao_associada : '' }}"  style="position: absolute;float:left;width:10%;" disabled>  
                            <input type="hidden" name="total_coloracao_associada" id="total_coloracao_associada" value="{{ isset($presuntivashepatopancreas->total_coloracao_associada) ? $presuntivashepatopancreas->total_coloracao_associada : '' }}">
                        </div>
                        <div class="box-body">
                            <label for="descricao">Danos Tubulos:</label>  
                            <div class="container-fluid">
                                <div class="row ">
                                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                        <label for="descricao">1</label>
                                        <input type="text" class="form-control" style="width:30px;padding:0px" onkeyup="porcentagemdano();" onblur="checkvalue('danos_tubulos1');" name="danos_tubulos1" id="danos_tubulos1" value="{{ isset($presuntivashepatopancreas->danos_tubulos1) ? $presuntivashepatopancreas->danos_tubulos1 : '' }}" >
                                    </div>
                                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                        <label for="descricao">2</label>
                                        <input type="text" class="form-control" style="width:30px;padding:0px" onkeyup="porcentagemdano();" onblur="checkvalue('danos_tubulos2');" name="danos_tubulos2" id="danos_tubulos2" value="{{ isset($presuntivashepatopancreas->danos_tubulos2) ? $presuntivashepatopancreas->danos_tubulos2 : '' }}" >
                                    </div>
                                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                        <label for="descricao">3</label>
                                        <input type="text" class="form-control" style="width:30px;padding:0px" onkeyup="porcentagemdano();" onblur="checkvalue('danos_tubulos3');" name="danos_tubulos3" id="danos_tubulos3" value="{{ isset($presuntivashepatopancreas->danos_tubulos3) ? $presuntivashepatopancreas->danos_tubulos3 : '' }}" >
                                    </div>
                                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                        <label for="descricao">4</label>
                                        <input type="text" class="form-control" style="width:30px;padding:0px" onkeyup="porcentagemdano();" onblur="checkvalue('danos_tubulos4');" name="danos_tubulos4" id="danos_tubulos4" value="{{ isset($presuntivashepatopancreas->danos_tubulos4) ? $presuntivashepatopancreas->danos_tubulos4 : '' }}" >
                                    </div>
                                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                        <label for="descricao">5</label>
                                        <input type="text" class="form-control" style="width:30px;padding:0px" onkeyup="porcentagemdano();" onblur="checkvalue('danos_tubulos5');" name="danos_tubulos5" id="danos_tubulos5" value="{{ isset($presuntivashepatopancreas->danos_tubulos5) ? $presuntivashepatopancreas->danos_tubulos5 : '' }}" >
                                    </div>
                                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                        <label for="descricao">6</label>
                                        <input type="text" class="form-control" style="width:30px;padding:0px" onkeyup="porcentagemdano();" onblur="checkvalue('danos_tubulos6');" name="danos_tubulos6" id="danos_tubulos6" value="{{ isset($presuntivashepatopancreas->danos_tubulos6) ? $presuntivashepatopancreas->danos_tubulos6 : '' }}" >
                                    </div>
                                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                        <label for="descricao">7</label>
                                        <input type="text" class="form-control" style="width:30px;padding:0px" onkeyup="porcentagemdano();" onblur="checkvalue('danos_tubulos7');" name="danos_tubulos7" id="danos_tubulos7" value="{{ isset($presuntivashepatopancreas->danos_tubulos7) ? $presuntivashepatopancreas->danos_tubulos7 : '' }}" >
                                    </div>
                                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                        <label for="descricao">8</label>
                                        <input type="text" class="form-control" style="width:30px;padding:0px" onkeyup="porcentagemdano();" onblur="checkvalue('danos_tubulos8');" name="danos_tubulos8" id="danos_tubulos8" value="{{ isset($presuntivashepatopancreas->danos_tubulos8) ? $presuntivashepatopancreas->danos_tubulos8 : '' }}" >
                                    </div>
                                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                        <label for="descricao">9</label>
                                        <input type="text" class="form-control" style="width:30px;padding:0px" onkeyup="porcentagemdano();" onblur="checkvalue('danos_tubulos9');" name="danos_tubulos9" id="danos_tubulos9" value="{{ isset($presuntivashepatopancreas->danos_tubulos9) ? $presuntivashepatopancreas->danos_tubulos9 : '' }}" >
                                    </div>
                                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                        <label for="descricao">10</label>
                                        <input type="text" class="form-control" style="width:30px;padding:0px" onkeyup="porcentagemdano();" onblur="checkvalue('danos_tubulos10');" name="danos_tubulos10" id="danos_tubulos10" value="{{ isset($presuntivashepatopancreas->danos_tubulos10) ? $presuntivashepatopancreas->danos_tubulos10 : '' }}" >
                                    </div>
                                </div>
                            </div>
                            <div class="container-fluid">
                                <label for="descricao">Danos</label>
                                <div class="row ">
                                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                        <label for="descricao">Normal (%)</label>
                                        <input type="text" class="form-control" name="lipideos_dano_normal" id="lipideos_dano_normal" value="{{ isset($danos_tubulos[0]) ? $danos_tubulos[0] : '' }}" disabled />
                                    </div>
                                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                        <label for="descricao">Gr 1(%)</label>
                                        <input type="text" class="form-control" name="lipideos_dano_grau1" id="lipideos_dano_grau1" value="{{ isset($danos_tubulos[1]) ? $danos_tubulos[1] : '' }}" disabled />
                                    </div>
                                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                        <label for="descricao">Gr 2(%)</label>
                                        <input type="text" class="form-control" name="lipideos_dano_grau2" id="lipideos_dano_grau2" value="{{ isset($danos_tubulos[2]) ? $danos_tubulos[2] : '' }}" disabled />
                                    </div>
                                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                        <label for="descricao">Gr 3(%)</label>
                                        <input type="text" class="form-control" name="lipideos_dano_grau3" id="lipideos_dano_grau3" value="{{ isset($danos_tubulos[3]) ? $danos_tubulos[3] : '' }}" disabled />
                                    </div>
                                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                        <label for="descricao">Gr 4(%)</label>
                                        <input type="text" class="form-control" name="lipideos_dano_grau4" id="lipideos_dano_grau4" value="{{ isset($danos_tubulos[4]) ? $danos_tubulos[4] : '' }}" disabled />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                    <label for="descricao">Qualidade do Lipideo:</label><br />
                                    <select name="qualidade_lipideos" onchange="confereaditivo();" id="qualidade_lipideos" style="width:100%;">
                                        <option value="0" {{ empty($presuntivashepatopancreas->qualidade_lipideos) ? 'selected' : '' }}>Selecione a Cor</option>
                                        <option value="Boa" {{ ! empty($presuntivashepatopancreas->qualidade_lipideos) ? ($presuntivashepatopancreas->qualidade_lipideos) == "Boa" ? 'selected' : '' : '' }}>Boa</option>
                                        <option value="Regular" {{ ! empty($presuntivashepatopancreas->qualidade_lipideos) ? ($presuntivashepatopancreas->qualidade_lipideos) == "Regular" ? 'selected' : '' : '' }}>Regular</option>
                                        <option value="Ruim" {{ ! empty($presuntivashepatopancreas->qualidade_lipideos) ? ($presuntivashepatopancreas->qualidade_lipideos) == "Ruim" ? 'selected' : '' : '' }}>Ruim</option>
                                    </select>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Cristais Nos Opérculos</h3>
                    </div>
                    <div class="box-body">
                        <label for="descricao">Danos:</label>  
                        <div class="container-fluid">
                            <div class="row ">
                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                    <label for="descricao">1</label>
                                <input type="text" class="form-control" style="width:30px;padding:0px" onkeyup="porcentagemdanoO();" onblur="checkvalue('cristais1');" name="cristais1" id="cristais1" value="{{ isset($presuntivasinformacoescomplementares->cristais1) ? $presuntivasinformacoescomplementares->cristais1 : '' }}" >
                                </div>
                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                    <label for="descricao">2</label>
                                    <input type="text" class="form-control" style="width:30px;padding:0px" onkeyup="porcentagemdanoO();" onblur="checkvalue('cristais2');" name="cristais2" id="cristais2" value="{{ isset($presuntivasinformacoescomplementares->cristais2) ? $presuntivasinformacoescomplementares->cristais2 : '' }}" >
                                </div>
                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                    <label for="descricao">3</label>
                                    <input type="text" class="form-control" style="width:30px;padding:0px" onkeyup="porcentagemdanoO();" onblur="checkvalue('cristais3');" name="cristais3" id="cristais3" value="{{ isset($presuntivasinformacoescomplementares->cristais3) ? $presuntivasinformacoescomplementares->cristais3 : '' }}" >
                                </div>
                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                    <label for="descricao">4</label>
                                    <input type="text" class="form-control" style="width:30px;padding:0px" onkeyup="porcentagemdanoO();" onblur="checkvalue('cristais4');" name="cristais4" id="cristais4" value="{{ isset($presuntivasinformacoescomplementares->cristais4) ? $presuntivasinformacoescomplementares->cristais4 : '' }}" >
                                </div>
                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                    <label for="descricao">5</label>
                                    <input type="text" class="form-control" style="width:30px;padding:0px" onkeyup="porcentagemdanoO();" onblur="checkvalue('cristais5');" name="cristais5" id="cristais5" value="{{ isset($presuntivasinformacoescomplementares->cristais5) ? $presuntivasinformacoescomplementares->cristais5 : '' }}" >
                                </div>
                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                    <label for="descricao">6</label>
                                    <input type="text" class="form-control" style="width:30px;padding:0px" onkeyup="porcentagemdanoO();" onblur="checkvalue('cristais6');" name="cristais6" id="cristais6" value="{{ isset($presuntivasinformacoescomplementares->cristais6) ? $presuntivasinformacoescomplementares->cristais6 : '' }}" >
                                </div>
                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                    <label for="descricao">7</label>
                                    <input type="text" class="form-control" style="width:30px;padding:0px" onkeyup="porcentagemdanoO();" onblur="checkvalue('cristais7');" name="cristais7" id="cristais7" value="{{ isset($presuntivasinformacoescomplementares->cristais7) ? $presuntivasinformacoescomplementares->cristais7 : '' }}" >
                                </div>
                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                    <label for="descricao">8</label>
                                    <input type="text" class="form-control" style="width:30px;padding:0px" onkeyup="porcentagemdanoO();" onblur="checkvalue('cristais8');" name="cristais8" id="cristais8" value="{{ isset($presuntivasinformacoescomplementares->cristais8) ? $presuntivasinformacoescomplementares->cristais8 : '' }}" >
                                </div>
                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                    <label for="descricao">9</label>
                                    <input type="text" class="form-control" style="width:30px;padding:0px" onkeyup="porcentagemdanoO();" onblur="checkvalue('cristais9');" name="cristais9" id="cristais9" value="{{ isset($presuntivasinformacoescomplementares->cristais9) ? $presuntivasinformacoescomplementares->cristais9 : '' }}" >
                                </div>
                                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                    <label for="descricao">10</label>
                                    <input type="text" class="form-control" style="width:30px;padding:0px" onkeyup="porcentagemdanoO();" onblur="checkvalue('cristais10');" name="cristais10" id="cristais10" value="{{ isset($presuntivasinformacoescomplementares->cristais10) ? $presuntivasinformacoescomplementares->cristais10 : '' }}" >
                                </div>
                            </div>
                        </div>
                        <div class="container-fluid">
                            <div class="row ">
                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                    <label for="descricao">Normal Gr(%)</label>
                                <input type="text" name="cristais_dano_normal" id="cristais_dano_normal" value="{{ isset($cristais[0]) ? $cristais[0] : '' }}" class="form-control"  disabled>
                                </div>
                                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                    <label for="descricao">Gr 1(%)</label>
                                    <input type="text" name="cristais_dano_grau1" id="cristais_dano_grau1" value="{{ isset($cristais[1]) ? $cristais[1] : '' }}" class="form-control"  disabled>
                                </div>
                                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                    <label for="descricao">Gr 2(%)</label>
                                    <input type="text" name="cristais_dano_grau2" id="cristais_dano_grau2" value="{{ isset($cristais[2]) ? $cristais[2] : '' }}" class="form-control"  disabled>
                                </div>
                                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                    <label for="descricao">Gr 3(%)</label>
                                    <input type="text" name="cristais_dano_grau3" id="cristais_dano_grau3" value="{{ isset($cristais[3]) ? $cristais[3] : '' }}" class="form-control"  disabled>
                                </div>
                                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                    <label for="descricao">Gr 4(%)</label>
                                    <input type="text" name="cristais_dano_grau4" id="cristais_dano_grau4" value="{{ isset($cristais[4]) ? $cristais[4] : '' }}" class="form-control"  disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Observação</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                                <textarea name="observacao" id="observacao" class="form-control" >{{! empty($presuntivasinformacoescomplementares->observacao) ? $presuntivasinformacoescomplementares->observacao : ''}} </textarea>
                        </div>       
                    </div>
                </div>
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Intestinos</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                                <label for="descricao">Ração(%)</label>
                        <input type="text" name="intestino_racao" id="intestino_racao" value="{{! empty($presuntivasinformacoescomplementares->intestino_racao) ? $presuntivasinformacoescomplementares->intestino_racao : '' }}" class="form-control">
                        </div>
                        <div class="form-group">
                                <label for="descricao">Biofloco(%)</label>
                                <input type="text" name="intestino_biofloco" id="intestino_biofloco" value="{{ ! empty($presuntivasinformacoescomplementares->intestino_biofloco) ? $presuntivasinformacoescomplementares->intestino_biofloco : '' }}" class="form-control">
                        </div> 
                        <div class="form-group">
                                <label for="descricao">Algas(%)</label>
                                <input type="text" name="intestino_algas" id="intestino_algas" value="{{ ! empty($presuntivasinformacoescomplementares->intestino_algas) ? $presuntivasinformacoescomplementares->intestino_algas : '' }}" class="form-control">
                        </div> 
                        <div class="form-group">
                                <label for="descricao">Canibalismo(%)</label>
                                <select name="intestino_canibalismo" id="intestino_canibalismo" class="form-control">
									<option value="0" {{ empty($presuntivasinformacoescomplementares->intestino_canibalismo) ? 'selected' : '' }}>Selecione a Aparência</option>
									<option value="Normal" {{ ! empty($presuntivasinformacoescomplementares->intestino_canibalismo) ? ($presuntivasinformacoescomplementares->intestino_canibalismo) == "Normal" ? 'selected' : '' : '' }}>Normal</option>
									<option value="Antena" {{ ! empty($presuntivasinformacoescomplementares->intestino_canibalismo) ? ($presuntivasinformacoescomplementares->intestino_canibalismo) == "Antena" ? 'selected' : '' : '' }}>Antena</option>
                                    <option value="Carapaça" {{ ! empty($presuntivasinformacoescomplementares->intestino_canibalismo) ? ($presuntivasinformacoescomplementares->intestino_canibalismo) == "Carapaça" ? 'selected' : '' : '' }}>Carapaça</option>
									<option value="Carapaça com Musculo" {{ ! empty($presuntivasinformacoescomplementares->intestino_canibalismo) ? ($presuntivasinformacoescomplementares->intestino_canibalismo) == "Carapaça com Musculo" ? 'selected' : '' : '' }}>Carapaça com Musculo</option>
								</select>
                        </div>       
                    </div>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title">Branquias</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="descricao">Epistilis(%)</label>
                            <input type="text" name="epistilis" id="epistilis" class="form-control" value="{{ ! empty($presuntivasbranquias->epistilis) ? $presuntivasbranquias->epistilis : '' }}">
                        </div>  
                        <div class="form-group">
                            <label for="descricao">Zoothamium(%)</label>
                            <input type="text" name="zoothamium" id="zoothamium" class="form-control" value="{{ ! empty($presuntivasbranquias->zoothamium) ? $presuntivasbranquias->zoothamium : '' }}">
                        </div>  
                        <div class="form-group">
                            <label for="descricao">Acinetos sp.(%)</label>
                            <input type="text" name="acinetos" id="acinetos" class="form-control" value="{{ ! empty($presuntivasbranquias->acinetos) ? $presuntivasbranquias->acinetos : '' }}">
                        </div>  
                        <div class="form-group">
                            <label for="descricao">Necroses(%)</label>
                            <input type="text" name="necroses" id="necroses" class="form-control" value="{{ ! empty($presuntivasbranquias->necroses) ? $presuntivasbranquias->necroses : '' }}">
                        </div>  
                        <div class="form-group">
                            <label for="descricao">Melanoses(%)</label>
                            <input type="text" name="melanoses" id="melanoses" class="form-control" value="{{ ! empty($presuntivasbranquias->melanoses) ? $presuntivasbranquias->melanoses : '' }}">
                        </div>  
                        <div class="form-group">
                            <label for="descricao">Deformidade(%)</label>
                            <input type="text" name="deformidade" id="deformidade" class="form-control" value="{{ ! empty($presuntivasbranquias->deformidade) ? $presuntivasbranquias->deformidade : '' }}">
                        </div>
                        <div class="form-group">
                            <label for="descricao">Leucotrix(%)</label>
                            <input type="text" name="leucotrix" id="leucotrix" class="form-control" value="{{ ! empty($presuntivasbranquias->leucotrix) ? $presuntivasbranquias->leucotrix : '' }}">
                        </div> 
                        <div class="form-group">
                            <label for="descricao">BioFloco(%)</label>
                            <input type="text" name="biofloco" id="biofloco" class="form-control" value="{{ ! empty($presuntivasbranquias->biofloco) ? $presuntivasbranquias->biofloco : '' }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title">Hemolinfa</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="descricao">Tempo de Coagulação</label>
                            <input type="text" name="hemolinfa" id="hemolinfa" value="{{ ! empty($presuntivasinformacoescomplementares->hemolinfa) ? $presuntivasinformacoescomplementares->hemolinfa : '' }}">
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
                    <button type="button" onclick="document.getElementById('form_presuntiva').submit(); this.disabled = true" class="btn btn-primary">Salvar</button>
                    <a href="{{ route('admin.presuntivas') }}" class="btn btn-success">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
