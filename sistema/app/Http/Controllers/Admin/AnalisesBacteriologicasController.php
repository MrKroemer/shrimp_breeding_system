<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TanquesTipos;
use App\Models\AnalisesLaboratoriaisNew;
use App\Models\AnalisesLaboratoriaisTipos;
use App\Models\AnalisesBacteriologicasNew;
use App\Http\Requests\AnalisesLaboratoriaisCreateFormRequest;
use App\Http\Controllers\Util\DataPolisher;

class AnalisesBacteriologicasController extends Controller
{
    private $rowsPerPage = 10;
    private $analisesLaboratoriaisTipos = [1, 2, 3];

    public function listingAnalisesBacteriologicas()
    {
        $analises_laboratoriais = AnalisesLaboratoriaisNew::listing($this->rowsPerPage, [
            'filial_id' => session('_filial')->id,
            'analises_laboratoriais_tipos' => $this->analisesLaboratoriaisTipos,
        ]);

        $analises_laboratoriais_tipos = AnalisesLaboratoriaisTipos::whereIn('id', $this->analisesLaboratoriaisTipos)
        ->orderBy('nome', 'desc')
        ->get();

        $tanques_tipos = TanquesTipos::orderBy('nome')->get();

        return view('admin.analises_bacteriologicas.list')
        ->with('analises_laboratoriais',       $analises_laboratoriais)
        ->with('analises_laboratoriais_tipos', $analises_laboratoriais_tipos)
        ->with('tanques_tipos',                $tanques_tipos);
    }
    
    public function searchAnalisesBacteriologicas(Request $request)
    {
        $formData = $request->except(['_token']);
        $formData['filial_id'] = session('_filial')->id;
        $formData['analises_laboratoriais_tipos'] = $this->analisesLaboratoriaisTipos;

        $analises_laboratoriais = AnalisesLaboratoriaisNew::search($formData, $this->rowsPerPage);

        $analises_laboratoriais_tipos = AnalisesLaboratoriaisTipos::whereIn('id', $this->analisesLaboratoriaisTipos)
        ->orderBy('nome', 'desc')
        ->get();

        $tanques_tipos = TanquesTipos::orderBy('nome')->get();

        return view('admin.analises_bacteriologicas.list')
        ->with('formData',                     $formData)
        ->with('analises_laboratoriais',       $analises_laboratoriais)
        ->with('analises_laboratoriais_tipos', $analises_laboratoriais_tipos)
        ->with('tanques_tipos',                $tanques_tipos);
    }

    public function createAnalisesBacteriologicas()
    {
        $analises_laboratoriais_tipos = AnalisesLaboratoriaisTipos::whereIn('id', $this->analisesLaboratoriaisTipos)
        ->orderBy('nome', 'desc')
        ->get();

        $tanques_tipos = TanquesTipos::orderBy('nome')->get();

        return view('admin.analises_bacteriologicas.create')
        ->with('analises_laboratoriais_tipos', $analises_laboratoriais_tipos)
        ->with('tanques_tipos',                $tanques_tipos);
    }

    public function storeAnalisesBacteriologicas(AnalisesLaboratoriaisCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $data['filial_id'] = session('_filial')->id;

        $analise = AnalisesLaboratoriaisNew::create($data);

        if ($analise instanceof AnalisesLaboratoriaisNew) {
            return redirect()->route('admin.analises_bacteriologicas.amostras.to_create', ['analise_laboratorial_id' => $analise['id']])
            ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->back()
        ->with('data_analise',                 $data['data_analise'])
        ->with('tanque_tipo_id',               $data['tanque_tipo_id'])
        ->with('analise_laboratorial_tipo_id', $data['analise_laboratorial_tipo_id'])
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function editAnalisesBacteriologicas(int $id)
    {
        $data = AnalisesLaboratoriaisNew::find($id);

        $analises_laboratoriais_tipos = AnalisesLaboratoriaisTipos::whereIn('id', $this->analisesLaboratoriaisTipos)
        ->orderBy('nome', 'desc')
        ->get();

        $tanques_tipos = TanquesTipos::orderBy('nome')->get();

        return view('admin.analises_bacteriologicas.edit')
        ->with('id',                           $id)
        ->with('data_analise',                 $data->data_analise())
        ->with('tanque_tipo_id',               $data['tanque_tipo_id'])
        ->with('analise_laboratorial_tipo_id', $data['analise_laboratorial_tipo_id'])
        ->with('analises_laboratoriais_tipos', $analises_laboratoriais_tipos)
        ->with('tanques_tipos',                $tanques_tipos);
    }

    public function updateAnalisesBacteriologicas(AnalisesLaboratoriaisCreateFormRequest $request, int $id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $analise = AnalisesLaboratoriaisNew::find($id);

        if ($analise->update($data)) {
            return redirect()->route('admin.analises_bacteriologicas')
            ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->back()
        ->with('id',    $id)
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function removeAnalisesBacteriologicas(int $id)
    {
        $analise = AnalisesLaboratoriaisNew::find($id);

        $amostras = AnalisesBacteriologicasNew::where('analise_laboratorial_id', $analise->id)
        ->get();

        foreach($amostras as $amostra) {
            $amostra->delete();
        }

        if ($analise->delete()) {
            return redirect()->back()
            ->with('success', 'Registro excluido com sucesso!');
        }

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }
}
