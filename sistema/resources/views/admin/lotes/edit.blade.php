@extends('adminlte::page')

@section('title', 'Registro de lotes de pós-larvas')

@section('content_header')
<h1>Edição de lotes de pós-larvas</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Lotes de pós-larvas</a></li>
    <li><a href="">Edição</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')
@include('admin.especies.create')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        <form action="{{ route('admin.lotes.to_update', ['id' => $id]) }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
 <!--            <div class="form-group">
                <label for="estoque_entrada_id">Pós-larvas em estoque:</label>
                <input type="text" name="estoque_entrada_id" placeholder="Pós-larvas em estoque" class="form-control" value="{{ (float) $poslarva_estocada->quantidade }} UND (NFe: {{ $poslarva_estocada->nota_fiscal_numero }})" disabled>
            </div> -->
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
                <a href="{{ route('admin.lotes') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
    
    

</div>
<div class="box">
    <div class="box-header"><h3>Informações de Recepção da Larvicultura</h3></div>
    <div class="box-body">
        <form action="{{ route('admin.lotes.lotes_larvicultura.to_store', ['lote_id' => $id, 'redirectBack' => $redirectBack, 'povoamento_id' => $povoamento_id]) }}" method="POST">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <label for="lote_fabricacao">Lote de fabricação:</label>
                    <input type="text" name="lote" placeholder="Lote de fabricação" class="form-control" value="">
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <label for="lote_fabricacao">Quantidade:</label>
                    <input type="number" step="any" name="quantidade" placeholder="Lote de fabricação" class="form-control" value="">
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <label for="lote_fabricacao">Sobrevivência:</label>
                    <input type="number" step="any" name="sobrevivencia" placeholder="Lote de fabricação" class="form-control" value="">
                </div>
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <label for="lote_fabricacao">Coeficiente de Variação:</label>
                    <input type="number" step="any" name="cv" placeholder="Lote de fabricação" class="form-control" value="">
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Adicionar Lote</button>
            </div>
        </form>
    </div>
    
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Lote</th>
                    <th>Quantidade</th>
                    <th>Sobrevivência</th>
                    <th>Coeficiente de Variação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lotes_larvicultura as $lote_larvicultura)
                    <tr>
                        <td>{{ $lote_larvicultura->lote }}</td>
                        <td>{{ $lote_larvicultura->quantidade }}</td>
                        <td>{{ round($lote_larvicultura->sobrevivencia) }} %</td>
                        <td>{{ round($lote_larvicultura->cv) }} %</td>
                        <td>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.lotes.lotes_larvicultura.to_remove', ['id' => $lote_larvicultura->id, 'lote_id' => $id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    <tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection