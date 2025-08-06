<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BaixasJustificadas extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'baixas_justificadas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'data_movimento',
        'descricao',
        'situacao',
        'filial_id',
        'usuario_id',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'usuario_id', 'id');
    }

    public function baixas_justificadas_produtos()
    {
        return $this->hasMany(BaixasJustificadasProdutos::class, 'baixa_justificada_id', 'id');
    }

    public function estoque_saidas_descartes()
    {
        return $this->hasMany(EstoqueSaidasDescartes::class, 'baixa_justificada_id', 'id');
    }

    public function data_movimento(String $format = 'd/m/Y')
    {
        if (! $this->data_movimento) {
            return $this->data_movimento;
        }

        return Carbon::parse($this->data_movimento)->format($format);
    }

    public function alterado_em(String $format = 'd/m/Y')
    {
        if (! $this->alterado_em) {
            return $this->alterado_em;
        }

        return Carbon::parse($this->alterado_em)->format($format);
    }

    public function situacao()
    {
        $situacoes = [
            'V' => 'Validado',
            'P' => 'Parcialmente validado',
            'N' => 'Não validado',
        ];

        if (! $this->situacao) {
            return $situacoes;
        }

        return $situacoes[$this->situacao];
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }

            if (isset($data['data_inicial']) && isset($data['data_final'])) {

                $data['data_inicial'] = Carbon::createFromFormat('d/m/Y', $data['data_inicial'])->format('Y-m-d');
                $data['data_final']   = Carbon::createFromFormat('d/m/Y', $data['data_final']  )->format('Y-m-d');

                $query->whereBetween('data_movimento', [$data['data_inicial'], $data['data_final']]);

            }

        })
        ->orderBy('data_movimento', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }

            if (isset($data['data_inicial']) && isset($data['data_final'])) {

                $data['data_inicial'] = Carbon::createFromFormat('d/m/Y', $data['data_inicial'])->format('Y-m-d');
                $data['data_final']   = Carbon::createFromFormat('d/m/Y', $data['data_final']  )->format('Y-m-d');

                $query->whereBetween('data_movimento', [$data['data_inicial'], $data['data_final']]);

            }

        })
        ->orderBy('data_movimento', 'desc')
        ->paginate($rowsPerPage);
    }
}

