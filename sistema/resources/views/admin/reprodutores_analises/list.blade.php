@extends('adminlte::page')

@section('title', 'Registro de Análises')

@section('content_header')
<h1>Listagem de Tipos de Análise</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Tipos de Análise</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.reprodutores_analises.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="id" class="form-control" placeholder="ID">
            <input type="text" name="sigla" class="form-control" placeholder="Sigla">
            <input type="text" name="descricao" class="form-control" placeholder="Nome">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.reprodutores_analises.to_create') }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sigla</th>
                    <th>Descrição</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reprodutores_analises as $analise)
                    <tr>
                        <td>{{ $analise->id }}</td>
                        <td>{{ $analise->sigla }}</td>
                        <td>{{ $analise->descricao }}</td>
                        <td>
                            @php
                                if ($analise->ativo == 'ON') {
                                    $btnColor = 'success';
                                    $btnTitle = 'Ativo';
                                } else {
                                    $btnColor = 'default';
                                    $btnTitle = 'Inativo';
                                }
                            @endphp
                            <a href="{{ route('admin.reprodutores_analises.to_turn', ['id' => $analise->id]) }}" class="btn btn-{{ $btnColor }} btn-xs">
                                <i class="fa fa-power-off" aria-hidden="true"></i> {{ $btnTitle }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.reprodutores_analises.to_edit', ['id' => $analise->id]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-edit" aria-hidden="true"></i> Editar
                            </a>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.reprodutores_analises.to_remove', ['id' => $analise->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>
        @if(isset($formData))
            {!! $reprodutores_analises->appends($formData)->links() !!}
        @else
            {!! $reprodutores_analises->links() !!}
        @endif
    </div>
</div>
@endsection