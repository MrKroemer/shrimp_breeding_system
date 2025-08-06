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
        <form action="{{ route('admin.analises_biometricas_v2.to_search') }}" method="POST" class="form form-inline">
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
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" name="data_analise" placeholder="Data da análise" class="form-control pull-right" id="date_picker">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.analises_biometricas_v2.to_create') }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
            <a href="{{ route('admin.analises_biometricas_v2.amostras.to_generate') }}" class="btn btn-default">
                <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Gerar Bio Parcial
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
                    <th>Amostra</th>
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
                        <td>{{ $analise_biometrica->ciclo->tanque->sigla }} ( Ciclo Nº {{ $analise_biometrica->ciclo->numero }} )</td>
                        <td>{{ $analise_biometrica->data_analise() }}</td>
                        <td>{{ $analise_biometrica->ciclo->dias_cultivo($analise_biometrica->data_analise) }} dias</td>
                        <td>{{ $analise_biometrica->total_amostras() }} animais</td>
                        <td>{{ (float) $analise_biometrica->peso_total }} g</td>
                        <td>{{ (float) $analise_biometrica->pesomedio() }} g</td>
                        <td>{{ (float) $analise_biometrica->sobrevivencia }} %</td>
                        <td>
                            @if ($analise_biometrica->situacao == 'S')
                                <span class="label label-success">Validado</span>
                            @else
                                <span class="label label-warning">Pendente</span>
                            @endif
                        </td>
                        <td>
                            @if ($analise_biometrica->ciclo->verificarSituacao([6, 7]))
                                @if ($analise_biometrica->situacao != 'S')
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Mais opções <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="{{ route('admin.analises_biometricas_v2.amostras.to_create', ['id' => $analise_biometrica->id]) }}"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i>Gramaturas</a></li>
                                        </ul>
                                    </div>
                                    <a href="{{ route('admin.analises_biometricas_v2.to_edit', ['id' => $analise_biometrica->id]) }}" class="btn btn-primary btn-xs">
                                        <i class="fa fa-edit" aria-hidden="true"></i> Editar
                                    </a>                                    
                                @else
                                <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Mais opções <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="{{ route('admin.analises_biometricas_v2.sobrevivencia.to_edit', ['id' => $analise_biometrica->id]) }}"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i>Editar Sobrevivência</a></li>
                                        </ul>
                                    </div>
                                @endif
                                <button type="button" onclick="onActionForRequest('{{ route('admin.analises_biometricas_v2.to_remove', ['id' => $analise_biometrica->id]) }}');" class="btn btn-danger btn-xs">
                                    <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                                </button>
                            @else
                                <a href="{{ route('admin.analises_biometricas_v2.to_view', ['id' => $analise_biometrica->id]) }}" class="btn btn-primary btn-xs">
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