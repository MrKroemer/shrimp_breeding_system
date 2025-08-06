<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupos extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'grupos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome',
        'situacao',
    ];

    public function filiais()
    {
        return $this->hasMany(GruposFiliais::class, 'grupo_id', 'id');
    }

    // Método deve ser removido
    public function permissoes()
    {
        return $this->hasMany(GruposPermissoes::class, 'grupo_id', 'id');
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
