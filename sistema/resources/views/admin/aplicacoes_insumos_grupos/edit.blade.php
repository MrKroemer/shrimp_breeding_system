@extends('adminlte::page')

@section('title', 'Grupos de aplicações de insumos')

@section('content_header')
<h1>Edição de grupos de aplicações de insumos</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Grupos de aplicações de insumos</a></li>
    <li><a href="">Edição</a></li>
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

        if (this.checked) {
            div_class = $(this).parent().attr('class').replace('red', 'blue');
            $(this).parent().attr('class', div_class);
        }
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
        <form action="{{ route('admin.aplicacoes_insumos_grupos.to_update', ['id' => $aplicacao_insumo_grupo->id]) }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="nome">Nome do grupo:</label>
                <input type="text" name="nome" placeholder="Nome do grupo" class="form-control" value="{{ $aplicacao_insumo_grupo->nome }}">
            </div>
            <div class="form-group">
                <label for="descricao">Descrição sobre o grupo:</label>
                <textarea rows="2" name="descricao" placeholder="Descrição sobre o grupo" class="form-control" style="width:100%;">{{ $aplicacao_insumo_grupo->descricao }}</textarea>
            </div>
            
            @foreach ($setores as $setor)
            <div class="form-group" id="setor_{{ $setor->id }}" name="setores">
                <div class="box box-default box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ $setor->nome }}</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                        @forelse ($setor->tanques->sortBy('sigla') as $tanque)
                            @php
                                $aplicacao_insumo_grupo_tanque = $tanque->aplicacao_insumo_grupo;
                            @endphp
                            <div class="col-xs-2">
                                <div style="margin: 5px 0 5px 0;" title="
                                @if ($aplicacao_insumo_grupo_tanque)
                                    {{ ($aplicacao_insumo_grupo->id != $aplicacao_insumo_grupo_tanque->aplicacao_insumo_grupo_id) ? "Pertence a: {$aplicacao_insumo_grupo_tanque->nome}" : '' }}
                                @else
                                    {{ 'Não possui grupo' }}
                                @endif
                                ">
                                    <input type="checkbox" name="tanque_{{ $tanque->id }}" placeholder="{{ $tanque->sigla }}"
                                    @if ($aplicacao_insumo_grupo_tanque)
                                        {{ ($aplicacao_insumo_grupo->id == $aplicacao_insumo_grupo_tanque->aplicacao_insumo_grupo_id) ? 'checked' : '' }}
                                    @endif
                                    >
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
            @endforeach

            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.aplicacoes_insumos_grupos') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
