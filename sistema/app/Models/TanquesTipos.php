<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TanquesTipos extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'tanques_tipos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome'
    ];

    public function tanques()
    {
        return $this->hasMany(Tanques::class, 'tanque_tipo_id', 'id');
    }
}
