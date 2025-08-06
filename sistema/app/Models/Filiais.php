<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Filiais extends Model
{
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'alterado_em';

    protected $table = 'filiais';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nome',
        'cidade',
        'endereco',
        'cnpj',
    ];

    public function areaProdutiva(string $data_referencia, int $tipo_cultivo = 1, array $tanques = [])
    {
        $tanques = '{' . implode(',', $tanques) . '}';

        $area = DB::selectOne('select f_area_produtiva(?, ?, ?, ?) as valor_total', [
            $data_referencia, $tipo_cultivo, $this->id, $tanques
        ]);

        return $area->valor_total;
    }

    public function ciclosAtivos(string $data_referencia, int $tipo_cultivo = 1, array $tanques = [])
    {
        $tanques = '{' . implode(',', $tanques) . '}';
        
        $ciclos = DB::select('select * from f_ciclos_ativos(?, ?, ?, ?)', [
            $data_referencia, $tipo_cultivo, $this->id, $tanques
        ]);

        $ciclos = VwCiclosPorSetor::hydrate($ciclos);

        return $ciclos;
    }

    public function confirmaCnpj(string $cnpj)
    {
        return ($this->cnpj == $cnpj);
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            if (isset($data['id'])) {
                $query->where('id', $data['id']);
            }

            if (isset($data['nome'])) {
                $query->where('nome', 'LIKE', "%{$data['nome']}%");
            }

            if (isset($data['cnpj'])) {
                $query->where('cnpj', $data['cnpj']);
            }

        })
        ->orderBy('id')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            // 
        })
        ->orderBy('id')
        ->paginate($rowsPerPage);
    }
}
