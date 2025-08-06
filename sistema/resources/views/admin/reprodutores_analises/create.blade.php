@extends('adminlte::page')

@section('title', 'Análises de Reprodutores')

@section('content_header')
<h1>Cadastro de Análises</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Análise</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $sigla             = !empty(session('sigla'))             ? session('sigla')             : old('sigla');
            $descricao         = !empty(session('descricao'))         ? session('descricao')         : old('cor');
            $alerta            = !empty(session('alerta'))            ? session('alerta')            : old('alerta');
        @endphp
        <form action="{{ route('admin.reprodutores_analises.to_store') }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="sigla">Sigla:</label>
                <input type="text" name="sigla" placeholder="Sigla" class="form-control" value="{{ $sigla }}">
            </div>
            <div class="form-group">
                <label for="descricao">Descricao:</label>
                <input type="text" name="descricao" placeholder="Nome" class="form-control" value="{{ $descricao }}">
            </div>
            <!--div class="form-group">
                <input type="checkbox" name="alerta" class="form-control" value="{{ $alerta }}">
                <label for="alerta">Alertar teste positivo</label>
            </div-->
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.reprodutores_analises') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection