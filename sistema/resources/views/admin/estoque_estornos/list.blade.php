@extends('adminlte::page')

@section('title', 'Histórico de estornos de produtos')

@section('content_header')
<h1>Histórico de estornos de produtos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Estornos de produtos</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.estoque_estornos.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <div class="form-group" style="width: 175px;">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_inicial" placeholder="Á partir do dia" class="form-control pull-right" id="date_picker">
                </div>
            </div>
            <div class="form-group" style="width: 175px;">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_final" placeholder="Até o dia" class="form-control pull-right" id="date_picker">
                </div>
            </div>
            <div class="form-group">
                <select name="tipo_origem" class="form-control" style="width: 190px;">
                    <option value="">..:: Origem do estorno ::..</option>
                    @foreach($tipos_origens as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i> Buscar</button>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Justificativa</th>
                    <th>Origem do estorno</th>
                    <th>Usuário responsável</th>
                    <th>Realizado em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($estornos_justificativas as $estorno_justificativa)
                    <tr>
                        <td>{{ $estorno_justificativa->id }}</td>
                        <td>{{ substr($estorno_justificativa->descricao, 0, 50) }} {{ (strlen($estorno_justificativa->descricao) > 50) ? '[ ... ]' : '' }}</td>
                        <td>{{ $estorno_justificativa->tipo_origem() }}</td>
                        <td>{{ $estorno_justificativa->usuario->nome }}</td>
                        <td>{{ $estorno_justificativa->alterado_em('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.estoque_estornos.produtos', ['estorno_justificativa_id' => $estorno_justificativa->id]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-eye" aria-hidden="true"></i> Visualizar
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $estornos_justificativas->appends($formData)->links() !!}
        @else
            {!! $estornos_justificativas->links() !!}
        @endif
    </div>
</div>
@endsection
