@extends('adminlte::page')

@section('title', 'Gráfico de parâmetros')

@section('content_header')
<h1>Gráfico de parâmetros</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Gráfico de parâmetros</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')
<style>
    .text{
      position: relative;
      left: 43%;
      top: 36px;
      z-index: 1;
      font-size: 20px;
      color: #fff;
      font-weight: 800;
    }

    .btnNew {
    position: relative;
    top: -3px;
    width: 100%;
    height: 29px;
    background-color: #e56c69;
    color: #fff;
    border-style: none;
    border-radius: 3px;
    }

    .btnNew:hover{
        background-color: rgb(255, 160, 160);
        opacity: 5;
    }

    .btnNew:visited{
        background-color: aqua;
    }
</style>
<script>
    $(document).ready(function () {
    
        var checkbox_tanque = $('input[type="checkbox"]');
        var allCheckbox = $("#all");

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

        let checkbox = document.querySelectorAll('.tanques');
        let btn = document.querySelector('#selectAll');

        btn.addEventListener('click', () =>{
            for(current of checkbox){
                current.checked = !current.checked;
                if(current.checked){
                   btn.style.backgroundColor = '#72afd2';
                }else{
                    btn.style.backgroundColor = '#e56c69';
                }
            } 
        })
      });  
    </script>
<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $data_inicial = ! empty(session('data_inicial')) ? session('data_inicial') : old('data_inicial');
            $data_final   = ! empty(session('data_final'))   ? session('data_final')   : old('data_final');
        @endphp
        <form action="{{ route('admin.graficos_coletas_parametros.to_generate') }}" method="POST">
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
            <div class="form-group">
                <label for="parametros[]">Parâmetros:</label>
                <select name="parametros[]" class="select2_multiple" multiple="multiple">
                    @foreach($parametros as $parametro)
                        <option value="{{ $parametro->id }}" >{{ $parametro->descricao }} ({{ $parametro->sigla }})</option>
                    @endforeach
                </select>
            </div>
            <!-- <div class="form-group"> Select de viveiros
                <label for="tanques[]">Tanques:</label>
                <select name="tanques[]" class="select2_multiple" multiple="multiple">
                @foreach ($setores as $setor)
                    <optgroup label="{{ $setor->nome }}">
                    @foreach ($setor->tanques->sortBy('sigla') as $tanque)
                        <option value="{{ $tanque->id }}">{{ $tanque->sigla }}</option>
                    @endforeach
                    </optgroup>
                @endforeach
                </select>
            </div> -->
            <p class="text">Selecionar tudo</p>
            <input type="button" id="selectAll" placeholder="Selecionar todos" class="btnNew">

            @foreach ($setores as $setor)    
            <div class="form-group" id="setor_{{ $setor->id }}" name="setores">
                <div class="box box-default box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ $setor->nome }} <a style="font-size: 9px;" href="{{ route('admin.graficos_coletas_parametros.listagem', ['listagem' => 2]) }}">Listagem Completa<a/></h3>
                    </div>
                    <div class="box-body" id="grid"> 
                        <div class="row all">  
                        @if($listagem == 1)
                            @forelse ($setor->viveiros_bercarios->sortBy('sigla') as $tanque)   
                                <div class="col-xs-2">
                                    <div style="margin: 5px 0 5px 0;">
                                        <input class="tanques" type="checkbox" name="tanque_{{ $tanque->id }}" placeholder="{{ $tanque->sigla }}">
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
                                        <input type="checkbox" class="tanques" name="tanque_{{ $tanque->id }}" placeholder="{{ $tanque->sigla }}" value="tanque">
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