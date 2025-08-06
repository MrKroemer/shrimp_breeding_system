<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AnalisesBiometricasAmostras extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'analises_biometricas_amostras';
    protected $primaryKey = 'id';
    protected $fillable = [
        'gramatura',
        'qualificacao',
        'analise_biometrica_id',
        'usuario_id',
    ];

    public function analise_biometrica()
    {
        return $this->belongsTo(AnalisesBiometricas::class, 'analise_biometrica_id', 'id');
    }

    public function criado_em(string $format = 'd/m/Y H:i:s')
    {
        if (! $this->criado_em) {
            return $this->criado_em;
        }

        return Carbon::parse($this->criado_em)->format($format);
    }
}
