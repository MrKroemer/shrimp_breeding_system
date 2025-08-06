@extends('adminlte::page')

@section('title', 'Registro de analises biométricas')

@section('content_header')
<h1>Listagem de analises biométricas</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Analises biométricas</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        @php
            $data_analise   = ! empty($formData['data_analise'])    ? $formData['data_analise']    : old('data_analise');
            $ciclo_id       = ! empty($formData['ciclo_id '])       ? $formData['ciclo_id ']       : old('ciclo_id ');
        @endphp
        <form action="{{ route('admin.analises_biometricas.to_search') }}" method="POST" class="form form-inline">
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
                    <input type="text" name="data_analise" placeholder="Data da análise" class="form-control pull-right" id="date_picker">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.analises_biometricas.to_create') }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
            <a href="{{ route('admin.analises_biometricas.amostras.to_generate') }}" class="btn btn-default">
                <i class="fa fa-file-excel-o" aria-hidden="true"></i> Gerar biometria
            </a>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Cultivo</th>
                    <th>Data da análise</th>
                    <th>Dias de cultivo</th>
                    <th>Total de animais</th>
                    <th>Peso total</th>
                    <th>Peso médio</th>
                    <th>Sobrevivência</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($analises_biometricas as $analise_biometrica)
               
                    <tr>
                        <td>{{ $analise_biometrica->analise->ciclo->tanque->sigla }} ( Ciclo Nº {{ $analise_biometrica->analise->ciclo->numero }} )</td>
                        <td>{{ $analise_biometrica->analise->data_analise() }}</td>
                        <td>{{ $analise_biometrica->analise->ciclo->dias_cultivo($analise_biometrica->analise->data_analise) }} dias</td>
                        <td>{{ $analise_biometrica->analise->total_animais() }} animais</td>
                        <td>{{ (float) $analise_biometrica->analise->peso_total() }} g</td>
                        <td>{{ (float) round($analise_biometrica->analise->peso_medio(),2) }} g</td>
                        <td>{{ (float) $analise_biometrica->analise->sobrevivencia }} %</td>
                        <td>
                            @if ($analise_biometrica->analise->situacao == '1')
                                <span class="label label-danger">Em Coleta</span>
                            @elseif ($analise_biometrica->analise->situacao == '2')
                                <span class="label label-warning">Coletado</span>
                            @else 
                                <span class="label label-success">Validado</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Mais opções <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('admin.analises_biometricas.amostras.to_create', ['analise_biometrica_id' => $analise_biometrica->analise->id, 'ciclo_id' => $ciclo_id, 'data_analise' => $data_analise]) }}"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i>Registro de amostras</a></li>
                                </ul>
                            </div>
                        @if ($analise_biometrica->analise->ciclo->verificarSituacao([6, 7]))
                            <a href="{{ route('admin.analises_biometricas.to_edit', ['id' => $analise_biometrica->analise->id, 'ciclo_id' => $ciclo_id, 'data_analise' => $data_analise]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-edit" aria-hidden="true"></i> Editar
                            </a>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.analises_biometricas.to_remove', ['id' => $analise_biometrica->analise->id, 'ciclo_id' => $ciclo_id, 'data_analise' => $data_analise]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>                                
                        @else
                            <a href="{{ route('admin.analises_biometricas.to_view', ['id' => $analise_biometrica->analise->id, 'ciclo_id' => $ciclo_id, 'data_analise' => $data_analise]) }}" class="btn btn-primary btn-xs">
                                <i class="fa fa-eye" aria-hidden="true"></i> Visualizar
                            </a>
                        @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $analises_biometricas->appends($formData)->links() !!}
        @else
            {!! $analises_biometricas->links() !!}
        @endif
    </div>
</div>
@endsection