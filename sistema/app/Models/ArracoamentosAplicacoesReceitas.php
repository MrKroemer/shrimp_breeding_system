<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArracoamentosAplicacoesReceitas extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'arracoamentos_aplicacoes_receitas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'quantidade',
        'qtd_aplicada',
        'receita_laboratorial_id',
        'arracoamento_aplicacao_id',
        'usuario_id',
    ];

    public function receita_laboratorial()
    {
        return $this->belongsTo(ReceitasLaboratoriais::class, 'receita_laboratorial_id', 'id');
    }

    public function arracoamento_aplicacao()
    {
        return $this->belongsTo(ArracoamentosAplicacoes::class, 'arracoamento_aplicacao_id', 'id');
    }

    public function qtd_aplicada()
    {
        return $this->qtd_aplicada ?: $this->quantidade;
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['receita_laboratorial_id'])) {
                $query->where('receita_laboratorial_id', $data['receita_laboratorial_id']);
            }

            if (isset($data['arracoamento_aplicacao_id'])) {
                $query->where('arracoamento_aplicacao_id', $data['arracoamento_aplicacao_id']);
            }

        })
        ->orderBy('id')
        ->get();
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            
            if (isset($data['arracoamento_aplicacao_id'])) {
                $query->where('arracoamento_aplicacao_id', $data['arracoamento_aplicacao_id']);
            }

        })
        ->orderBy('id')
        ->get();
    }
}
