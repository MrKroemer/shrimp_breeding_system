@extends('adminlte::page')

@section('title', 'Registro de lotes de peixes')

@section('content_header')
<h1>Cadastro de ovos coletados</h1>

<ol class="breadcrumb">
    <li><a href="">Dashboard</a></li>
    <li><a href="">Ovos coletados</a></li>
    <li><a href="">Cadastro</a></li>
</ol>
@endsection

@section('content')

@include('admin.includes.alerts')

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        @php
            $qtd_ovos          = !empty(session('qtd_ovos'))          ? session('qtd_ovos')          : old('qtd_ovos');
            $qtd_femeas        = !empty(session('qtd_femeas'))        ? session('qtd_femeas')        : old('qtd_femeas');
            $tanque_origem_id  = !empty(session('tanque_origem_id'))  ? session('tanque_origem_id')  : old('tanque_origem_id');
            $tanque_destino_id = !empty(session('tanque_destino_id')) ? session('tanque_destino_id') : old('tanque_destino_id');
        @endphp
        <form action="{{ route('admin.lotes_peixes.ovos.to_store', ['lote_peixes_id' => $lote_peixes_id]) }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="qtd_ovos">Qtd. de ovos (mL):</label>
                <input type="number" step="any" name="qtd_ovos" placeholder="Qtd. de ovos" class="form-control" value="{{ $qtd_ovos }}">
            </div>
            <div class="form-group">
                <label for="qtd_femeas">Qtd. de fêmeas:</label>
                <input type="number" step="any" name="qtd_femeas" placeholder="Qtd. de fêmeas" class="form-control" value="{{ $qtd_femeas }}">
            </div>
            <div class="form-group">
                <label for="tanque_origem_id">Tanque de origem:</label>
                <select name="tanque_origem_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($tanques as $tanque)
                        @if($tanque->tanque_tipo_id == 9) {{-- Reprodução de peixes --}}
                            <option value="{{ $tanque->id }}" {{ ($tanque->id == $tanque_origem_id) ? 'selected' : '' }}>{{ $tanque->sigla }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="tanque_destino_id">Tanque de destino:</label>
                <select name="tanque_destino_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($tanques as $tanque)
                        @if($tanque->tanque_tipo_id == 8) {{-- Larvicultura de peixes --}}
                            <option value="{{ $tanque->id }}" {{ ($tanque->id == $tanque_destino_id) ? 'selected' : '' }}>{{ $tanque->sigla }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> Salvar</button>
                <a href="{{ route('admin.lotes_peixes') }}" class="btn btn-success">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar para listagem
                </a>
            </div>
        </form>
    </div>
</div>

<div class="box">
    <div class="box-header"></div>
    <div class="box-body">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Qtd. de ovos</th>
                    <th>Qtd. de fêmeas</th>
                    <th>Tanque de origem</th>
                    <th>Tanque de destino</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ovos as $ovo)
                    <tr>
                        <td>{{ $ovo->id }}</td>
                        <td>{{ $ovo->qtd_ovos }} mL</td>
                        <td>{{ $ovo->qtd_femeas }} Und.</td>
                        <td>{{ $ovo->tanque_origem->sigla }}</td>
                        <td>{{ $ovo->tanque_destino->sigla }}</td>
                        <td>
                            <button type="button" onclick="onActionForRequest('{{ route('admin.lotes_peixes.ovos.to_remove', ['lote_peixes_id' => $lote_peixes_id, 'id' => $ovo->id]) }}');" class="btn btn-danger btn-xs">
                                <i class="fa fa-trash" aria-hidden="true"></i> Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection