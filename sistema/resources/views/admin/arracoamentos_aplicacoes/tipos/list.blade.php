@extends('adminlte::page')

@section('title', 'Registro de tipos de aplicações de arraçoamentos')

@section('content_header')
<h1>Listagem de tipos de aplicações de arraçoamentos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Tipos de aplicações de arraçoamentos</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.arracoamentos_aplicacoes_tipos.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="id" class="form-control" placeholder="ID">
            <input type="text" name="nome" class="form-control" placeholder="Nome">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.arracoamentos_aplicacoes_tipos.to_create') }}" class="btn btn-success">
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
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($arracoamentos_aplicacoes_tipos as $arracoamento_aplicacao)
                    <tr>
                        <td>{{ $arracoamento_aplicacao->id }}</td>
                        <td>{{ $arracoamento_aplicacao->nome }}</td>
                        <td>
                            <a href="{{ route('admin.arracoamentos_aplicacoes_tipos.to_edit', ['id' => $arracoamento_aplicacao->id]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-edit" aria-hidden="true"></i> Editar
                            </a>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.arracoamentos_aplicacoes_tipos.to_remove', ['id' => $arracoamento_aplicacao->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $arracoamentos_aplicacoes_tipos->appends($formData)->links() !!}
        @else
            {!! $arracoamentos_aplicacoes_tipos->links() !!}
        @endif
    </div>
</div>
@endsection