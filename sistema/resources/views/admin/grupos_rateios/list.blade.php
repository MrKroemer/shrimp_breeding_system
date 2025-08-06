@extends('adminlte::page')

@section('title', 'Registro de grupos de rateios')

@section('content_header')
<h1>Listagem de grupos de rateios</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Grupos de rateios</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.grupos_rateios.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="id" class="form-control" placeholder="ID">
            <input type="text" name="nome" class="form-control" placeholder="Nome do grupo">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.grupos_rateios.to_create') }}" class="btn btn-success">
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
                @foreach($grupos_rateios as $grupo_rateio)
                    <tr>
                        <td>{{ $grupo_rateio->id }}</td>
                        <td>{{ $grupo_rateio->nome }}</td>
                        <td>{{ $grupo_rateio->descricao }}</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Mais opções <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('admin.grupos_rateios.grupos_tanques', ['grupo_rateio_id' => $grupo_rateio->id]) }}"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i> Tanques</a></li>
                                </ul>
                            </div>
                            <a href="{{ route('admin.grupos_rateios.to_edit', ['id' => $grupo_rateio->id]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-edit" aria-hidden="true"></i> Editar
                            </a>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.grupos_rateios.to_remove', ['id' => $grupo_rateio->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $grupos_rateios->appends($formData)->links() !!}
        @else
            {!! $grupos_rateios->links() !!}
        @endif
    </div>
</div>
@endsection