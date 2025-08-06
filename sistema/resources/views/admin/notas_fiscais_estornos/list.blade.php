@extends('adminlte::page')

@section('title', 'Registro de notas de estornos')

@section('content_header')
<h1>Listagem de notas de estornos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Notas de estornos</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.notas_fiscais_estornos.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="numero" class="form-control" placeholder="Número da NF">
            <input type="text" name="chave" class="form-control" placeholder="Chave de acesso">
            <input type="text" name="data_emissao" class="form-control" placeholder="Data de emissão">
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
                    <th>Chave de acesso</th>
                    <th>Número da NF</th>
                    <th>Valor total da NF</th>
                    <th>Data de emissão</th>
                    <th>Entrada no estoque</th>
                    <th>Cliente</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notas_fiscais as $nota_fiscal)
                    <tr>
                        <td>{{ $nota_fiscal->id }}</td>
                        <td><a class="btn btn-default btn-xs" onclick="consultaNfe('{{ $nota_fiscal->chave }}');">Consultar NF-e</a></td>
                        <td>{{ $nota_fiscal->numero }}</td>
                        <td>R$ {{ $nota_fiscal->valor_total }}</td>
                        <td>{{ $nota_fiscal->data_emissao() }}</td>
                        <td>{{ $nota_fiscal->data_movimento() }}</td>
                        <td>{{ mb_strtoupper($nota_fiscal->cliente->nome) }}</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Mais opções <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('admin.notas_fiscais_estornos.notas_fiscais_itens', ['nota_fiscal_id' => $nota_fiscal->id]) }}"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i> Itens da NF</a></li>
                                </ul>
                            </div>
                            <a href="{{ route('admin.notas_fiscais_estornos.to_view', ['id' => $nota_fiscal->id]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-eye" aria-hidden="true"></i> Visualizar
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $notas_fiscais->appends($formData)->links() !!}
        @else
            {!! $notas_fiscais->links() !!}
        @endif
    </div>
</div>
@endsection
