<div class="modal fade" id="arracoamento_horario_modal_{{ $arracoamento_horario->id }}" tabindex="-1" role="dialog" aria-labelledby="arracoamento_horario_modal_label_{{ $arracoamento_horario->id }}">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="arracoamento_horario_modal_label_{{ $arracoamento_horario->id }}">Adicionar aplicação</h4>
            </div>
            <div class="modal-body">
                <form id="arracoamento_horario_form_{{ $arracoamento_horario->id }}" action="{{ route('admin.arracoamentos.arracoamentos_aplicacoes.to_store', ['arracoamento_id' => $arracoamento->id, 'arracoamento_horario_id' => $arracoamento_horario->id]) }}" method="POST">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label for="add_arracoamento_aplicacao_tipo_id_{{ $arracoamento_horario->id }}">Aplicação de:</label>
                        <select name="add_arracoamento_aplicacao_tipo_id_{{ $arracoamento_horario->id }}" class="form-control" 
                        onchange="onChangeArracoamentoAplicacaoTipo('{{ url('admin') }}', ['add_arracoamento_aplicacao_tipo_id_{{ $arracoamento_horario->id }}', 'add_arracoamento_aplicacao_item_id_{{ $arracoamento_horario->id }}']);">
                            <option value="">..:: Selecione ::..</option>
                            @foreach($arracoamentos_aplicacoes_tipos as $arracoamento_aplicacao_tipo)
                                <option value="{{ $arracoamento_aplicacao_tipo->id }}">{{ $arracoamento_aplicacao_tipo->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="add_arracoamento_aplicacao_item_id_{{ $arracoamento_horario->id }}">Item:</label>
                        <select name="add_arracoamento_aplicacao_item_id_{{ $arracoamento_horario->id }}" class="form-control">
                            <option value="">..:: Selecione ::..</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="add_quantidade_{{ $arracoamento_horario->id }}">Quantidade:</label>
                        <input type="number" step="any" name="add_quantidade_{{ $arracoamento_horario->id }}" placeholder="Quantidade" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" form="arracoamento_horario_form_{{ $arracoamento_horario->id }}"><i class="fa fa-check" aria-hidden="true"></i> Salvar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Cancelar</button>
            </div>
        </div>
    </div>
</div>