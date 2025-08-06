@extends('adminlte::page')

@section('title', 'Registro de clientes e fornecedores')

@section('content_header')
<h1>Edição de clientes e fornecedores</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Clientes e fornecedores</a></li>
    <li><a href="">Edição</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $categorias = explode('|', $tipo);
            
            $cliente    = false;
            $fornecedor = false;

            foreach ($categorias as $categoria) {
                switch ($categoria) {
                    case 'C': $cliente    = true; break;
                    case 'F': $fornecedor = true; break;
                }
            }
        @endphp
        <form action="{{ route('admin.clientes_fornecedores.to_update', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="tipo">Tipo:</label>
                <div>
                    <input type="checkbox" name="tipo_cliente"    {{ $cliente == 'C' ? 'checked' : '' }}> Cliente
                    <input type="checkbox" name="tipo_fornecedor" {{ $fornecedor == 'F' ? 'checked' : '' }}> Fornecedor
                </div>
            </div>
            <div class="form-group">
                <label for="nome">Nome fantasia:</label>
                <input type="text" name="nome" placeholder="Nome fantasia" class="form-control" value="{{ $nome }}">
            </div>
            <div class="form-group">
                <label for="razao">Razão social:</label>
                <input type="text" name="razao" placeholder="Razão social" class="form-control" value="{{ $razao }}">
            </div>
			<div class="form-group">
                <label for="cnpj">CNPJ:</label>
                <input type="text" name="cnpj" placeholder="CNPJ" class="form-control" value="{{ $cnpj }}">
            </div>
            <div class="form-group">
                <label for="ie">I.E.:</label>
                <input type="text" name="ie" placeholder="Incrição estadual" class="form-control" value="{{ $ie }}">
            </div>
            <div class="form-group">
                <label for="cep">CEP:</label>
                <input type="text" name="cep" placeholder="CEP" class="form-control" value="{{ $cep }}">
            </div>
			<div class="form-group">
                <label for="logradouro">Logradouro:</label>
                <input type="text" name="logradouro" placeholder="Logradouro" class="form-control" value="{{ $logradouro }}">
            </div>
			<div class="form-group">
                <label for="numero">Número:</label>
                <input type="text" name="numero" placeholder="Número" class="form-control" value="{{ $numero }}">
            </div>
			<div class="form-group">
                <label for="bairro">Bairro:</label>
                <input type="text" name="bairro" placeholder="Bairro" class="form-control" value="{{ $bairro }}">
            </div>
            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="text" name="telefone" placeholder="Telefone" class="form-control" value="{{ $telefone }}">
            </div>
			<div class="form-group">
                <label for="pais_id">País:</label>
                <select name="pais_id" class="form-control" onchange="onChangePais('{{ url('admin') }}');">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($paises as $pais)
                        <option value="{{ $pais->id }}" {{ ($pais->id == $pais_id) ? 'selected' : '' }}>{{ $pais->nome }}</option>
                    @endforeach
                </select>
            </div>
			<div class="form-group">
                <label for="estado_id">Estado:</label>
                <select name="estado_id" class="form-control" onchange="onChangeEstado('{{ url('admin') }}');">
                    <option value="">..:: Selecione ::..</option>
                     @foreach($estados as $estado)
                        <option value="{{ $estado->id }}" {{ ($estado->id == $estado_id) ? 'selected' : '' }}>{{ $estado->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="cidade_id">Cidade:</label>
                <select name="cidade_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                     @foreach($cidades as $cidade)
                        <option value="{{ $cidade->id }}" {{ ($cidade->id == $cidade_id) ? 'selected' : '' }}>{{ $cidade->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.clientes_fornecedores') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection