@extends('adminlte::page')

@section('title', 'Registro de clientes e fornecedores')

@section('content_header')
<h1>Listagem de clientes e fornecedores</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Clientes e fornecedores</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.clientes_fornecedores.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="id" class="form-control" placeholder="ID">
            <input type="text" name="razao" class="form-control" placeholder="Razao social">
            <input type="text" name="cnpj" class="form-control" placeholder="CNPJ">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.clientes_fornecedores.to_create') }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
					<th style="width:15%;">Nome fantasia</th>
					<th style="width:20%;">Razão social</th>
                    <th>CNPJ</th>
                    <th style="width:25%;">Endereço</th>
                    <th>Tipo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clientes_fornecedores as $cliente_fornecedor)
                    <tr>
                        <td>{{ $cliente_fornecedor->id }}</td>
                        <td>{{ $cliente_fornecedor->nome }}</td>
                        <td>{{ $cliente_fornecedor->razao }}</td>
						<td>{{ $cliente_fornecedor->cnpj }}</td>
                        <td>
                            {{ 
                                $cliente_fornecedor->logradouro . ', ' . 
                                $cliente_fornecedor->numero . ', ' . 
                                $cliente_fornecedor->bairro . ', ' . 
                                $cliente_fornecedor->cidade->nome . '/' . 
                                $cliente_fornecedor->estado->sigla 
                            }}
                        </td>
						<td>{{ $cliente_fornecedor->tipo($cliente_fornecedor->tipo) }}</td>
                        <td>               
                            <a href="{{ route('admin.clientes_fornecedores.to_edit', ['id' => $cliente_fornecedor->id]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-edit" aria-hidden="true"></i> Editar
                            </a>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.clientes_fornecedores.to_remove', ['id' => $cliente_fornecedor->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $clientes_fornecedores->appends($formData)->links() !!}
        @else
            {!! $clientes_fornecedores->links() !!}
        @endif
    </div>
</div>
@endsection