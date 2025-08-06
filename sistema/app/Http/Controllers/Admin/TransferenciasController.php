<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transferencias;
use App\Models\Ciclos;
use App\Models\Tanques;
use App\Models\VwConsolidacaoQuantidadePeixes;
use App\Models\VwCicloOrigemTransferencia;
use App\Http\Requests\TransferenciasCreateFormRequest;
use App\Http\Controllers\Util\DataPolisher;
use Carbon\Carbon;
use App\Http\Resources\TanquesJsonResource;

class TransferenciasController extends Controller
{
    private $rowsPerPage = 10;

    public function listingTransferencias()
    {
        $transferencias = Transferencias::listing($this->rowsPerPage);

        $tanques_origem = Tanques::where('tanques.filial_id', session('_filial')->id)->distinct()
        ->join('ciclos', 'ciclos.tanque_id', '=', 'tanques.id')
        ->join('vw_ciclo_origem_transferencia', 'vw_ciclo_origem_transferencia.ciclo_id', '=', 'ciclos.id')
        ->get();

        $tanques_destino = Tanques::where('tanques.filial_id', session('_filial')->id)->distinct()
        ->join('ciclos', 'ciclos.tanque_id', '=', 'tanques.id')
        ->where('ciclos.tipo', 2)
        ->get();

        return view('admin.transferencias.list')
        ->with('transferencias',  $transferencias)
        ->with('tanques_origem',  $tanques_origem)
        ->with('tanques_destino', $tanques_destino);
    }

    public function searchTransferencias(Request $request)
    {
        $formData = $request->except(['_token']);
        $formData['filial_id'] = session('_filial')->id;

        $transferencias = Transferencias::search($formData, $this->rowsPerPage);

        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->where('situacao', '<>', 1) // Vazio
        ->get();

        return view('admin.transferencias.list')
        ->with('formData',    $formData)
        ->with('transferencias', $transferencias)
        ->with('ciclos',      $ciclos);
    }

    public function createTransferencias()
    {
        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->where('tipo','<>',1)
        ->get();

        $tanques_origem = VwCicloOrigemTransferencia::where('filial_id', session('_filial')->id)
        ->get(['ciclo_id','numero','data_inicio','sigla']);

        /* $tanques_destino = Tanques::where('tanques.filial_id', session('_filial')->id)->distinct()
        ->join('ciclos', 'ciclos.tanque_id', '=', 'tanques.id')
        ->where('ciclos.tipo', 2)
        ->get(['ciclos.id','ciclos.numero','ciclos.data_inicio','tanques.sigla']); */

        return view('admin.transferencias.create')
        ->with('tanques_origem', $tanques_origem);
    }

    public function storeTransferencias(TransferenciasCreateFormRequest $request)
    {
        $data = $request->except(['_token']);
        
        $data = DataPolisher::toPolish($data, ['EMPTY_TO_NULL']);

        $data['filial_id']  = session('_filial')->id;
        $data['usuario_id'] = auth()->user()->id;
        
        if(!$data['proporcao']){
            $data['proporcao'] = 0;
        }

        $transferencias = Transferencias::create($data);

        if ($transferencias instanceof Transferencias) {
            return redirect()->route('admin.transferencias')
            ->with('success', 'Registro salvo com sucesso!');
        }

        $message = 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.';

        return redirect()->back()->withInput()
        ->with('error', $message);
    }

    public function removeTransferencias(int $id)
    {
        $data = Transferencias::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->route('admin.setores')
            ->with('success', 'Transferência excluida com sucesso!');
        } 
        
        return redirect()->route('admin.setores')
        ->with('error', 'Ocorreu um erro ao excluir a Transferência. Tente novamente!');
    }

    public function getJsonCiclos(int $ciclo)
    {
        
        $ciclo = Ciclos::find($ciclo);
        //dd($ciclo);
        switch ($ciclo->tipo) {

            case 2: // Maturação ou Engorda
                $ciclos = Ciclos::where('ciclos.filial_id', session('_filial')->id)
                ->join('tanques', 'tanques.id', '=', 'ciclos.tanque_id')
                ->where('ciclos.tipo', 2) // Maturação ou engorda
                ->where('tanques.setor_id','<>', 1) // Maturação ou engorda
                ->where('ciclos.id','<>', $ciclo->id) // Maturação ou engorda
                ->get(['ciclos.id','ciclos.numero','ciclos.data_inicio','tanques.sigla']);
                break;

            case 3: // Reprodução
                $ciclos = Ciclos::where('ciclos.filial_id', session('_filial')->id)
                ->join('tanques', 'tanques.id', '=', 'ciclos.tanque_id')
                ->where('ciclos.tipo', 2) // Maturação
                ->get(['ciclos.id','ciclos.numero','ciclos.data_inicio','tanques.sigla']);
                break;

        }

        return TanquesJsonResource::collection($ciclos);

    }

    public function getQuantidadePeixes(int $ciclo_id)
    {
        $quantidade_peixes = VwConsolidacaoQuantidadePeixes::where('ciclo_id', $ciclo_id)
        ->first();
        
        $quantidade = "";

        if($quantidade_peixes->tipo == 3){
            $quantidade = "∞";
        }else{
            $quantidade = $quantidade_peixes->quantidade;
        }

        return $quantidade;
    }






































































































































































































