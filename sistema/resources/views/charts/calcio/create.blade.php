@extends('adminlte::page')

@section('title', 'Gráfico de análise de cálcio')

@section('content_header')
<h1>Gráfico de análise de cálcio</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Gráfico de análise de cálcio</a></li>
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
            $data_inicial = ! empty(session('data_inicial')) ? session('data_inicial') : old('data_inicial');
            $data_final   = ! empty(session('data_final'))   ? session('data_final')   : old('data_final');
        @endphp
        <form action="{{ route('admin.graficos_calcio.to_generate') }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="data_inicial">Data inicial:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_inicial" placeholder="Data inicial" class="form-control pull-right" id="date_picker" value="{{ $data_inicial }}">
                </div>
            </div>
            <div class="form-group">
                <label for="data_final">Data final:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_final" placeholder="Data final" class="form-control pull-right" id="date_picker" value="{{ $data_final }}">
                </div>
            </div>
            @foreach ($setores as $setor)
                
            <div class="form-group" id="setor_{{ $setor->id }}" name="setores">
                <div class="box box-default box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ $setor->nome }} <a style="font-size: 9px;" href="{{ route('admin.graficos_coletas_parametros.listagem', ['listagem' => 2]) }}">Listagem Completa<a/></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                        @if($listagem == 1)
                            @forelse ($setor->viveiros_bercarios->sortBy('sigla') as $tanque)
                                <div class="col-xs-2">
                                    <div style="margin: 5px 0 5px 0;">
                                        <input type="checkbox" name="tanque_{{ $tanque->id }}" placeholder="{{ $tanque->sigla }}">
                                    </div>
                                </div>
                            @empty
                                <div class="col-xs-12">
                                    <i>Não há tanques</i>
                                </div>
                            @endforelse
                        @else
                            @forelse ($setor->tanques->sortBy('sigla') as $tanque)
                                <div class="col-xs-2">
                                    <div style="margin: 5px 0 5px 0;">
                                        <input type="checkbox" name="tanque_{{ $tanque->id }}" placeholder="{{ $tanque->sigla }}">
                                    </div>
                                </div>
                            @empty
                                <div class="col-xs-12">
                                    <i>Não há tanques</i>
                                </div>
                            @endforelse
                        @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="form-group">
                <input type="submit" class="btn btn-success" value="Gerar gráficos">
            </div>
        </form>
    </div>
</div>
@endsection