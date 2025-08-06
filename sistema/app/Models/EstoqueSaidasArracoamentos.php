<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstoqueSaidasArracoamentos extends Model
{
    public $timestamps = false;
    
    protected $table = 'estoque_saidas_arracoamentos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'estoque_saida_id',
        'arracoamento_id',
        'tanque_id',
        'ciclo_id',
    ];

    public function estoque_saida()
    {
        return $this->belongsTo(EstoqueSaidas::class, 'estoque_saida_id', 'id');
    }

    public function arracoamento()
    {
        return $this->belongsTo(Arracoamentos::class, 'arracoamento_id', 'id');
    }

    public function tanque()
    {
        return $this->belongsTo(Tanques::class, 'tanque_id', 'id');
    }

    public function ciclo()
    {
        return $this->belongsTo(Ciclos::class, 'ciclo_id', 'id');
    }
}
