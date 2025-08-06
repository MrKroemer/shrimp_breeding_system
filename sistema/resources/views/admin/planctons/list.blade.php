@extends('adminlte::page')

@section('title', 'Registro de Análises Planctons')

@section('content_header')
<h1>Análise de Planctôns</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Análise de Planctôns</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.planctons.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <div class="form-group">
                <select name="ciclo_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($planctons as $plancton)
                        <option value="{{ $plancton->ciclo->id }}" >{{ $plancton->ciclo->tanque->sigla }}</option>
                    @endforeach
                </select>
            </div>
            <input type="text" name="dataanalise" class="form-control" placeholder="Data">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.planctons.to_create') }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Viveiro</th>
                    <th>Data</th>
                    <th>Usuário</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($planctons as $plancton)
                    <tr>
                        <td>{{ $plancton->id }}</td>
                        <td>{{ $plancton->ciclo->tanque->sigla }}</td>
                        <td>{{ $plancton->data_analise() }}</td>
                        <td>{{ $plancton->usuario->nome }}</td>
                        <td>             
                            <a href="{{ route('admin.planctons.to_edit', ['id' => $plancton->id]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-edit" aria-hidden="true"></i> Editar
                            </a>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.planctons.to_remove', ['id' => $plancton->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $planctons->appends($formData)->links() !!}
        @else
            {!! $planctons->links() !!}
        @endif
    </div>
</div>
@endsection