<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceitasLaboratoriaisProdutos extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'receitas_laboratoriais_produtos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'quantidade',
        'produto_id',
        'receita_laboratorial_id',
        'receita_laboratorial_periodo_id',
    ];

    public function produto()
    {
        return $this->belongsTo(Produtos::class, 'produto_id', 'id');
    }

    public function receita_laboratorial()
    {
        return $this->belongsTo(ReceitasLaboratoriais::class, 'receita_laboratorial_id', 'id');
    }

    public function receita_laboratorial_periodo()
    {
        return $this->belongsTo(ReceitasLaboratoriaisPeriodos::class, 'receita_laboratorial_periodo_id', 'id');
    }

    public function quantidade()
    {
        return rtrim(rtrim($this->quantidade, '0'), '.');
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

            if (isset($data['receita_laboratorial_periodo_id'])) {
                $query->where('receita_laboratorial_periodo_id', $data['receita_laboratorial_periodo_id']);
            }

        })
        ->orderBy('id', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['receita_laboratorial_id'])) {
                $query->where('receita_laboratorial_id', $data['receita_laboratorial_id']);
            }

            if (isset($data['receita_laboratorial_periodo_id'])) {
                $query->where('receita_laboratorial_periodo_id', $data['receita_laboratorial_periodo_id']);
            }
            
        })
        ->orderBy('id', 'desc')
        ->paginate($rowsPerPage);
    }
}
