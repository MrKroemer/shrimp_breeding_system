@extends('adminlte::page')

@section('title', 'Registro de grupos de rateios')

@section('content_header')
<h1>Edição de grupos de rateios</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Grupos de rateios</a></li>
    <li><a href="">Edição</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        <form action="{{ route('admin.grupos_rateios.to_update', ['id' => $id]) }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="nome">Nome:</label>
                <input type="text" name="nome" placeholder="Nome" class="form-control" value="{{ $nome }}">
            </div>
            <div class="form-group">
                <label for="descricao">Descrição:</label>
                <input type="text" name="descricao" placeholder="Descrição" class="form-control" value="{{ $descricao }}">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.grupos_rateios') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection