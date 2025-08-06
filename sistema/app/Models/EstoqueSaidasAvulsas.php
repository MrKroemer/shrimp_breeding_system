<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstoqueSaidasAvulsas extends Model
{
    public $timestamps = false;
    
    protected $table = 'estoque_saidas_avulsas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'estoque_saida_id',
        'saida_avulsa_id',
        'tanque_id',
    ];

    public function estoque_saida()
    {
        return $this->belongsTo(EstoqueSaidas::class, 'estoque_saida_id', 'id');
    }

    public function saida_avulsa()
    {
        return $this->belongsTo(SaidasAvulsas::class, 'saida_avulsa_id', 'id');
    }

    public function tanque()
    {
        return $this->belongsTo(Tanques::class, 'tanque_id', 'id');
    }
}
