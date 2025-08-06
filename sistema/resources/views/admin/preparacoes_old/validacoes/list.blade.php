@extends('adminlte::page')

@section('title', 'Registro de preparaçoes de cultivos')

@section('content_header')
<h1>Listagem de aplicações</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Preparações de cultivos</a></li>
    <li><a href="">Aplicações</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        @if ($preparacao->situacao == 'N')
            <button type="button" onclick="onActionForRequest('{{ route('admin.preparacoes_v2.validacoes.to_close', ['preparacao_id' => $preparacao->id]) }}');" class="btn btn-success">
                <i class="fa fa-check" aria-hidden="true"></i> Validar
            </button>
        @else
            @include('admin.estoque_estornos.justificativas.create', [
                'reverse_route' => route('admin.preparacoes_v2.validacoes.to_reverse', [
                    'preparacao_id' => $preparacao->id,
                ]),
                'form_id'   => "form_estorno_preparacoes_v2",
                'btn_name'  => 'Estornar',
                'btn_icon'  => 'fa fa-repeat',
                'btn_class' => 'btn btn-warning',
            ])
        @endif

        <a href="{{ route('admin.preparacoes_v2.to_search', ['ciclo_id' => $preparacao->ciclo_id]) }}" class="btn btn-default">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
        </a>
    </div>
    <div class="box-body">
        
        <h5 class="box-title" style="font-weight: bold;">
            Cultivo: {{ $preparacao->ciclo->tanque->sigla }} ( Ciclo Nº <span style="color: red;">{{ $preparacao->ciclo->numero }}</span> iniciado em <span style="color: red;">{{ $preparacao->ciclo->data_inicio() }}</span> )
            [<span style="font-weight:bold;{{ ($preparacao->situacao == 'N') ? 'color:red;' : 'color:green;' }}"> {{ mb_strtoupper($preparacao->situacao($preparacao->situacao)) }} </span>]
        </h5>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data de aplicação</th>
                    <th>Tipo de preparação</th>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($preparacao->aplicacoes->sortBy('id') as $aplicacao)
                    <tr>
                    @if ($preparacao->situacao == 'N')
                        <form class="form form-inline" action="{{ route('admin.preparacoes_v2.aplicacoes.to_update',  ['preparacao_id' => $preparacao->id, 'id' => $aplicacao->id]) }}" method="POST">
                            {!! csrf_field() !!}
                    @else
                        <form class="form form-inline">
                    @endif
                            <td>{{ $aplicacao->id }}</td>
                            <td>{{ $aplicacao->data_aplicacao() }}</td>
                            <td>
                                <select name="preparacao_tipo_id" class="form-control" {{ ($preparacao->situacao != 'N') ? 'disabled' : '' }}>
                                    <option value="">..:: Selecione ::..</option>
                                    @foreach($preparacoes_tipos as $preparacao_tipo)
                                        <option value="{{ $preparacao_tipo->id }}" {{ ($preparacao_tipo->id == $aplicacao->preparacao_tipo_id) ? 'selected' : '' }}>{{ $preparacao_tipo->nome }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="produto_id" class="form-control" {{ ($preparacao->situacao != 'N') ? 'disabled' : '' }}>
                                    <option value="">..:: Selecione ::..</option>
                                    @foreach($produtos as $produto)
                                        <option value="{{ $produto->produto_id }}" {{ ($produto->produto_id == $aplicacao->produto_id) ? 'selected' : '' }}>{{ $produto->produto_nome }} (Und.: {{ $produto->unidade_saida->sigla }})</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" name="quantidade" placeholder="Quantidade" class="form-control" value="{{ (float) $aplicacao->quantidade }}" {{ ($preparacao->situacao != 'N') ? 'disabled' : '' }}>
                            </td>
                            <td>
                                @if ($preparacao->situacao == 'N')
                                    <button type="submit" class="btn btn-primary btn-xs">
                                        <i class="fa fa-edit" aria-hidden="true"></i> Alterar
                                    </button>
                                @else
                                    <button class="btn btn-primary btn-xs" disabled>
                                        <i class="fa fa-edit" aria-hidden="true"></i> Alterar
                                    </button>
                                @endif
                            </td>
                        </form>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection