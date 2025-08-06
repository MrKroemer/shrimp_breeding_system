<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Especies extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'especies';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome_comum',
        'nome_cientifico',
    ];

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['nome_comum'])) {
                $query->where('nome_comum', $data['nome_comum']);
            }

            if (isset($data['nome_cientifico'])) {
                $query->where('nome_cientifico', $data['nome_cientifico']);
            }

        })
        ->orderBy('nome_cientifico', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            // 
        })
        ->orderBy('nome_cientifico', 'desc')
        ->paginate($rowsPerPage);
    }
}

