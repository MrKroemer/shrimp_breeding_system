<div class="modal fade" id="grupos_usuarios_modal" tabindex="-1" role="dialog" aria-labelledby="grupos_usuarios_modal_label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="grupos_usuarios_modal_label">Adicionar novo usuário</h4>
      </div>
      <div class="modal-body">
        @php
            $usuarios = \App\Models\Usuarios::orderBy('nome')->get();
        @endphp
        <!-- Form  -->
        <form id="grupos_usuarios_form" action="{{ route('admin.grupos.grupos_usuarios.to_store', ['grupo_id' => $grupo_id]) }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="usuarios_usuario">Usuário:</label>
                <select name="usuarios_usuario" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($usuarios as $usuario)
                        <option value="{{ $usuario->id }}">{{ $usuario->nome }}</option>
                    @endforeach
                </select>
            </div>
        </form>
        <!-- /Form  -->
      </div>
      <div class="modal-footer">
        <!-- Buttons -->
        <button type="submit" class="btn btn-primary" form="grupos_usuarios_form"><i class="fa fa-check" aria-hidden="true"></i> Salvar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Cancelar</button>
        <!-- /Buttons -->
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
