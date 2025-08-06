@extends('adminlte::page')

@section('title', 'Registro de taxas e custos')

@section('content_header')
<h1>Listagem de taxas e custos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Setores</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.taxas_custos.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="id" class="form-control" placeholder="ID">
            <input type="text" name="data_referencia" class="form-control" placeholder="Data de Referência">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.taxas_custos.to_create') }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
        </form>
    </div>
    <div class="box-body" style="padding: 10px 0;">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data de referência</th>
                    <th>Custo fixo</th>
                    <th>Energia</th>
                    <th>Combustível</th>
                    <th>Depreciação</th>
                    <!-- <th style="width: 8%;">FG CB</th>
                    <th style="width: 8%;">Starter #</th>
                    <th style="width: 8%;">FG AR</th>
                    <th style="width: 8%;">BKC</th>
                    <th style="width: 8%;">Starter P.</th> -->
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($taxas_custos as $taxa_custo)
                    <tr>
                        <td>{{ $taxa_custo->id }}</td>
                        <td>{{ $taxa_custo->data_referencia() }}</td>
                        <td>R$ {{ $taxa_custo->custo_fixo }}</td>
                        <td>R$ {{ $taxa_custo->custo_energia }}</td>
                        <td>R$ {{ $taxa_custo->custo_combustivel }}</td>
                        <td>R$ {{ $taxa_custo->custo_depreciacao }}</td>
                        <!--<td>R$ {{ $taxa_custo->custovar_racao }}</td>-->
                        <!--<td>R$ {{ $taxa_custo->custofix_racao }}</td>-->
                        <!-- <td>R$ {{ $taxa_custo->racao_fgcb }}</td>
                        <td>R$ {{ $taxa_custo->racao_starter }}</td>
                        <td>R$ {{ $taxa_custo->racao_fgar}}</td>
                        <td>R$ {{ $taxa_custo->bkc_camanor }}</td>
                        <td>R$ {{ $taxa_custo->starter_peletizada }}</td> -->
                        <td>
                            <a href="{{ route('admin.taxas_custos.to_edit', ['id' => $taxa_custo->id]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-edit" aria-hidden="true"></i> Editar
                            </a>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.taxas_custos.to_remove', ['id' => $taxa_custo->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $taxas_custos->appends($formData)->links() !!}
        @else
            {!! $taxas_custos->links() !!}
        @endif
    </div>
</div>
@endsection