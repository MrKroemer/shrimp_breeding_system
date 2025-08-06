<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArracoamentosPerfis extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'arracoamentos_perfis';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome',
        'descricao',
        'situacao',
        'filial_id',
    ];

    public function arracoamentos_esquemas()
    {
        return $this->hasMany(ArracoamentosEsquemas::class, 'arracoamento_perfil_id', 'id');
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
                $query->where('descricao', 'LIKE', "%{$data['descricao']}%");
            }

            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }

        })
        ->orderBy('id')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            
            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }

        })
        ->orderBy('id')
        ->paginate($rowsPerPage);
    }

    public static function ativos()
    {
        return static::where('situacao', 'ON');
    }
}
