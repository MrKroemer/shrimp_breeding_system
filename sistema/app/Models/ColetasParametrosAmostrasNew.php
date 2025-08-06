<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColetasParametrosAmostrasNew extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'coletas_parametros_amostras_new';
    protected $primaryKey = 'id';
    protected $fillable = [
        'medicao',
        'valor',
        'valor_fatorado',
        'tanque_id',
        'coleta_parametro_id',
        'usuario_id',
    ];

    public function tanque()
    {
        return $this->belongsTo(Tanques::class, 'tanque_id', 'id');
    }

    public function coleta_parametro()
    {
        return $this->belongsTo(ColetasParametrosNew::class, 'coleta_parametro_id', 'id');
    }

    public function valor()
    {
        if (is_null($this->valor_fatorado)) {
            return $this->valor;
        }

        return $this->valor_fatorado;
    }
}
