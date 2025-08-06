<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tanques;
use App\Models\TransferenciasAnimais;
use App\Models\VwCiclosPeixes;
use App\Http\Requests\TransferenciasAnimaisCreateFormRequest;
use App\Http\Controllers\Util\DataPolisher;
use Carbon\Carbon;
use App\Http\Resources\TanquesJsonResource;

class TransferenciasAnimaisController extends Controller
{
    private $rowsPerPage = 10;

    public function listing()
    {
        $transferencias = TransferenciasAnimais::listing($this->rowsPerPage);

        $tanques = Tanques::where('filial_id', session('_filial')->id)
        ->where('tanque_tipo_id', 6) // Viveiro de peixes
        ->orderBy('sigla', 'asc')
        ->get();

        return view('admin.transferencias_animais.list')
        ->with('transferencias', $transferencias)
        ->with('tanques',        $tanques);
    }

    public function search(Request $request)
    {
        $formData = $request->except(['_token']);

        $transferencias = TransferenciasAnimais::search($formData, $this->rowsPerPage);

        $tanques = Tanques::where('filial_id', session('_filial')->id)
        ->where('tanque_tipo_id', 6) // Viveiro de peixes
        ->orderBy('sigla', 'asc')
        ->get();

        return view('admin.transferencias_animais.list')
        ->with('formData',       $formData)
        ->with('transferencias', $transferencias)
        ->with('tanques',        $tanques);
    }

    public function create()
    {
        $ciclos = VwCiclosPeixes::where('filial_id', session('_filial')->id)
        ->where('ciclo_situacao', '<>', 8) // Encerrado
        ->where('tanque_situacao', 'ON')
        ->orderBy('tanque_sigla', 'asc')
        ->get();

        /* $tanques_origem = VwCicloOrigemTransferencia::where('filial_id', session('_filial')->id)
        ->get(['ciclo_id','numero','data_inicio','sigla']); */

        /* $tanques_destino = Tanques::where('tanques.filial_id', session('_filial')->id)->distinct()
        ->join('ciclos', 'ciclos.tanque_id', '=', 'tanques.id')
        ->where('ciclos.tipo', 2)
        ->get(['ciclos.id','ciclos.numero','ciclos.data_inicio','tanques.sigla']); */

        return view('admin.transferencias_animais.create')
        ->with('ciclos', $ciclos);
    }

    public function store(TransferenciasAnimaisCreateFormRequest $request)
    {
        $data = $request->except(['_token']);
        
        $data = DataPolisher::toPolish($data);

        $data['filial_id']  = session('_filial')->id;
        $data['usuario_id'] = auth()->user()->id;
        $data['proporcao']  = 1;

        $ciclo_origem = VwCiclosPeixes::where('ciclo_id', $data['ciclo_origem_id'])
        ->first();

        if ($ciclo_origem->ciclo_tipo == 2) { // Cultivo de peixes

            $data['qtd_disponivel'] = ($ciclo_origem->animais_recebidos - $ciclo_origem->animais_enviados);

            if ($data['qtd_disponivel'] <= 0 || $data['qtd_disponivel'] < $data['quantidade']) {
                return redirect()->back()->withInput()
                ->with('warning', 'O tanque de origem não possui animais suficientes para realizar está transferência.');
            }

            $data['proporcao'] = ($data['quantidade'] / $data['qtd_disponivel']);

        }

        $transferencias = TransferenciasAnimais::create($data);

        if ($transferencias instanceof TransferenciasAnimais) {
            return redirect()->route('admin.transferencias_animais')
            ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->back()->withInput()
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function remove(int $id)
    {
        $data = TransferenciasAnimais::find($id);

        if ($data->delete()) {
            return redirect()->back()
            ->with('success', 'Registro excluída com sucesso!');
        } 

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }

    public function getJsonCiclosDestino(int $ciclo_id)
    {
        $ciclos = VwCiclosPeixes::where('filial_id', session('_filial')->id)
        ->where('ciclo_situacao', '<>', 8) // Encerrado
        ->where('tanque_situacao', 'ON')
        ->orderBy('tanque_sigla', 'asc')
        ->get();

        $ciclos->each(function ($item, $key) use ($ciclos, $ciclo_id) {
            
            $ciclos[$key]->ciclo_inicio = $item->ciclo_inicio();
            $ciclos[$key]->ciclo_fim    = $item->ciclo_fim();

            if ($item->ciclo_id == $ciclo_id) {

                $recebidos = $item->animais_recebidos;
                $enviados  = $item->animais_enviados;

                $ciclos[$key]->disponivel = ($recebidos - $enviados);

                if ($item->ciclo_tipo == 3) { // Reprodução de peixes
                    $ciclos[$key]->disponivel = -1;
                }

            }

        });

        return TanquesJsonResource::collection($ciclos);
    }
}
