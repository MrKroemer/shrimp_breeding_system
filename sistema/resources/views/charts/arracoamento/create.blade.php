@extends('adminlte::page')

@section('title', 'Gráfico de alimentação')

@section('content_header')
<h1>Gráficos de Alimentação</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Gráficos de Alimentação</a></li>
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
    <div class="box-header">
        
    </div>
    <div class="box-body">
        @php
            $data_inicial = !empty(session('data_inicio')) ? session('data_inicio') : old('data_inicio');
            $data_final = !empty(session('data_final')) ? session('data_final') : old('data_final');
        @endphp
        <form action="{{ route('admin.graficos_arracoamentos.to_generate') }}" method="POST" target="_blank">
            {!! csrf_field() !!}
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-6">
                        <label for="data_inicio">Data Inicial:</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" name="data_inicio" placeholder="Data Inicial" class="form-control pull-right" id="date_picker" value="{{ $data_inicial }}">
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <label for="data_final">Data Final:</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" name="data_final" placeholder="Data Final" class="form-control pull-right" id="date_picker" value="{{ $data_final }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                @foreach ($setores as $setor)
                    @if($setor->tanques->where('tanque_tipo_id', 1)->count() > 0)
                        <div class="box box-default box-solid">
                            <div class="box-header with-border">
                                <h3 class="box-title">{{ $setor->nome }}</h3>
                            </div>
                            <div class="box-body">    
                                <div class="row">
                                @foreach ($setor->tanques->sortBy('sigla') as $tanque)
                                    @if ($tanque->tanque_tipo_id == 1)
                                        <div class="col-xs-2">
                                            <div style="margin: 5px 0 5px 0;">
                                                <input type="checkbox" name="tanque_{{ $tanque->id }}" placeholder="{{ $tanque->sigla }}">
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            <div>
            <div class="form-group">
                <button type="submit" class="btn btn-success"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Gerar Gráficos</button>
            </div>
        </form>
    </div>
</div>
@endsection