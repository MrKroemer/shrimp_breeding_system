<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReceitasLaboratoriais extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'receitas_laboratoriais';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome',
        'descricao',
        'situacao',
        'unidade_medida_id',
        'receita_laboratorial_tipo_id',
        'filial_id',
    ];

    public function unidade_medida()
    {
        return $this->belongsTo(UnidadesMedidas::class, 'unidade_medida_id', 'id');
    }

    public function receita_laboratorial_tipo()
    {
        return $this->belongsTo(ReceitasLaboratoriaisTipos::class, 'receita_laboratorial_tipo_id', 'id');
    }

    public function receita_laboratorial_produtos()
    {
        return $this->hasMany(ReceitasLaboratoriaisProdutos::class, 'receita_laboratorial_id', 'id');
    }

    public function receita_laboratorial_periodos()
    {
        return $this->hasMany(ReceitasLaboratoriaisPeriodos::class, 'receita_laboratorial_id', 'id');
    }

    public function receitaPorDiasDeCultivo(int $diasCultivo)
    {
        $periodo = null;

        foreach ($this->receita_laboratorial_periodos as $receita_laboratorial_periodo) {

            switch ($receita_laboratorial_periodo->periodo) {

                case 1: // ATÉ O
                    if ($receita_laboratorial_periodo->dia_base >= $diasCultivo) {
                        $periodo = $receita_laboratorial_periodo;
                    }
                    break;

                case 2: // ACIMA DO
                    if ($receita_laboratorial_periodo->dia_base < $diasCultivo) {
                        $periodo = $receita_laboratorial_periodo;
                    }
                    break;

                case 3: // ABAIXO DO
                    if ($receita_laboratorial_periodo->dia_base > $diasCultivo) {
                        $periodo = $receita_laboratorial_periodo;
                    }
                    break;

            }

        }

        return $periodo;
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['nome'])) {

                $data['nome'] = mb_strtoupper($data['nome']);

                $query->where('nome', 'LIKE', "%{$data['nome']}%");

            }

            if (isset($data['descricao'])) {

                $data['descricao'] = mb_strtoupper($data['descricao']);

                $query->where('descricao', 'LIKE', "%{$data['descricao']}%");

            }

        })
        ->orderBy('id')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            $query->where('filial_id', session('_filial')->id);
        })
        ->orderBy('id')
        ->paginate($rowsPerPage);
    }

    public static function ativos()
    {
        return static::where('situacao', 'ON');
    }
}
