<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArracoamentosReferenciais extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'arracoamentos_referenciais';
    protected $primaryKey = 'id';
    protected $fillable = [
        'dias_cultivo',
        'peso_medio',
        'porcentagem',
        'crescimento',
        'observacoes',
        'arracoamento_clima_id',
    ];

    public function arracoamento_clima()
    {
        return $this->belongsTo(ArracoamentosClimas::class, 'arracoamento_clima_id', 'id');
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['dias_cultivo'])) {
                $query->where('dias_cultivo', $data['dias_cultivo']);
            }

            if (isset($data['arracoamento_clima_id'])) {
                $query->where('arracoamento_clima_id', $data['arracoamento_clima_id']);
            }

        })
        ->orderBy('dias_cultivo')
        ->orderBy('id')
        ->get();
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            
            if (isset($data['arracoamento_clima_id'])) {
                $query->where('arracoamento_clima_id', $data['arracoamento_clima_id']);
            }

        })
        ->orderBy('dias_cultivo')
        ->orderBy('id')
        ->get();
    }
}
