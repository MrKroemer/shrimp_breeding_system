@extends('adminlte::page')
@if ($alt == 0)
    @section('title', 'Registro de Coleta de Parâmetros')
@else
    @section('title', 'Alteração do Registro de Coleta de Parâmetros')
@endif

@section('content_header')
<h1>Registro de amostras</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Coleta de Parâmetros</a></li>
    <li><a href="">Registro de amostras</a></li>
    <li><a href="">{{$alt}}</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')
@php
    $id_tanque = 0;
    $cont = 1;
    $medicao = $coleta->medicao;
@endphp
<div class="box">
    <div class="box-header">
        <div class="row">
            <div class="col-xs-3">
                <label for="data_analise">Data da análise</label>
                <input value="{{ $coleta->data_coleta() }}" name="data_coleta" type="text" class="form-control" placeholder="Data da Coleta" disabled>
            </div>
            <div class="col-xs-3">
                <label for="setor_nome">Setor</label>
                <input value="{{ $coleta->setor->nome }}" name="setor_nome" type="text" class="form-control" placeholder="Setor" disabled>
            </div>
            <div class="col-xs-3">
                <label for="bacteriologia_tipo">Tipo de análise</label>
                <input value="{{ $coleta->coletas_parametros_tipos->descricao }} - ({{ $coleta->coletas_parametros_tipos->sigla }})" name="coletas_parametros_tipo" type="text" class="form-control" placeholder="Tipo de bacteriologia" disabled>
            </div>
            <div class="col-xs-3">
                <label for="bacteriologia_tipo">Medição</label>
                <input value="{{ $medicao }} " name="coletas_parametros_medicao" type="text" class="form-control" placeholder="Tipo de bacteriologia" disabled>
            </div>
        </div>
    </div>
    <div class="box-body">
        <form id="form_parametro_amostra" action="{{ route('admin.coletas_parametros.amostras.to_store', ['id' => $coleta->id]) }}" method="post" enctype="multipart/form-data">
        {!! csrf_field() !!}
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    
                @foreach ($tanques as $tanque)

                @php
                    
                    $nome_campo = "valor_{$tanque->id}";
                    $$nome_campo  = !empty(session($nome_campo)) ? session($nome_campo) : old($nome_campo);
                @endphp
   
                    @if ($alt == 0)
                        <input type="hidden" name="alt" value="{{$alt}}">
                        @php 
                            $medicao =  $coleta->medicao;
                        @endphp
                        <tr>
                            <td>
                                <span class="label label-default" style="font-size: 14px;">{{ $tanque->sigla }}</span>
                            </td>
                            <td>
                                @php
                                    $contador = 0;
                                    $variacao = 0;
                                    $qtd_arr = count($amostras[$tanque->id]);
                                @endphp
                                
                                @if ($qtd_arr != 1)
                                    @forelse ($amostras[$tanque->id] as $amostra)
                                    
                                            @if ($contador == 0)
                                                <div class="row">
                                                <div class="col-xs-3">
                                                    <label for="valor_anterior1{{ $tanque->id }}">{{$amostra->data_coleta('d/m/Y')}} - Medição {{$amostra->medicao}}</label>
                                                    <input name="valor_anterior1{{ $tanque->id }}" value="{{$amostra->cpa_valor}}" min="" max="" type="number" class="form-control" placeholder="Valor" disabled>
                                                    @php
                                                        $data_medicao = $amostra->data_coleta('d/m/Y');
                                                   
                                                   @endphp
                                                </div>
                                                <div class="col-xs-3">
                                                    <label for="{{$nome_campo}}">Valor</label>
                                                    <input name="{{$nome_campo}}" value="{{$$nome_campo}}" min="{{ isset($coleta->coletas_parametros_tipos->minimov) ? (int)  $coleta->coletas_parametros_tipos->minimov : '' }}" max="{{ isset($coleta->coletas_parametros_tipos->maximov) ? (int) $coleta->coletas_parametros_tipos->maximov : ''  }}" {{ $medicao > 1 ? "onkeyup=calVariacao({$tanque->id},{$amostra->cpa_valor})" : '' }} onkeypress="tabenter(event,getElementById('{{ $cont+1 }}'))" id="{{$cont}}" type="number" class="form-control" placeholder="Valor">
                                                </div>
                                            </div>  
                                                @php
                                                    $variacao = $amostra->cpa_valor;
                                                @endphp 
                                            @else
                                                @php
                                                    $variacao = $amostra->cpa_valor - $variacao;
                                                    if ($data_medicao != $amostra->data_coleta('d/m/Y')){
                                                        $variacao = 0;
                                                    }
                                                @endphp 
                                                <div class="row">
                                                    <div class="col-xs-3">
                                                        <label for="valor_anterior2{{ $tanque->id }}">{{$amostra->data_coleta('d/m/Y')}} - Medição {{$amostra->medicao}}</label>
                                                        <input name="valor_anterior2{{ $tanque->id }}" value="{{$amostra->cpa_valor}}" min="" max="" type="number" class="form-control" placeholder="Valor" disabled>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <label for="variacao_{{ $tanque->id }}">Variação</label>
                                                    <input name="variacao_{{ $tanque->id }}" disabled value="{{ $variacao }}" type="number" class="form-control" placeholder="Variação">
                                                    </div>
                                                </div>
                                            @endif
                                            @php
                                                $contador++;

                                            @endphp
                                    @empty
                                    
                                        <div class="row">
                                            <div class="col-xs-3">
                                                Primeira Coleta
                                            </div>
                                            <div class="col-xs-3">
                                                <label for="{{$nome_campo}}">Valor</label>
                                                <input name="{{$nome_campo}}" value="{{$$nome_campo}}" min="{{ isset($coleta->coletas_parametros_tipos->minimov) ? (int)  $coleta->coletas_parametros_tipos->minimov : '' }}" max="{{ isset($coleta->coletas_parametros_tipos->maximov) ? (int) $coleta->coletas_parametros_tipos->maximov : ''  }}" {{ $medicao > 1 ? "onkeyup=calVariacao({$tanque->id},{$amostra->cpa_valor})" : '' }} onkeypress="tabenter(event,getElementById('{{ $cont+1 }}'))" id="{{$cont}}" type="number" class="form-control" placeholder="Valor">
                                            </div>
                                        </div>  
                                    @endforelse
                                
                                @else
                                
                                    @foreach ($amostras[$tanque->id] as $amostra)
                                        @php
                                            $data_medicao = $amostra->data_coleta('d/m/Y');

                                         @endphp   
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <label for="valor_anterior1{{ $tanque->id }}">{{$amostra->data_coleta('d/m/Y')}} - Medição {{$amostra->medicao}}</label>
                                                <input name="valor_anterior1{{ $tanque->id }}" value="{{$amostra->cpa_valor}}" min="" max=""type="number" class="form-control" placeholder="Valor" disabled>
                                            </div>
                                            <div class="col-xs-3">
                                                <label for="{{$nome_campo}}">Valor</label>
                                                <input name="{{$nome_campo}}" value="{{$$nome_campo}}" min="{{ isset($coleta->coletas_parametros_tipos->minimov) ? (int)  $coleta->coletas_parametros_tipos->minimov : '' }}" max="{{ isset($coleta->coletas_parametros_tipos->maximov) ? (int) $coleta->coletas_parametros_tipos->maximov : ''  }}" {{ $medicao > 1 ? "onkeyup=calVariacao({$tanque->id},{$amostra->cpa_valor})" : '' }} onkeypress="tabenter(event,getElementById('{{ $cont+1 }}'))" id="{{$cont}}" type="number" class="form-control" placeholder="Valor">
                                            </div>
                                            <div class="col-xs-3">
                                                <label for="variacao_{{ $tanque->id }}">Variação</label>
                                                <input name="variacao_{{ $tanque->id }}" disabled value="{{ $variacao }}" type="number" class="form-control" placeholder="Variação">
                                            </div>
                                            <div class="col-xs-3">
                                                <label for="zera_valor{{ $tanque->id }}">*Excluir Valor*</label>
                                                <input name="zera_valor{{ $tanque->id }}"  id="zera_valor{{ $tanque->id }}" type="checkbox" class="form-control">
                                            </div>
                                        </div> 
                                    @endforeach   

                                @endif
                                <hr style="margin: 5px 0 5px 0;">
                            </td>
                        </tr>
                        @php $cont++; @endphp
                    @else
                        <input type="hidden" name="alt" value="{{$alt}}">
                        <tr>
                            <td>
                                <span class="label label-default" style="font-size: 14px;">{{ $tanque->sigla }}</span>
                            </td>
                            <td>
                                @foreach ($amostras[$tanque->id] as $amostra)
                                @php
                                    $data_medicao = $amostra->data_coleta('d/m/Y');                                    
                                @endphp   
                                <div class="row">
                                        <div class="col-xs-3">
                                            <label for="{{$nome_campo}}">Valor</label>
                                            <input name="{{$nome_campo}}" value="{{$$nome_campo}}" min="{{ isset($coleta->coletas_parametros_tipos->minimov) ? (int)  $coleta->coletas_parametros_tipos->minimov : '' }}" max="{{ isset($coleta->coletas_parametros_tipos->maximov) ? (int) $coleta->coletas_parametros_tipos->maximov : ''  }}" {{ $medicao > 1 ? "onkeyup=calVariacao({$tanque->id},{$amostra->cpa_valor})" : '' }} onkeypress="tabenter(event,getElementById('{{ $cont+1 }}'))" id="{{$cont}}" type="number" class="form-control" placeholder="Valor">
                                        </div>
                                        <div class="col-xs-3">
                                            <label for="valor_anterior_{{ $tanque->id }}">Valor Anterior</label>
                                            <input name="valor_anterior_{{ $tanque->id }}" disabled id="vaEnabled" value="{{ $amostra->cpa_valor}}" type="text" class="form-control" placeholder="Valor Anterior">
                                        </div>
                                        <div class="col-xs-3">
                                            <label for="zera_valor{{ $tanque->id }}">*Excluir Valor*</label>
                                            <input name="zera_valor{{ $tanque->id }}"  id="zera_valor{{ $tanque->id }}" type="checkbox" class="form-control">
                                        </div>
                                    </div>
                                    <hr style="margin: 5px 0 5px 0;">
                                @endforeach
                            </td>
                        </tr>
                        @php $cont++; @endphp
                    @endif
                
                @endforeach    
                </tbody>
            </table>
            <div style="margin-top: 10px;">
                @if ($alt == 0)
                    <button type="button" onclick="$('#form_parametro_amostra').submit();" class="btn btn-primary">
                        <i class="fa fa-save" aria-hidden="true"></i> Salvar
                    </button>
                @else
                    <button type="button" onclick="$('#form_parametro_amostra').submit();" onmousedown="$('input').prop('disabled', false);" class="btn btn-primary">
                        <i class="fa fa-save" aria-hidden="true"></i> Salvar
                    </button>
                @endif
                <a href="{{ route('admin.coletas_parametros') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection