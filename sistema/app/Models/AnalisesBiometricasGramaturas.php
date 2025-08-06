<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AnalisesBiometricasGramaturas extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'analises_biometricas_gramaturas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'gramatura',
        'classificacao',
        'analise_biometrica_id',
        'usuario_id',
    ];

    public function analise_biometrica()
    {
        return $this->belongsTo(AnalisesBiometricas::class, 'analise_biometrica_id', 'id');
    }
}
