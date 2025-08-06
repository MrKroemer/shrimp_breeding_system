@extends('adminlte::page')

@section('title', 'Registro de perfis de arraçoamentos')

@section('content_header')
<h1>Listagem de perfis de arraçoamentos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Perfis de arraçoamentos</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.arracoamentos_perfis.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="id" class="form-control" placeholder="ID">
            <input type="text" name="nome" class="form-control" placeholder="Nome">
            <input type="text" name="descricao" class="form-control" placeholder="Descrição">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.arracoamentos_perfis.to_create') }}" class="btn btn-success">
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
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($arracoamentos_perfis as $arracoamento_perfil)
                    <tr>
                        <td>{{ $arracoamento_perfil->id }}</td>
                        <td>{{ $arracoamento_perfil->nome }}</td>
                        <td>{{ $arracoamento_perfil->descricao }}</td>
                        <td>
                            @php
                                if ($arracoamento_perfil->situacao == 'ON') {
                                    $btnColor = 'success';
                                    $btnTitle = 'Ativo';
                                } else {
                                    $btnColor = 'default';
                                    $btnTitle = 'Inativo';
                                }
                            @endphp
                            <a href="{{ route('admin.arracoamentos_perfis.to_turn', ['id' => $arracoamento_perfil->id]) }}" class="btn btn-{{ $btnColor }} btn-xs">
                                <i class="fa fa-power-off" aria-hidden="true"></i> {{ $btnTitle }}
                            </a>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Mais opções <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('admin.arracoamentos_perfis.arracoamentos_esquemas', ['arracoamento_perfil_id' => $arracoamento_perfil->id]) }}"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i> Esquema de alimentação</a></li>
                                </ul>
                            </div>                 
                            <a href="{{ route('admin.arracoamentos_perfis.to_edit', ['id' => $arracoamento_perfil->id]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-edit" aria-hidden="true"></i> Editar
                            </a>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.arracoamentos_perfis.to_remove', ['id' => $arracoamento_perfil->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $arracoamentos_perfis->appends($formData)->links() !!}
        @else
            {!! $arracoamentos_perfis->links() !!}
        @endif
    </div>
</div>
@endsection