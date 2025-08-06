<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AplicacoesInsumosGruposTanques extends Model
{
    public $timestamps = false;
    
    protected $table = 'aplicacoes_insumos_grupos_tanques';
    protected $primaryKey = 'id';
    protected $fillable = [
        'aplicacao_insumo_grupo_id',
        'tanque_id',
    ];

    public function tanque()
    {
        return $this->belongsTo(Tanques::class, 'tanque_id', 'id');
    }

    public function aplicacao_insumo_grupo()
    {
        return $this->belongsTo(AplicacoesInsumosGrupos::class, 'aplicacao_insumo_grupo_id', 'id');
    }
}
