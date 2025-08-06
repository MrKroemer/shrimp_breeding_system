<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AplicacoesInsumos extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'aplicacoes_insumos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'data_aplicacao',
        'situacao',
        'observacoes',
        'aplicacao_insumo_grupo_id',
        'tanque_id',
        'filial_id',
        'usuario_id',
    ];

    public function tanque()
    {
        return $this->belongsTo(Tanques::class, 'tanque_id', 'id');
    }

    public function aplicacoes_insumos_receitas()
    {
        return $this->hasMany(AplicacoesInsumosReceitas::class, 'aplicacao_insumo_id', 'id');
    }

    public function aplicacoes_insumos_produtos()
    {
        return $this->hasMany(AplicacoesInsumosProdutos::class, 'aplicacao_insumo_id', 'id');
    }

    public function estoque_saidas_manejos()
    {
        return $this->hasMany(EstoqueSaidasManejos::class, 'aplicacao_insumo_id', 'id');
    }

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
            'V' => 'Validado',
            'P' => 'Parcialmente validado',
            'N' => 'Não validado',
            'B' => 'Bloqueado',
        ];

        if (! $situacao) {
            return $situacoes;
        }

        return $situacoes[$situacao];
    }

    public static function search(Array $data)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['data_aplicacao'])) {
                $query->where('data_aplicacao', $data['data_aplicacao']);
            }

            if (isset($data['aplicacao_insumo_grupo_id'])) {
                $query->where('aplicacao_insumo_grupo_id', $data['aplicacao_insumo_grupo_id']);
            }

            if (isset($data['tanque_id'])) {
                $query->where('tanque_id', $data['tanque_id']);
            }

            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }

        })
        ->orderBy('data_aplicacao', 'desc')
        ->orderBy('tanque_id', 'asc')
        ->get();
    }

    public static function listing(Array $data = null)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['data_aplicacao'])) {
                $query->where('data_aplicacao', '=', $data['data_aplicacao']);
            }

            if (isset($data['aplicacao_insumo_grupo_id'])) {
                $query->where('aplicacao_insumo_grupo_id', $data['aplicacao_insumo_grupo_id']);
            }

            if (isset($data['tanque_id'])) {
                $query->where('tanque_id', $data['tanque_id']);
            }

            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }

        })
        ->orderBy('data_aplicacao', 'desc')
        ->orderBy('tanque_id', 'asc')
        ->get();
    }
}
