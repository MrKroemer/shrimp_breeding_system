@extends('adminlte::page')

@section('title', 'Registro de tanques')

@section('content_header')
<h1>Cadastro de tanques</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Tanques</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')
@include('admin.tanques.tipos.create')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $nome           = !empty(session('nome'))           ? session('nome')           : old('nome');
            $sigla          = !empty(session('sigla'))          ? session('sigla')          : old('sigla');
            $situacao       = !empty(session('situacao'))       ? session('situacao')       : old('situacao');
            $area           = !empty(session('area'))           ? session('area')           : old('area');
            $altura         = !empty(session('altura'))         ? session('altura')         : old('altura');
            $volume         = !empty(session('volume'))         ? session('volume')         : old('volume');
            $nivel          = !empty(session('nivel'))          ? session('nivel')          : old('nivel');
            $tanque_tipo_id = !empty(session('tanque_tipo_id')) ? session('tanque_tipo_id') : old('tanque_tipo_id');
            $setor_id       = !empty(session('setor_id'))       ? session('setor_id')       : old('setor_id');
        @endphp
        <form action="{{ route('admin.tanques.to_store') }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" placeholder="Nome" class="form-control" value="{{ $nome }}">
            </div>
            <div class="form-group">
                <label for="sigla">Sigla:</label>
                <input type="text" name="sigla" placeholder="Sigla" class="form-control" value="{{ $sigla }}">
            </div>
            <div class="form-group">
                <label for="tanque_tipo_id">Tipo:</label>
                <div class="input-group">
                    <select name="tanque_tipo_id" class="form-control">
                        <option value="">..:: Selecione ::..</option>
                        @foreach($tanques_tipos as $tanque_tipo)
                            <option value="{{ $tanque_tipo->id }}" {{ ($tanque_tipo->id == $tanque_tipo_id) ? 'selected' : '' }}>{{ $tanque_tipo->nome }}</option>
                        @endforeach
                    </select>
                    <span class="input-group-btn">
                        <a class="btn btn-success btn-flat" data-toggle="modal" data-target="#tanques_tipos_modal">
                            <i class="fa fa-plus" aria-hidden="true"></i> Criar novo
                        </a>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <label for="setor_id">Setor:</label>
                <select name="setor_id" class="form-control" onchange="onChangeSetores('{{ url('admin') }}');">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($setores as $setor)
                        <option value="{{ $setor->id }}" {{ ($setor->id == $setor_id) ? 'selected' : '' }}>{{ $setor->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="altura">Altura (m):</label>
                <input type="number" step="any" name="altura" placeholder="Altura" class="form-control" value="{{ $altura }}">
            </div>
            <div class="form-group">
                <label for="area">Área (m²):</label>
                <input type="number" step="any" name="area" placeholder="Área" class="form-control" value="{{ $area }}">
            </div>
            <div class="form-group">
                <label for="volume">Volume (m³):</label>
                <input type="number" step="any" name="volume" placeholder="Volume" class="form-control" value="{{ $volume }}">
            </div>
            <div class="form-group">
                <label for="nivel">Nível (m):</label>
                <input type="number" step="any" name="nivel" placeholder="Nível" class="form-control" value="{{ $nivel }}">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.tanques') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection