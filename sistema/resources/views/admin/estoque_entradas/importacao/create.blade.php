<div class="modal fade" id="importacao_entradas_modal" tabindex="-1" role="dialog" aria-labelledby="importacao_entradas_modal_label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="importacao_entradas_modal_label">Importação de arquivo de entradas</h4>
            </div>
            <div class="modal-body">
            <!-- Form  -->
                @php
                    $arquivo_importacao = !empty(session('arquivo_importacao')) ? session('arquivo_importacao') : old('arquivo_importacao');
                @endphp
                <form id="importacao_entradas_form" action="{{ route('admin.estoque_entradas.to_import') }}" method="POST" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label for="arquivo_importacao">Arquivo de importação:</label>
                        <input type="file" accept=".csv" name="arquivo_importacao" placeholder="Arquivo de importação" class="form-control" value="{{ $arquivo_importacao }}">
                    </div>
                </form>
            <!-- /Form  -->
            </div>
            <div class="modal-footer">
            <!-- Buttons -->
                <button type="submit" class="btn btn-primary" form="importacao_entradas_form">
                    <i class="fa fa-download" aria-hidden="true"></i> Importar
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fa fa-times" aria-hidden="true"></i> Cancelar
                </button>
            <!-- /Buttons -->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
