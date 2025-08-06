@extends('adminlte::page')

@section('title', 'Registro de transferência de animais')

@section('content_header')
<h1>Listagem de transferência de animais</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Transferência de animais</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.transferencias_animais.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <div class="form-group">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_transferencia" placeholder="Data de transferência" class="form-control pull-right" id="date_picker">
                </div>
            </div>
            <div class="form-group">
                <select name="tanque_id" class="form-control" style="width: 185px;">
                    <option value="">..:: Tanque ::..</option>
                    @foreach($tanques as $tanque)
                        <option value="{{ $tanque->id }}">{{ $tanque->sigla }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
            <a href="{{ route('admin.transferencias_animais.to_create') }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data da transferência</th>
                    <th>Quantidade</th>
                    <th>Proporção</th>
                    <th>Ciclo de origem</th>
                    <th>Ciclo de destino</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transferencias as $transferencia)
                    <tr>
                        <td>{{ $transferencia->id }}</td>
                        <td>{{ $transferencia->data_movimento() }}</td>
                        <td>{{ $transferencia->quantidade }} animais</td>
                        <td>{{ $transferencia->proporcao() }} %</td>
                        <td>{{ $transferencia->ciclo_origem->tanque->sigla }} ( Ciclo Nº {{ $transferencia->ciclo_origem->numero }} )</td>
                        <td>{{ $transferencia->ciclo_destino->tanque->sigla }} ( Ciclo Nº {{ $transferencia->ciclo_destino->numero }} )</td>
                        <td>
                        <button type="button" onclick="onActionForRequest('{{ route('admin.transferencias_animais.to_remove', ['id' => $transferencia->id ]) }}');" class="btn btn-danger btn-xs">
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