@extends('adminlte::page')

@section('title', 'Registro de programações de arraçoamentos')

@section('content_header')
<h1>Cadastro de programações de arraçoamentos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Programações de arraçoamentos</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<script>
$(document).ready(function () {
    
    var checkbox_tanque = $('input[type="checkbox"]');

    checkbox_tanque.each(function () {
        $(this).iCheck({
            checkboxClass: 'icheckbox_line-red',
            radioClass: 'iradio_line-red',
            insert: '<div class="icheck_line-icon"></div><b>' + $(this).attr('placeholder') + '</b>'
        });
    });

    checkbox_tanque.on('ifChecked', function () {
        div_class = $(this).parent().attr('class').replace('red', 'blue');
        $(this).parent().attr('class', div_class);
    }).on('ifUnchecked', function () {
        div_class = $(this).parent().attr('class').replace('blue', 'red');
        $(this).parent().attr('class', div_class);
    });

});
</script>

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $data_aplicacao = !empty(session('data_aplicacao')) ? session('data_aplicacao') : old('data_aplicacao');
            $setor_id       = !empty(session('setor_id'))       ? session('setor_id')       : old('setor_id');
        @endphp
        <form action="{{ route('admin.arracoamentos.to_store') }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="data_aplicacao">Data da aplicação:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_aplicacao" placeholder="Data da aplicação" class="form-control pull-right" id="date_picker" value="{{ $data_aplicacao }}">
                </div>
            </div>
            <div class="form-group">
                <label for="setor_id">Setores:</label>
                <select name="setor_id" class="form-control" onchange="onChangeSelectSetores();">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($setores as $setor)
                        <option value="{{ $setor->id }}" {{ ($setor->id == $setor_id) ? 'selected' : '' }}>{{ $setor->nome }}</option>
                    @endforeach
                </select>
            </div>

            @foreach ($setores as $setor)
            <div class="form-group" style="display:none;" id="setor_{{ $setor->id }}" name="setores">
                <div class="box box-default box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ $setor->nome }}</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                        @forelse ($ciclos->getJsonVwCiclosPorSetor(6, $setor->id) as $ciclo)
                            <div class="col-xs-2">
                                <div style="margin: 5px 0 5px 0;">
                                    <input type="checkbox" name="ciclo_{{ $ciclo->ciclo_id }}" placeholder="{{ $ciclo->tanque_sigla }}">
                                </div>
                            </div>
                        @empty
                            <div class="col-xs-12">
                                <i>Não há tanques com ciclo ativo</i>
                            </div>
                        @endforelse
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.arracoamentos') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
