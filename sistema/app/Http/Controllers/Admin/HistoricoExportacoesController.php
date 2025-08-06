<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HistoricoExportacoes;

class HistoricoExportacoesController extends Controller
{
    private $rowsPerPage = 10;

    public function listingHistoricoExportacoes()
    {
        $historico_exportacoes = HistoricoExportacoes::listing($this->rowsPerPage);

        return view('admin.historico_exportacoes.list')
        ->with('historico_exportacoes', $historico_exportacoes);
    }

    public function searchHistoricoExportacoes(Request $request)
    {
        $formData = $request->except(['_token']);

        $historico_exportacoes = HistoricoExportacoes::search($formData, $this->rowsPerPage);

        return view('admin.historico_exportacoes.list')
        ->with('formData', $formData)
        ->with('historico_exportacoes', $historico_exportacoes);
    }

    public function removeHistoricoExportacoes(int $id)
    {
        $data = HistoricoExportacoes::find($id);

        if ($data->delete()) {
            return redirect()->back()
            ->with('success', 'Registro excluído com sucesso!');
        } 

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }

    public function exportHistoricoExportacoes(Request $request)
    {
        $formData = $request->except('_token');

        $rules = [
            'data_exportacao' => 'required',
            'tipo_exportacao' => 'required',
        ];

        $messages = [
            'data_exportacao.required' => 'Para gerar o arquivo, e necessário informar a data das movimentações',
            'tipo_exportacao.required' => 'Para gerar o arquivo, é necessário informar os tipos de saídas',
        ];

        $formData = $this->validate($request, $rules, $messages);

        switch ($formData['tipo_exportacao']) {

            case 1: // produtos químicos
                return HistoricoExportacoes::exportaConsumoQuimicos($formData);

            case 2: // rações
                return HistoricoExportacoes::exportaConsumoRacoes($formData);

            /* case 3: // Rateio de reservatórios
                return HistoricoExportacoes::exportaConsumoRateios($formData); */

            default:
                return redirect()->back()
                ->with('error', 'O tipo de exportação não foi identificado.');

        }
    }
}
