<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Util\DataPolisher;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VwEstoqueSaidas extends Model
{
    protected $table = 'vw_estoque_saidas';

    public function produto()
    {
        return $this->belongsTo(Produtos::class, 'produto_id', 'id');
    }

    public function tanque()
    {
        return $this->belongsTo(Tanques::class, 'tanque_id', 'id');
    }

    public function filial()
    {
        return $this->belongsTo(Filiais::class, 'filial_id', 'id');
    }

    public function ciclo()
    {
        return $this->belongsTo(Ciclos::class, 'ciclo_id', 'id');
    }

    public function data_movimento(String $format = 'd/m/Y')
    {
        if (! $this->data_movimento) {
            return $this->data_movimento;
        }

        return Carbon::parse($this->data_movimento)->format($format);
    }

    public function qtd_exportacao(int $decimais = 4)
    {
        return round($this->quantidade, $decimais);
    }

    public function valor_medio()
    {
        $valor_medio = 0;

        if ($this->quantidade != 0) {
            $valor_medio = ($this->valor_total / $this->quantidade);
        }

        return $valor_medio;
    }

    public function tipo_destino($tipo_destino = null)
    {
        $tipos_destinos = [
            1 => 'Preparação',
            2 => 'Povoamento',
            3 => 'Arraçoamento',
            4 => 'Manejo',
            5 => 'Avulsa',
            6 => 'Descarte',
            7 => 'Estorno',
        ];

        if (! $tipo_destino) {
            $tipo_destino = $this->tipo_destino;
        }

        return $tipos_destinos[$tipo_destino];
    }

    public static function totaisPorCiclo(int $ciclo_id, string $data_movimento)
    {
        $saidas_totais = DB::select("select * from f_totais_por_ciclo(?, ?)", [
            $data_movimento, $ciclo_id
        ]);

        return $saidas_totais;
    }

    public static function totaisPorProduto(int $filial_id, string $data_inicial, string $data_final, array $produtos = [])
    {
        $produtos = '{' . implode(',', $produtos) . '}';
        
        $saidas_totais = DB::select("select * from f_totais_por_produto(?, ?, ?, ?)", [
            $data_inicial, $data_final, $filial_id, $produtos
        ]);

        return $saidas_totais;
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            $query->where('filial_id', session('_filial')->id);

            if (isset($data['produto_id'])) {
                $query->where('produto_id', $data['produto_id']);
            }

            if (isset($data['data_inicial']) && isset($data['data_final'])) {

                $data['data_inicial'] = Carbon::createFromFormat('d/m/Y', $data['data_inicial'])->format('Y-m-d');
                $data['data_final']   = Carbon::createFromFormat('d/m/Y', $data['data_final']  )->format('Y-m-d');

                $query->whereBetween('data_movimento', [$data['data_inicial'], $data['data_final']]);

            } elseif (isset($data['data_inicial'])) {

                $data['data_inicial'] = Carbon::createFromFormat('d/m/Y', $data['data_inicial'])->format('Y-m-d');

                $query->where('data_movimento', '>=', $data['data_inicial']);

            } elseif (isset($data['data_final'])) {

                $data['data_final']   = Carbon::createFromFormat('d/m/Y', $data['data_final']  )->format('Y-m-d');

                $query->where('data_movimento', '<=', $data['data_final']);

            }

            if (isset($data['tipo_destino'])) {
                $query->where('tipo_destino', $data['tipo_destino']);
            }

        })
        ->orderBy('data_movimento', 'desc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            $query->where('filial_id', session('_filial')->id);
        })
        ->orderBy('data_movimento', 'desc')
        ->paginate($rowsPerPage);
    }
}
