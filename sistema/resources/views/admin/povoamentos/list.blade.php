@extends('adminlte::page')

@section('title', 'Registro de povoamentos de tanques')

@section('content_header')
<h1>Listagem de povoamentos de tanques</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Povoamentos de tanques</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.povoamentos.to_search') }}" method="POST" class="form form-inline">
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
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.povoamentos.to_create') }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Cultivo</th>
                    <th>Data de início</th>
                    <th>Data de fim</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($povoamentos as $povoamento)
                    <tr>
                        <td>{{ $povoamento->ciclo->tanque->sigla }} ( Ciclo Nº {{ $povoamento->ciclo->numero }} )</td>
                        <td>{{ $povoamento->data_inicio() }}</td>
                        <td>{{ $povoamento->data_fim() ?: '- - / - - / - - - -' }}</td>
                        <td>
                            @if ($povoamento->ciclo->situacao == 5)
                                <p style="font-weight:bold;color:green;">EM POVOAMENTO</p>
                            @else
                                <p style="font-weight:bold;color:red;">POVOAMENTO ENCERRADO</p>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Mais opções <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('admin.povoamentos.lotes.to_edit', ['povoamento_id' => $povoamento->id]) }}"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i> Lotes de pós-larvas</a></li>
                                </ul>
                            </div>
                            @if ($povoamento->ciclo->verificarSituacao([5]))
                                {{-- <button type="button" onclick="onActionForRequest('{{ route('admin.povoamentos.to_close', ['id' => $povoamento->id]) }}');" class="btn btn-warning btn-xs">
                                    <i class="fa fa-check" aria-hidden="true"></i> Encerrar povoamento
                                </button> --}}
                                <button type="button" onclick="onActionForRequest('{{ route('admin.povoamentos.to_remove', ['id' => $povoamento->id]) }}');" class="btn btn-danger btn-xs">
                                    <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $povoamentos->appends($formData)->links() !!}
        @else
            {!! $povoamentos->links() !!}
        @endif
    </div>
</div>
@endsection