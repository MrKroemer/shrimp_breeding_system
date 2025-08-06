@extends('adminlte::page')

@section('title', 'Registro de análises magnésicas')

@section('content_header')
<h1>Registro de amostras</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Análises magnésicas</a></li>
    <li><a href="">Registro de amostras</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <div class="row">
            <div class="col-md-4">
                <label for="data_analise">Data da análise</label>
                <input value="{{ $analise_laboratorial->data_analise() }}" name="data_analise" type="text" class="form-control" placeholder="Data da análise" disabled>
            </div>
            <div class="col-md-4">
                <label for="tanque_tipo">Tipo de tanque</label>
                <input value="{{ $analise_laboratorial->tanque_tipo->nome }}" name="tanque_tipo" type="text" class="form-control" placeholder="Tipo de tanque" disabled>
            </div>
            <div class="col-md-4">
                <label for="tipo_analise">Tipo da análise</label>
                <input value="{{ $analise_laboratorial->analise_laboratorial_tipo->nome }}" name="tipo_analise" type="text" class="form-control" placeholder="Tipo de análise" disabled>
            </div>
        </div>
    </div>
    <div class="box-body">
        <form id="form_analise_magnesio_amostra" action="{{ route('admin.analises_magnesicas.amostras.to_store', ['analise_laboratorial_id' => $analise_laboratorial->id]) }}" method="POST" onchange="submitByAjax(this.id);">
        {!! csrf_field() !!}
            <div style="margin-bottom: 10px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save" aria-hidden="true"></i> Salvar
                </button>
                <a href="{{ route('admin.analises_magnesicas.to_search', ['tanque_tipo_id' => $analise_laboratorial->tanque_tipo_id, 'analise_laboratorial_tipo_id' => $analise_laboratorial->analise_laboratorial_tipo_id, 'data_analise' => $analise_laboratorial->data_analise()]) }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
            <table class="table table-striped table-hover">
                @foreach ($tanques as $tanque)
                <tr>
                    <td>
                        <div class="row">
                            <div class="col-md-1">
                                <span class="label label-default" style="font-size: 14px;">{{ $tanque->sigla() }}</span>
                            </div>
                            <div class="col-md-2">
                                <label for="dureza_{{ $tanque->id() }}">Dureza do magnésio</label>
                                <input name="dureza_{{ $tanque->id() }}" {{ $tanque->dureza == -1 ? 'disabled' : '' }} value="{{ $tanque->dureza >= 0 ? $tanque->dureza : '' }}" type="text" class="form-control" onkeypress="changeFocusOnPressEnter(event);">
                            </div>
                            <div class="col-md-2">
                                <label for="moleculas_{{ $tanque->id() }}">Magnésio</label>
                                <input name="moleculas_{{ $tanque->id() }}" {{ $tanque->moleculas == -1 ? 'disabled' : '' }} value="{{ $tanque->moleculas >= 0 ? $tanque->moleculas : '' }}" type="text" class="form-control" onkeypress="changeFocusOnPressEnter(event);">
                            </div>
                            <div class="col-md-1">
                                <button type="button" onclick="ausentarValoresCalcicas({{ $tanque->id() }});" class="btn btn-primary btn-xs"  style="margin-bottom: 10px;">
                                    Ausentes
                                </button>
                                @if (isset($tanque->analise_calcica_id))
                                    <button type="button" onclick="onActionForRequest('{{ route('admin.analises_magnesicas.amostras.to_remove', ['analise_laboratorial_id' => $analise_laboratorial->id, 'id' => $tanque->analise_calcica_id]) }}');" class="btn btn-danger btn-xs">
                                        <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                                    </button>
                                @endif
                            </div>
                        </div>
                        <hr style="margin: 5px 0 5px 0;">
                    </td>
                </tr>
                @endforeach
            </table>
            <div style="margin-bottom: 10px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save" aria-hidden="true"></i> Salvar
                </button>
                <a href="{{ route('admin.analises_calcicas.to_search', ['tanque_tipo_id' => $analise_laboratorial->tanque_tipo_id, 'analise_laboratorial_tipo_id' => $analise_laboratorial->analise_laboratorial_tipo_id, 'data_analise' => $analise_laboratorial->data_analise()]) }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection