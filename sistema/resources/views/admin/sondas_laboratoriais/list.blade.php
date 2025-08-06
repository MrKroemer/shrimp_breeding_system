@extends('adminlte::page')

@section('title', 'Listagem de Sondas Laboratoriais')

@section('content_header')
<h1>Listagem de Sondas Laboratoriais</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Sondas Laboratoriais</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.sondas_laboratoriais.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="id" class="form-control" placeholder="ID">
            <input type="text" name="nome" class="form-control" placeholder="Nome">
            <input type="text" name="marca" class="form-control" placeholder="Marca">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.sondas_laboratoriais.to_create') }}" class="btn btn-success">
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
                    <th>Marca</th>
                    <th>Nº de Série</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sondas_laboratoriais as $sonda_laboratorial)
                    <tr>
                        <td>{{ $sonda_laboratorial->id }}</td>
                        <td>{{ $sonda_laboratorial->nome }}</td>
                        <td>{{ $sonda_laboratorial->marca }}</td>
                        <td>{{ $sonda_laboratorial->numero_serie }}</td>
                        <td>
                            @php
                                if ($sonda_laboratorial->situacao == 'ON') {
                                    $btnColor = 'success';
                                    $btnTitle = 'Ativo';
                                } else {
                                    $btnColor = 'default';
                                    $btnTitle = 'Inativo';
                                }
                                
                            @endphp
                            <a href="{{ route('admin.sondas_laboratoriais.to_turn', ['id' => $sonda_laboratorial->id]) }}" class="btn btn-{{ $btnColor }} btn-xs">
                                <i class="fa fa-power-off" aria-hidden="true"></i> {{ $btnTitle }}
                            </a>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Mais opções <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('admin.sondas_laboratoriais.sondas_fatores', ['sonda_id' => $sonda_laboratorial->id]) }}"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i>Fatores de Conversão</a></li>
                                </ul>
                            </div> 
                            <a href="{{ route('admin.sondas_laboratoriais.to_edit', ['sonda_id' => $sonda_laboratorial->id]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-edit" aria-hidden="true"></i> Editar
                            </a>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.sondas_laboratoriais.to_remove', ['id' => $sonda_laboratorial->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $sondas_laboratoriais->appends($formData)->links() !!}
        @else
            {!! $sondas_laboratoriais->links() !!}
        @endif
    </div>
</div>
@endsection