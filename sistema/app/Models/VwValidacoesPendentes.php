<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VwValidacoesPendentes extends Model
{
    protected $table = 'vw_validacoes_pendentes';

    public function data_aplicacao(String $format = 'd/m/Y')
    {
        if (! $this->data_aplicacao) {
            return $this->data_aplicacao;
        }

        return Carbon::parse($this->data_aplicacao)->format($format);
    }

    public function situacao()
    {
        $situacoes = [
            'V' => 'Validado',
            'P' => 'Parcialmente validado',
            'N' => 'Não validado',
            'B' => 'Bloqueado',
        ];

        if (! $this->situacao) {
            return $situacoes;
        }

        return $situacoes[$this->situacao];
    }

    public function tipo_aplicacao()
    {
        $tipos_aplicacoes = [
            3 => 'Arraçoamento',
            4 => 'Aplicação de insumo',
            5 => 'Saída avulsa',
        ];

        if (! $this->tipo_aplicacao) {
            return $tipos_aplicacoes;
        }

        return $tipos_aplicacoes[$this->tipo_aplicacao];
    }

    public static function search(Array $data, int $rowsPerPage = null)
    {
        return static::where(function ($query) use ($data) {

            $query->where('filial_id', session('_filial')->id);

            if (isset($data['data_aplicacao'])) {
                $query->where('data_aplicacao', $data['data_aplicacao']);
            }

            if (isset($data['tipo_aplicacao'])) {
                $query->where('tipo_aplicacao', $data['tipo_aplicacao']);
            }

            if (isset($data['situacao'])) {
                $query->where('situacao', $data['situacao']);
            }

            if (isset($data['tanque_id'])) {
                $query->where('tanque_id', $data['tanque_id']);
            }

        })
        ->orderBy('tanque_sigla', 'asc')
        ->orderBy('data_aplicacao', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            $query->where('filial_id', session('_filial')->id);
            $query->where('situacao', '<>', 'V');
        })
        ->orderBy('tanque_sigla', 'asc')
        ->orderBy('data_aplicacao', 'desc')
        ->paginate($rowsPerPage);
    }
}
