@extends('adminlte::page')

@section('title', 'Lista de Certificação de Reprodutores')

@section('content_header')
<h1>Listagem de Certificação d Reprodutores</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Certificação de Reprodutores</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        @php
            $data_coleta           = ! empty($formData['data_coleta'])           ? $formData['data_coleta']          : old('data_coleta');
            $numero_certificacao   = ! empty($formData['numero_certificacao'])   ? $formData['numero_certificacao']  : old('numero_certificacao');
            $familia               = ! empty($formData['familia'])               ? $formData['familia']              : old('familia');
        @endphp
        <form action="{{ route('admin.analises_biometricas.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <div class="form-group">
                <input type="text" name="numero_certificacao" class="form-control" placeholder="Número Certificação">
            </div>
            <div class="form-group">
                <input type="text" name="familia" class="form-control" placeholder="Familia">
            </div>
            <div class="form-group">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_coleta" placeholder="Data da Coleta" class="form-control pull-right" id="date_picker">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.certificacao_reprodutores.to_create') }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Numero Certificação</th>
                    <th>Plantel</th>
                    <th>Familia</th>
                    <th>Data Coleta </th>
                    <th>Data Estresse</th>
                    <th>Data Certificação</th>
                    <th>Tanque de Origem</th>
                    <th>Tanque de Maturação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($certificacao_reprodutores as $certificacao)
               
                    <tr>
                        <td>{{ $certificacao->numero_certificacao }} </td>
                        <td>{{ $certificacao->plantel }}</td>
                        <td>{{ $certificacao->familia }}</td>
                        <td>{{ $certificacao->data_coleta() }}</td>
                        <td>{{ $certificacao->data_estresse() }}</td>
                        <td>{{ $certificacao->data_certificacao() }}</td>
                        <td>{{ $certificacao->tanque_origem->sigla }}</td>
                        <td>{{ $certificacao->tanque_maturacao->sigla }}</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Mais opções <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('admin.certificacao_reprodutores.reprodutores.to_create', ['id' => $certificacao->id] ) }}"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i>Registro de Reprodutores</a></li>
                                </ul>
                            </div>
                       
                            <a href="{{ route('admin.certificacao_reprodutores.to_edit', ['id' => $certificacao->id] ) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-edit" aria-hidden="true"></i> Editar
                            </a>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.certificacao_reprodutores.to_remove', ['id' => $certificacao->id] ) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>                                
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $certificacao_reprodutores->appends($formData)->links() !!}
        @else
            {!! $certificacao_reprodutores->links() !!}
        @endif
    </div>
</div>
@endsection