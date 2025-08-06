<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Lotes extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'lotes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'lote_fabricacao',
        'data_saida',
        'data_entrada',
        'quantidade',
        'classe_idade',
        'temperatura',
        'salinidade',
        'ph',
        'especie_id',
        'genetica',
        'estoque_entrada_id',
        'filial_id',
        'usuario_id',
    ];

    public function especie()
    {
        return $this->belongsTo(Especies::class, 'especie_id', 'id');
    }

    public function estoque_entrada()
    {
        return $this->belongsTo(EstoqueEntradas::class, 'estoque_entrada_id', 'id');
    }

    public function filial()
    {
        return $this->belongsTo(Filiais::class, 'filial_id', 'id');
    }

    public function lote_biometria()
    {
        return $this->hasOne(LotesBiometrias::class, 'lote_id', 'id');
    }

    public function lote_larvicultura()
    {
        return $this->hasMany(LotesLarvicultura::class, 'lote_id', 'id');
    }

    public function povoamento()
    {
        return $this->hasOne(PovoamentosLotes::class, 'lote_id', 'id');
    }

    public function data_saida(String $format = 'd/m/Y H:i')
    {
        if (! $this->data_saida) {
            return $this->data_saida;
        }

        return Carbon::parse($this->data_saida)->format($format);
    }

    public function data_entrada(String $format = 'd/m/Y H:i')
    {
        if (! $this->data_entrada) {
            return $this->data_entrada;
        }

        return Carbon::parse($this->data_entrada)->format($format);
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['lote_fabricacao'])) {
                $query->where('lote_fabricacao', $data['lote_fabricacao']);
            }

            if (isset($data['data_inicio'])) {
                $data['data_inicio'] = Carbon::createFromFormat('d/m/Y H:i', $data['data_inicio'])->format('Y-m-d H:i:s');
            }

            if (isset($data['data_fim'])) {
                $data['data_fim'] = Carbon::createFromFormat('d/m/Y H:i', $data['data_fim'])->format('Y-m-d H:i:s');
            }

            if (isset($data['data_inicio']) && isset($data['data_fim'])) {

                $query->whereBetween('data_entrada', [$data['data_inicio'], $data['data_fim']]);

            } elseif (isset($data['data_inicio'])) {

                $query->where('data_entrada', $data['data_inicio']);

            } elseif (isset($data['data_fim'])) {

                $query->where('data_entrada', $data['data_fim']);

            }

        })
        ->orderBy('data_entrada', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            
            if (isset($data['filial_id'])) {
                $query->where('filial_id', $data['filial_id']);
            }

        })
        ->orderBy('data_entrada', 'desc')
        ->paginate($rowsPerPage);
    }
}
