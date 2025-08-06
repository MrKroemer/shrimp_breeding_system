@extends('adminlte::page')

@section('title', 'Registro de referenciais de alimentações')

@section('content_header')
<h1>Listagem de referenciais de alimentações</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Períodos climáticos</a></li>
    <li><a href="">Referenciais de alimentações</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.arracoamentos_climas.arracoamentos_referenciais.to_search', ['arracoamento_clima_id' => $arracoamento_clima_id]) }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="id" class="form-control" placeholder="ID">
            <input type="text" name="dias_cultivo" class="form-control" placeholder="Dias de cultivo">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.arracoamentos_climas.arracoamentos_referenciais.to_create', ['arracoamento_clima_id' => $arracoamento_clima_id]) }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
            <a href="{{ route('admin.arracoamentos_climas') }}" class="btn btn-default">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
            </a>
        </form>
    </div>
    <div class="box-body">
        <h5 class="box-title">{{ $arracoamento_clima->nome }} ( {{ $arracoamento_clima->descricao }} )</h5>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Dias de cultivo</th>
                    <th>Peso médio</th>
                    <th>Percentual de ração</th>
                    <th>Crescimento diário</th>
                    <th>Observações</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($arracoamentos_referenciais as $arracoamento_referencial)
                    <tr>
                        <td>{{ $arracoamento_referencial->id }}</td>
                        <td>{{ $arracoamento_referencial->dias_cultivo }}º dia</td>
                        <td>{{ (float) $arracoamento_referencial->peso_medio }} g</td>
                        <td>{{ (float) $arracoamento_referencial->porcentagem }} % do total de biomassa</td>
                        <td>{{ (float) $arracoamento_referencial->crescimento }} g</td>
                        <td>{{ $arracoamento_referencial->observacoes }}</td>
                        <td>                
                            <a href="{{ route('admin.arracoamentos_climas.arracoamentos_referenciais.to_edit', ['arracoamento_clima_id' => $arracoamento_clima_id, 'id' => $arracoamento_referencial->id]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-edit" aria-hidden="true"></i> Editar
                            </a>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.arracoamentos_climas.arracoamentos_referenciais.to_remove', ['arracoamento_clima_id' => $arracoamento_clima_id, 'id' => $arracoamento_referencial->id]) }}');" class="btn btn-danger btn-xs">
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