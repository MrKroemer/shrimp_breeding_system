<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class RecriacaoPeixes extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'recriacao_peixes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'codigo',
        'data_inicio',
        'data_fim',
        'situacao',
        'tanque_id',
        'filial_id',
        'usuario_id',
    ];

    public function situacao()
    {
        $situacoes = [ 
            1 => 'Em povoamento', 
            2 => 'Em engorda', 
            3 => 'Em despesca',
            4 => 'Encerrado',
        ];

        if (! empty($this->getAttributes())) {
            return $situacoes[$this->situacao];
        }

        return $situacoes;
    }

    public function tanque()
    {
        return $this->belongsTo(Tanques::class, 'tanque_id', 'id');
    }

    public function data_inicio(string $format = 'd/m/Y')
    {
        if (! $this->data_inicio) {
            return $this->data_inicio;
        }

        return Carbon::parse($this->data_inicio)->format($format);
    }

    public function data_fim(string $format = 'd/m/Y')
    {
        if (! $this->data_fim) {
            return $this->data_fim;
        }

        return Carbon::parse($this->data_fim)->format($format);
    }

    public static function listing(int $rowsPerPage = null)
    {
        return static::where(function ($query) {
            $query->where('filial_id', session('_filial')->id);
        })
        ->orderBy('codigo', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function search(array $data, int $rowsPerPage = null)
    {
        return static::where(function ($query) use ($data) {

            $query->where('filial_id', session('_filial')->id);

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['codigo'])) {
                $query->where('codigo', mb_strtoupper($data['codigo']));
            }

            if (isset($data['tanque_id'])) {
                $query->where('tanque_id', mb_strtoupper($data['tanque_id']));
            }

            if (isset($data['data_inicio']) && isset($data['data_fim'])) {

                $query->where(function ($query) use ($data) {
                    $query->whereBetween('data_inicio', [$data['data_inicio'], $data['data_fim']]);
                    $query->orWhereBetween('data_fim',  [$data['data_inicio'], $data['data_fim']]);
                });

            } elseif (isset($data['data_inicio'])) {

                $query->where('data_inicio', $data['data_inicio']);

            } elseif (isset($data['data_fim'])) {

                $query->where('data_fim', $data['data_fim']);

            }

        })
        ->orderBy('codigo', 'desc')
        ->paginate($rowsPerPage);
    }
}
