@extends('adminlte::page')

@section('title', 'Registro de baixas justificadas')

@section('content_header')
<h1>Validações de produtos de baixas justificadas</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Baixas justificadas</a></li>
    <li><a href="">Validações</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">

        @if ($baixa_justificada->baixas_justificadas_produtos->isNotEmpty()) {{-- TODO: adicionar essa verificação em todas as validações --}}

            @if ($baixa_justificada->situacao == 'N')
                <button type="button" onclick="onActionForRequest('{{ route('admin.baixas_justificadas_validacoes.to_close', ['baixa_justificada_id' => $baixa_justificada->id]) }}');" class="btn btn-success">
                    <i class="fa fa-check" aria-hidden="true"></i> Validar
                </button>
            @else
                @include('admin.estoque_estornos.justificativas.create', [
                    'reverse_route' => route('admin.baixas_justificadas_validacoes.to_reverse', [
                        'baixa_justificada_id' => $baixa_justificada->id,
                    ]),
                    'form_id'   => "form_estorno_baixas_justificadas",
                    'btn_name'  => 'Estornar',
                    'btn_icon'  => 'fa fa-repeat',
                    'btn_class' => 'btn btn-warning',
                ])
            @endif

        @endif

        <a href="{{ route('admin.baixas_justificadas_validacoes'/* , ['data_inicial' => $data_inicial, 'data_final' => $data_final] */) }}" class="btn btn-default">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
        </a>
    </div>
    <div class="box-body">
        
        <form class="form form-inline">
            <div class="form-group" style="width:100%;">
                <label for="descricao">Justificativa da saída:</label>
                <textarea rows="2" name="descricao" placeholder="Justificativa da saída" class="form-control" style="width:100%;" disabled>{{ $baixa_justificada->descricao }}</textarea>
            </div>
        </form>

        <h5 class="box-title" style="font-weight: bold;">Baixas referentes a <span style="color: red;">{{ $baixa_justificada->data_movimento() }}</span></h5>
        
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Quantidade</th>
                    <th>Registrado em</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($baixa_justificada->baixas_justificadas_produtos as $baixa_justificada_produto)
                    <tr>
                        <td>{{ $baixa_justificada_produto->produto_id }}</td>
                        <td>{{ $baixa_justificada_produto->produto->nome }}</td>
                        <td>
                        @if ($baixa_justificada->situacao == 'N')
                            <form class="form form-inline" action="{{ route('admin.baixas_justificadas.produtos.to_update', ['baixa_justificada_id' => $baixa_justificada->id, 'id' => $baixa_justificada_produto->id]) }}" method="POST">
                                {!! csrf_field() !!}
                        @else
                            <form class="form form-inline">
                        @endif
                                <div class="form-group">
                                    <input type="text" name="quantidade" placeholder="Quantidade" class="form-control" style="width:80px;" value="{{ (float) $baixa_justificada_produto->quantidade }}" {{ ($baixa_justificada->situacao != 'N') ? 'disabled' : '' }}>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="unidade" placeholder="Unidade" class="form-control" style="width:80px;" value="{{ $baixa_justificada_produto->produto->unidade_saida->sigla }}" disabled></input>
                                </div>
                                @if ($baixa_justificada->situacao == 'N')
                                    <div class="form-group" style="margin-left: 4%;">
                                        <button type="submit" class="btn btn-primary btn-xs"><i class="fa fa-edit" aria-hidden="true"></i> Alterar</button>
                                    </div>
                                @endif
                            </form>
                        </td>
                        <td>{{ $baixa_justificada_produto->alterado_em('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center;font-style:italic;">Não há registros</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>
@endsection
