<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ciclos;
use App\Models\Tanques;
use App\Models\Setores;
use App\Models\Subsetores;
use App\Models\Despescas;
use App\Models\AnalisesBiometricas;
use App\Models\ColetasParametrosNew;
use App\Models\LotesPeixes;

class DashboardController extends Controller
{
    private $cores = [
        0 => '#b7b7b72e',
        1 => '#713c16',
        2 => '#30d4d4', 
        3 => '#5f5fff', 
        4 => '#ff894e', 
        5 => '#d2af00', 
        6 => '#17a917', 
        7 => '#fb4dfb',
        8 => '#b7b7b7', 
        9 => '#222d32',
    ];

    public function getCores()
    {
        return $this->cores;
    }

    public function dashboard()
    {
        $setores = Setores::where('filial_id', session('_filial')->id)
        ->orderBy('nome', 'desc')
        ->get();

        $despescas = Despescas::where('filial_id', session('_filial')->id)
        ->orderBy('data_fim', 'desc')
        ->limit(5)
        ->get();

        $biometrias = AnalisesBiometricas::where('filial_id', session('_filial')->id)
        ->orderBy('data_analise', 'desc')
        ->limit(5)
        ->get();

        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->orderBy('data_inicio', 'desc')
        ->limit(5)
        ->get();

        return view('admin.dashboard.main')
        ->with('setores',    $setores)
        ->with('despescas',  $despescas)
        ->with('biometrias', $biometrias)
        ->with('ciclos',     $ciclos);
    }

    public function tanques(int $setor_id)
    {
        $tanques = Tanques::where('setor_id', $setor_id)
        ->orderBy('nome')
        ->get();

        $setor = Setores::find($setor_id);

        $view = view('admin.dashboard.tanques');

        if ($setor->tipo == 2) { // Produção de peixes
            $view = view('admin.dashboard.tanques_peixes');
        }

        return $view
        ->with('setor',   $setor)
        ->with('tanques', $tanques)
        ->with('cores',   $this->cores);
    }


    public function tanquesInformacoes(int $setor_id, int $tanque_id)
    {
        $biomassa    = 0;
        $oxigenio    = 0;
        $biofloco    = 0;
        $salinidade  = 0;
        $ph          = 0;
        $nivel       = 0;
        $temperatura = 0;

        $parametros = [
            1 => 'oxigenio',
            2 => 'biofloco',
            3 => 'salinidade',
            6 => 'ph',
            7 => 'nivel',
            8 => 'temperatura',
        ];

        $setor = Setores::find($setor_id);

        $tanque = Tanques::find($tanque_id);

        $ciclo = $tanque->ultimo_ciclo();

        if ($ciclo instanceof Ciclos) {

            if (! is_null($ciclo->biomassa())) {
                $biomassa = $ciclo->biomassa();
            }

        }

        foreach ($parametros as $id => $parametro) {

            $coleta = ColetasParametrosNew::where('tanque_tipo_id', $tanque->tanque_tipo_id)
            ->where('coleta_parametro_tipo_id', $id)
            ->orderBy('id', 'desc')
            ->first();

            if ($coleta instanceof ColetasParametrosNew) {

                if ($coleta->amostras->isNotEmpty()) {

                    $amostra = $coleta->amostras
                    ->where('tanque_id', $tanque->id)
                    ->sortByDesc('id')
                    ->first();

                    ${$parametro} = (! empty($amostra) && ! empty($amostra->valor) ? $amostra->valor : 0);

                }

            }

        }

        return view('admin.dashboard.informacoes')
        ->with('ciclo',       $ciclo)
        ->with('setor',       $setor)
        ->with('tanque',      $tanque)
        ->with('setor_id',    $setor_id)
        ->with('tanque_id',   $tanque_id)
        ->with('biomassa',    $biomassa)
        ->with('oxigenio',    $oxigenio)
        ->with('ph',          $ph)
        ->with('biofloco',    $biofloco)
        ->with('temperatura', $temperatura)
        ->with('nivel',       $nivel)
        ->with('salinidade',  $salinidade);
    }

    public function tanquesInformacoesPeixes(int $setor_id, int $tanque_id, int $lote_peixes_id)
    {
        $biomassa    = 0;
        $oxigenio    = 0;
        $biofloco    = 0;
        $salinidade  = 0;
        $ph          = 0;
        $nivel       = 0;
        $temperatura = 0;

        $parametros = [
            1 => 'oxigenio',
            2 => 'biofloco',
            3 => 'salinidade',
            6 => 'ph',
            7 => 'nivel',
            8 => 'temperatura',
        ];

        $setor = Setores::find($setor_id);

        $tanque = Tanques::find($tanque_id);

        $lote = LotesPeixes::find($lote_peixes_id);

        if ($lote instanceof LotesPeixes) {

            if (! is_null($lote->biomassa())) {
                $biomassa = $lote->biomassa();
            }

        }

        foreach ($parametros as $id => $parametro) {

            $coleta = ColetasParametrosNew::where('tanque_tipo_id', $tanque->tanque_tipo_id)
            ->where('coleta_parametro_tipo_id', $id)
            ->orderBy('id', 'desc')
            ->first();

            if ($coleta instanceof ColetasParametrosNew) {

                if ($coleta->amostras->isNotEmpty()) {

                    $amostra = $coleta->amostras
                    ->where('tanque_id', $tanque->id)
                    ->sortByDesc('id')
                    ->first();

                    ${$parametro} = (! empty($amostra) && ! empty($amostra->valor) ? $amostra->valor : 0);

                }

            }

        }

        return view('admin.dashboard.informacoes_peixes')
        ->with('lote',        $lote)
        ->with('setor',       $setor)
        ->with('tanque',      $tanque)
        ->with('setor_id',    $setor_id)
        ->with('tanque_id',   $tanque_id)
        ->with('biomassa',    $biomassa)
        ->with('oxigenio',    $oxigenio)
        ->with('ph',          $ph)
        ->with('biofloco',    $biofloco)
        ->with('temperatura', $temperatura)
        ->with('nivel',       $nivel)
        ->with('salinidade',  $salinidade);
    }
}
