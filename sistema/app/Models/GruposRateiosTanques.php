<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GruposRateiosTanques extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'grupos_rateios_tanques';
    protected $primaryKey = 'id';
    protected $fillable = [
        'tipo',
        'grupo_rateio_id',
        'tanque_id',
        'usuario_id',
    ];

    public function tanque()
    {
        return $this->belongsTo(Tanques::class, 'tanque_id', 'id');
    }

    public function grupo_rateio()
    {
        return $this->belongsTo(GruposRateios::class, 'grupo_rateio_id', 'id');
    }
}
