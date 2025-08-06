<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VwAnalisesBiometricas extends Model
{
    protected $table = 'vw_analises_biometricas';
    protected $primaryKey = 'analise_biometrica_id';

    public function analise()
    {
        return $this->belongsTo(AnalisesBiometricas::class, 'analise_biometrica_id', 'id');
    }

    public function ciclo()
    {
        return $this->belongsTo(Ciclos::class, 'ciclo_id', 'id');
    }

    public function amostras()
    {
        return $this->hasMany(AnalisesBiometricasAmostras::class, 'analise_biometrica_id', 'id');
    }

    public function data_analise(string $format = 'd/m/Y')
    {
        return $this->analise->data_analise($format);
    }

    public function ultimas_amostras()
    {
        return $this->analise->ultimas_amostras();
    }

    public function classificacoes()
    {
        return $this->analise->classificacoes();
    }

    public function qualificacoes()
    {
        return $this->analise->qualificacoes();
    }

    public function coeficiente_variacao()
    {
        return $this->analise->coeficiente_variacao();
    }

    public function classe_variacao(float $classe = 0.0)
    {
        return $this->analise->classe_variacao($classe);
    }

    public function total_animais()
    {
        return $this->analise->total_animais();
    }

    public function peso_total()
    {
        return $this->analise->peso_total();
    }

    public function peso_medio()
    {
        return $this->analise->peso_medio();
    }

    public function sobrevivencia()
    {
        return $this->analise->sobrevivencia();
    }

    public function desvio_padrao()
    {
        return $this->analise->desvio_padrao();
    }

    public static function search(Array $data, int $rowsPerPage)
    {
        return static::where(function ($query) use ($data) {

            $query->where('filial_id', session('_filial')->id);

            if (isset($data['data_analise'])) {
                $data['data_analise'] = Carbon::createFromFormat('d/m/Y', $data['data_analise'])->format('Y-m-d');
                $query->where('data_analise', $data['data_analise']);
            }

            if (isset($data['ciclo_id'])) {
                $query->where('ciclo_id', $data['ciclo_id']);
            }

        })
        ->orderBy('data_analise', 'desc')
        ->orderBy('tanque_sigla', 'asc')
        ->paginate($rowsPerPage);
    }

    public static function listing(int $rowsPerPage = null, Array $data = null)
    {
        return static::where(function ($query) use ($data) {
            $query->where('filial_id', session('_filial')->id);
        })
        ->orderBy('data_analise', 'desc')
        ->orderBy('tanque_sigla', 'asc')
        ->paginate($rowsPerPage);
    }
}
