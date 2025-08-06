@extends('adminlte::page')

@section('title', 'Registro de lotes de pós-larvas')

@section('content_header')
<h1>Listagem de lotes de pós-larvas</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Lotes de pós-larvas</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header">
        <form action="{{ route('admin.lotes.to_search') }}" method="POST" class="form form-inline">
            {!! csrf_field() !!}
            <div class="form-group">
                <input type="text" name="id" class="form-control" placeholder="ID">
            </div>
            <div class="form-group">
                <input type="text" name="lote_fabricacao" class="form-control" placeholder="Lote de fabricacao">
            </div>
            <div class="form-group">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_inicio" placeholder="Início do período" class="form-control pull-right" id="datetime_picker">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group date">
                    <div class="input-group-addon">
                        <a onclick="clearDateValue(this);"><i class="fa fa-calendar"></i></a>
                    </div>
                    <input type="text" name="data_fim" placeholder="Fim do período" class="form-control pull-right" id="datetime_picker">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search" aria-hidden="true"></i> Buscar
            </button>
            <a href="{{ route('admin.lotes.to_create') }}" class="btn btn-success">
                <i class="fa fa-plus" aria-hidden="true"></i> Adicionar
            </a>
        </form>
    </div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Lote de fabricação</th>
                    <th>Quantidade</th>
                    <th>Classe/Idade</th>
                    <th>Espécie</th>
                    <th>Entrada (Fazenda)</th>
                    <th>Info. biométricas</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lotes as $lote)
                    <tr>
                        <td>{{ $lote->id }}</td>
                        <td>Nº 
                            @if ($lote->lote_fabricacao)
                                {{ $lote->lote_fabricacao }}
                            @else
                                @foreach($lote->lote_larvicultura()->get() as $lote_larvicultura)
                                    {{ $lote_larvicultura->lote }}/
                                @endforeach
                            @endif
                        </td>
                        <td>{{ $lote->quantidade }} Und.</td>
                        <td>{{ $lote->classe_idade }}</td>
                        <td>{{ $lote->especie->nome_cientifico }}</td>
                        <td>{{ $lote->data_entrada() }}</td>
                        <td>
                            @if (is_null($lote->lote_biometria))
                                <p style="color:red;">NÃO INFORMADAS</p>
                            @else
                                <p style="color:green;">INFORMADAS</p>                                
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Mais opções <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('admin.lotes.biometrias.to_create', ['lote_id' => $lote->id]) }}"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i> Informações biométricas</a></li>
                                </ul>
                            </div>
                            {{--@if (is_null($lote->povoamento))--}}
                                <a href="{{ route('admin.lotes.to_edit', ['id' => $lote->id]) }}" class="btn btn-primary btn-xs">
                                    <i class="fa fa-edit" aria-hidden="true"></i> Editar
                                </a>
                                <button type="button" onclick="onActionForRequest('{{ route('admin.lotes.to_remove', ['id' => $lote->id]) }}');" class="btn btn-danger btn-xs">
                                    <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                                </button>
                            {{--@else
                                <button class="btn btn-primary btn-xs" disabled>
                                    <i class="fa fa-edit" aria-hidden="true"></i> Editar
                                </button>
                                <button class="btn btn-danger btn-xs" disabled>
                                    <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                                </button>
                            @endif--}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if(isset($formData))
            {!! $lotes->appends($formData)->links() !!}
        @else
            {!! $lotes->links() !!}
        @endif
    </div>
</div>
@endsection