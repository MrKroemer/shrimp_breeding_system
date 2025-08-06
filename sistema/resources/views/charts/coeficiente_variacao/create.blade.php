@extends('adminlte::page')

@section('title', 'Gráfico de Coeficiente de Variação')

@section('content_header')
<h1>Gráfico de Coeficiente de Variação</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Gráfico de Coeficiente de Variação</a></li>
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
        <h5>* Para gerar o gráfico de todos os ciclos em andamento, basta deixar o campo tanques em branco *</h5>    </div>
    <div class="box-body">
        <form action="{{ route('admin.graficos_coeficiente_variacao.to_generate') }}" method="POST">
            {!! csrf_field() !!}
            <!--div class="form-group">
                <label for="ciclos[]">Tanques:</label>
                <select name="ciclos[]" class="select2_multiple" multiple="multiple">
                    @foreach ($ciclos as $ciclo)
                        <option value="{{ $ciclo->id }}">{{ $ciclo->tanque->sigla }} (Ciclo Nº {{ $ciclo->numero }})</option>
                    @endforeach
                </select>
            </div-->
            <div class="form-group" id="ciclos_ativos" name="ciclos_ativos">
                <div class="box box-default box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">Ciclos Ativos <a style="font-size: 9px;" href="{{ route('admin.graficos_coeficiente_variacao.listagem', ['listagem' => 2]) }}">Listagem Completa<a/></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            @forelse ($ciclos_ativos->sortBy('tanque_sigla') as $tanque)
                                <div class="col-xs-3">
                                    <div style="margin: 5px 0 5px 0;">
                                        <input type="checkbox" name="ciclo_{{ $tanque->ciclo_id }}" placeholder="{{ $tanque->tanque_sigla }} (Ciclo Nº {{ $tanque->ciclo_numero }})">
                                    </div>
                                </div>
                            @empty
                                <div class="col-xs-12">
                                    <i>Não há tanques</i>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            @if($listagem > 1)
                <div class="form-group" id="ciclos_inativos" name="ciclos_inativos">
                    <div class="box box-default box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">Ciclos Inativos <a style="font-size: 9px;" href="{{ route('admin.graficos_coeficiente_variacao.listagem', ['listagem' => 1]) }}">Listagem Simples<a/></h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                            
                                @forelse ($ciclos_inativos->sortBy('tanque_sigla') as $tanque)
                                    <div class="col-xs-3">
                                        <div style="margin: 5px 0 5px 0;">
                                            <input type="checkbox" name="ciclo_{{ $tanque->ciclo_id}}" placeholder="{{ $tanque->tanque_sigla }} (Ciclo Nº {{ $tanque->ciclo_numero }})">
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-xs-12">
                                        <i>Não há tanques</i>
                                    </div>
                                @endforelse
                            
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="form-group">
                <input type="submit" class="btn btn-success" value="Gerar gráficos">
            </div>
        </form>
    </div>
</div>
@endsection