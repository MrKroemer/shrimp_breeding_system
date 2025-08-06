<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AplicacoesInsumosReceitas extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'aplicacoes_insumos_receitas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'quantidade',
        'solvente',
        'receita_laboratorial_id',
        'aplicacao_insumo_id',
        'usuario_id',
    ];

    public function receita_laboratorial()
    {
        return $this->belongsTo(ReceitasLaboratoriais::class, 'receita_laboratorial_id', 'id');
    }
}
