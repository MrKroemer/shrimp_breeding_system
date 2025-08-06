@extends('adminlte::page')

@section('title', 'Registro de tanques')

@section('content_header')
<h1>Listagem de tanques</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Tanques</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.tanques.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="id" class="form-control" placeholder="ID">
            <input type="text" name="nome" class="form-control" placeholder="Nome do tanque">
            <input type="text" name="sigla" class="form-control" placeholder="Sigla do tanque">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.tanques.to_create') }}" class="btn btn-success">
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
                    <th>Tipo</th>
                    <th>Setor</th>
                    {{-- <th>Subsetor</th> --}}
                    <th>Altura</th>
                    <th>Área</th>
                    <th>Volume</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tanques as $tanque)
                    <tr>
                        <td>{{ $tanque->id }}</td>
                        <td>{{ $tanque->sigla }}</td>
                        <td>{{ $tanque->tanque_tipo->nome }}</td>
                        <td>{{ $tanque->setor->sigla }}</td>
                        {{-- <td>{{ !empty($tanque->subsetor->nome) ? $tanque->subsetor->nome : 'n/a' }}</td> --}}
                        <td>{{ (float) $tanque->altura }} m</td>
                        <td>{{ (float) $tanque->area }} m²</td>
                        <td>{{ (float) $tanque->volume }} m³</td>
                        <td>
                            @php
                                if ($tanque->situacao == 'ON') {
                                    $btnColor = 'success';
                                    $btnTitle = 'Ativo';
                                } else {
                                    $btnColor = 'default';
                                    $btnTitle = 'Inativo';
                                }
                            @endphp
                            <a href="{{ route('admin.tanques.to_turn', ['id' => $tanque->id]) }}" class="btn btn-{{ $btnColor }} btn-xs">
                                <i class="fa fa-power-off" aria-hidden="true"></i> {{ $btnTitle }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('admin.tanques.to_edit', ['id' => $tanque->id]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-edit" aria-hidden="true"></i> Editar
                            </a>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.tanques.to_remove', ['id' => $tanque->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $tanques->appends($formData)->links() !!}
        @else
            {!! $tanques->links() !!}
        @endif
    </div>
</div>
@endsection