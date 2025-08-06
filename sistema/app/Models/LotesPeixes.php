<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LotesPeixes extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'lotes_peixes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'codigo',
        'data_inicio',
        'data_fim',
        'tipo',
        'situacao',
        'especie_id',
        'filial_id',
        'usuario_id',
    ];

    public function especie()
    {
        return $this->belongsTo(Especies::class, 'especie_id', 'id');
    }

    public function ovos()
    {
        return $this->hasMany(LotesPeixesOvos::class, 'lote_peixes_id', 'id');
    }

    public function transferencias()
    {
        return $this->hasMany(TransferenciasPeixes::class, 'lote_peixes_id', 'id');
    }

    public function dias_atividade(string $data_referencia = null)
    {
        $data_referencia = $data_referencia ?: date('Y-m-d');

        if (! is_null($this->data_fim)) {

            $data_referencia = Carbon::createFromFormat('Y-m-d', $data_referencia);
            $data_fim = Carbon::createFromFormat('Y-m-d', $this->data_fim);

            if ($data_referencia->greaterThan($data_fim)) {
                $data_referencia = $data_fim->format('Y-m-d');
            } else {
                $data_referencia = $data_referencia->format('Y-m-d');
            }

        }

        return Carbon::parse($this->data_inicio)->diffInDays($data_referencia) + 1;
    }
    
    public function biomassa()
    {
        return 0;
    }

    public function total_ovos()
    {
        return $this->ovos->sum('qtd_ovos');
    }

    public function ultima_transferencia()
    {
        $transferencia = $this->transferencias
        ->sortByDesc('data_movimento')
        ->sortByDesc('id')
        ->first();

        if ($transferencia instanceof TransferenciasPeixes) {
            return $transferencia;
        }

        return null;
    }

    public function data_inicio(String $format = 'd/m/Y')
    {
        if (! $this->data_inicio) {
            return $this->data_inicio;
        }

        return Carbon::parse($this->data_inicio)->format($format);
    }

    public function data_fim(String $format = 'd/m/Y')
    {
        if (! $this->data_fim) {
            return $this->data_fim;
        }

        return Carbon::parse($this->data_fim)->format($format);
    }

    public function situacao()
    {
        $situacoes = [
            1 => 'Em eclosão', 
            2 => 'Em conversão', 
            3 => 'Em recriação', 
            4 => 'Em cultivo',
            5 => 'Em separação',
            6 => 'Em reprodução',
            7 => 'Encerrado',
        ];

        if (! empty($this->getAttributes())) {
            return $situacoes[$this->situacao];
        }

        return $situacoes;
    }

    public function tipo()
    {
        $tipos = [
            1 => 'Produção', 
            2 => 'Reprodução',
        ];

        if (! empty($this->getAttributes())) {
            return $tipos[$this->tipo];
        }

        return $tipos;
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

            if (isset($data['tipo'])) {
                $query->where('tipo', $data['tipo']);
            }

            if (isset($data['codigo'])) {
                $query->where('codigo', mb_strtoupper($data['codigo']));
            }

        })
        ->orderBy('codigo', 'desc')
        ->paginate($rowsPerPage);
    }
}
