<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class HistoricoImportacoes extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'historico_importacoes';
    protected $primaryKey = 'id';
    protected $fillable = [
        'data_importacao',
        'filial_id',
        'usuario_id',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class, 'usuario_id', 'id');
    }

    public function data_importacao(String $format = 'd/m/Y')
    {
        if (! $this->data_importacao) {
            return $this->data_importacao;
        }

        return Carbon::parse($this->data_importacao)->format($format);
    }

    public static function search(array $data, int $rowsPerPage = null)
    {
        return static::where(function ($query) use ($data) {

            $query->where('filial_id', session('_filial')->id);

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['data_inicial']) && isset($data['data_final'])) {

                $data['data_inicial'] = Carbon::createFromFormat('d/m/Y', $data['data_inicial'])->format('Y-m-d');
                $data['data_final']   = Carbon::createFromFormat('d/m/Y', $data['data_final']  )->format('Y-m-d');

                $query->whereBetween('data_importacao', [$data['data_inicial'], $data['data_final']]);

            } elseif (isset($data['data_inicial'])) {

                $data['data_inicial'] = Carbon::createFromFormat('d/m/Y', $data['data_inicial'])->format('Y-m-d');

                $query->where('data_importacao', '>=', $data['data_inicial']);

            } elseif (isset($data['data_final'])) {

                $data['data_final']   = Carbon::createFromFormat('d/m/Y', $data['data_final']  )->format('Y-m-d');

                $query->where('data_importacao', '<=', $data['data_final']);

            }

        })
        ->orderBy('data_importacao', 'desc')
        ->orderBy('id',              'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null)
    {
        return static::where(function ($query) {
            $query->where('filial_id', session('_filial')->id);
        })
        ->orderBy('data_importacao', 'desc')
        ->orderBy('id',              'desc')
        ->paginate($rowsPerPage);
    }
}
