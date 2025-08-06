<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstoqueSaidasDescartes extends Model
{
    public $timestamps = false;
    
    protected $table = 'estoque_saidas_descartes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'estoque_saida_id',
        'baixa_justificada_id',
    ];

    public function estoque_saida()
    {
        return $this->belongsTo(EstoqueSaidas::class, 'estoque_saida_id', 'id');
    }

    public function baixa_justificada()
    {
        return $this->belongsTo(BaixasJustificadas::class, 'baixa_justificada_id', 'id');
    }
}
