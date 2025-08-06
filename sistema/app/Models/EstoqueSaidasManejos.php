<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstoqueSaidasManejos extends Model
{
    public $timestamps = false;
    
    protected $table = 'estoque_saidas_manejos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'estoque_saida_id',
        'aplicacao_insumo_id',
        'tanque_id',
    ];

    public function estoque_saida()
    {
        return $this->belongsTo(EstoqueSaidas::class, 'estoque_saida_id', 'id');
    }

    public function aplicacao_insumo()
    {
        return $this->belongsTo(AplicacoesInsumos::class, 'aplicacao_insumo_id', 'id');
    }

    public function tanque()
    {
        return $this->belongsTo(Tanques::class, 'tanque_id', 'id');
    }
}
