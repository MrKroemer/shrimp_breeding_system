<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotesPeixesOvos extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'lotes_peixes_ovos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'qtd_ovos',
        'qtd_femeas',
        'tanque_origem_id',
        'tanque_destino_id',
        'lote_peixes_id',
    ];

    public function tanque_origem()
    {
        return $this->belongsTo(Tanques::class, 'tanque_origem_id', 'id');
    }

    public function tanque_destino()
    {
        return $this->belongsTo(Tanques::class, 'tanque_destino_id', 'id');
    }
}
