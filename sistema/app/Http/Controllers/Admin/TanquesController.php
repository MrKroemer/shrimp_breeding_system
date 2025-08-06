<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TanquesCreateFormRequest;
use App\Http\Requests\TanquesTiposCreateFormRequest;
use App\Http\Controllers\Util\DataPolisher;
use App\Models\Tanques;
use App\Models\TanquesTipos;
use App\Models\Setores;
use App\Models\Ciclos;
use App\Http\Resources\TanquesJsonResource;

class TanquesController extends Controller
{
    private $rowsPerPage = 10;

    public function listingTanques()
    {
        $tanques = Tanques::listing($this->rowsPerPage);

        return view('admin.tanques.list')
        ->with('tanques', $tanques);
    }
    
    public function searchTanques(Request $request)
    {
        $formData = $request->except(['_token']);

        $tanques = Tanques::search($formData, $this->rowsPerPage);

        return view('admin.tanques.list')
        ->with('formData',   $formData)
        ->with('tanques', $tanques);
    }

    public function createTanques()
    {
        $tanques_tipos = TanquesTipos::orderBy('nome')
        ->get();

        $setores = Setores::where('filial_id', session('_filial')->id)
        ->orderBy('nome')
        ->get();

        return view('admin.tanques.create')
        ->with('tanques_tipos', $tanques_tipos)
        ->with('setores',       $setores);
    }

    public function storeTanques(TanquesCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $data['filial_id']  = session('_filial')->id;
        $data['usuario_id'] = auth()->user()->id;

        $insert = Tanques::create($data);

        if ($insert) {
            return redirect()->route('admin.tanques')
            ->with('success', 'Tanque cadastrado com sucesso!');
        }

        return redirect()->back()->withInput()
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o tanque. Tente novamente!');
    }

    public function editTanques(int $id)
    {
        $data = Tanques::find($id);

        $tanques_tipos = TanquesTipos::orderBy('nome')
        ->get();

        $setores = Setores::where('filial_id', session('_filial')->id)
        ->orderBy('nome')
        ->get();

        // $subsetores    = Subsetores::where('setor_id', $data['setor_id'])->get(['id', 'nome']);

        return view('admin.tanques.edit')
        ->with('id',             $id)
        ->with('nome',           $data['nome'])
        ->with('sigla',          $data['sigla'])
        ->with('area',           $data['area'])
        ->with('altura',         $data['altura'])
        ->with('volume',         $data['volume'])
        ->with('nivel',          $data['nivel'])
        ->with('tanque_tipo_id', $data['tanque_tipo_id'])
        ->with('setor_id',       $data['setor_id'])
        ->with('tanques_tipos',  $tanques_tipos)
        ->with('setores',        $setores);
    }

    public function updateTanques(TanquesCreateFormRequest $request, int $id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $data['usuario_id'] = auth()->user()->id;

        $update = Tanques::find($id);

        if ($update->update($data)) {
            return redirect()->back()
            ->with('success', 'Tanque atualizada com sucesso!');
        }

        return redirect()->back()->withInput()
        ->with('id', $id)
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o tanque. Tente novamente!');
    }

    public function removeTanques(int $id)
    {
        $delete = Tanques::find($id);

        if ($delete->delete()) {
            return redirect()->back()
            ->with('success', 'Tanque excluido com sucesso!');
        }

        return redirect()->back()
        ->with('error', 'Ocorreu um erro ao excluir o tanque. Tente novamente!');
    }

    public function turnTanques(int $id)
    {
        $data = Tanques::find($id);

        $data->usuario_id = auth()->user()->id;

        if ($data->situacao == 'ON') {

            $data->situacao = 'OFF';
            $data->save();

            return redirect()->back()
            ->with('success', 'Tanque desabilitado com sucesso!');

        }

        $data->situacao = 'ON';
        $data->save();

        return redirect()->route('admin.tanques')
        ->with('success', 'Tanque habilitado com sucesso!');
    }

    public function getJsonTanques(int $tanque_tipo_id)
    {
        $ciclos = Ciclos::where('situacao', '<>', 8)
        ->groupBy('tanque_id')
        ->get(['tanque_id']);

        $tanques = Tanques::tanquesAtivos()
        ->join('tanques_tipos', 'tanques_tipos.id', '=', 'tanques.tanque_tipo_id')
        ->join('setores', 'setores.id', '=', 'tanques.setor_id')
        ->where('tanques.situacao', 'ON')
        ->whereNotIn('tanques.id', $ciclos)
        ->orderBy('tanques.sigla');

        $colunas = [
            'tanques.id', 
            'tanques.sigla', 
            'tanques_tipos.nome AS tipo', 
            'setores.sigla AS setor'
        ];

        switch ($tanque_tipo_id) {

            case 1: // Camarões
                $tanques->whereIn('tanque_tipo_id', [1, 9]);
                break;

            case 2: // Peixes
                $tanques->whereIn('tanque_tipo_id', [2, 3, 6]);
                break;
            
            case 3: // Peixes Reprodução
                $tanques->where('setor_id', 1);
                break;

        }

        return TanquesJsonResource::collection($tanques->get($colunas));

    }
}
