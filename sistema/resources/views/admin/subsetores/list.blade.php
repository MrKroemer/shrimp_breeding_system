@extends('adminlte::page')

@section('title', 'Registro de subsetores')

@section('content_header')
<h1>Listagem de subsetores</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Setores</a></li>
    <li><a href="">Subsetores</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.setores.subsetores.to_search', ['setor_id' => $setor_id]) }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="id" class="form-control" placeholder="ID">
            <input type="text" name="nome" class="form-control" placeholder="Nome do subsetor">
            <input type="text" name="sigla" class="form-control" placeholder="Sigla do subsetor">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.setores.subsetores.to_create', ['setor_id' => $setor_id]) }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
            <a href="{{ route('admin.setores') }}" class="btn btn-default">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
            </a>
        </form>
    </div>
    <div class="box-body">
        @php
            $setor = \App\Models\Setores::find($setor_id);
        @endphp
        <h5 class="box-title">{{ $setor->nome }} /</h5>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Sigla</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subsetores as $subsetor)
                    <tr>
                        <td>{{ $subsetor->id }}</td>
                        <td>{{ $subsetor->nome }}</td>
                        <td>{{ $subsetor->sigla }}</td>
                        <td>              
                            <a href="{{ route('admin.setores.subsetores.to_edit', ['setor_id' => $setor_id, 'id' => $subsetor->id]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-edit" aria-hidden="true"></i> Editar
                            </a>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.setores.subsetores.to_remove', ['setor_id' => $setor_id, 'id' => $subsetor->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $subsetores->appends($formData)->links() !!}
        @else
            {!! $subsetores->links() !!}
        @endif
    </div>
</div>
@endsection