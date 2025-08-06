@extends('adminlte::page')

@section('title', 'Registro de aplicações de insumos')

@section('content_header')
<h1>Cadastro de aplicações de insumos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Aplicações de insumos</a></li>
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
        @endphp
        <form action="{{ route('admin.aplicacoes_insumos.to_store') }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="data_aplicacao">Data da aplicação:</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" name="data_aplicacao" placeholder="Data da aplicação" class="form-control pull-right" id="date_picker" value="{{ $data_aplicacao }}">
                </div>
            </div>
            <div class="form-group">
                @foreach ($setores as $setor)
                    <div class="box box-default box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{ $setor->nome }}</h3>
                        </div>
                        <div class="box-body">    
                            <div class="row">
                            @foreach ($setor->tanques->sortBy('sigla') as $tanque)
                                @if ($tanque->tanque_tipo_id != 2) {{-- Exceto berçarios --}}
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
                @endforeach
            <div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.aplicacoes_insumos') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
