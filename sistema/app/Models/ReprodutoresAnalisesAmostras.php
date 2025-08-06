<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReprodutoresAnalisesAmostras extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'reprodutores_analises_amostras';
    protected $primaryKey = 'id';
    protected $fillable = [
        'valor',
        'reprodutor_id',
        'reprodutor_analise_id',
        'usuario_id',
    ];

    public function reprodutores()
    {
        return $this->belongsTo(Reprodutores::class, 'reprodutor_id', 'id');
    }

    public function reprodutores_analises()
    {
        return $this->belongsTo(ReprodutoresAnalises::class, 'reprodutor_analise_id', 'id');
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

        })
        ->orderBy('id')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            // 
        })
        ->paginate($rowsPerPage);
    }
}
