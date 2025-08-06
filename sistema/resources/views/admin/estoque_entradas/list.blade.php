@extends('adminlte::page')
@php
    use App\Http\Controllers\Util\DataPolisher;
@endphp
@section('title', 'Histórico de entradas de produtos')

@section('content_header')
<h1>Histórico de entradas de produtos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Entradas de produtos</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.estoque_entradas.to_search') }}" method="POST" class="form form-inline">
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
                <select name="tipo_origem" class="form-control" style="width: 190px;">
                    <option value="">..:: Origem da entrada ::..</option>
                    @foreach($tipos_origens as $key => $value)
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
                    <th>Origem da entrada</th> 
                    <th>Nota fiscal (Número)</th> 
                </tr>
            </thead>
            <tbody>
                @foreach($estoque_entradas as $estoque_entrada)
                    <tr>
                        <td>{{ $estoque_entrada->produto_id }}</td>
                        <td>{{ $estoque_entrada->produto->nome }}</td>
                        <td>{{ DataPolisher::numberFormat($estoque_entrada->quantidade) }} {{ $estoque_entrada->produto->unidade_saida->sigla }}</td>
                        <td>R$ {{ DataPolisher::numberFormat($estoque_entrada->valor_unitario) }}</td>
                        <td>R$ {{ DataPolisher::numberFormat($estoque_entrada->valor_total) }}</td>
                        <td>{{ $estoque_entrada->data_movimento() }}</td>
                        <td>{{ $estoque_entrada->tipo_origem($estoque_entrada->tipo_origem) }}</td>
                        <td>
                        @if (! empty($estoque_entrada->estoque_entrada_nota))
                            <a href="{{ route('admin.notas_fiscais_entradas.notas_fiscais_itens', ['nota_fiscal_id' => $estoque_entrada->estoque_entrada_nota->nota_fiscal->id]) }}">
                                Nº {{ $estoque_entrada->estoque_entrada_nota->nota_fiscal->numero }}
                            </a>
                        @else
                            n/a
                        @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $estoque_entradas->appends($formData)->links() !!}
        @else
            {!! $estoque_entradas->links() !!}
        @endif
    </div>
</div>
@endsection