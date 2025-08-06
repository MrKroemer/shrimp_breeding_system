<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VwAplicacoesInsumosGruposTanques extends Model
{
    protected $table = 'vw_aplicacoes_insumos_grupos_tanques';
    protected $primaryKey = 'id';

    public function tanque()
    {
        return $this->belongsTo(Tanques::class, 'tanque_id', 'id');
    }

    public function aplicacao_insumo_grupo()
    {
        return $this->belongsTo(AplicacoesInsumosGrupos::class, 'aplicacao_insumo_grupo_id', 'id');
    }
}
