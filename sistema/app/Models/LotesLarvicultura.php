<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotesLarvicultura extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'lotes_larvicultura';
    protected $primaryKey = 'id';
    protected $fillable = [
        'lote',
        'quantidade',
        'sobrevivencia',
        'cv',
        'lote_id',
    ];

    public function lote()
    {
        return $this->belongsTo(Lotes::class, 'lote_id', 'id');
    }

    public function sobrevivencia()
    {
        $sobrevivencia = (float) $this->sobrevivencia;

        if (empty($sobrevivencia)) {
            $sobrevivencia = 100;
        }

        return $sobrevivencia;
    }
    
    public function cv()
    {
        $cv = (int) $this->cv;

        if (empty($cv)) {
            $cv = 100;
        }

        return $cv;
    }

}
