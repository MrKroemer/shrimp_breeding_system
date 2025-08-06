@extends('adminlte::page')

@section('title', 'Registro de Reprodutores')

<style type="text/css">
    .a1{
    background: #000000;
    color: #FFFFFF;
    }
    .a2{
    background: #00cc00;
    color: #ffff00;
    }
    .a3{
    background: #00ffff;
    color: #000000;
    }
    .a4{
    background: #ff3300;
    color: #ffffff;
    }
    .a5{
    background: #cc33cc;
    color: #cccc00;
    }
</style>

@section('content_header')
<h1>Cadastro de Reprodutores</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Reprodutores</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')
    <div class="box">
        <div class="box-header">Certificação de Reprodutores</div>
            <div class="box-body">
                <div class="container-fluid">    
                    <div class="row">
                        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                            <div class="form-group">
                                <label for="tanque">Numero Certificacao:</label>
                                <input value="{{ $certificacao_reprodutores->numero_certificacao }}" name="numero_certificacao" type="text" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                            <div class="form-group">
                                <label for="familia">Familia:</label>
                                <input type="text" name="familia" id="familia" class="form-control" value="{{ $certificacao_reprodutores->familia }}" disabled>
                            </div>
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                            <div class="form-group">
                                <label for="plantel">Plantel:</label>
                                <input type="text" name="plantel" id="plantel" class="form-control" value="{{ $certificacao_reprodutores->plantel }}" disabled>
                            </div>
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                            <div class="form-group">
                                <label for="data_povoamento">Data Coleta:</label>
                                <input type="text" name="data_coleta" id="data_coleta" class="form-control" value="{{ $certificacao_reprodutores->data_coleta() }}" disabled>
                            </div>       
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                            <div class="form-group">
                                <label for="tanque_origem">Tanque Origem:</label>
                                <input type="text" name="tanque_origem" id="tanque_origem" class="form-control" value="{{ empty($certificacao_reprodutores->tanque_origem->sigla) ? '' : $certificacao_reprodutores->tanque_origem->sigla  }}" disabled>
                            </div>
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                            <div class="form-group">
                                <label for="tanque_maturacao">Tanque Maturacao:</label>     
                                <input type="text" name="tanque_maturacao" id="tanque_maturacao" class="form-control" value="{{ empty($certificacao_reprodutores->tanque_maturacao->sigla)  ? '' : $certificacao_reprodutores->tanque_maturacao->sigla }}" disabled>                            
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <form  action="{{ route('admin.certificacao_reprodutores.reprodutores.to_search', ['id' => $certificacao_reprodutores->id] ) }}" method="GET" class="form form-inline">
                            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                Filtros:
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                <label for="filtro_sexo">Sexo:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label> 
                                <select style="width: 130px;" name="filtro_sexo" id="filtro_sexo" class="form-control">
                                    <option value="">..Selecione..</option>
                                    <option value="M">Macho</option>
                                    <option value="F">Fêmea</option>
                                </select>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                <label for="filtro_cor_anel">Cor Anel:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label> 
                                <tr>
                                    <td><input type="text" style="width: 130px;" name="filtro_cor_anel" id="filtro_cor_anel" class="form-control" placeholder="Cor Anel"></td>
                                </tr>
                                <!--<select style="width: 130px;" name="filtro_cor_anel" id="filtro_cor_anel" class="form-control">
                                    <option value="">..Selecione..</option>
                                    <option value="Vermelho">Vermelho</option>
                                    <option value="Branco">Branco</option>
                                    <option value="Laranja">Laranja</option>
                                    <option value="Azul">Azul</option>
                                </select>-->
                            </div>
                            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
                                <label for="button_submit">&nbsp;&nbsp;&nbsp;</label> 
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search" aria-hidden="true"></i> Buscar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-body">
            <div class="box box-danger">
                <div class="container-fluid">
                    <div class="row"> 
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="box-header" style="width: 100%;">
                                <h3 class="box-title" style="width: 100%; text-align: center; font-size: larger">Reprodutores Cadastrados</h3>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"> 
                            <table cellspacing="10px" cellpadding="10px"  style="width: 100%;text-align: center;padding: 5px;border: 1px solid #fff;border-spacing: 5px;">
                                <thead>
                                    <tr>
                                        <td>Item/Tubo</td>
                                        <td>Sexo</td>
                                        <td>Numero Anel</td>
                                        <td>Cor do Anel</td>
                                        @foreach ($analises as $analise)
                                            <td>{{$analise->sigla}}</td>
                                        @endforeach  
                                        <td>Ações</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reprodutores as $reprodutor)    
                                    @php
                                        $positivo = $reprodutor->amostras->where('valor','TRUE')->isNotEmpty();

                                    @endphp 
                                    <form id="reprodutores_{{ $reprodutor->id }}" name="reprodutores_{{ $reprodutor->id }}" action="{{ route('admin.certificacao_reprodutores.reprodutores.to_update', ['id' => $reprodutor->id ]) }}" method="POST" class="form form-inline">
                                        {!! csrf_field() !!}
                                        <tr>
                                            <td id="{{$reprodutor->id}}_item_tubo" style="{{ $positivo ? 'color:red' : '' }}" >{{$reprodutor->item_tubo}}</td>
                                            <td id="{{$reprodutor->id}}_sexo" style="{{ $positivo ? 'color:red' : '' }}" >{{ ($reprodutor->sexo == 'F') ? 'Fêmea' : 'Macho'}}</td>
                                            <td id="{{$reprodutor->id}}_numero_anel" style="{{ $positivo ? 'color:red' : '' }}" >{{$reprodutor->numero_anel}}</td>
                                            <td id="{{$reprodutor->id}}_cor_anel" style="{{ $positivo ? 'color:red' : '' }}" >{{$reprodutor->cor_anel}}</td>
                                            @foreach ($analises as $analise)
                                                @php
                                                    $seleciona_reprodutor = $analises_amostras->where('reprodutor_id',$reprodutor->id);
                                                    $seleciona_analise = $seleciona_reprodutor->where('reprodutor_analise_id',$analise->id)->first();
                                                @endphp
                                                <td>
                                                    <select style="width: 130px;" name="{{ $reprodutor->id }}_{{ $analise->id }}" id="{{ $reprodutor->id }}_{{ $analise->id }}" onchange="onChangeMudaStatus({{$reprodutor->id}},this.value)" class="form-control">
                                                        {{ $seleciona_analise['valor'] }}
                                                        <option value="" {{ ($seleciona_analise['valor'] == '') ? 'selected' : '' }}>..Selecione..</option>
                                                        <option value="TRUE"  {{ ($seleciona_analise['valor'] == 'TRUE') ? 'selected' : '' }}>Detectável</option>
                                                        <option value="FALSE" {{ ($seleciona_analise['valor'] == 'FALSE') ? 'selected' : '' }}>N/ Detectável</option> 
                                                    </select>
                                                </td>
                                            @endforeach
                                            <td>
                                                <button type="submit" class="btn btn-success btn-xs" form="reprodutores_{{ $reprodutor->id }}">
                                                    <i class="fa fa-check" aria-hidden="true"></i>
                                                </button> 
                                                <button type="button" onclick="onActionForRequest('{{ route('admin.certificacao_reprodutores.reprodutores.to_remove', ['id' => $reprodutor->id] ) }}');" class="btn btn-danger btn-xs">
                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                </button> 
                                            </td>
                                        </tr>
                                    </form>
                                    @endforeach 
                                    <form action="{{ route('admin.certificacao_reprodutores.reprodutores.to_store', ['id' => $certificacao_reprodutores->id]) }}" name="form_reprodutores" id="form_reprodutores" method="POST">
                                        {!! csrf_field() !!} 
                                        <tr>
                                            <td style="height: 50px;">&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td colspan="8"><h3 style="text-align: center; font-size: larger" class="box-title">Cadastro de Reprodutores</h3></td>
                                        </tr>
                                        <tr>
                                            <td>Item/Tubo</td>
                                            <td>Sexo</td>
                                            <td>Numero Anel</td>
                                            <td>Cor do Anel</td>
                                            <td colspan="2">Observação</td>
                                            <td colspan="2">Ações</td>
                                        </tr>
                                        <tr>
                                            <td><input type="number" style="width: 130px;" name="item_tubo" value="{{$item_tubo + 1}}" id="item_tubo" class="form-control" placeholder="Item/Tubo"></td>
                                            <td>
                                                <select style="width: 130px;" name="sexo" id="sexo" class="form-control">
                                                    <option value="">..Selecione..</option>
                                                    <option value="M">Macho</option>
                                                    <option value="F">Fêmea</option>
                                                </select>
                                            </td>
                                            <td><input type="number" style="width: 130px;" name="numero_anel" value="" id="numero_anel" class="form-control" placeholder="numero_anel"></td>
                                            <!--<td>
                                                <select style="width: 130px;" name="cor_anel" id="cor_anel" class="form-control">
                                                    <option value="">..Selecione..</option>
                                                    <option value="Vermelho">Vermelho</option>
                                                    <option value="Branco">Branco</option>
                                                    <option value="Laranja">Laranja</option>
                                                    <option value="Azul">Azul</option>
                                                </select>
                                            </td>-->
                                            <td colspan="2"><input type="text" style="width:130px;" name="cor_anel" id="cor_anel" class="form-control" placeholder="Cor Anel"></td>
                                            <td colspan="2"><input type="text" style="width: 300px;" name="observacao" value="" id="observacao" class="form-control" placeholder="Observação"></td>
                                            <td colspan="2">
                                                <button type="submit" class="btn btn-primary">Salvar</button>
                                                <a href="{{ route('admin.certificacao_reprodutores') }}" class="btn btn-success">
                                                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar
                                                </a>    
                                            </td>
                                        </tr>
                                    </form>
                                </tbody>
                            </table>
                        </div>            
                    </div>  
                </div>
            </div>
        </div>  
    </div>                    
</form>
@endsection