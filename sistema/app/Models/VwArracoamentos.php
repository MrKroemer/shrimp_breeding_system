<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VwArracoamentos extends Model
{
    protected $table = 'vw_arracoamentos';

    public function arracoamento()
    {
        return $this->belongsTo(Arracoamentos::class, 'id', 'id');
    }

    public function ciclo()
    {
        return $this->belongsTo(Ciclos::class, 'ciclo_id', 'id');
    }

    public function tanque()
    {
        return $this->belongsTo(Tanques::class, 'tanque_id', 'id');
    }

    public function horarios()
    {
        return $this->hasMany(ArracoamentosHorarios::class, 'arracoamento_id', 'id');
    }

    public function aplicacoes()
    {
        return $this->hasMany(ArracoamentosAplicacoes::class, 'arracoamento_id', 'id');
    }

    public function estoque_saidas_arracoamentos()
    {
        return $this->hasMany(EstoqueSaidasArracoamentos::class, 'arracoamento_id', 'id');
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
        return $this->arracoamento->situacao($situacao);
    }

    public static function search(Array $data, int $rowsPerPage = null)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['data_aplicacao'])) {
                $query->where('data_aplicacao', $data['data_aplicacao']);
            }

            if (isset($data['setor_id'])) {

                $tanques = Tanques::where('setor_id', $data['setor_id'])->get(['id']);

                if ($tanques->isNotEmpty()) {
                    $query->whereIn('tanque_id', $tanques);
                }

            }

            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }

        })
        ->orderBy('data_aplicacao', 'desc')
        ->orderBy('sigla', 'asc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['data_aplicacao'])) {
                $query->where('data_aplicacao', $data['data_aplicacao']);
            }

            if (isset($data['setor_id'])) {

                $tanques = Tanques::where('setor_id', $data['setor_id'])->get(['id']);

                if ($tanques->isNotEmpty()) {
                    $query->whereIn('tanque_id', $tanques);
                }

            }

            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }

        })
        ->orderBy('data_aplicacao', 'desc')
        ->orderBy('sigla', 'asc')
        ->paginate($rowsPerPage);
    }
}
