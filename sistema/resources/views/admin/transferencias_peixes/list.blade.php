@extends('adminlte::page')
@php
    use App\Http\Controllers\Util\DataPolisher;
@endphp
@section('title', 'Registro de transferência de peixes')

@section('content_header')
<h1>Listagem de transferência de peixes</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Transferência de peixes</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.transferencias_peixes.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <div class="form-group">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_movimento" placeholder="Data de transferência" class="form-control pull-right" id="date_picker">
                </div>
            </div>
            <div class="form-group">
                <select name="lote_peixes_id" class="form-control" style="width: 185px;">
                    <option value="">..:: Lote ::..</option>
                    @foreach($lotes as $lote)
                        <option value="{{ $lote->id }}">{{ $lote->codigo }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
            <a href="{{ route('admin.transferencias_peixes.to_create') }}" class="btn btn-success">
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
                    <th>Código do lote</th>
                    <th>Tanque de destino</th>
                    <th>Quantidade</th>
                    <th>Proporção</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transferencias as $transferencia)
                    <tr>
                        <td>{{ $transferencia->id }}</td>
                        <td>{{ $transferencia->data_movimento() }}</td>
                        <td>{{ $transferencia->lote_peixes->codigo }}</td>
                        <td>{{ $transferencia->tanque->sigla }}</td>
                        <td>{{ DataPolisher::numberFormat($transferencia->quantidade, 0) }} Und.</td>
                        <td>{{ DataPolisher::numberFormat($transferencia->proporcao(), 2) }} %</td>
                        <td>
                            <div>
                                <button type="button" data-toggle="modal" data-target="#modal_transferencia_{{ $transferencia->id }}" class="btn btn-primary btn-xs">
                                    <i class="fa fa-eye" aria-hidden="true"></i> Observações
                                </button>
                                
                                <button type="button" onclick="onActionForRequest('{{ route('admin.transferencias_peixes.to_remove', ['id' => $transferencia->id ]) }}');" class="btn btn-danger btn-xs">
                                    <i class="fa fa-edit" aria-hidden="true"></i> Excluir
                                </button>

                                <div class="modal fade" id="modal_transferencia_{{ $transferencia->id }}" tabindex="-1" role="dialog" aria-labelledby="modal_transferencia_{{ $transferencia->id }}_label" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modal_transferencia_{{ $transferencia->id }}_title">
                                                <b>Observações do lote {{ $transferencia->lote_peixes->codigo }}</b>
                                            </h5>
                                        </div>
                                        <div class="modal-body">
                                            <i>{{ $transferencia->observacoes ?: 'Não existem observações' }}</i>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
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