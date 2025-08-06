@extends('adminlte::page')

@section('title', 'Registro de aplicações de insumos')

@section('content_header')
<h1>Cadastro de aplicações de insumos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Aplicações de insumos</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')
@include('admin.aplicacoes_insumos_itens.receitas.create')
@include('admin.aplicacoes_insumos_itens.produtos.create')

<div class="box">
    <div class="box-header">
        <a href="{{ route('admin.aplicacoes_insumos.to_search', ['data_aplicacao' => $aplicacao_insumo->data_aplicacao('d/m/Y')]) }}" class="btn btn-default">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
        </a>
    </div>
    <div class="box-body">

        <h5 class="box-title" style="font-weight: bold;">Aplicações do dia <span style="color: red;">{{ $aplicacao_insumo->data_aplicacao('d/m/Y') }}</span> para o tanque <span style="color: red;">{{ $aplicacao_insumo->tanque->sigla }}</span></h5>
        
        <div class="box box-default box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Receitas</h3>
            </div>
            <div class="box-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Receita</th>
                            <th>Quantidade</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($aplicacao_insumo->aplicacoes_insumos_receitas as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td style="width: 30%;">{{ $item->receita_laboratorial->nome }}</td>
                                <td>{{ (float) $item->quantidade }} {{ $item->receita_laboratorial->unidade_medida->sigla }}</td>
                                <td>
                                    <a href="{{ route('admin.aplicacoes_insumos.aplicacoes_insumos_receitas.to_remove', ['aplicacao_insumo_id' => $aplicacao_insumo->id, 'id' => $item->id]) }}" class="btn btn-danger btn-xs">
                                        <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center;font-style:italic;">Não há registros</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <a class="btn btn-xs btn-success" data-toggle="modal" data-target="#aplicacoes_insumos_receitas_modal">
                    <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
                </a>
            </div>
        </div>

        <div class="box box-default box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Produtos</h3>
            </div>
            <div class="box-body">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($aplicacao_insumo->aplicacoes_insumos_produtos as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td style="width: 30%;">{{ $item->produto->nome }}</td>
                                <td>{{ (float) $item->quantidade }} {{ $item->produto->unidade_saida->sigla }}</td>
                                <td>
                                    <a href="{{ route('admin.aplicacoes_insumos.aplicacoes_insumos_produtos.to_remove', ['aplicacao_insumo_id' => $aplicacao_insumo->id, 'id' => $item->id]) }}" class="btn btn-danger btn-xs">
                                        <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center;font-style:italic;">Não há registros</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <a class="btn btn-xs btn-success" data-toggle="modal" data-target="#aplicacoes_insumos_produtos_modal">
                    <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
