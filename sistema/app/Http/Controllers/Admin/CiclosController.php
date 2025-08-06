<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ciclos;
use App\Models\VwCiclosPorSetor;
use App\Models\Tanques;
use App\Http\Requests\CiclosCreateFormRequest;
use App\Http\Controllers\Util\DataPolisher;
use App\Http\Resources\CiclosJsonResource;

class CiclosController extends Controller
{
    private $rowsPerPage = 10;

    public function listingCiclos()
    {
        $ciclos = Ciclos::listing($this->rowsPerPage, [
            'filial_id' => session('_filial')->id
        ]);

        $tanques = Tanques::where('filial_id', session('_filial')->id)
        ->where('tanque_tipo_id', 1)   // Viveiro de camarões
        ->orWhere('tanque_tipo_id', 6) // Viveiro de peixes
        ->orderBy('sigla')
        ->get();

        return view('admin.ciclos.list')
        ->with('ciclos',  $ciclos)
        ->with('tanques', $tanques);
    }

    public function searchCiclos(Request $request)
    {
        $formData = $request->except(['_token']);
        $formData['filial_id'] = session('_filial')->id;

        $ciclos = Ciclos::search($formData, $this->rowsPerPage);

        $tanques = Tanques::where('filial_id', session('_filial')->id)
        ->where('tanque_tipo_id', 1)   // Viveiro de camarões
        ->orWhere('tanque_tipo_id', 6) // Viveiro de peixes
        ->orderBy('sigla')
        ->get();

        return view('admin.ciclos.list')
        ->with('formData', $formData)
        ->with('ciclos',   $ciclos)
        ->with('tanques',  $tanques);
    }

    public function createCiclos()
    {
        return view('admin.ciclos.create')
        ->with('tipos', (new Ciclos)->tipo());
    }

    public function storeCiclos(CiclosCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $data['filial_id'] = session('_filial')->id;
        $data['usuario_id'] = auth()->user()->id;

        $ciclo = Ciclos::where('filial_id', session('_filial')->id)
        ->where('numero', $data['numero'])
        ->first();

        if ($ciclo instanceof Ciclos) {
            return redirect()->back()->withInput()
            ->with('warning', "O ciclo Nº{$ciclo->numero} está registrado para o tanque {$ciclo->tanque->sigla}.");
        }

        $ciclo = Ciclos::create($data);

        if ($ciclo instanceof Ciclos) {
            return redirect()->route('admin.ciclos')
            ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->back()
        ->with('tanque_id', $data['tanque_id'])
        ->with('data_inicio', $data['data_inicio'])
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function removeCiclos(int $id)
    {
        $ciclo = Ciclos::find($id);

       //if ($ciclo->situacao == 1) { // Vazio
            
            if ($ciclo->delete()) {
                return redirect()->route('admin.ciclos')
                ->with('success', 'Registro excluido com sucesso!');
            }

       //}

        return redirect()->route('admin.ciclos')
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }

    public function getJsonVwCiclosPorSetor(int $ciclo_situacao, int $setor_id)
    {
        $ciclos = VwCiclosPorSetor::where('filial_id', session('_filial')->id)
        ->where('setor_id', $setor_id)
        ->where('tanque_situacao', 'ON')
        ->where(function ($query) use ($ciclo_situacao) {
            if ($ciclo_situacao > 0) {
                $query->where('ciclo_situacao', $ciclo_situacao);
            }
        })
        ->orderBy('tanque_sigla', 'asc')
        ->get(['ciclo_id', 'tanque_id', 'tanque_sigla', 'setor_id']);

        return CiclosJsonResource::collection($ciclos);
    }

    public function getNumeroCiclo(int $tipo)
    {
        $ciclo = Ciclos::where('filial_id', session('_filial')->id)
        ->where('tipo', $tipo)
        ->orderBy('numero', 'desc')
        ->first();

        $numero = 1;

        if ($ciclo instanceof Ciclos) {
            $numero += $ciclo->numero;
        }

        return $numero;
    }
}
