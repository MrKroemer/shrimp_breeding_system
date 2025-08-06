@extends('adminlte::page')

@section('title', 'Registro de saídas avulsas')

@section('content_header')
<h1>Validações de produtos de saídas avulsas</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Saídas avulsas</a></li>
    <li><a href="">Validações</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">

        @if (
            $saida_avulsa->saidas_avulsas_produtos->isNotEmpty() ||
            $saida_avulsa->saidas_avulsas_receitas->isNotEmpty()
        ) {{-- TODO: adicionar essa verificação em todas as validações --}}

            @if ($saida_avulsa->situacao == 'B')
                <button type="button" class="btn btn-danger" disabled>
                    <i class="fa fa-ban" aria-hidden="true"></i> Bloqueado
                </button>
            @elseif ($saida_avulsa->situacao == 'N')
                <button type="button" onclick="onActionForRequest('{{ route('admin.saidas_avulsas_validacoes.to_close', ['saida_avulsa_id' => $saida_avulsa->id]) }}');" class="btn btn-success locked">
                    <i class="fa fa-check" aria-hidden="true"></i> Validar
                </button>
            @else
                @include('admin.estoque_estornos.justificativas.create', [
                    'reverse_route' => route('admin.saidas_avulsas_validacoes.to_reverse', [
                        'saida_avulsa_id' => $saida_avulsa->id,
                    ]),
                    'form_id'   => "form_estorno_saidas_avulsas",
                    'btn_name'  => 'Estornar',
                    'btn_icon'  => 'fa fa-repeat',
                    'btn_class' => 'btn btn-warning',
                ])
            @endif

        @endif

        <a href="{{ route('admin.saidas_avulsas_validacoes'/* , ['tanque_id' => $saida_avulsa->tanque_id, 'data_inicial' => $data_inicial, 'data_final' => $data_final] */) }}" class="btn btn-default">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
        </a>
    </div>
    <div class="box-body">

        <form class="form form-inline">
            <div class="form-group" style="width:100%;">
                <label for="descricao">Justificativa da saída:</label>
                <textarea rows="2" name="descricao" placeholder="Justificativa da saída" class="form-control" style="width:100%;" disabled>{{ $saida_avulsa->descricao }}</textarea>
            </div>
        </form>

        <h5 class="box-title" style="font-weight: bold;">Saídas para o <span style="color: red;">{{ $saida_avulsa->tanque->sigla }}</span> referentes a <span style="color: red;">{{ $saida_avulsa->data_movimento() }}</span></h5>

        @if(! is_null($saida_avulsa->saidas_avulsas_produtos))
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Produto avulso</th>
                        <th>Quantidade</th>
                        <th>Registrado em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($saida_avulsa->saidas_avulsas_produtos as $saida_avulsa_produto)
                        <tr>
                            <td>{{ $saida_avulsa_produto->produto_id }}</td>
                            <td>{{ $saida_avulsa_produto->produto->nome }}</td>
                            <td>{{ (float) $saida_avulsa_produto->quantidade }} {{ $saida_avulsa_produto->produto->unidade_saida->sigla }}</td>
                            <td>{{ $saida_avulsa_produto->alterado_em('d/m/Y H:i') }}</td>
                            <th>
                                @if ($saida_avulsa->situacao == 'N')
                                    <button type="button" onclick="onActionForRequest('{{ route('admin.saidas_avulsas.produtos.to_remove', ['saida_avulsa_id' => $saida_avulsa->id, 'id' => $saida_avulsa_produto->id]) }}');" class="btn btn-danger btn-xs">
                                        <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                                    </button>
                                @else
                                    <button class="btn btn-danger btn-xs" disabled>
                                        <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                                    </button>
                                @endif
                            </th>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @if(! is_null($saida_avulsa->saidas_avulsas_receitas))
            <hr>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Receita laboratorial</th>
                        <th>Quantidade</th>
                        <th>Registrado em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($saida_avulsa->saidas_avulsas_receitas as $saida_avulsa_receita)
                        <tr>
                            <td>{{ $saida_avulsa_receita->receita_laboratorial_id }}</td>
                            <td>{{ $saida_avulsa_receita->receita_laboratorial->nome }}</td>
                            <td>{{ (float) $saida_avulsa_receita->quantidade }} {{ $saida_avulsa_receita->receita_laboratorial->unidade_medida->sigla }}</td>
                            <td>{{ $saida_avulsa_receita->alterado_em('d/m/Y H:i') }}</td>
                            <th>
                                @if ($saida_avulsa->situacao == 'N')
                                    <button type="button" onclick="onActionForRequest('{{ route('admin.saidas_avulsas.receitas.to_remove', ['saida_avulsa_id' => $saida_avulsa->id, 'id' => $saida_avulsa_receita->id]) }}');" class="btn btn-danger btn-xs">
                                        <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                                    </button>
                                @else
                                    <button class="btn btn-danger btn-xs" disabled>
                                        <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                                    </button>
                                @endif
                            </th>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

    </div>
</div>
@endsection
