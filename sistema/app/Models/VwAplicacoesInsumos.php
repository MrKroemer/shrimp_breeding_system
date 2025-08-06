<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VwAplicacoesInsumos extends Model
{
    protected $table = 'vw_aplicacoes_insumos';

    public function aplicacao_insumo()
    {
        return $this->belongsTo(AplicacoesInsumos::class, 'id', 'id');
    }

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
        return $this->aplicacao_insumo->situacao($situacao);
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
        ->orderBy('tanque_tipo_id', 'asc')
        ->orderBy('tanque_sigla',   'asc')
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
        ->orderBy('tanque_tipo_id', 'asc')
        ->orderBy('tanque_sigla',   'asc')
        ->get();
    }
}
