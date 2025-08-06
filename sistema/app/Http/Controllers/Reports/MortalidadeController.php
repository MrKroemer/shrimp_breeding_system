<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Arracoamentos;
use App\Models\Tanques;
use App\Models\VwMortalidade;

class MortalidadeController extends Controller
{
    public function createMortalidade()
    {
        return view('reports.mortalidade.create');
    }

    public function generateMortalidade(Request $request)
    {
        $data = $request->except(['_token']);

        if (empty($data['data_inicial'])) {
            return redirect()->back()
            ->with('warning', 'Informe uma data inicial para gerar o relatório.');
        }

        if (empty($data['data_final'])) {
            return redirect()->back()
            ->with('warning', 'Informe uma data final para gerar o relatório.');
        }
        
        $data_inicial = Carbon::createFromFormat('d/m/Y', $data['data_inicial']);
        $data_final = Carbon::createFromFormat('d/m/Y', $data['data_final']);

        $tanques = Tanques::where('tanque_tipo_id', 1)
        ->orderBy('sigla')
        ->get();

        $taxasmortalidades  = VwMortalidade::whereBetween('data_aplicacao',[$data['data_inicial'],$data['data_final']])
        ->where('filial_id', session('_filial')->id)
        ->get();

        if ($data_inicial->greaterThan($data_final)) {
            return redirect()->back()
            ->with('warning', 'Informe uma data final posterior a inicial.');
        }
        

        $document = new MortalidadeReport;
        
        $document->MakeDocument([$tanques, $taxasmortalidades, $data['data_inicial'], $data['data_final']]);
        
        $fileName = 'mortalidade_' . date('d-m-Y');

        return $document->Output($fileName, 'I');
        
    }
}
