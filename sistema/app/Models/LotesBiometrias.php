<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotesBiometrias extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'lotes_biometrias';
    protected $primaryKey = 'id';
    protected $fillable = [
        'mm5',
        'mm6',
        'mm7',
        'mm8',
        'mm9',
        'mm10',
        'mm11',
        'mm12',
        'mm13',
        'mm14',
        'mm15',
        'mm16',
        'mm17',
        'mm18',
        'mm19',
        'mm20',
        'total_animais',
        'estresse',
        'sobrevivencia',
        'tamanho_medio',
        'peso_total',
        'peso_medio',
        'data_analise',
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
    
    public function total_animais()
    {
        $total_animais = (int) $this->total_animais;

        if (empty($total_animais)) {
            $total_animais = 100;
        }

        return $total_animais;
    }

    public function peso_total()
    {
        $peso_total = (float) $this->peso_total;

        if (empty($peso_total)) {
            $peso_total = 0.8;
        }

        return $peso_total;
    }

    public function peso_medio()
    {
        $peso_medio = (float) $this->peso_medio;

        if (empty($peso_medio) && $this->total_animais() > 0) {
            $peso_medio = ($this->peso_total() / $this->total_animais());
        }

        return $peso_medio;
    }
}
