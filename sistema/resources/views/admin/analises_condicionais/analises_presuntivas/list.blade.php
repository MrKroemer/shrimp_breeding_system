@extends('adminlte::page')

@section('title', 'Registro de Análises Presuntivas')

@section('content_header')
<h1>Análises Presuntivas</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Análises Condicionais</a></li>
    <li><a href="">Análises Presuntivas</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <div class="form-group">
                <select name="presuntiva_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                </select>
            </div>
            <input type="text" name="dataanalise" class="form-control" placeholder="Data">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.analises_condicionais.analises_presuntivas.to_create', ['analise_condicional_id' => $analise_condicional_id]) }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
            <a href="{{ route('admin.analises_condicionais', ['analise_condicional_id' => $analise_condicional_id]) }}" class="btn btn-success">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar
            </a>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Viveiro/Ciclo</th>
                    <th>Data</th>
                    <th>Usuário</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($presuntivas as $presuntiva)
                    <tr>
                        <td>{{ $presuntiva->id }}</td>
                        <td>{{ $presuntiva->sigla }}</td>
                        <td>{{ $presuntiva->data_analise() }}</td>
                        <td>{{ $presuntiva->nome }}</td>
                        <td> 
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Mais opções <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('admin.presuntivas.amostras.to_create', ['presuntiva_id' => $presuntiva->presuntiva_id]) }}"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i>Registro de amostras</a></li>
                                </ul>
                            </div>              
                            <a href="{{ route('admin.presuntivas.to_edit', ['presuntiva_id' => $presuntiva->presuntiva_id]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-edit" aria-hidden="true"></i> Editar
                            </a>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.presuntivas.to_remove', ['id' => $presuntiva->presuntiva_id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{-- @if(isset($formData))
            {!! $presuntivas->appends($formData)->links() !!}
        @else
            {!! $presuntivas->links() !!}
        @endif --}}
    </div>
</div>
@endsection