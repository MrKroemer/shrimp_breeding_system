<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AplicacoesInsumosGrupos extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'aplicacoes_insumos_grupos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome',
        'descricao',
        'filial_id',
        'usuario_id',
    ];

    public function tanques()
    {
        return $this->hasMany(VwAplicacoesInsumosGruposTanques::class, 'aplicacao_insumo_grupo_id', 'id');
    }

    public function aplicacoes()
    {
        return $this->hasMany(AplicacoesInsumos::class, 'aplicacao_insumo_grupo_id', 'id');
    }

    public function observacoes()
    {
        return $this->hasMany(AplicacoesInsumosGruposObservacoes::class, 'aplicacao_insumo_grupo_id', 'id');
    }

    public static function search(Array $data)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['nome'])) {
                $query->where('nome', 'LIKE', "%{$data['id']}%");
            }

            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }

        })
        ->orderBy('id', 'desc')
        ->get();
    }

    public static function listing(Array $data = null)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }

        })
        ->orderBy('id', 'desc')
        ->get();
    }
}
