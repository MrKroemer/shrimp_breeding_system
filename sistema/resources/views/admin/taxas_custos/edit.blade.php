@extends('adminlte::page')

@section('title', 'Registro de taxas e custos')

@section('content_header')
<h1>Edição de taxas e custos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Taxas e custos</a></li>
    <li><a href="">Edição</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        <form action="{{ route('admin.taxas_custos.to_update', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="data_referencia">Data de referência:</label>
                <input type="date" name="data_referencia" placeholder="Data de referência" class="form-control" value="{{ $data_referencia }}">
            </div>
            <div class="form-group">
                <label for="custo_fixo">Taxa de custos fixos:</label>
                <input type="number" step="any" name="custo_fixo" placeholder="Taxa de custos fixos" class="form-control" value="{{ $custo_fixo }}">
            </div>
            <div class="form-group">
                <label for="custo_energia">Taxa de energia:</label>
                <input type="number" step="any" name="custo_energia" placeholder="Taxa de energia" class="form-control" value="{{ $custo_energia }}">
            </div>
            <div class="form-group">
                <label for="custo_combustivel">Taxa de combustível:</label>
                <input type="number" step="any" name="custo_combustivel" placeholder="Taxa de combustível" class="form-control" value="{{ $custo_combustivel }}">
            </div>
            <div class="form-group">
                <label for="custo_depreciacao">Taxa de depreciação:</label>
                <input type="number" step="any" name="custo_depreciacao" placeholder="Taxa de depreciação" class="form-control" value="{{ $custo_depreciacao }}">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.taxas_custos') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection