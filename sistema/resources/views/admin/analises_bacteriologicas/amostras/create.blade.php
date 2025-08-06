@extends('adminlte::page')

@section('title', 'Registro de análises bacteriológicas')

@section('content_header')
<h1>Registro de amostras</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Análises bacteriológicas</a></li>
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
        <form id="form_analise_bacteriologica_amostra" action="{{ route('admin.analises_bacteriologicas.amostras.to_store', ['analise_laboratorial_id' => $analise_laboratorial->id]) }}" method="POST" onchange="submitByAjax(this.id);">
        {!! csrf_field() !!}
            <div style="margin-bottom: 10px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save" aria-hidden="true"></i> Salvar
                </button>
                <a href="{{ route('admin.analises_bacteriologicas.to_search', ['tanque_tipo_id' => $analise_laboratorial->tanque_tipo_id, 'analise_laboratorial_tipo_id' => $analise_laboratorial->analise_laboratorial_tipo_id, 'data_analise' => $analise_laboratorial->data_analise()]) }}" class="btn btn-success">
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
                                <label for="amarela_opaca_mm1_{{ $tanque->id() }}">Amarela opaca 1mm</label>
                                <input name="amarela_opaca_mm1_{{ $tanque->id() }}" {{ $tanque->amarela_opaca_mm1 == -1 ? 'disabled' : '' }} value="{{ $tanque->amarela_opaca_mm1 >= 0 ? $tanque->amarela_opaca_mm1 : '' }}" type="text" class="form-control" onkeypress="changeFocusOnPressEnter(event);">
                            </div>
                            <div class="col-md-2">
                                <label for="amarela_opaca_mm2_{{ $tanque->id() }}">Amarela opaca 2mm</label>
                                <input name="amarela_opaca_mm2_{{ $tanque->id() }}" {{ $tanque->amarela_opaca_mm1 == -1 ? 'disabled' : '' }} value="{{ $tanque->amarela_opaca_mm2 >= 0 ? $tanque->amarela_opaca_mm2 : '' }}" type="text" class="form-control" onkeypress="changeFocusOnPressEnter(event);">
                            </div>
                            <div class="col-md-2">
                                <label for="amarela_opaca_mm3_{{ $tanque->id() }}">Amarela opaca 3mm</label>
                                <input name="amarela_opaca_mm3_{{ $tanque->id() }}" {{ $tanque->amarela_opaca_mm3 == -1 ? 'disabled' : '' }} value="{{ $tanque->amarela_opaca_mm3 >= 0 ? $tanque->amarela_opaca_mm3 : '' }}" type="text" class="form-control" onkeypress="changeFocusOnPressEnter(event);">
                            </div>
                            <div class="col-md-2">
                                <label for="amarela_opaca_mm4_{{ $tanque->id() }}">Amarela opaca 4mm</label>
                                <input name="amarela_opaca_mm4_{{ $tanque->id() }}" {{ $tanque->amarela_opaca_mm4 == -1 ? 'disabled' : '' }} value="{{ $tanque->amarela_opaca_mm4 >= 0 ? $tanque->amarela_opaca_mm4 : '' }}" type="text" class="form-control" onkeypress="changeFocusOnPressEnter(event);">
                            </div>
                            <div class="col-md-2">
                                <label for="amarela_opaca_mm5_{{ $tanque->id() }}">Amarela opaca +4mm</label>
                                <input name="amarela_opaca_mm5_{{ $tanque->id() }}" {{ $tanque->amarela_opaca_mm5 == -1 ? 'disabled' : '' }} value="{{ $tanque->amarela_opaca_mm5 >= 0 ? $tanque->amarela_opaca_mm5 : '' }}" type="text" class="form-control" onkeypress="changeFocusOnPressEnter(event);">
                            </div>
                            <div class="col-md-1">
                                <button type="button" onclick="ausentarValoresBacteriologia({{ $tanque->id() }});" class="btn btn-primary btn-xs"  style="margin-bottom: 10px;">
                                    Ausentes
                                </button>
                                @if (isset($tanque->analise_bacteriologica_id))
                                    <button type="button" onclick="onActionForRequest('{{ route('admin.analises_bacteriologicas.amostras.to_remove', ['analise_laboratorial_id' => $analise_laboratorial->id, 'id' => $tanque->analise_bacteriologica_id]) }}');" class="btn btn-danger btn-xs">
                                        <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                                    </button>
                                @endif
                            </div>
                        </div>
                        <hr style="margin: 5px 0 5px 0;">
                        <div class="row">
                            <div class="col-md-1">&nbsp;</div>
                            <div class="col-md-2">
                                <input name="amarela_opaca_mm1_inc_{{ $tanque->id() }}" {{ $tanque->amarela_opaca_mm1 == -1 ? 'checked' : '' }} type="checkbox"> Incontáveis
                            </div>
                            <div class="col-md-2">
                                <input name="amarela_opaca_mm2_inc_{{ $tanque->id() }}" {{ $tanque->amarela_opaca_mm2 == -1 ? 'checked' : '' }} type="checkbox"> Incontáveis
                            </div>
                            <div class="col-md-2">
                                <input name="amarela_opaca_mm3_inc_{{ $tanque->id() }}" {{ $tanque->amarela_opaca_mm3 == -1 ? 'checked' : '' }} type="checkbox"> Incontáveis
                            </div>
                            <div class="col-md-2">
                                <input name="amarela_opaca_mm4_inc_{{ $tanque->id() }}" {{ $tanque->amarela_opaca_mm4 == -1 ? 'checked' : '' }} type="checkbox"> Incontáveis
                            </div>
                            <div class="col-md-2">
                                <input name="amarela_opaca_mm5_inc_{{ $tanque->id() }}" {{ $tanque->amarela_opaca_mm5 == -1 ? 'checked' : '' }} type="checkbox"> Incontáveis
                            </div>
                            <div class="col-md-1">&nbsp;</div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">&nbsp;</div>
                        </div>
                        <div class="row">
                            <div class="col-md-1">&nbsp;</div>
                            <div class="col-md-2">
                                <label for="amarela_esverdeada_{{ $tanque->id() }}">Amarela esverdeada</label>
                                <input name="amarela_esverdeada_{{ $tanque->id() }}" {{ $tanque->amarela_esverdeada == -1 ? 'disabled' : '' }} value="{{ $tanque->amarela_esverdeada >= 0 ? $tanque->amarela_esverdeada : '' }}" type="text" class="form-control" onkeypress="changeFocusOnPressEnter(event);">
                            </div>
                            <div class="col-md-2">
                                <label for="verde_{{ $tanque->id() }}">Verde</label>
                                <input name="verde_{{ $tanque->id() }}" {{ $tanque->verde == -1 ? 'disabled' : '' }} value="{{ $tanque->verde >= 0 ? $tanque->verde : '' }}" type="text" class="form-control" onkeypress="changeFocusOnPressEnter(event);">
                            </div>
                            <div class="col-md-2">
                                <label for="azul_{{ $tanque->id() }}">Azul</label>
                                <input name="azul_{{ $tanque->id() }}" {{ $tanque->azul == -1 ? 'disabled' : '' }} value="{{ $tanque->azul >= 0 ? $tanque->azul : '' }}" type="text" class="form-control" onkeypress="changeFocusOnPressEnter(event);">
                            </div>
                            <div class="col-md-2">
                                <label for="translucida_{{ $tanque->id() }}">Translúcida</label>
                                <input name="translucida_{{ $tanque->id() }}" {{ $tanque->translucida == -1 ? 'disabled' : '' }} value="{{ $tanque->translucida >= 0 ? $tanque->translucida : '' }}" type="text" class="form-control" onkeypress="changeFocusOnPressEnter(event);">
                            </div>
                            <div class="col-md-2">
                                <label for="preta_{{ $tanque->id() }}">Preta</label>
                                <input name="preta_{{ $tanque->id() }}" {{ $tanque->preta == -1 ? 'disabled' : '' }} value="{{ $tanque->preta >= 0 ? $tanque->preta : '' }}" type="text" class="form-control" onkeypress="changeFocusOnPressEnter(event);">
                            </div>
                            <div class="col-md-1">&nbsp;</div>
                        </div>
                        <hr style="margin: 5px 0 5px 0;">
                        <div class="row">
                            <div class="col-md-1">&nbsp;</div>
                            <div class="col-md-2">
                                <input name="amarela_esverdeada_inc_{{ $tanque->id() }}" {{ $tanque->amarela_esverdeada == -1 ? 'checked' : '' }} type="checkbox"> Incontáveis
                            </div>
                            <div class="col-md-2">
                                <input name="verde_inc_{{ $tanque->id() }}" {{ $tanque->verde == -1 ? 'checked' : '' }} type="checkbox"> Incontáveis
                            </div>
                            <div class="col-md-2">
                                <input name="azul_inc_{{ $tanque->id() }}" {{ $tanque->azul == -1 ? 'checked' : '' }} type="checkbox"> Incontáveis
                            </div>
                            <div class="col-md-2">
                                <input name="translucida_inc_{{ $tanque->id() }}" {{ $tanque->translucida == -1 ? 'checked' : '' }} type="checkbox"> Incontáveis
                            </div>
                            <div class="col-md-2">
                                <input name="preta_inc_{{ $tanque->id() }}" {{ $tanque->preta == -1 ? 'checked' : '' }} type="checkbox"> Incontáveis
                            </div>
                            <div class="col-md-1">&nbsp;</div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">&nbsp;</div>
                        </div>
                        <div class="row">
                            <div class="col-md-1">&nbsp;</div>
                            <div class="col-md-10">
                                <label for="observacoes_{{ $tanque->id() }}">Observações (255 caracteres)</label>
                                <input name="observacoes_{{ $tanque->id() }}" value="{{ $tanque->observacoes ? $tanque->observacoes : '' }}" type="text" maxlength="255" class="form-control" onkeypress="changeFocusOnPressEnter(event);"> 
                            </div> 
                            <div class="col-md-1">&nbsp;</div>
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
                <a href="{{ route('admin.analises_bacteriologicas.to_search', ['tanque_tipo_id' => $analise_laboratorial->tanque_tipo_id, 'analise_laboratorial_tipo_id' => $analise_laboratorial->analise_laboratorial_tipo_id, 'data_analise' => $analise_laboratorial->data_analise()]) }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection