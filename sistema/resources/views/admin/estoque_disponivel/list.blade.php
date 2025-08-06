@extends('adminlte::page')
@php
    use App\Http\Controllers\Util\DataPolisher;
@endphp
@section('title', 'Disponibilidade de produtos em estoque')

@section('content_header')
<h1>Disponibilidade de produtos em estoque</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Produtos em estoque</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.estoque_disponivel.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="produto_id" class="form-control" placeholder="ID ou cód. externo">
            <input type="text" name="produto_nome" class="form-control" placeholder="Nome ou sigla">
            <select name="produto_tipo_id" class="form-control">
                <option value="">..:: Tipo do produto ::..</option>
                @foreach($produtos_tipos as $produto_tipo)
                    <option value="{{ $produto_tipo->id }}">{{ $produto_tipo->nome }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID do produto</th>
                    <th>Nome do produto</th>
                    <th>Tipo do produto</th>
                    <th>Quantidade em estoque</th>
                    <th>Custo por unidade</th>
                    <th>Custo do estoque</th>
                    <th>Última entrada</th>
                </tr>
            </thead>
            <tbody>
                @foreach($estoque_disponiveis as $estoque_disponivel)
                    <tr>
                        <td>{{ $estoque_disponivel->produto_id }}</td>
                        <td>{{ $estoque_disponivel->produto_nome }}</td>
                        <td>{{ $estoque_disponivel->produto_tipo_nome }}</td>
                        <td style="color: {{ ($estoque_disponivel->em_estoque < 0) ? 'red' : 'green' }};">
                            {{ DataPolisher::numberFormat($estoque_disponivel->em_estoque) }} {{ $estoque_disponivel->produto->unidade_saida->sigla }}
                        </td>
                        <td>
                            R$ {{ DataPolisher::numberFormat($estoque_disponivel->valor_unitario()) }}
                        </td>
                        <td style="color: {{ ($estoque_disponivel->valor_estoque < 0) ? 'red' : 'green' }};">
                            R$ {{ DataPolisher::numberFormat($estoque_disponivel->valor_estoque) }}
                        </td>
                        <td>{{ $estoque_disponivel->ultima_entrada() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $estoque_disponiveis->appends($formData)->links() !!}
        @else
            {!! $estoque_disponiveis->links() !!}
        @endif
    </div>
</div>
@endsection
