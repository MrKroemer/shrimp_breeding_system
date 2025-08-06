<div class="modal fade" id="usuarios_permissoes_modal" tabindex="-1" role="dialog" aria-labelledby="usuarios_permissoes_modal_label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="usuarios_permissoes_modal_label">Adicionar nova permissão</h4>
      </div>
      <div class="modal-body">
        <!-- Form  -->
        <form id="usuarios_permissoes_form" action="{{ route('admin.usuarios.filiais.permissoes.to_store', ['usuario_id' => $usuario->id, 'usuario_filial_id' => $usuario_filial->id]) }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="permissoes_modulo">Modulo:</label>
                <select name="permissoes_modulo" class="form-control" onchange="onChangePermissoesModulo('{{ url('admin') }}');">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($modulos as $modulo)
                        <option value="{{ $modulo->id }}">{{ $modulo->nome }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="permissoes_opcao">Opção:</label>
                <select name="permissoes_opcao" class="form-control" onchange="onChangePermissoesOpcao('{{ url('admin') }}');">
                    <option value="">..:: Selecione ::..</option>
                </select>
            </div>
            <div class="form-group">
                <label for="permissoes_item">Item:</label>
                <select name="permissoes_item" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                </select>
            </div>
        </form>
        <!-- /Form  -->
      </div>
      <div class="modal-footer">
        <!-- Buttons -->
        <button type="submit" class="btn btn-primary" form="usuarios_permissoes_form"><i class="fa fa-check" aria-hidden="true"></i> Salvar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Cancelar</button>
        <!-- /Buttons -->
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
