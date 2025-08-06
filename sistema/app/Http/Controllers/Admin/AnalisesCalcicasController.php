<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TanquesTipos;
use App\Models\AnalisesLaboratoriaisNew;
use App\Models\AnalisesLaboratoriaisTipos;
use App\Models\AnalisesCalcicasNew;
use App\Http\Requests\AnalisesLaboratoriaisCreateFormRequest;
use App\Http\Controllers\Util\DataPolisher;

class AnalisesCalcicasController extends Controller
{
    private $rowsPerPage = 10;
    private $analisesLaboratoriaisTipos = [4];

    public function listingAnalisesCalcicas()
    {
        $analises_laboratoriais = AnalisesLaboratoriaisNew::listing($this->rowsPerPage, [
            'filia_id' => session('_filial')->id,
            'analises_laboratoriais_tipos' => $this->analisesLaboratoriaisTipos,
        ]);

        $analises_laboratoriais_tipos = AnalisesLaboratoriaisTipos::whereIn('id', $this->analisesLaboratoriaisTipos)
        ->orderBy('nome', 'desc')
        ->get();

        $tanques_tipos = TanquesTipos::orderBy('nome')->get();

        return view('admin.analises_calcicas.list')
        ->with('analises_laboratoriais',       $analises_laboratoriais)
        ->with('analises_laboratoriais_tipos', $analises_laboratoriais_tipos)
        ->with('tanques_tipos',                $tanques_tipos);
    }
    
    public function searchAnalisesCalcicas(Request $request)
    {
        $formData = $request->except(['_token']);
        $formData['filial_id'] = session('_filial')->id;
        $formData['analises_laboratoriais_tipos'] = $this->analisesLaboratoriaisTipos;

        $analises_laboratoriais = AnalisesLaboratoriaisNew::search($formData, $this->rowsPerPage);

        $analises_laboratoriais_tipos = AnalisesLaboratoriaisTipos::whereIn('id', $this->analisesLaboratoriaisTipos)
        ->orderBy('nome', 'desc')
        ->get();

        $tanques_tipos = TanquesTipos::orderBy('nome')->get();

        return view('admin.analises_calcicas.list')
        ->with('formData',                     $formData)
        ->with('analises_laboratoriais',       $analises_laboratoriais)
        ->with('analises_laboratoriais_tipos', $analises_laboratoriais_tipos)
        ->with('tanques_tipos',                $tanques_tipos);
    }

    public function createAnalisesCalcicas()
    {
        $analises_laboratoriais_tipos = AnalisesLaboratoriaisTipos::whereIn('id', $this->analisesLaboratoriaisTipos)
        ->orderBy('nome', 'desc')
        ->get();

        $tanques_tipos = TanquesTipos::orderBy('nome')->get();

        return view('admin.analises_calcicas.create')
        ->with('analises_laboratoriais_tipos', $analises_laboratoriais_tipos)
        ->with('tanques_tipos',                $tanques_tipos);
    }

    public function storeAnalisesCalcicas(AnalisesLaboratoriaisCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);
        
        $data['filial_id'] = session('_filial')->id;

        $insert = AnalisesLaboratoriaisNew::create($data);

        if ($insert) {
            return redirect()->route('admin.analises_calcicas.amostras.to_create', ['analise_laboratorial_id' => $insert->id])
            ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->back()
        ->with('data_analise',                 $data['data_analise'])
        ->with('tanque_tipo',                  $data['tanque_tipo'])
        ->with('analise_laboratorial_tipo_id', $data['analise_laboratorial_tipo_id'])
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function editAnalisesCalcicas(int $id)
    {
        $data = AnalisesLaboratoriaisNew::find($id);

        $analises_laboratoriais_tipos = AnalisesLaboratoriaisTipos::whereIn('id', $this->analisesLaboratoriaisTipos)
        ->orderBy('nome', 'desc')
        ->get();

        $tanques_tipos = TanquesTipos::orderBy('nome')->get();

        return view('admin.analises_calcicas.edit')
        ->with('id',                           $id)
        ->with('data_analise',                 $data->data_analise())
        ->with('tanque_tipo_id',               $data['tanque_tipo_id'])
        ->with('analise_laboratorial_tipo_id', $data['analise_laboratorial_tipo_id'])
        ->with('analises_laboratoriais_tipos', $analises_laboratoriais_tipos)
        ->with('tanques_tipos',                $tanques_tipos);
    }

    public function updateAnalisesCalcicas(AnalisesLaboratoriaisCreateFormRequest $request, int $id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $update = AnalisesLaboratoriaisNew::where('id', $id)->update($data);

        if ($update) {
            return redirect()->route('admin.analises_calcicas')
            ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->back()
        ->with('id', $id)
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function removeAnalisesCalcicas(int $id)
    {
        $data = AnalisesLaboratoriaisNew::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->back()
            ->with('success', 'Registro excluido com sucesso!');
        }

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }
}
