<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnidadesMedidas extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'unidades_medidas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome',
        'sigla',
    ];
}
