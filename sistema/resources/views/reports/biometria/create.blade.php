@extends('adminlte::page')

@section('title', 'Relatório de Biometria')

@section('content_header')
<h1>Relatório de Biometria</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Relatório de Biometria</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        <form action="{{ route('admin.relatorio_biometria.to_view') }}" method="get" target="_blank">
            <div class="form-group">
                <input type="submit" class="btn btn-success" value="Gerar relatório">
            </div>
        </form>
    </div>
</div>
@endsection
