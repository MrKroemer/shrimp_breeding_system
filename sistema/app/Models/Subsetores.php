<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subsetores extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'subsetores';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome',
        'sigla',
        'setor_id',
    ];

    public function setor()
    {
        return $this->belongsTo(Setores::class, 'setor_id', 'id');
    }

    public function tanques()
    {
        return $this->hasMany(Tanques::class, 'subsetor_id', 'id');
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

            if (isset($data['setor_id'])) {
                $query->where('setor_id', $data['setor_id']);
            }

        })
        ->orderBy('id')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['setor_id'])) {
                $query->where('setor_id', $data['setor_id']);
            }
            
        })
        ->orderBy('id')
        ->paginate($rowsPerPage);
    }
}