/*


   

    public function editPreparacoes(int $id)
    {
        $data = Preparacoes::find($id);

        $cicloSituacao = $data->ciclo->verificarSituacao([2, 3, 4]);

        return view('admin.preparacoes.edit')
        ->with('id',            $id)
        ->with('data_inicio' ,  $data->data_inicio())
        ->with('data_fim' ,     $data->data_fim())
        ->with('abas_inicio' ,  $data->abas_inicio())
        ->with('abas_fim' ,     $data->abas_fim())
        ->with('bandejas',      $data->bandejas)
        ->with('ph_solo',       $data->ph_solo)
        ->with('observacoes',   $data->observacoes)
        ->with('ciclo',         $data->ciclo)
        ->with('cicloSituacao', $cicloSituacao);
    }

    public function updatePreparacoes(PreparacoesEditFormRequest $request, int $id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data, ['EMPTY_TO_NULL']);

        $data['usuario_id'] = auth()->user()->id;

        $preparacao = Preparacoes::find($id);

        $ciclo = $preparacao->ciclo;

        $data_inicio = Carbon::createFromFormat('d/m/Y', $data['data_inicio'])->format('Y-m-d');

        $message = 'A data de início da preparação deve suceder a data de início do ciclo.';

        if ($ciclo->sucedeDataInicio($data_inicio)) {

            $data = $this->checkDatesSequence($data, $ciclo);

            if (! is_array($data)) {
                return $data;
            }

            if ($preparacao->update($data)) {
                return redirect()->route('admin.preparacoes')
                ->with('success', 'Registro salvo com sucesso!');
            }

            $message = 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.';
        
        }

        return redirect()->back()->withInput()
        ->with('id', $id)
        ->with('error', $message);
    }

    public function removePreparacoes(int $id)
    {
        $preparacao = Preparacoes::find($id);

        if ($preparacao->delete()) {

            $ciclo = $preparacao->ciclo;
            $ciclo->situacao = 1; // Vazio
            $ciclo->save();

            return redirect()->route('admin.preparacoes')
            ->with('success', 'Registro excluido com sucesso!');

        }

        return redirect()->route('admin.preparacoes')
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }

    public function viewPreparacoes(int $id)
    {
        return $this->editPreparacoes($id);
    }

    private function checkDatesSequence(Array $data, Ciclos $ciclo)
    {
        $dates[] = Carbon::createFromFormat('d/m/Y', $data['data_inicio']);
        $ciclo->situacao = 2; // LIMPEZA

        if (! is_null($data['abas_inicio'])) {
            $dates[] = Carbon::createFromFormat('d/m/Y', $data['abas_inicio']);
            $ciclo->situacao = 3; // ABASTECIMENTO
        }

        if (! is_null($data['abas_fim'])) {
            if (! is_null($data['abas_inicio'])) {
                $dates[] = Carbon::createFromFormat('d/m/Y', $data['abas_fim']);
                $ciclo->situacao = 4; // FERTILIZAÇÃO
            } else {
                $data['abas_fim'] = null;
            }
        }

        if (! is_null($data['data_fim'])) {
            if (! is_null($data['abas_fim'])) {
                if (! is_null($data['abas_inicio'])) {
                    $dates[] = Carbon::createFromFormat('d/m/Y', $data['data_fim']);
                    $ciclo->situacao = 5; // POVOAMENTO
                } else {
                    $data['abas_fim'] = null;
                    $data['data_fim'] = null;
                }
            } else {
                $data['data_fim'] = null;
            }
        }

        foreach ($dates as $date1) {

            $date2 = next($dates);

            if (! empty($date2)) {

                if ($date1->greaterThan($date2)) {
                    return redirect()->back()->withInput()
                    ->with('warning', 'A data posterior deve suceder a anterior.');
                }

            }

        }

        if (! $ciclo->save()) {
            return redirect()->back()->withInput()
            ->with('error', 'Não foi possível alterar o status do ciclo.');
        }

        return $data;
    }
*/
}
