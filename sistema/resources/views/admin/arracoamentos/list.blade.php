@extends('adminlte::page')

@section('title', 'Registro de programações de arraçoamentos')

@section('content_header')
<h1>Programações de arraçoamentos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Programações de arraçoamentos</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        @php
            $data_aplicacao = ! empty($formData['data_aplicacao']) ? $formData['data_aplicacao'] : old('data_aplicacao');
            $setor_id       = ! empty($formData['setor_id'])       ? $formData['setor_id']       : old('setor_id');
        @endphp
        <form action="{{ route('admin.arracoamentos.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <div class="form-group">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_aplicacao" placeholder="Data da aplicação" class="form-control pull-right" id="date_picker" value="{{ $data_aplicacao }}">
                </div>
            </div>
            <div class="form-group">
                <select name="setor_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($setores as $setor)
                        <option value="{{ $setor->id }}" {{ ($setor->id == $setor_id) ? 'selected' : '' }}>{{ $setor->nome }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.arracoamentos.to_create') }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
            @if ($arracoamentos->isNotEmpty())
                <button type="submit" class="btn btn-info" form="form_arracoamentos_fichas">
                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Gerar fichas
                </button>
            @endif
        </form>
    </div>
    <div class="box-body">
        <form id="form_arracoamentos_fichas" action="{{ route('admin.arracoamentos.fichas.to_view') }}" method="POST" target="_blank" class="form form-inline">
        {!! csrf_field() !!}
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Data da aplicação</th>
                        <th>Cultivo</th>
                        <th>Situação</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($arracoamentos as $arracoamento)
                        <tr>
                            <td>{{ $arracoamento->id }}</td>
                            <td>{{ $arracoamento->data_aplicacao() }}</td>
                            <td>
                            @if ($arracoamento->horarios->count() > 0)
                                <span style="font-weight:bold;">
                                    <input type="checkbox" name="arracoamento_{{ $arracoamento->id }}" checked>
                            @else
                                <span>
                            @endif
                                    {{ $arracoamento->tanque->sigla }} ( Ciclo Nº {{ $arracoamento->ciclo->numero }} )
                                </span>
                            </td>
                            <td>
                                <p style="font-weight:bold;{{ ($arracoamento->situacao == 'N') ? 'color:red;' : 'color:green;' }}">
                                    @if ($arracoamento->horarios->count() > 0)
                                        {{ mb_strtoupper($arracoamento->situacao($arracoamento->situacao)) }}
                                    @else
                                        HORÁRIOS NÃO PROGRAMADOS
                                    @endif
                                </p>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Mais opções <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="{{ route('admin.arracoamentos.arracoamentos_horarios', ['arracoamento_id' => $arracoamento->id]) }}"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i>Horários programados</a></li>
                                    </ul>
                                </div>
                                @if ($arracoamento->situacao == 'N')
                                    <button type="button" onclick=" alertaExclusaoArracoamento('{{ route('admin.arracoamentos.to_remove', ['id' => $arracoamento->id]) }}', {{ ($arracoamento->situacao == 'N') }});" class="btn btn-danger btn-xs">
                                        <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                                    </button>
                                @else
                                    <button class="btn btn-danger btn-xs" disabled>
                                        <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </form>
        @if(isset($formData))
            {!! $arracoamentos->appends($formData)->links() !!}
        @else
            {!! $arracoamentos->links() !!}
        @endif
    </div>
</div>
@endsection