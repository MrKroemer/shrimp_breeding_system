<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estados extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'estados';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome',
        'sigla',
        'pais_id',
    ];

    public function pais()
    {
        return $this->belongsTo(Paises::class, 'pais_id', 'id');
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['nome'])) {
                $query->where('nome', 'LIKE', "%{$data['nome']}%");
            }

            if (isset($data['sigla'])) {
                $query->where('sigla', 'LIKE', "%{$data['sigla']}%");
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
        ->orderBy('id')
        ->paginate($rowsPerPage);
    }
}

