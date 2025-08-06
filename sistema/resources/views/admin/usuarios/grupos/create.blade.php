<div class="modal fade" id="usuarios_grupos_modal" tabindex="-1" role="dialog" aria-labelledby="usuarios_grupos_modal_label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="usuarios_grupos_modal_label">Adicionar novo grupo</h4>
      </div>
      <div class="modal-body">
        @php
            $grupos = \App\Models\Grupos::orderBy('nome')->get();
        @endphp
        <!-- Form  -->
        <form id="usuarios_grupos_form" action="{{ route('admin.usuarios.usuarios_grupos.to_store', ['usuario_id' => $usuario_id]) }}" method="POST">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="grupos_grupo">Grupo:</label>
                <select name="grupos_grupo" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($grupos as $grupo)
                        <option value="{{ $grupo->id }}">{{ $grupo->nome }}</option>
                    @endforeach
                </select>
            </div>
        </form>
        <!-- /Form  -->
      </div>
      <div class="modal-footer">
        <!-- Buttons -->
        <button type="submit" class="btn btn-primary" form="usuarios_grupos_form"><i class="fa fa-check" aria-hidden="true"></i> Salvar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Cancelar</button>
        <!-- /Buttons -->
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
