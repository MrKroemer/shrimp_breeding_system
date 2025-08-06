@extends('adminlte::page')

@section('title', 'Registro de permissões de acesso')

@section('content_header')
<h1>Listagem de permissões de acesso</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Grupos</a></li>
    <li><a href="">Filiais</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')
@include('admin.grupos.filiais.create')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.grupos.filiais.to_search', ['grupo_id' => $grupo->id]) }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <div class="form-group">
                <select name="filial_id" class="form-control" style="width: 285px;">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($filiais as $filial)
                        <option value="{{ $filial->id }}">{{ $filial->nome }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
            <a class="btn btn-success" data-toggle="modal" data-target="#grupos_filiais_modal">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
            <a href="{{ route('admin.grupos') }}" class="btn btn-default">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
            </a>
        </form>
    </div>
    <div class="box-body">
        
        <h5 class="box-title">Grupos / {{ $grupo->nome }} /</h5>
        
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID da Filial</th>
                    <th>Nome</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($grupos_filiais as $grupo_filial)
                    <tr>
                        <td>{{ $grupo_filial->filial->id }}</td>
                        <td>
                            <a href="{{ route('admin.grupos.filiais.permissoes', ['grupo_id' => $grupo->id, 'grupo_filial_id' => $grupo_filial->id]) }}" class="btn btn-default btn-xs">
                                {{ $grupo_filial->filial->nome }}
                            </a>
                        </td>
                        <td>
                            @php
                                if ($grupo_filial->situacao == 'ON') {
                                    $btnColor = 'success';
                                    $btnTitle = 'Ativo';
                                } else {
                                    $btnColor = 'default';
                                    $btnTitle = 'Inativo';
                                }
                            @endphp
                            <a href="{{ route('admin.grupos.filiais.to_turn', ['grupo_id' => $grupo->id, 'id' => $grupo_filial->id]) }}" class="btn btn-{{ $btnColor }} btn-xs">
                                <i class="fa fa-power-off" aria-hidden="true"></i> {{ $btnTitle }}
                            </a>
                        </td>
                        <td>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.grupos.filiais.to_remove', ['grupo_id' => $grupo->id, 'id' => $grupo_filial->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $grupos_filiais->appends($formData)->links() !!}
        @else
            {!! $grupos_filiais->links() !!}
        @endif
    </div>
</div>
@endsection