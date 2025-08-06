@extends('adminlte::page')

@section('title', 'Registro de esquemas de alimentação')

@section('content_header')
<h1>Listagem de esquemas de alimentação</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Perfis de arraçoamentos</a></li>
    <li><a href="">Esquemas de alimentação</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.arracoamentos_perfis.arracoamentos_esquemas.to_search', ['arracoamento_perfil_id' => $arracoamento_perfil_id]) }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <input type="text" name="id" class="form-control" placeholder="ID">
            <input type="text" name="dias_cultivo" class="form-control" placeholder="Dias de cultivo">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.arracoamentos_perfis.arracoamentos_esquemas.to_create', ['arracoamento_perfil_id' => $arracoamento_perfil_id]) }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
            <a href="{{ route('admin.arracoamentos_perfis') }}" class="btn btn-default">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
            </a>
        </form>
    </div>
    <div class="box-body">
        <h5 class="box-title">{{ $arracoamento_perfil->nome }} ( {{ $arracoamento_perfil->descricao }} )</h5>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Intervalo de dias</th>
                    <th>Raçoes utilizadas</th>
                    <th>Quantidade de alimentações</th>
                    <th>Período</th>
                    {{-- <th style="width:25%;">Descrição/Observações</th> --}}
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($arracoamentos_esquemas as $arracoamento_esquema)
                    @php
                        $racoes       = $arracoamento_esquema->racoes; 
                        $alimentacoes = $arracoamento_esquema->alimentacoes;
                        $qtd_alimentacoes = 0;
                        
                        foreach ($alimentacoes as $alimentacao) {
                            $qtd_alimentacoes += $alimentacao->quantidade_alimentacoes();
                        }
                    @endphp
                    <tr>
                        <td>{{ $arracoamento_esquema->id }}</td>
                        @if($arracoamento_esquema->dia_inicial == $arracoamento_esquema->dia_final)
                            <td>No <b>{{ $arracoamento_esquema->dia_inicial }}º</b> dia de cultivo</td>
                        @else
                            <td>Do <b>{{ $arracoamento_esquema->dia_inicial }}º</b> ao <b>{{ $arracoamento_esquema->dia_final }}º</b> dia de cultivo</td>    
                        @endif
                        <td>
                            @if($racoes->count() > 0)
                                @foreach($racoes as $racao)
                                    {{ $racao->porcentagem }}% de {{$racao->racao->nome }}<br>
                                @endforeach
                            @endif
                        </td>
                        <td><b>{{ $qtd_alimentacoes }}</b> alimentações por dia</td>
                        <td>{{ $arracoamento_esquema->periodo($arracoamento_esquema->periodo) }}</td>
                        {{-- <td>{{ $arracoamento_esquema->descricao }}</td> --}}
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Mais opções <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('admin.arracoamentos_perfis.arracoamentos_esquemas.arracoamentos_esquemas_itens', ['arracoamento_perfil_id' => $arracoamento_perfil_id, 'arracoamento_esquema_id' => $arracoamento_esquema->id]) }}"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i> Raçoes e alimentações</a></li>
                                </ul>
                            </div>                 
                            <a href="{{ route('admin.arracoamentos_perfis.arracoamentos_esquemas.to_edit', ['arracoamento_perfil_id' => $arracoamento_perfil_id, 'id' => $arracoamento_esquema->id]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-edit" aria-hidden="true"></i> Editar
                            </a>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.arracoamentos_perfis.arracoamentos_esquemas.to_remove', ['arracoamento_perfil_id' => $arracoamento_perfil_id, 'id' => $arracoamento_esquema->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection