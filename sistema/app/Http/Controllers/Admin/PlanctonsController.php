<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Util\DataPolisher;
use App\Http\Requests\PlanctonsCreateFormRequest;
use App\Models\Planctons;
use App\Models\Ciclos;
use App\Models\VwCiclosPorSetor;

class PlanctonsController extends Controller
{
    private $rowsPerPage = 10;

    public function listingPlancton()
    {
        $planctons = Planctons::listing($this->rowsPerPage, [
            'filial_id' => session('_filial')->id,
        ]);

        return view('admin.planctons.list')
        ->with('planctons',       $planctons);
    }
    
    public function searchPlancton(Request $request)
    {
        $formData = $request->except(['_token']);

        $plancton = Planctons::search($formData, $this->rowsPerPage);

        return view('admin.planctons.list')
        ->with('formData', $formData)
        ->with('planctons',  $plancton);
    }

    public function createPlancton()
    {
        $planctons = Planctons::all();

        $ciclos = VwCiclosPorSetor::where('filial_id', session('_filial')->id)
        ->whereBetween('ciclo_situacao', [6, 7])
        ->orderBy('tanque_sigla') // Engorda e despesca
        // ->whereBetween('tipo', [2, 3])     // Cultivo e reprodução de peixes
        ->get();      

        return view('admin.planctons.create')
        ->with('ciclos', $ciclos)
        ->with('planctons', $planctons);
    }

    public function storePlancton(PlanctonsCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $data['usuario_id']  = auth()->user()->id;

        $data['filial_id'] = session('_filial')->id;

        //dd($data);
        $insert = Planctons::create($data);
    
        if ($insert) {

           return redirect()->route('admin.planctons')
           ->with('success', 'Análise cadastrada com sucesso!');

        }

        return redirect()->back()
        ->with('ciclo_id',                    $data['ciclo_id'])
        ->with('data',                        $data['data'])
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar a análise. Tente novamente!');
    }

    public function editPlancton(int $id)
    {        
        $data = Planctons::find($id);

        $ciclos = Ciclos::join('tanques', 'tanque_id', '=','tanques.id')->get(['ciclos.id', 'tanque_id', 'sigla']);
        
        $total_algas = $data['cloroficeas'] + $data['cianoficeas'] + $data['diatomaceas'] + $data['euglenoficeas'];

        return view('admin.planctons.edit')
        ->with('id',                          $id)
        ->with('planctons',                   $data)
        ->with('total_algas',                 $total_algas)
        ->with('ciclos',                      $ciclos);
    }

    public function updatePlancton(Request $request, int $id)
    {   
        $data = $request->only(['data']);
        
        $data = DataPolisher::toPolish($data);
        $data['usuario_id'] = auth()->user()->id;
        $update = Planctons::where('id', $id)->update($data);
        
        if ($update) {
            
            return redirect()->route('admin.planctons')
            ->with('success', 'Plancton atualizada com sucesso!');

        }
        $data = $request->except(['_token']);
        return redirect()->back()
        ->with('ciclo_id',                    $data['ciclo_id'])
        ->with('data',                        $data['data'])
        ->with('error','Oops! Algo de errado ocorreu ao salvar a Plancton. Tente novamente!');
    }

    public function removePlancton(int $id)
    {
        $data = Planctons::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->route('admin.planctons')
            ->with('success', 'Análise Plancton excluída com sucesso!');
        } 
        
        return redirect()->route('admin.planctons')
        ->with('success', 'Ocorreu um erro ao excluir o Análise. Tente novamente!');
    }
}
