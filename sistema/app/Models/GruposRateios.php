<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GruposRateios extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'grupos_rateios';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome',
        'descricao',
        'filial_id',
        'usuario_id',
    ];

    public function tanques(string $tipo)
    {
        return VwGruposRateiosTanques::where('grupo_rateio_id', $this->id)
        ->where('tipo', $tipo)
        ->get();
    }

    public function areaRecebimentoConsumo(string $data_referencia, int $tipo_cultivo = 1)
    {
        $area = DB::selectOne('select f_area_recebimento_consumos(?, ?, ?, ?) as valor_total', [
            $data_referencia, $tipo_cultivo, $this->filial_id, $this->id
        ]);

        return $area->valor_total;
    }

    public static function search(array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            $query->where('filial_id', session('_filial')->id);

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['nome'])) {
                $query->where('nome', 'LIKE', "%{$data['nome']}%");
            }

        })
        ->orderBy('nome', 'asc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, array $data = null)
    {
        return static::where(function ($query) use ($data) {
            $query->where('filial_id', session('_filial')->id);
        })
        ->orderBy('nome', 'asc')
        ->paginate($rowsPerPage);
    }
}
