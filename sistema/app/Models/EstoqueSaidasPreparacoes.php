<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstoqueSaidasPreparacoes extends Model
{
    public $timestamps = false;

    protected $table = 'estoque_saidas_preparacoes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'estoque_saida_id',
        'preparacao_id',
        'preparacao_aplicacao_id',
        'tanque_id',
        'ciclo_id',
    ];

    public function estoque_saida()
    {
        return $this->belongsTo(EstoqueSaidas::class, 'estoque_saida_id', 'id');
    }

    public function preparacao()
    {
        return $this->belongsTo(Preparacoes::class, 'preparacao_id', 'id');
    }

    public function preparacao_aplicacao()
    {
        return $this->belongsTo(PreparacoesAplicacoes::class, 'preparacao_aplicacao_id', 'id');
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
