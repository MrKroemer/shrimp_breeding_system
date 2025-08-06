<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TransferenciasAnimais extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'transferencias_animais';
    protected $primaryKey = 'id';
    protected $fillable = [
        'data_movimento',
        'quantidade',
        'proporcao',
        'situacao',
        'observacoes',
        'ciclo_origem_id',
        'ciclo_destino_id',
        'usuario_id',
        'filial_id',
    ];

    public function ciclo_origem()
    {
        return $this->belongsTo(Ciclos::class, 'ciclo_origem_id', 'id');
    }

    public function ciclo_destino()
    {
        return $this->belongsTo(Ciclos::class, 'ciclo_destino_id', 'id');
    }

    public function data_movimento(String $format = 'd/m/Y')
    {
        if (! $this->data_movimento) {
            return $this->data_movimento;
        }

        return Carbon::parse($this->data_movimento)->format($format);
    }

    public function proporcao()
    {
        return round(($this->proporcao * 100), 2);
    }

    public function situacao($situacao = null)
    {
        $situacoes = [
            'V' => 'Validado',
            'N' => 'Não validado',
        ];

        if (! $situacao) {
            return $situacoes;
        }

        return $situacoes[$situacao];
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['data_movimento'])) {
                $query->where('data_movimento', $data['data_movimento']);
            }

            if (isset($data['ciclo_origem_id'])) {
                $query->where('ciclo_origem_id', $data['ciclo_origem_id']);
            }

            if (isset($data['ciclo_destino_id'])) {
                $query->where('ciclo_destino_id', $data['ciclo_destino_id']);
            }

        })
        ->orderBy('data_movimento', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            //
        })
        ->orderBy('data_movimento', 'desc')
        ->paginate($rowsPerPage);
    }
}
