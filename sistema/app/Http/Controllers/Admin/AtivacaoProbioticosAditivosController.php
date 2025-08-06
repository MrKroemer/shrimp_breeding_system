<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Util\DataPolisher;
use App\Http\Controllers\Reports\AtivacaoProbioticosAditivosReport;
use App\Models\Setores;
use App\Models\Arracoamentos;
use App\Models\ReceitasLaboratoriais;

class AtivacaoProbioticosAditivosController extends Controller
{
    public function createAtivacaoProbioticosAditivos()
    {
        $setores = Setores::where('filial_id', session('_filial')->id)
        ->orderBy('nome', 'desc')
        ->get();

        return view('reports.ativacao_probioticos_aditivos.create')
        ->with('setores', $setores);
    }

    public function generateAtivacaoProbioticosAditivos(Request $request)
    {
        $data = $request->except(['_token']);

        if (empty($data['data_aplicacao'])) {
            return redirect()->back()
            ->with('warning', 'Informe uma data para gerar o relatório.');
        }
        
        $ativacoes = [];

        foreach ($data as $key => $value) {
            
            if (strpos($key, 'tanque_') === 0) {

                $tanque_id = str_replace('tanque_', '', $key);

                $arracoamento = Arracoamentos::where('tanque_id', $tanque_id)
                ->where('data_aplicacao', $data['data_aplicacao'])
                ->orderBy('data_aplicacao', 'desc')
                ->first();

                if (! is_null($arracoamento)) {

                    $probioticos = $arracoamento->qtdProbioticoPorPeriodo();
                    $aditivos    = $arracoamento->qtdAditivoPorPeriodo();

                    $ativacao = [
                        'data_aplicacao' => $arracoamento->data_aplicacao,
                        'tanque_id'      => $arracoamento->tanque_id,
                        'tanque_sigla'   => $arracoamento->tanque->sigla,
                        'dias_cultivo'   => $arracoamento->ciclo->dias_cultivo(),
                        'ciclo_id'       => $arracoamento->ciclo->id,
                    ];

                    foreach ($probioticos as $receita_id => $probiotico) {

                        if (! empty($probiotico)) {

                            $ativacao['porcoes'][$receita_id]['probiotico_id']      = $probiotico['receita_id'];
                            $ativacao['porcoes'][$receita_id]['probiotico_nome']    = $probiotico['receita_nome'];
                            $ativacao['porcoes'][$receita_id]['probiotico_unidade'] = $probiotico['receita_unidade'];
                            $ativacao['porcoes'][$receita_id]['probiotico_manha']   = $probiotico['qtd_manha'];
                            $ativacao['porcoes'][$receita_id]['probiotico_tarde']   = $probiotico['qtd_tarde'];

                        }

                    }

                    foreach ($aditivos as $receita_id => $aditivo) {

                        if (! empty($aditivo)) {

                            $ativacao['porcoes'][$receita_id]['aditivo_id']      = $aditivo['receita_id'];
                            $ativacao['porcoes'][$receita_id]['aditivo_nome']    = $aditivo['receita_nome'];
                            $ativacao['porcoes'][$receita_id]['aditivo_unidade'] = $aditivo['receita_unidade'];
                            $ativacao['porcoes'][$receita_id]['aditivo_manha']   = $aditivo['qtd_manha'];
                            $ativacao['porcoes'][$receita_id]['aditivo_tarde']   = $aditivo['qtd_tarde'];

                        }

                    }

                    $ativacoes[] = $ativacao;

                }

            }
            
        }

        $receitas = new ReceitasLaboratoriais();

        $document = new AtivacaoProbioticosAditivosReport();
        
        $document->MakeDocument([$ativacoes, $receitas, $data['data_aplicacao']]);
        
        $fileName = 'ativacao_probioticos_aditivos_' . date('d-m-Y');

        return $document->Output($fileName, 'I');
    }
}
