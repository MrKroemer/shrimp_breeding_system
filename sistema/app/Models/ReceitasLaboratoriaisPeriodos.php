<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceitasLaboratoriaisPeriodos extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'receitas_laboratoriais_periodos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'periodo',
        'dia_base',
        'quantidade',
        'receita_laboratorial_id'
    ];

    public function receita_laboratorial()
    {
        return $this->belongsTo(ReceitasLaboratoriais::class, 'receita_laboratorial_id', 'id');
    }

    public function receita_laboratorial_produtos()
    {
        return $this->hasMany(ReceitasLaboratoriaisProdutos::class, 'receita_laboratorial_periodo_id', 'id');
    }

    public function periodo($periodo = null)
    {
        $periodos = [
            1 => 'ATÉ O',
            2 => 'ACIMA DO',
            3 => 'ABAIXO DO',
        ];

        if (! $periodo) {
            return $periodos;
        }

        return $periodos[$periodo];
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

        })
        ->orderBy('id')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['receita_laboratorial_id'])) {
                $query->where('receita_laboratorial_id', $data['receita_laboratorial_id']);
            }
            
        })
        ->orderBy('id')
        ->paginate($rowsPerPage);
    }
}
