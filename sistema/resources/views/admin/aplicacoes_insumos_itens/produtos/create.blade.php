<div class="modal fade" id="aplicacoes_insumos_produtos_modal" tabindex="-1" role="dialog" aria-labelledby="aplicacoes_insumos_produtos_modal_label">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="aplicacoes_insumos_produtos_modal_label">Cadastro de produtos para aplicação</h4>
      </div>
      <div class="modal-body">
        <!-- Form  -->
        <form id="aplicacoes_insumos_produtos_form" action="{{ 
            route('admin.aplicacoes_insumos.aplicacoes_insumos_produtos.to_store', ['aplicacao_insumo_id' => $aplicacao_insumo->id])
        }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="form-group">
                <label for="produto_id">Produto:</label>
                <select name="produto_id" class="form-control">
                    <option value="">..:: Selecione ::..</option>
                    @foreach($produtos as $produto)
                        <option value="{{ $produto->produto_id }}">{{ $produto->produto_nome }} (Und.: {{ $produto->unidade_saida->sigla }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="quantidade">Quantidade:</label>
                <input type="text" name="quantidade" placeholder="Quantidade" class="form-control">
            </div>
        </form>
        <!-- /Form  -->
      </div>
      <div class="modal-footer">
        <!-- Buttons -->
        <button type="submit" class="btn btn-primary" form="aplicacoes_insumos_produtos_form"><i class="fa fa-check" aria-hidden="true"></i> Salvar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Cancelar</button>
        <!-- /Buttons -->
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
