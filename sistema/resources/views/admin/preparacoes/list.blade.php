@extends('adminlte::page')

@section('title', 'Registro de preparações de cultivos')

@section('content_header')
<h1>Listagem de preparações de cultivos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Preparações de cultivos</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.preparacoes.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <div class="form-group">
                <select name="ciclo_id" class="form-control" style="width: 185px;">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($ciclos as $ciclo)
                        <option value="{{ $ciclo->id }}">{{ $ciclo->tanque->sigla }} ( Ciclo Nº {{ $ciclo->numero }} )</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_inicio" placeholder="Data do início" class="form-control pull-right" id="datetime_picker">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_fim" placeholder="Data do fim" class="form-control pull-right" id="datetime_picker">
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
            <a href="{{ route('admin.preparacoes.to_create') }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cultivo</th>
                    <th>Início da prep.</th>
                    <th>Início do abas.</th>
                    <th>Fim do abas.</th>
                    <th>Fim da prep.</th>
                    {{-- <th>Qtd. de aplicações</th> --}}
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($preparacoes as $preparacao)
                    @php 
                        $cicloSituacao = $preparacao->ciclo->verificarSituacao([2, 3, 4]);
                    @endphp
                    <tr>
                        <td>{{ $preparacao->id }}</td>
                        <td>{{ $preparacao->ciclo->tanque->sigla }} ( Ciclo Nº {{ $preparacao->ciclo->numero }} )</td>
                        <td>{{ $preparacao->data_inicio() }}</td>
                        <td>{{ $preparacao->abas_inicio() ?: '- - / - - / - - - -' }}</td>
                        <td>{{ $preparacao->abas_fim() ?: '- - / - - / - - - -' }}</td>
                        <td>{{ $preparacao->data_fim() ?: '- - / - - / - - - -' }}</td>
                        {{-- <td>{{ $preparacao->aplicacoes->count() }} aplicações de produtos</td> --}}
                        <td>
                            {{-- <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Mais opções <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('admin.preparacoes.aplicacoes', ['preparacao_id' => $preparacao->id]) }}"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i> Aplicações de produtos</a></li>
                                </ul>
                            </div> --}}
                            @if ($cicloSituacao)
                                <a href="{{ route('admin.preparacoes.to_edit', ['id' => $preparacao->id]) }}" class="btn btn-primary btn-xs">
                                    <i class="fa fa-trash" aria-hidden="true"></i> Editar
                                </a>
                                <button type="button" onclick="onActionForRequest('{{ route('admin.preparacoes.to_remove', ['id' => $preparacao->id]) }}');" class="btn btn-danger btn-xs">
                                    <i class="fa fa-edit" aria-hidden="true"></i> Excluir
                                </button>
                            @else
                                <a href="{{ route('admin.preparacoes.to_view', ['id' => $preparacao->id]) }}" class="btn btn-primary btn-xs">
                                    <i class="fa fa-eye" aria-hidden="true"></i> Visualizar
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $preparacoes->appends($formData)->links() !!}
        @else
            {!! $preparacoes->links() !!}
        @endif
    </div>
</div>
@endsection