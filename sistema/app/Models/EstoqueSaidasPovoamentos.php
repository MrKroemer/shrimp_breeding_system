<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstoqueSaidasPovoamentos extends Model
{
    public $timestamps = false;

    protected $table = 'estoque_saidas_povoamentos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'estoque_saida_id',
        'povoamento_id',
        'tanque_id',
        'ciclo_id',
        'lote_id',
    ];

    public function estoque_saida()
    {
        return $this->belongsTo(EstoqueSaidas::class, 'estoque_saida_id', 'id');
    }

    public function povoamento()
    {
        return $this->belongsTo(Povoamentos::class, 'povoamento_id', 'id');
    }

    public function tanque()
    {
        return $this->belongsTo(Tanques::class, 'tanque_id', 'id');
    }

    public function ciclo()
    {
        return $this->belongsTo(Ciclos::class, 'ciclo_id', 'id');
    }

    public function lote()
    {
        return $this->belongsTo(Lotes::class, 'lote_id', 'id');
    }
}
