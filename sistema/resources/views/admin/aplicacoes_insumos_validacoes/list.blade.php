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
        @if ($aplicacao_insumo->situacao == 'N')
            <button type="button" onclick="onActionForRequest('{{ route('admin.aplicacoes_insumos.aplicacoes_insumos_validacoes.to_close', ['aplicacao_insumo_id' => $aplicacao_insumo->id]) }}');" class="btn btn-success">
                <i class="fa fa-check" aria-hidden="true"></i> Validar
            </button>
        @else
            @include('admin.estoque_estornos.justificativas.create', [
                'reverse_route' => route('admin.aplicacoes_insumos.aplicacoes_insumos_validacoes.to_reverse', [
                    'aplicacao_insumo_id' => $aplicacao_insumo->id,
                ]),
                'form_id'   => "form_estorno_aplicacoes_insumos",
                'btn_name'  => 'Estornar',
                'btn_icon'  => 'fa fa-repeat',
                'btn_class' => 'btn btn-warning',
            ])
        @endif
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
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($aplicacao_insumo->aplicacoes_insumos_receitas as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td style="width: 30%;">{{ $item->receita_laboratorial->nome }}</td>
                                <td>
                                @if ($aplicacao_insumo->situacao == 'N')
                                    <form class="form form-inline" action="{{ route('admin.aplicacoes_insumos.aplicacoes_insumos_receitas.to_update', ['aplicacao_insumo_id' => $aplicacao_insumo->id, 'id' => $item->id]) }}" method="POST">
                                        {!! csrf_field() !!}
                                @else
                                    <form class="form form-inline">
                                @endif
                                        <div class="form-group">
                                            <input type="text" name="quantidade" placeholder="Quantidade" class="form-control" style="width:80px;" value="{{ (float) $item->quantidade }}" {{ ($aplicacao_insumo->situacao != 'N') ? 'disabled' : '' }}>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="unidade" placeholder="Unidade" class="form-control" style="width:80px;" value="{{ $item->receita_laboratorial->unidade_medida->sigla }}" disabled></input>
                                        </div>
                                        @if ($aplicacao_insumo->situacao == 'N')
                                            <div class="form-group" style="margin-left: 4%;">
                                                <button type="submit" class="btn btn-primary btn-xs"><i class="fa fa-edit" aria-hidden="true"></i> Alterar</button>
                                            </div>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center;font-style:italic;">Não há registros</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($aplicacao_insumo->aplicacoes_insumos_produtos as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td style="width: 30%;">{{ $item->produto->nome }}</td>
                                <td>
                                @if ($aplicacao_insumo->situacao == 'N')
                                    <form class="form form-inline" action="{{ route('admin.aplicacoes_insumos.aplicacoes_insumos_produtos.to_update', ['aplicacao_insumo_id' => $aplicacao_insumo->id, 'id' => $item->id]) }}" method="POST">
                                        {!! csrf_field() !!}
                                @else
                                    <form class="form form-inline">
                                @endif
                                        <div class="form-group">
                                            <input type="text" name="quantidade" placeholder="Quantidade" class="form-control" style="width:80px;" value="{{ (float) $item->quantidade }}" {{ ($aplicacao_insumo->situacao != 'N') ? 'disabled' : '' }}>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="unidade" placeholder="Unidade" class="form-control" style="width:80px;" value="{{ $item->produto->unidade_saida->sigla }}" disabled></input>
                                        </div>
                                        @if ($aplicacao_insumo->situacao == 'N')
                                            <div class="form-group" style="margin-left: 4%;">
                                                <button type="submit" class="btn btn-primary btn-xs"><i class="fa fa-edit" aria-hidden="true"></i> Alterar</button>
                                            </div>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align:center;font-style:italic;">Não há registros</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
