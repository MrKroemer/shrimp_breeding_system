<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PreparacoesAplicacoes extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'preparacoes_aplicacoes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'data_aplicacao',
        'quantidade',
        'situacao',
        'produto_id',
        'preparacao_id',
        'preparacao_tipo_id',
        'usuario_id',
    ];

    public function data_aplicacao(String $format = 'd/m/Y')
    {
        if (! $this->data_aplicacao) {
            return $this->data_aplicacao;
        }

        return Carbon::parse($this->data_aplicacao)->format($format);
    }

    public function situacao($situacao = null)
    {
        $situacoes = [
            'A' => 'Aplicado',
            'E' => 'Estornado',
        ];

        if (! $situacao) {
            return $situacoes;
        }

        return $situacoes[$situacao];
    }

    public function produto()
    {
        return $this->belongsTo(Produtos::class, 'produto_id', 'id');
    }

    public function preparacao()
    {
        return $this->belongsTo(Preparacoes::class, 'preparacao_id', 'id');
    }

    public function preparacao_tipo()
    {
        return $this->belongsTo(PreparacoesTipos::class, 'preparacao_tipo_id', 'id');
    }

    public function estoque_saida_preparacao()
    {
        return $this->hasOne(EstoqueSaidasPreparacoes::class, 'preparacao_aplicacao_id', 'id');
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['data_aplicacao'])) {
                $query->where('data_aplicacao', $data['data_aplicacao']);
            }

            if (isset($data['preparacao_id'])) {
                $query->where('preparacao_id', $data['preparacao_id']);
            }

            if (isset($data['preparacao_tipo_id'])) {
                $query->where('preparacao_tipo_id', $data['preparacao_tipo_id']);
            }

        })
        // ->orderBy('id', 'desc')
        ->orderBy('data_aplicacao', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            
            if (isset($data['preparacao_id'])) {
                $query->where('preparacao_id', $data['preparacao_id']);
            }

        })
        // ->orderBy('id', 'desc')
        ->orderBy('data_aplicacao', 'desc')
        ->paginate($rowsPerPage);
    }
}
