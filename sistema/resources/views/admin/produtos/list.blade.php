@extends('adminlte::page')

@section('title', 'Registro de produtos')

@section('content_header')
<h1>Listagem de produtos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Produtos</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.produtos.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="id" class="form-control" placeholder="ID ou cód. externo">
            <input type="text" name="nome" class="form-control" placeholder="Nome ou sigla">
            <select name="produto_tipo_id" class="form-control">
                <option value="">..:: Selecione ::..</option>
                @foreach($produtos_tipos as $produto_tipo)
                    <option value="{{ $produto_tipo->id }}">{{ $produto_tipo->nome }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.produtos.to_create') }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cód. externo</th>
                    <th>Nome</th>
                    <th>Sigla</th>
                    <th>Und. de entrada</th>
                    <th>Und. de saida</th>
                    <th>Tipo</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produtos as $produto)
                    <tr>
                        <td>{{ $produto->id }}</td>
                        <td>{{ $produto->codigo_externo ?: 'n/a' }}</td>
                        <td>{{ $produto->nome }}</td>
                        <td>{{ $produto->sigla }}</td>
                        <td>Cada ({{ $produto->unidade_entrada->sigla }})</td>
                        <td>Possui {{ $produto->unidade_razao }} ({{ $produto->unidade_saida->sigla }})</td>
                        <td>{{ $produto->produto_tipo->nome }}</td>
                        <td>
                            @php
                                if ($produto->situacao == 'ON') {
                                    $btnColor = 'success';
                                    $btnTitle = 'Ativo';
                                } else {
                                    $btnColor = 'default';
                                    $btnTitle = 'Inativo';
                                }
                            @endphp
                            <a href="{{ route('admin.produtos.to_turn', ['id' => $produto->id]) }}" class="btn btn-{{ $btnColor }} btn-xs">
                                <i class="fa fa-power-off" aria-hidden="true"></i> {{ $btnTitle }}
                            </a>
                        </td>
                        <td>              
                            <a href="{{ route('admin.produtos.to_edit', ['id' => $produto->id]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-edit" aria-hidden="true"></i> Editar
                            </a>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.produtos.to_remove', ['id' => $produto->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $produtos->appends($formData)->links() !!}
        @else
            {!! $produtos->links() !!}
        @endif
    </div>
</div>
@endsection