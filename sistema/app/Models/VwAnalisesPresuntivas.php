<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class VwAnalisesPresuntivas extends Model
{
    protected $table = 'vw_analises_presuntivas';
    protected $primaryKey = 'analise_presuntiva_id';

    public function analise()
    {
        return $this->belongsTo(Presuntivas::class, 'analise_presuntiva_id', 'id');
    }

    public function ciclo()
    {
        return $this->belongsTo(Ciclos::class, 'ciclo_id', 'id');
    }

    public function data_analise(String $format = 'd/m/Y')
    {
        if (! $this->data_analise) {
            return $this->data_analise;
        }

        return Carbon::parse($this->data_analise)->format($format);
    }

    public function operculosGrau(int $grau)
    {
        $percentual = 0;
        $quantidade = 0;
        $total      = 0;

        for ($i = 1; $i <= 10; $i++) {

            $valor = $this->{"cristais{$i}"};

            if (! is_null($valor)) {

                if ($valor == $grau) {
                    $quantidade ++;
                }

                $total ++;

            }

        }

        if ($total != 0) {
            $percentual = round((($quantidade / $total) * 100));
        }

        return $percentual;
    }

    public function tubulosGrau(int $grau)
    {
        $percentual = 0;
        $quantidade = 0;
        $total      = 0;

        for ($i = 1; $i <= 10; $i++) {

            $valor = $this->{"danos_tubulos{$i}"};

            if (! is_null($valor)) {

                if ($valor == $grau) {
                    $quantidade ++;
                }

                $total ++;

            }

        }

        if ($total != 0) {
            $percentual = round((($quantidade / $total) * 100));
        }

        return $percentual;
    }

    public function lipideosGrau(int $grau)
    {
        $percentual = 0;
        $quantidade = 0;
        $total      = 0;

        for ($i = 1; $i <= 10; $i++) {

            $valor = $this->{"lipideos{$i}"};

            if (! is_null($valor)) {

                if ($valor == $grau) {
                    $quantidade ++;
                }

                $total ++;

            }

        }

        if ($total != 0) {
            $percentual = round((($quantidade / $total) * 100));
        }

        return $percentual;
    }

    public function lipideosMedia()
    {
        $quantidade = 0;
        $media      = 0;
        $total      = 0;

        for ($i = 1; $i <= 10; $i++) {

            $valor = $this->{"lipideos{$i}"};

            if (! is_null($valor)) {

                $quantidade += $valor;

                $total ++;

            }

        }

        if ($total != 0) {
            $media = round(($quantidade / $total), 1);
        }

        return $media;
    }
}
