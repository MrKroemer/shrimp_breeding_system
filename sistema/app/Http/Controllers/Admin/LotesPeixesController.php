<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LotesPeixes;
use App\Models\Especies;
use App\Http\Requests\LotesPeixesFormRequest;
use App\Http\Controllers\Util\DataPolisher;

class LotesPeixesController extends Controller
{
    private $rowsPerPage = 10;

    public function listing()
    {
        $lotes_peixes = LotesPeixes::listing($this->rowsPerPage);

        return view('admin.lotes_peixes.list')
        ->with('tipos', (new LotesPeixes)->tipo())
        ->with('lotes_peixes', $lotes_peixes);
    }

    public function search(Request $request)
    {
        $data = $request->except('_token');

        $lotes_peixes = LotesPeixes::search($data, $this->rowsPerPage);

        return view('admin.lotes_peixes.list')
        ->with('tipos', (new LotesPeixes)->tipo())
        ->with('lotes_peixes', $lotes_peixes);
    }

    public function create()
    {
        $especies = Especies::all();

        return view('admin.lotes_peixes.create')
        ->with('tipos', (new LotesPeixes)->tipo())
        ->with('especies', $especies);
    }

    public function store(LotesPeixesFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $data['filial_id']  = session('_filial')->id;
        $data['usuario_id'] = auth()->user()->id;

        if ($data['tipo'] == 2) { // Reprodução
            $data['situacao'] = 5; // Em separação
        }

        $lote_peixes = LotesPeixes::create($data);

        if ($lote_peixes instanceof LotesPeixes)  {

            if ($lote_peixes->tipo == 2) { // Reprodução
                return redirect()->route('admin.lotes_peixes');
            }
            
            return redirect()->route('admin.lotes_peixes.ovos.to_create', ['lote_peixes_id' => $lote_peixes->id]);
        }

        return redirect()->back()->withInput()
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function remove(int $id)
    {
        $data = LotesPeixes::find($id);
        
        if ($data->delete()) {
            return redirect()->back()
            ->with('success', 'Registro excluído com sucesso!');
        }
        
        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }
}
