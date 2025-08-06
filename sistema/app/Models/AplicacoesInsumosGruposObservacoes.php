<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AplicacoesInsumosGruposObservacoes extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'aplicacoes_insumos_grupos_observacoes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'data_aplicacao',
        'observacoes',
        'aplicacao_insumo_grupo_id',
        'usuario_id',
    ];
}
