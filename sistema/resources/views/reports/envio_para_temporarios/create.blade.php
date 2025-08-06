@extends('adminlte::page')

@section('title', 'Relatório de envio para estoques temporários')

@section('content_header')
<h1>Relatório de envio para estoques temporários</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Relatório de envio para estoques temporários</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $data_transferencia = !empty(session('data_transferencia')) ? session('data_transferencia') : old('data_transferencia');
        @endphp
        <form action="{{ route('admin.envio_para_temporarios.to_view') }}" method="POST" target="_blank">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="data_inicial">Data da transferência:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_transferencia" placeholder="Data da transferência" class="form-control pull-right" id="date_picker" value="{{ $data_transferencia }}">
                </div>
            </div>
            <div class="form-group">
                <label for="produtos[]">Produtos:</label>
                <select name="produtos[]" class="select2_multiple" multiple="multiple">
                    @foreach ($produtos as $produto)
                        <option value="{{ $produto->id }}">{{ $produto->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-success" value="Gerar relatório">
            </div>
        </form>
    </div>
</div>
@endsection