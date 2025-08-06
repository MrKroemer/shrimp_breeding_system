<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PovoamentosLotes extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'povoamentos_lotes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'data_vinculo',
        'situacao',
        'povoamento_id',
        'lote_id',
        'usuario_id',
    ];

    public function lote()
    {
        return $this->belongsTo(Lotes::class, 'lote_id', 'id');
    }

    public function data_vinculo(String $format = 'd/m/Y')
    {
        if (! $this->data_vinculo) {
            return $this->data_vinculo;
        }

        return Carbon::parse($this->data_vinculo)->format($format);
    }
}
