@extends('adminlte::page')
@php
    $data_aplicacao = isset($data_aplicacao) ? $data_aplicacao : date('d/m/Y');
    $data_referencia = \Carbon\Carbon::createFromFormat('d/m/Y', $data_aplicacao)->format('Y-m-d');
@endphp
@section('title', 'Grupos de aplicações de insumos')

@section('content_header')
<h1>Registro de aplicações</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Grupos de aplicações de insumos</a></li>
    <li><a href="">Registro de aplicações</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')
@include('admin.aplicacoes_insumos_grupos.registro_aplicacoes.observacoes.create')

<script>
$(document).ready(function () {

    var checkbox_tanque = $('input[type="checkbox"]');

    checkbox_tanque.each(function () {
        $(this).iCheck({
            checkboxClass: 'icheckbox_line-red',
            radioClass: 'iradio_line-red',
            insert: '<div class="icheck_line-icon"></div><b>' + $(this).attr('placeholder') + '</b>'
        });

        if (this.checked) {
            div_class = $(this).parent().attr('class').replace('red', 'blue');
            $(this).parent().attr('class', div_class);
        }
    });

    checkbox_tanque.on('ifChecked', function () {
        div_class = $(this).parent().attr('class').replace('red', 'blue');
        $(this).parent().attr('class', div_class);
    }).on('ifUnchecked', function () {
        div_class = $(this).parent().attr('class').replace('blue', 'red');
        $(this).parent().attr('class', div_class);
    });

});
</script>

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        <form id="form_aplicacoes_por_data" action="{{ route('admin.aplicacoes_insumos_grupos.registro_aplicacoes.to_view', ['aplicacao_insumo_grupo_id' => $aplicacao_insumo_grupo->id, 'data_aplicacao' => $data_aplicacao]) }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="data_aplicacao">Data da aplicação:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_aplicacao" placeholder="Data da aplicação" class="form-control pull-right" id="date_picker" value="{{ $data_aplicacao }}" onchange="submitForm('form_aplicacoes_por_data');">
                </div>
            </div>
        </form>

        <form action="{{ route('admin.aplicacoes_insumos_grupos.registro_aplicacoes.to_store', ['aplicacao_insumo_grupo_id' => $aplicacao_insumo_grupo->id]) }}" method="POST">
            {!! csrf_field() !!}
            <input type="hidden" name="data_aplicacao" value="{{ $data_aplicacao }}">
            <div class="row">
                <div class="col-sm-12">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#add_receita" data-toggle="tab" aria-expanded="true">Receita laboratorial</a>
                            </li>
                            <li>
                                <a href="#add_produto" data-toggle="tab" aria-expanded="false">Produto avulso</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="add_receita" class="tab-pane active">
                                <div class="form-group">
                                    <label for="receita_laboratorial_id">Receita laboratorial:</label>
                                    <select name="receita_laboratorial_id" class="form-control">
                                        <option value="">..:: Selecione ::..</option>
                                        @foreach($receitas_laboratoriais as $receita_laboratorial)
                                            <option value="{{ $receita_laboratorial->id }}">{{ $receita_laboratorial->nome }} (Und.: {{ $receita_laboratorial->unidade_medida->sigla }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="qtd_receita">Quantidade:</label>
                                    <input type="number" step="any" name="qtd_receita" placeholder="Quantidade" class="form-control">
                                </div>
                            </div>
                            <div id="add_produto" class="tab-pane">
                                <div class="form-group">
                                    <label for="produto_id">Produto avulso:</label>
                                    <select name="produto_id" class="form-control">
                                        <option value="">..:: Selecione ::..</option>
                                        @foreach($produtos as $produto)
                                            <option value="{{ $produto->produto_id }}">{{ $produto->produto_nome }} (Und.: {{ $produto->unidade_saida->sigla }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="qtd_produto">Quantidade:</label>
                                    <input type="number" step="any" name="qtd_produto" placeholder="Quantidade" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <div class="box box-default box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Tanques do grupo {{ $aplicacao_insumo_grupo->nome }}</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                        @forelse ($aplicacao_insumo_grupo->tanques->sortBy('sigla') as $tanque_grupo)
                            @if ($tanque_grupo->tanque->situacao == 'ON')
                            <div class="col-xs-2">
                                <div style="margin: 5px 0 5px 0;">
                                    @php
                                        $dias_cultivo = 'sem ciclo';

                                        $ciclo = $tanque_grupo->tanque->ultimo_ciclo();
                                        
                                        if (! is_null($ciclo) && $ciclo->situacao <> 8) {
                                            $dias_cultivo = $ciclo->dias_cultivo($data_referencia);
                                        }
                                    @endphp
                                    <input type="checkbox" name="tanque_{{ $tanque_grupo->tanque->id }}" placeholder="{{ $tanque_grupo->tanque->sigla }} ({{ $dias_cultivo }})" {{ is_numeric($dias_cultivo) ? 'checked' : ''}}>
                                </div>
                            </div>
                            @endif
                        @empty
                            <div class="col-xs-12" style="font-style:italic; font-weight:bold; color:red; text-align:center;">
                                NÃO HÁ TANQUES
                            </div>
                        @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
            <a href="{{ route('admin.aplicacoes_insumos_grupos') }}" class="btn btn-success">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
            </a>

        </form>
    </div>
</div>

<div class="box box-default box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">Aplicações registradas</h3>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead></thead>
            <tbody>
                @forelse ($aplicacoes_insumos as $aplicacao_insumo)
                    <tr>
                        <td>
                            <span class="label label-default" style="font-size: 14px;">{{ $aplicacao_insumo->tanque->sigla }}</span>
                        </td>
                    @if(
                        $aplicacao_insumo->aplicacoes_insumos_receitas->isNotEmpty() ||
                        $aplicacao_insumo->aplicacoes_insumos_produtos->isNotEmpty()
                    )
                        <td>
                            @foreach ($aplicacao_insumo->aplicacoes_insumos_receitas as $aplicacao_receita)
                                <div class="row">
                                    <div class="col-lg-8">
                                        <label for="receita_nome_{{ $aplicacao_receita->id }}">Receita laboratorial</label>
                                        <input name="receita_nome_{{ $aplicacao_receita->id }}" value="{{ $aplicacao_receita->receita_laboratorial->nome }}" type="text" class="form-control" placeholder="Receita" disabled>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="quantidade_{{ $aplicacao_receita->id }}">Quantidade</label>
                                        <input name="quantidade_{{ $aplicacao_receita->id }}" value="{{ (float) $aplicacao_receita->quantidade }}" type="text" class="form-control" placeholder="Quantidade" disabled>
                                    </div>
                                    <div class="col-lg-2" style="padding-top: 3%">
                                    @if ($aplicacao_insumo->situacao == 'N')
                                    <button type="button" onclick="onActionForRequest('{{ route('admin.aplicacoes_insumos_grupos.registro_aplicacoes.receitas.to_remove', ['aplicacao_insumo_grupo_id' => $aplicacao_insumo_grupo->id, 'id' => $aplicacao_receita->id, 'data_aplicacao' => $data_aplicacao]) }}');" class="btn btn-danger btn-xs">
                                        <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                                    </button>
                                    @else
                                        <span style="font-weight:bold; color:green;">
                                            {{ mb_strtoupper($aplicacao_insumo->situacao($aplicacao_insumo->situacao)) }}
                                        </span>
                                    @endif
                                    </div>
                                </div>
                                <hr style="margin: 5px 0 5px 0;">
                            @endforeach

                            @foreach ($aplicacao_insumo->aplicacoes_insumos_produtos as $aplicacao_produto)
                                <div class="row">
                                    <div class="col-lg-8">
                                        <label for="produto_nome_{{ $aplicacao_produto->id }}">Produto avulso</label>
                                        <input name="produto_nome_{{ $aplicacao_produto->id }}" value="{{ $aplicacao_produto->produto->nome }}" type="text" class="form-control" placeholder="Receita" disabled>
                                    </div>
                                    <div class="col-lg-2">
                                        <label for="quantidade_{{ $aplicacao_produto->id }}">Quantidade</label>
                                        <input name="quantidade_{{ $aplicacao_produto->id }}" value="{{ (float) $aplicacao_produto->quantidade }}" type="text" class="form-control" placeholder="Quantidade" disabled>
                                    </div>
                                    <div class="col-lg-2" style="padding-top: 3%">
                                    @if ($aplicacao_insumo->situacao == 'N')
                                        <button type="button" onclick="onActionForRequest('{{ route('admin.aplicacoes_insumos_grupos.registro_aplicacoes.produtos.to_remove', ['aplicacao_insumo_grupo_id' => $aplicacao_insumo_grupo->id, 'id' => $aplicacao_produto->id, 'data_aplicacao' => $data_aplicacao]) }}');" class="btn btn-danger btn-xs">
                                            <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                                        </button>
                                    @else
                                        <span style="font-weight:bold; color:green;">
                                            {{ mb_strtoupper($aplicacao_insumo->situacao($aplicacao_insumo->situacao)) }}
                                        </span>
                                    @endif
                                    </div>
                                </div>
                                <hr style="margin: 5px 0 5px 0;">
                            @endforeach
                        </td>
                    @else
                        <td>
                            <span style="font-style:italic; font-weight:bold; color:red; margin-right: 10%;">
                                NÃO HÁ PRODUTOS OU RECEITAS REGISTRADAS
                            </span>
                            @if ($aplicacao_insumo->situacao == 'N')
                            <button type="button" onclick="onActionForRequest('{{ route('admin.aplicacoes_insumos_grupos.registro_aplicacoes.to_remove', ['aplicacao_insumo_grupo_id' => $aplicacao_insumo_grupo->id, 'id' => $aplicacao_insumo->id, 'data_aplicacao' => $data_aplicacao]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                            @endif
                        </td>
                    @endif
                        <td>
                            <form action="{{ route('admin.aplicacoes_insumos_grupos.registro_aplicacoes.to_update', ['aplicacao_insumo_grupo_id' => $aplicacao_insumo_grupo->id, 'id' => $aplicacao_insumo->id]) }}" method="POST">
                                {!! csrf_field() !!}
                                <input type="hidden" name="data_aplicacao" value="{{ $data_aplicacao }}">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <textarea rows="2" name="observacoes" placeholder="Observações por tanque" class="form-control" style="width:100%;">{{ $aplicacao_insumo->observacoes }}</textarea>
                                        </div>
                                        <div class="col-md-3" style="padding-top: 4%">
                                            <button type="submit" class="btn btn-primary btn-xs">
                                                <i class="fa fa-save" aria-hidden="true"></i> Salvar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td style="font-style:italic; font-weight:bold; color:red; text-align:center;">
                            NÃO HÁ APLICAÇÕES
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if (! empty($data_aplicacao) && $aplicacoes_insumos->isNotEmpty())
        <form action="{{ route('admin.aplicacoes_insumos_grupos.fichas.to_view') }}" method="POST" target="_blank">
            {!! csrf_field() !!}
            <input type="hidden" name="aplicacao_insumo_grupo_id" value="{{ $aplicacao_insumo_grupo->id }}">
            <input type="hidden" name="data_aplicacao" value="{{ $data_aplicacao }}">
            <a href="{{ route('admin.aplicacoes_insumos_grupos.registro_aplicacoes.ajustes.to_create', ['aplicacao_insumo_grupo_id' => $aplicacao_insumo_grupo->id, 'data_aplicacao' => $data_aplicacao]) }}" class="btn btn-warning">
                <i class="fa fa-check" aria-hidden="true"></i> Ajuste de soluções
            </a>
            <button type="submit" class="btn btn-info">
                <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Gerar fichas
            </button>
            <a class="btn btn-default" data-toggle="modal" data-target="#aplicacoes_insumos_observacoes_modal">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar observações
            </a>
        </form>
        @endif
    </div>
</div>
@endsection
