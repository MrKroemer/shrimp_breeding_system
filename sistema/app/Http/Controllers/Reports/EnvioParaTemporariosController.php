<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\EnvioParaTemporariosReport;
use App\Http\Requests\EnvioParaTemporariosReportCreateFormRequest;
use App\Models\Produtos;
use App\Models\VwEstoqueSaidas;
use App\Models\VwProdutos;
use Carbon\Carbon;

class EnvioParaTemporariosController extends Controller
{
    public function create()
    {
        $produtos = Produtos::where('filial_id', session('_filial')->id)
        ->orderBy('nome', 'asc')
        ->get();

        return view('reports.envio_para_temporarios.create')
        ->with('produtos', $produtos);
    }

    public function generate(EnvioParaTemporariosReportCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data_transferencia = Carbon::createFromFormat('d/m/Y', $data['data_transferencia'])->format('Y-m-d');

        $estoque_saidas = VwEstoqueSaidas::where('filial_id', session('_filial')->id)
        ->where(function ($query) use ($data) {

            $query->where('data_movimento', $data['data_transferencia']);
            
            if (isset($data['produtos'])) {
                $query->whereIn('produto_id', $data['produtos']);
            }
            
            $query->where('tipo_destino', '<>', 7);    // Estorno

            $query->where(function ($query) {
                $query->where('produto_tipo_id',   1); // INSUMOS
                $query->orWhere('produto_tipo_id', 2); // RAÇÕES
                $query->orWhere('produto_tipo_id', 3); // PROBIÓTICOS
                $query->orWhere('produto_tipo_id', 4); // OUTROS
            });

            $query->whereIn('tanque_tipo_id', [1, 6]); // Viveiro de camarões e peixes

        })
        ->orderBy('produto_nome')
        ->get();

        $locais_transferencia = [];

        foreach ($estoque_saidas as $estoque_saida) {

            if (is_numeric($estoque_saida->ciclo_id) && $estoque_saida->ciclo_id > 0) {

                $local_estoque = 'ET-PQ';

                if ($estoque_saida->produto_tipo_id == 2) {
                    $local_estoque = $estoque_saida->tanque->id . '-' . $estoque_saida->tanque->sigla;
                }

                if (! isset($locais_transferencia[$local_estoque][$estoque_saida->produto_id])) {
                    $locais_transferencia[$local_estoque][$estoque_saida->produto_id] = 0;
                }

                $locais_transferencia[$local_estoque][$estoque_saida->produto_id] += $estoque_saida->qtd_exportacao();

            }

        }

        $ciclos = session('_filial')->ciclosAtivos($data_transferencia, 1); // Viveiro de camarões

        foreach ($ciclos as $ciclo) {

            $consumos_recebidos = $ciclo->consumosRecebidos($data_transferencia, $data_transferencia);

            foreach ($consumos_recebidos as $tanque_tipo_id => $produtos) {

                $local_estoque = 'ET-PQ';

                // Reservatório de água salgada OU Bacia de sedimentação
                if ($tanque_tipo_id == 3 || $tanque_tipo_id == 4) {
                    $local_estoque = 'ETR-PQ';
                }

                foreach ($produtos as $produto_id => $quantidade) {

                    $produto = VwProdutos::find($produto_id);

                    if (
                        $produto->produto_tipo_id == 1 || // INSUMOS
                        $produto->produto_tipo_id == 3 || // PROBIÓTICOS
                        $produto->produto_tipo_id == 4    // OUTROS
                    ) {

                        if (! isset($locais_transferencia[$local_estoque][$produto->id])) {
                            $locais_transferencia[$local_estoque][$produto->id] = 0;
                        }
        
                        $locais_transferencia[$local_estoque][$produto->id] += round($quantidade, 4);

                    }

                }

            }

        }

        krsort($locais_transferencia);

        $document = new EnvioParaTemporariosReport;

        $document->MakeDocument([$locais_transferencia, $data['data_transferencia']]);
        
        $fileName = 'envio_para_temporarios_' . date('Y-m-d_H-i-s') . '.pdf';

        return $document->Output($fileName, 'I');
    }
}
