@extends('adminlte::page')

@section('title', 'Registro de recriação de peixes')

@section('content_header')
<h1>Listagem de recriação de peixes</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Recriação de peixes</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.recriacao_peixes.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="codigo" class="form-control" placeholder="Código de recriação">
            <div class="form-group">
                <select name="tanque_id" class="form-control" style="width: 185px;">
                    <option value="">..:: Tanque ::..</option>
                    @foreach($tanques as $tanque)
                        <option value="{{ $tanque->id }}">{{ $tanque->sigla }} ( {{ $tanque->tanque_tipo->nome }} )</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_inicio" placeholder="Data do início" class="form-control pull-right" id="date_picker">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_fim" placeholder="Data do fim" class="form-control pull-right" id="date_picker">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.recriacao_peixes.to_create') }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tanque</th>
                    <th>Código</th>
                    <th>Data de início</th>
                    <th>Data de fim</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recriacoes as $recriacao)
                    <tr>
                        <td>{{ $recriacao->id }}</td>
                        <td>{{ $recriacao->tanque->sigla }}</td>
                        <td>{{ $recriacao->codigo }}</td>
                        <td>{{ $recriacao->data_inicio() }}</td>
                        <td>{{ $recriacao->data_fim() ?: '- - / - - / - - - -' }}</td>
                        <td>
                            <p style="font-weight:bold;color:{{ $recriacao->situacao == 1 ? 'green' : 'red' }};">
                                {{ mb_strtoupper($recriacao->situacao()) }}
                            </p>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Mais opções <span class="caret"></span>
                                </button>
                                {{-- <ul class="dropdown-menu">
                                    <li><a href="{{ route('admin.recriacao_peixes.lotes.to_edit', ['recriacao_peixes_id' => $recriacao->id]) }}"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i> Lotes de peixes</a></li>
                                </ul> --}}
                            </div>
                            @if ($recriacao->situacao != 5)
                                {{-- <button type="button" onclick="onActionForRequest('{{ route('admin.povoamentos.to_close', ['id' => $povoamento->id]) }}');" class="btn btn-warning btn-xs">
                                    <i class="fa fa-check" aria-hidden="true"></i> Encerrar povoamento
                                </button> --}}
                                {{-- <button type="button" onclick="onActionForRequest('{{ route('admin.recriacao_peixes.to_remove', ['id' => $recriacao->id]) }}');" class="btn btn-danger btn-xs">
                                    <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                                </button> --}}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $recriacoes->appends($formData)->links() !!}
        @else
            {!! $recriacoes->links() !!}
        @endif
    </div>
</div>
@endsection