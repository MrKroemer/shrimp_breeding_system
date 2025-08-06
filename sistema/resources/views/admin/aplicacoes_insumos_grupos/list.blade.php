@extends('adminlte::page')

@section('title', 'Grupos de aplicações de insumos')

@section('content_header')
<h1>Grupos de aplicações de insumos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Grupos de aplicações de insumos</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.aplicacoes_insumos_grupos.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <div class="form-group">
                <input type="text" name="nome" class="form-control" placeholder="Nome do grupo">
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.aplicacoes_insumos_grupos.to_create') }}" class="btn btn-success">
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
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($aplicacoes_insumos_grupos as $aplicacao_insumo_grupo)
                    <tr>
                        <td>{{ $aplicacao_insumo_grupo->id }}</td>
                        <td>{{ $aplicacao_insumo_grupo->nome }}</td>
                        <td>{{ $aplicacao_insumo_grupo->descricao }}</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Mais opções <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('admin.aplicacoes_insumos_grupos.registro_aplicacoes.to_view', ['aplicacao_insumo_grupo_id' => $aplicacao_insumo_grupo->id]) }}">
                                            <i class="fa fa-chevron-circle-right" aria-hidden="true"></i> Registro de aplicações de insumos
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <a href="{{ route('admin.aplicacoes_insumos_grupos.to_edit', ['id' => $aplicacao_insumo_grupo->id]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-edit" aria-hidden="true"></i> Editar
                            </a>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.aplicacoes_insumos_grupos.to_remove', ['id' => $aplicacao_insumo_grupo->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection