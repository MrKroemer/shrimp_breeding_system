<div class="modal fade" id="imagem_analise_modal" tabindex="-1" role="dialog" aria-labelledby="imagem_analise_modal_label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="imagem_analise_modal_label">Upload da Certificação</h4>
            </div>
            <div class="modal-body">
            <!-- Form  -->
                @php
                    $imagem_certificacao = !empty(session('imagem_certificacao')) ? session('imagem_certificacao') : old('imagem_certificacao');
                @endphp
                <form id="imagem_analise_form" action="" method="POST" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label for="imagem_certificacao">Imagem da Análise:</label>
                        <input type="file" accept="image/png, image/jpeg" name="imagem_certificacao" placeholder="Imagem da análise" class="form-control" value="{{ $imagem_certificacao }}">
                    </div>
                </form>
            <!-- /Form  -->
            </div>
            <div class="modal-footer">
            <!-- Buttons -->
                <button type="submit" class="btn btn-primary" form="imagem_analise_form">
                    <i class="fa fa-download" aria-hidden="true"></i> Importar
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="location.reload();">
                    <i class="fa fa-times" aria-hidden="true"></i> Fechar
                </button>
            <!-- /Buttons -->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->