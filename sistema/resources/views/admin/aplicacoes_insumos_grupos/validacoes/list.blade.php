@extends('adminlte::page')

@section('title', 'Registro de aplicações de insumos')

@section('content_header')
<h1>Validações de aplicações de insumos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Aplicações de insumos</a></li>
    <li><a href="">Validações</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <div class="row">
            @php
                $data_aplicacao = isset($data_aplicacao) ? $data_aplicacao : date('d/m/Y');
            @endphp
            <form id="form_aplicacoes_insumos_validacoes" action="{{ route('admin.aplicacoes_insumos_validacoes', ['data_aplicacao' => $data_aplicacao]) }}" method="POST">
                {!! csrf_field() !!}
                <div class="col-lg-3">
                    <label for="data_aplicacao">Data das aplicações</label>
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                        </div>
                        <input type="text" id="date_picker" name="data_aplicacao" placeholder="Data das aplicações" class="form-control pull-right" value="{{ $data_aplicacao }}" onchange="submitForm('form_aplicacoes_insumos_validacoes');">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
        @forelse ($aplicacoes_insumos as $aplicacao_insumo)
            <tr>
                <td>
                    <span class="label label-default" style="font-size: 14px;">
                        {{ $aplicacao_insumo->tanque->sigla }}
                    </span>
                </td>
            @if(
                $aplicacao_insumo->aplicacoes_insumos_receitas->isNotEmpty() ||
                $aplicacao_insumo->aplicacoes_insumos_produtos->isNotEmpty()
            )
                <td>
                    @foreach ($aplicacao_insumo->aplicacoes_insumos_receitas as $aplicacao_receita)
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="receita_nome_{{ $aplicacao_receita->id }}">Receita laboratorial</label>
                                <input name="receita_nome_{{ $aplicacao_receita->id }}" value="{{ $aplicacao_receita->receita_laboratorial->nome }}" type="text" class="form-control" placeholder="Receita" disabled>
                            </div>
                            <div class="col-lg-2">
                                <label for="quantidade_{{ $aplicacao_receita->id }}">Quantidade</label>
                                <input name="quantidade_{{ $aplicacao_receita->id }}" value="{{ (float) $aplicacao_receita->quantidade }}" type="text" class="form-control" placeholder="Quantidade" disabled>
                            </div>
                        </div>
                        <hr style="margin: 5px 0 5px 0;">
                    @endforeach

                    @foreach ($aplicacao_insumo->aplicacoes_insumos_produtos as $aplicacao_produto)
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="produto_nome_{{ $aplicacao_produto->id }}">Produto avulso</label>
                                <input name="produto_nome_{{ $aplicacao_produto->id }}" value="{{ $aplicacao_produto->produto->nome }}" type="text" class="form-control" placeholder="Receita" disabled>
                            </div>
                            <div class="col-lg-2">
                                <label for="quantidade_{{ $aplicacao_produto->id }}">Quantidade</label>
                                <input name="quantidade_{{ $aplicacao_produto->id }}" value="{{ (float) $aplicacao_produto->quantidade }}" type="text" class="form-control" placeholder="Quantidade" disabled>
                            </div>
                        </div>
                        <hr style="margin: 5px 0 5px 0;">
                    @endforeach
                    <span style="float:left;">
                        @if ($aplicacao_insumo->situacao == 'B')
                            <button type="button" class="btn btn-danger btn-xs" disabled>
                                <i class="fa fa-ban" aria-hidden="true"></i> Bloqueado
                            </button>
                        @elseif ($aplicacao_insumo->situacao == 'N')
                            <button type="button" onclick="onActionForRequest('{{ route('admin.aplicacoes_insumos_validacoes.to_close', ['aplicacao_insumo_id' => $aplicacao_insumo->id]) }}');" class="btn btn-success btn-xs locked">
                                <i class="fa fa-check" aria-hidden="true"></i> Validar
                            </button>
                        @else
                            @include('admin.estoque_estornos.justificativas.create', [
                                'reverse_route' => route('admin.aplicacoes_insumos_validacoes.to_reverse', [
                                    'aplicacao_insumo_id'       => $aplicacao_insumo->id,
                                ]),
                                'form_id'   => "form_estorno_aplicacao_insumo_{$aplicacao_insumo->id}",
                                'btn_name'  => 'Estornar',
                                'btn_icon'  => 'fa fa-repeat',
                                'btn_class' => 'btn btn-warning btn-xs',
                            ])
                        @endif
                    </span>
                </td>
            @else
                <td>
                    <span style="font-style:italic; font-weight:bold; color:red; margin-right: 10%;">
                        NÃO HÁ PRODUTOS OU RECEITAS REGISTRADAS
                    </span>
                </td>
            @endif
            </tr>
        @empty
            <tr>
                <td style="font-style:italic; font-weight:bold; color:red; text-align:center;">
                    NÃO HÁ APLICAÇÕES
                </td>
            </tr>
        @endforelse
        </table>
    </div>
</div>
@endsection
