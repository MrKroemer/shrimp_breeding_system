<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreparacoesTipos extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'preparacoes_tipos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome',
        'descricao',
        'metodo',
        'situacao',
    ];

    public function metodo($metodo = null)
    {
        $metodos = [
            'S' => 'Semi-intensivo',
            'I' => 'Intensivo',
            'A' => 'Ambos',
        ];

        if (! $metodo) {
            return $metodos;
        }

        return $metodos[$metodo];
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

            if (isset($data['descricao'])) {
                $query->where('descricao', 'LIKE', "%{$data['nome']}%");
            }

        })
        ->orderBy('nome')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            //
        })
        ->orderBy('nome')
        ->paginate($rowsPerPage);
    }
}
