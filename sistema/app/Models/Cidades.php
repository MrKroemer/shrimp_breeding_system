<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cidades extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'cidades';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome',
		'estado_id',
        'pais_id',
    ];

    public function estado()
    {
        return $this->belongsTo(Estados::class, 'estado_id', 'id');
    }

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
