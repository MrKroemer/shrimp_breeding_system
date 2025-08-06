@extends('adminlte::page')

@section('title', 'Registro de filiais')

@section('content_header')
<h1>Listagem de filiais</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Filiais</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.filiais.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="id" class="form-control" placeholder="ID">
            <input type="text" name="nome" class="form-control" placeholder="Nome da filial">
            <input type="text" name="cnpj" class="form-control" placeholder="CNPJ da filial">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.filiais.to_create') }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Cidade</th>
                    <th>Endereço</th>
                    <th>CNPJ</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($filiais as $filial)
                    <tr>
                        <td>{{ $filial->id }}</td>
                        <td>{{ $filial->nome }}</td>
                        <td>{{ $filial->cidade }}</td>
                        <td>{{ $filial->endereco }}</td>
                        <td>{{ $filial->cnpj }}</td>
                        <td>                
                            <a href="{{ route('admin.filiais.to_edit', ['id' => $filial->id]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-edit" aria-hidden="true"></i> Editar
                            </a>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.filiais.to_remove', ['id' => $filial->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $filiais->appends($formData)->links() !!}
        @else
            {!! $filiais->links() !!}
        @endif
    </div>
</div>
@endsection