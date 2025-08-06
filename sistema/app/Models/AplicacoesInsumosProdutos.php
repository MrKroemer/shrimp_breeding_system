<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AplicacoesInsumosProdutos extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'aplicacoes_insumos_produtos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'quantidade',
        'produto_id',
        'aplicacao_insumo_id',
        'usuario_id',
    ];

    public function produto()
    {
        return $this->belongsTo(Produtos::class, 'produto_id', 'id');
    }
}
