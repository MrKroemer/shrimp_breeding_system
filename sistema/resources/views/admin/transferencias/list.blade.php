@extends('adminlte::page')

@section('title', 'Registro de tranferencias entre cultivos')

@section('content_header')
<h1>Listagem de Transferência entre Cultivos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Transferência entre Cultivos</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.transferencias.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <div class="form-group">
                <select name="ciclo_origem_id" class="form-control" style="width: 185px;">
                    <option value="">..:: Selecione a Origem ::..</option>
                    @foreach($tanques_origem as $tanques)
                        <option value="{{ $tanques->id }}">{{ $tanques->sigla }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                    <select name="ciclo_destino_id" class="form-control" style="width: 185px;">
                        <option value="">..:: Selecione o Destino ::..</option>
                        @foreach($tanques_destino as $tanques)
                            <option value="{{ $tanques->id }}">{{ $tanques->sigla }}</option>
                        @endforeach
                    </select>
                </div>
            <div class="form-group">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_transferencia" placeholder="Data de Transferência" class="form-control pull-right" id="datetime_picker">
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
            <a href="{{ route('admin.transferencias.to_create') }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ciclo Origem</th>
                    <th>Ciclo Destino</th>
                    <th>Data Transferência</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transferencias as $transferencia)
                    <tr>
                        <td>{{ $transferencia->id }}</td>
                        <td>{{ $transferencia->ciclo_origem->tanque->sigla }} ( Ciclo Nº {{ $transferencia->ciclo_origem->numero }} )</td>
                        <td>{{ $transferencia->ciclo_destino->tanque->sigla }} ( Ciclo Nº {{ $transferencia->ciclo_destino->numero }} )</td>
                        <td>{{ $transferencia->data_movimento() }}</td>
                        <td>
                        <button type="button" onclick="onActionForRequest('{{ route('admin.transferencias.to_remove', ['id' => $transferencia->id ]) }}');" class="btn btn-danger btn-xs">
                            <i class="fa fa-edit" aria-hidden="true"></i> Excluir
                        </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $transferencias->appends($formData)->links() !!}
        @else
            {!! $transferencias->links() !!}
        @endif
    </div>
</div>
@endsection