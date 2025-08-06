@extends('adminlte::page')
@php
    use App\Http\Controllers\Util\DataPolisher;
@endphp
@section('title', 'Histórico de saídas de produtos')

@section('content_header')
<h1>Histórico de saídas de produtos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Saídas de produtos</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.estoque_saidas.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <div class="form-group">
                <input type="text" name="produto_id" class="form-control" placeholder="ID do produto">
            </div>
            <div class="form-group" style="width: 175px;">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_inicial" placeholder="Á partir do dia" class="form-control pull-right" id="date_picker">
                </div>
            </div>
            <div class="form-group" style="width: 175px;">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_final" placeholder="Até o dia" class="form-control pull-right" id="date_picker">
                </div>
            </div>
            <div class="form-group">
                <select name="tipo_destino" class="form-control" style="width: 190px;">
                    <option value="">..:: Destino de saída ::..</option>
                    @foreach($tipos_destinos as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Valor unitário</th>
                    <th>Valor total</th>
                    <th>Data do movimento</th> 
                    <th>Destino de saída</th>
                    <th>Tanque (ID)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($estoque_saidas as $estoque_saida)
                    <tr>
                        <td>{{ $estoque_saida->produto_id }}</td>
                        <td>{{ $estoque_saida->produto->nome }}</td>
                        <td>{{ DataPolisher::numberFormat($estoque_saida->quantidade) }} {{ $estoque_saida->produto->unidade_saida->sigla }}</td>
                        <td>R$ {{ DataPolisher::numberFormat($estoque_saida->valor_unitario) }}</td>
                        <td>R$ {{ DataPolisher::numberFormat($estoque_saida->valor_total) }}</td>
                        <td>{{ $estoque_saida->data_movimento() }}</td>
                        <td>{{ $estoque_saida->tipo_destino($estoque_saida->tipo_destino) }}</td>
                        <td>{{ $estoque_saida->tanque->sigla }} ({{ $estoque_saida->tanque->id }})</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $estoque_saidas->appends($formData)->links() !!}
        @else
            {!! $estoque_saidas->links() !!}
        @endif
    </div>
</div>
@endsection