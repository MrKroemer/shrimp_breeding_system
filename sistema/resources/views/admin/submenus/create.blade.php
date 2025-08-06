@extends('adminlte::page')

@section('title', 'Registro de submenus')

@section('content_header')
<h1>Cadastro de submenus</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Módulos</a></li>
    <li><a href="">Menus</a></li>
    <li><a href="">Submenus</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $nome  = !empty(session('nome'))  ? session('nome')  : old('nome');
            $rota  = !empty(session('rota'))  ? session('rota')  : old('rota');
            $icone = !empty(session('icone')) ? session('icone') : old('icone');

            $modulo = \App\Models\Modulos::find($modulo_id);
            $menu   = \App\Models\Menus::find($menu_id);
        @endphp
        <form action="{{ route('admin.modulos.submenus.to_store', ['modulo_id' => $modulo_id, 'menu_id' => $menu_id]) }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
                <label>Caminho:</label>
                <h5>{{ $modulo->nome }} / {{ $menu->nome }} /</h5>
            </div>
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" placeholder="Nome" class="form-control" value="{{ $nome }}">
            </div>
            <div class="form-group">
                <label for="rota">Rota:</label>
                <input type="text" name="rota" placeholder="Rota" class="form-control" value="{{ $rota }}">
            </div>
            <div class="form-group">
                <label for="icone">Icone:</label>
                <input type="text" name="icone" placeholder="Icone" class="form-control" value="{{ $icone }}">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.modulos.submenus', ['modulo_id' => $modulo_id, 'menu_id' => $menu_id]) }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection