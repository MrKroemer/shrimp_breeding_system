<div class="modal fade" id="user_image_modal" tabindex="-1" role="dialog" aria-labelledby="user_image_modal_label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="user_image_modal_label">Alteração da imagem do usuário</h4>
            </div>
            <div class="modal-body">
                <!-- Form  -->
                <form id="user_image" action="{{ route('admin.usuarios.imagem.to_update', ['id' => auth()->user()->id]) }}" method="POST" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <div class="form-group" style="width:100%;">
                        <label for="imagem">Adicione uma imagem de 160x160 nos formatos PNG ou JPG.</label>
                        <input type="file" name="imagem" accept="image/png, image/jpeg" class="form-control"/>
                    </div>
                </form>
                <!-- /Form  -->
            </div>
            <div class="modal-footer">
                <!-- Buttons -->
                <button type="button" onclick="onActionForSubmit('user_image');" class="btn btn-primary locked">
                    <i class="fa fa-check" aria-hidden="true"></i> Salvar
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fa fa-times" aria-hidden="true"></i> Cancelar
                </button>
                <!-- /Buttons -->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->