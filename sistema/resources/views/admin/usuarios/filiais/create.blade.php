<div class="modal fade" id="usuarios_filiais_modal" tabindex="-1" role="dialog" aria-labelledby="usuarios_filiais_modal_label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="usuarios_filiais_modal_label">Adicionar nova filial ao usuário</h4>
      </div>
      <div class="modal-body">
        <!-- Form  -->
        <form id="usuarios_filiais_form" action="{{ route('admin.usuarios.filiais.to_store', ['usuario_id' => $usuario->id]) }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="filial_id">Filial:</label>
                <select name="filial_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($filiais as $filial)
                        <option value="{{ $filial->id }}">{{ $filial->nome }}</option>
                    @endforeach
                </select>
            </div>
        </form>
        <!-- /Form  -->
      </div>
      <div class="modal-footer">
        <!-- Buttons -->
        <button type="submit" class="btn btn-primary" form="usuarios_filiais_form"><i class="fa fa-check" aria-hidden="true"></i> Salvar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Cancelar</button>
        <!-- /Buttons -->
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
