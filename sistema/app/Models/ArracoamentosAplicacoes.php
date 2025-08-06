<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArracoamentosAplicacoes extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'arracoamentos_aplicacoes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'arracoamento_id',
        'arracoamento_horario_id',
        'arracoamento_aplicacao_tipo_id',
    ];

    public function arracoamento()
    {
        return $this->belongsTo(Arracoamentos::class, 'arracoamento_id', 'id');
    }

    public function arracoamento_horario()
    {
        return $this->belongsTo(ArracoamentosHorarios::class, 'arracoamento_horario_id', 'id');
    }

    public function arracoamento_aplicacao_tipo()
    {
        return $this->belongsTo(ArracoamentosAplicacoesTipos::class, 'arracoamento_aplicacao_tipo_id', 'id');
    }

    public function arracoamento_aplicacao_produto()
    {
        return $this->hasOne(ArracoamentosAplicacoesProdutos::class, 'arracoamento_aplicacao_id', 'id');
    }

    public function arracoamento_aplicacao_receita()
    {
        return $this->hasOne(ArracoamentosAplicacoesReceitas::class, 'arracoamento_aplicacao_id', 'id');
    }

    public function arracoamento_aplicacao_item()
    {
        $arracoamento_aplicacao_item = new \stdClass;

        $arracoamento_aplicacao_item->tipo = $this->arracoamento_aplicacao_tipo_id;

        switch ($arracoamento_aplicacao_item->tipo) {

            case 1: // RAÇÂO
            case 5: // OUTROS INSUMOS

                $produto = $this->arracoamento_aplicacao_produto;

                $arracoamento_aplicacao_item->quantidade     = $produto->quantidade;
                $arracoamento_aplicacao_item->qtd_aplicada   = $produto->qtd_aplicada();
                $arracoamento_aplicacao_item->item_id        = $produto->produto_id;
                $arracoamento_aplicacao_item->nome           = $produto->produto->nome;
                $arracoamento_aplicacao_item->sigla          = $produto->produto->sigla;
                $arracoamento_aplicacao_item->unidade_medida = $produto->produto->unidade_saida->sigla;

                break;

            case 2: // PROBIÓTICO
            case 3: // ADITIVO
            case 4: // VITAMINA

                $receita = $this->arracoamento_aplicacao_receita;
                
                $arracoamento_aplicacao_item->quantidade     = $receita->quantidade;
                $arracoamento_aplicacao_item->qtd_aplicada   = $receita->qtd_aplicada();
                $arracoamento_aplicacao_item->item_id        = $receita->receita_laboratorial_id;
                $arracoamento_aplicacao_item->nome           = $receita->receita_laboratorial->nome;
                $arracoamento_aplicacao_item->sigla          = $receita->receita_laboratorial->nome;
                $arracoamento_aplicacao_item->unidade_medida = $receita->receita_laboratorial->unidade_medida->sigla;

                break;

        }

        return $arracoamento_aplicacao_item;
    }

    public function criarArracoamentoAplicacao(array $data)
    {
        switch($this->arracoamento_aplicacao_tipo_id) {

            // Produtos
            case 1: // RAÇÂO
            case 5: // OUTROS INSUMOS

                $produto = ArracoamentosAplicacoesProdutos::create([
                    'quantidade' => $data["add_quantidade_{$this->arracoamento_horario_id}"],
                    'produto_id' => $data["add_arracoamento_aplicacao_item_id_{$this->arracoamento_horario_id}"],
                    'arracoamento_aplicacao_id' => $this->id,
                    'usuario_id' => auth()->user()->id,
                ]);

                if ($produto instanceof ArracoamentosAplicacoesProdutos) {
                    return true;
                }

                break;

            // Receitas
            case 2: // PROBIÓTICO
            case 3: // ADITIVO
            case 4: // VITAMINA

                $receita = ArracoamentosAplicacoesReceitas::create([
                    'quantidade' => $data["add_quantidade_{$this->arracoamento_horario_id}"],
                    'receita_laboratorial_id'   => $data["add_arracoamento_aplicacao_item_id_{$this->arracoamento_horario_id}"],
                    'arracoamento_aplicacao_id' => $this->id,
                    'usuario_id' => auth()->user()->id,
                ]);

                if ($receita instanceof ArracoamentosAplicacoesReceitas) {
                    return true;
                }

                break;

        }

        return false;
    }

    public function alterarArracoamentoAplicacao(array $data, string $type)
    {
        $this->arracoamento_aplicacao_tipo_id = $data["edit_arracoamento_aplicacao_tipo_id_{$this->id}"];

        switch($this->arracoamento_aplicacao_tipo_id) {

            // Produtos
            case 1: // RAÇÂO
            case 5: // OUTROS INSUMOS

                if (! empty($this->arracoamento_aplicacao_receita)) {

                    $this->arracoamento_aplicacao_receita->delete();

                    $produto = ArracoamentosAplicacoesProdutos::create([
                        'quantidade' => $data["edit_quantidade_{$this->id}"],
                        'produto_id' => $data["edit_arracoamento_aplicacao_item_id_{$this->id}"],
                        'arracoamento_aplicacao_id' => $this->id,
                        'usuario_id' => auth()->user()->id,
                    ]);

                    if ($produto instanceof ArracoamentosAplicacoesProdutos) {
                        
                        $this->save();

                        return true;

                    }

                } else {

                    $produto = $this->arracoamento_aplicacao_produto;

                    $produto->qtd_aplicada = null;

                    if ($type == 'V' && $produto->produto_id == $data["edit_arracoamento_aplicacao_item_id_{$this->id}"]) {
                        $produto->qtd_aplicada = $data["edit_quantidade_{$this->id}"];
                    }

                    if ($type == 'P' || $produto->produto_id != $data["edit_arracoamento_aplicacao_item_id_{$this->id}"]) {
                        $produto->quantidade = $data["edit_quantidade_{$this->id}"];
                    }

                    $produto->produto_id = $data["edit_arracoamento_aplicacao_item_id_{$this->id}"];
                    $produto->usuario_id = auth()->user()->id;

                    if ($produto->save()) {

                        $this->save();

                        return true;

                    }

                }

                break;

            // Receitas
            case 2: // PROBIÓTICO
            case 3: // ADITIVO
            case 4: // VITAMINA

                if (! empty($this->arracoamento_aplicacao_produto)) {

                    $this->arracoamento_aplicacao_produto->delete();

                    $receita = ArracoamentosAplicacoesReceitas::create([
                        'quantidade' => $data["edit_quantidade_{$this->id}"],
                        'receita_laboratorial_id' => $data["edit_arracoamento_aplicacao_item_id_{$this->id}"],
                        'arracoamento_aplicacao_id' => $this->id,
                        'usuario_id' => auth()->user()->id,
                    ]);

                    if ($receita instanceof ArracoamentosAplicacoesReceitas) {

                        $this->save();

                        return true;

                    }

                } else {

                    $receita = $this->arracoamento_aplicacao_receita;

                    $receita->qtd_aplicada = null;

                    if ($type == 'V' && $receita->receita_laboratorial_id == $data["edit_arracoamento_aplicacao_item_id_{$this->id}"]) {
                        $receita->qtd_aplicada = $data["edit_quantidade_{$this->id}"];
                    }

                    if ($type == 'P' || $receita->receita_laboratorial_id != $data["edit_arracoamento_aplicacao_item_id_{$this->id}"]) {
                        $receita->quantidade = $data["edit_quantidade_{$this->id}"];
                    }

                    $receita->receita_laboratorial_id   = $data["edit_arracoamento_aplicacao_item_id_{$this->id}"];
                    $receita->usuario_id   = auth()->user()->id;

                    if ($receita->save()) {

                        $this->save();

                        return true;

                    }

                }

            break;

        }

        return false;
    }

    public function qtdRacaoPorAplicacao(int $tipo = 0)
    {
        $aplicacao_produto = $this->arracoamento_aplicacao_produto;

        if (! empty($aplicacao_produto)) {
        
            $produto = Produtos::find($aplicacao_produto->produto_id);

            if ($produto->produto_tipo_id == 2) { // RAÇÃO

                switch ($tipo) {

                    case 1: // Quantidade programada
                        return $aplicacao_produto->quantidade;

                    case 2: // Quantidade alterada após programação
                        return $aplicacao_produto->qtd_aplicada;

                    default: // Quantidade aplicada (validada)
                        return $aplicacao_produto->qtd_aplicada();

                }

            }

        }

        return 0;
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['arracoamento_id'])) {
                $query->where('arracoamento_id', $data['arracoamento_id']);
            }

        })
        ->orderBy('id', 'desc')
        ->paginate($rowsPerPage);
    }
}
