<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LotesPeixes;
use App\Models\TransferenciasPeixes;
use App\Models\Tanques;
use App\Http\Resources\LotesPeixesJsonResource;
use App\Http\Controllers\Util\DataPolisher;
use App\Http\Requests\TransferenciasPeixesCreateFormRequest;

class TransferenciasPeixesController extends Controller
{
    private $rowsPerPage = 10;

    public function listing()
    {
        $lotes = LotesPeixes::where('filial_id', session('_filial')->id)
        ->orderBy('codigo', 'desc')
        ->get();

        $transferencias = TransferenciasPeixes::listing($this->rowsPerPage);

        return view('admin.transferencias_peixes.list')
        ->with('transferencias', $transferencias)
        ->with('lotes',          $lotes);
    }

    public function search(Request $request)
    {
        $data = $request->except('_token');

        $lotes = LotesPeixes::where('filial_id', session('_filial')->id)
        ->orderBy('codigo', 'desc')
        ->get();

        $transferencias = TransferenciasPeixes::search($data, $this->rowsPerPage);

        return view('admin.transferencias_peixes.list')
        ->with('transferencias', $transferencias)
        ->with('lotes',          $lotes);
    }

    public function create()
    {
        $lotes = LotesPeixes::where('filial_id', session('_filial')->id)
        ->whereIn('situacao', [1, 2, 5, 6]) // Em eclosão, Em conversão, Em separação, Em reprodução
        ->orderBy('codigo', 'desc')
        ->get();

        $tanques = Tanques::where('filial_id', session('_filial')->id)
        // ->whereIn('tanque_tipo_id', [6, 9]) // Viveiro de peixes, Reprodução de peixes
        ->where('tanque_tipo_id', 9)
        ->orderBy('sigla')
        ->get();

        return view('admin.transferencias_peixes.create')
        ->with('tanques', $tanques)
        ->with('lotes',   $lotes);
    }

    public function store(TransferenciasPeixesCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $data['filial_id']  = session('_filial')->id;
        $data['usuario_id'] = auth()->user()->id;
        $data['proporcao']  = 1;

        $lote = LotesPeixes::find($data['lote_peixes_id']);
        $tanque = Tanques::find($data['tanque_id']);

        if ($tanque->tanque_tipo_id == 6) { // Viveiro de peixes
            return redirect()->back()->withInput()
            ->with('warning', 'Um lote de reprodução não pode ser transferido para um viveiro.');
        }

        $ultima_transferencia_l = $lote->ultima_transferencia();

        if ($ultima_transferencia_l instanceof TransferenciasPeixes) {

            if ($ultima_transferencia_l->tanque_id == $tanque->id) {
                return redirect()->back()->withInput()
                ->with('warning', 'O lote já encontra-se no tanque informado. Por favor, selecione outro tanque.');
            }

            $data['proporcao'] = $data['quantidade'] / $ultima_transferencia_l->quantidade;

        }

        $ultima_transferencia_t = $tanque->ultima_transferencia();

        if ($ultima_transferencia_t instanceof TransferenciasPeixes) {
        
            $lote_t = $ultima_transferencia_t->lote_peixes;

            $ultima_transferencia_lote_t = $lote_t->ultima_transferencia();

            // Última localização do lote é o tanque atual
            if ($ultima_transferencia_lote_t->tanque_id == $tanque->id) {
                
                switch ($lote->tipo) {

                    case 1: // Produção
        
                        // É de reprodução
                        if ($lote_t->tipo == 2) {
                            return redirect()->back()->withInput()
                            ->with('warning', 'Um lote de produção não pode ser transferido para um tanque que está ocupado com um de reprodução.');
                        }
        
                        break;
        
                    case 2: // Reprodução
        
                        // Não está encerrado
                        if ($lote_t->situacao != 7) {
                            return redirect()->back()->withInput()
                            ->with('warning', 'Um lote de reprodução não pode ser transferido para um tanque que está ocupado.');
                        }
                    
                        break;

                }

            }

        }

        $transferencia = TransferenciasPeixes::create($data);

        if ($transferencia instanceof TransferenciasPeixes) {

            $tanque = $transferencia->tanque;
            $lote   = $transferencia->lote_peixes;

            if ($lote->tipo == 1) { // Produção

                switch ($tanque->tanque_tipo_id) {
                    case 9: // Reprodução de peixes
                        $lote->situacao = 2; // Em conversão
                        break;
                    case 6: // Viveiro de peixes
                        $lote->situacao = 3; // Em recriação
                        break;
                    default:
                        $lote->situacao = 4; // Em cultivo
                }

            } else {

                $lote->situacao = 6; // Em reprodução

            }

            $lote->save();

            return redirect()->route('admin.transferencias_peixes')
            ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->back()->withInput()
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function remove(int $id)
    {
        $data = TransferenciasPeixes::find($id);

        if ($data->delete()) {

            $tanque = $data->tanque;
            $lote   = $data->lote_peixes;
            $ultima = $lote->ultima_transferencia();

            if ($lote->tipo == 1) { // Produção

                if ($ultima instanceof TransferenciasPeixes) {

                    switch ($ultima->tanque->tanque_tipo_id) {
                        case 9: // Reprodução de peixes
                            $lote->situacao = 2; // Em conversão
                            break;
                        case 6: // Viveiro de peixes
                            $lote->situacao = 3; // Em recriação
                            break;
                        default:
                            $lote->situacao = 4; // Em cultivo
                    }

                } else {

                    $lote->situacao = 1; // Em eclosão

                }

            } else {

                $lote->situacao = 5; // Em separação

            }

            $lote->save();

            return redirect()->back()
            ->with('success', 'Registro excluído com sucesso!');
        }

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }
}
