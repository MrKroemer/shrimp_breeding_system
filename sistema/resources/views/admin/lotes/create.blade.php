@extends('adminlte::page')

@section('title', 'Registro de lotes de pós-larvas')

@section('content_header')
<h1>Cadastro de lotes de pós-larvas</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Lotes de pós-larvas</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')
@include('admin.especies.create')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            //$lote_fabricacao    = !empty(session('lote_fabricacao'))    ? session('lote_fabricacao')    : old('lote_fabricacao');
            $data_saida         = !empty(session('data_saida'))         ? session('data_saida')         : old('data_saida');
            $data_entrada       = !empty(session('data_entrada'))       ? session('data_entrada')       : old('data_entrada');
            $quantidade         = !empty(session('quantidade'))         ? session('quantidade')         : old('quantidade');
            $classe_idade       = !empty(session('classe_idade'))       ? session('classe_idade')       : old('classe_idade');
            $temperatura        = !empty(session('temperatura'))        ? session('temperatura')        : old('temperatura');
            $salinidade         = !empty(session('salinidade'))         ? session('salinidade')         : old('salinidade');
            $ph                 = !empty(session('ph'))                 ? session('ph')                 : old('ph');
            $especie_id         = !empty(session('especie_id'))         ? session('especie_id')         : old('especie_id');
            $genetica           = !empty(session('genetica'))           ? session('genetica')           : old('genetica');
            $estoque_entrada_id = !empty(session('estoque_entrada_id')) ? session('estoque_entrada_id') : old('estoque_entrada_id');
        @endphp
        <form action="{{ route('admin.lotes.to_store', ['redirectBack' => $redirectBack, 'povoamento_id' => $povoamento_id]) }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="estoque_entrada_id">Pós-larvas em estoque:</label>
                <div class="input-group">
                    <select name="estoque_entrada_id" class="form-control">
                        <option value="">..:: Selecione ::..</option>
                        @foreach($poslarvas_estocadas as $poslarva_estocada)
                            @if($poslarva_estocada->quantidade > 0)
                                <option value="{{ $poslarva_estocada->estoque_entrada_id }}" {{ ($poslarva_estocada->estoque_entrada_id == $estoque_entrada_id) ? 'selected' : '' }}>{{ (float) $poslarva_estocada->quantidade }} UND (NFe: {{ $poslarva_estocada->nota_fiscal_numero }}) </option>
                            @endif
                        @endforeach
                    </select>
                    <span class="input-group-btn">
                        <a href="{{ route('admin.lotes.redirect_to.notas_fiscais_entradas.to_create') }}" class="btn btn-success btn-flat">
                            <i class="fa fa-plus" aria-hidden="true"></i> Entrada de NFe
                        </a>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <label for="quantidade">Quantidade:</label>
                <input type="number" step="any" name="quantidade" placeholder="Quantidade" class="form-control" value="{{ $quantidade }}">
            </div>
            <div class="form-group">
                <label for="especie_id">Espécie:</label>
                <div class="input-group">
                    <select name="especie_id" class="form-control">
                        <option value="">..:: Selecione ::..</option>
                        @foreach($especies as $especie)
                            <option value="{{ $especie->id }}" {{ ($especie->id == $especie_id) ? 'selected' : '' }}>{{ $especie->nome_cientifico }}</option>
                        @endforeach
                    </select>
                    <span class="input-group-btn">
                        <a class="btn btn-success btn-flat" data-toggle="modal" data-target="#especies_modal">
                            <i class="fa fa-plus" aria-hidden="true"></i> Criar novo
                        </a>
                    </span>
                </div>
            </div>
             <!-- Genetica ******************************************************************************************************************** -->
             <div class="form-group">
                <label for="genetica">Genética:</label>
                <input type="text" name="genetica" placeholder="Genética" class="form-control" style="text-transform:uppercase" value="{{ $genetica }}">
            </div>
            <!-- ****************************************************************************************************************************** -->
            <div class="form-group">
                <label for="classe_idade">Classe/Idade:</label>
                <input type="text" name="classe_idade" placeholder="Classe/Idade" class="form-control" style="text-transform:uppercase" value="{{ $classe_idade }}">
            </div>
            <!--div class="form-group">
                <label for="lote_fabricacao">Lote de fabricação:</label>
                <input type="text" name="lote_fabricacao" placeholder="Lote de fabricação" class="form-control" value="">
            </div-->
            <div class="form-group">
                <label for="data_saida">Data de saída (Larvicultura):</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_saida" placeholder="Data de saída" class="form-control pull-right" id="datetime_picker" value="{{ $data_saida }}">
                </div>
            </div>
            <div class="form-group">
                <label for="data_entrada">Data de entrada (Fazenda):</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_entrada" placeholder="Data de entrada" class="form-control pull-right" id="datetime_picker" value="{{ $data_entrada }}">
                </div>
            </div>
            <div class="form-group">
                <label for="temperatura">Temperatura:</label>
                <input type="number" step="any" name="temperatura" placeholder="Temperatura" class="form-control" value="{{ $temperatura }}">
            </div>
            <div class="form-group">
                <label for="salinidade">Salinidade:</label>
                <input type="number" step="any" name="salinidade" placeholder="Salinidade" class="form-control" value="{{ $salinidade }}">
            </div>
            <div class="form-group">
                <label for="ph">pH:</label>
                <input type="number" step="any" name="ph" placeholder="pH" class="form-control" value="{{ $ph }}">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                @if ($redirectBack == 'yes')
                    <a href="{{ route('admin.lotes.redirect_to.povoamentos.to_create', ['povoamento_id' => $povoamento_id]) }}" class="btn btn-default">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> Povoamento de tanques
                    </a>
                @else
                    <a href="{{ route('admin.lotes') }}" class="btn btn-success">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection