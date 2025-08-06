<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReceitasLaboratoriaisCreateFormRequest;
use App\Models\ReceitasLaboratoriais;
use App\Models\ReceitasLaboratoriaisTipos;
use App\Models\UnidadesMedidas;
use App\Http\Controllers\Util\DataPolisher;

class ReceitasLaboratoriaisController extends Controller
{
    private $rowsPerPage = 10;

    public function listingReceitasLaboratoriais()
    {
        $receitas_laboratoriais = ReceitasLaboratoriais::listing($this->rowsPerPage, [
            'filial_id' => session('_filial')->id
        ]);

        return view('admin.receitas_laboratoriais.list')
        ->with('receitas_laboratoriais', $receitas_laboratoriais);
    }

    public function searchReceitasLaboratoriais(Request $request)
    {
        $formData = $request->except(['_token']);

        $receitas_laboratoriais = ReceitasLaboratoriais::search($formData, $this->rowsPerPage);

        return view('admin.receitas_laboratoriais.list')
        ->with('formData', $formData)
        ->with('receitas_laboratoriais', $receitas_laboratoriais);
    }

    public function createReceitasLaboratoriais()
    {
        $unidades_medidas             = UnidadesMedidas::orderBy('nome', 'asc')->get();
        $receitas_laboratoriais_tipos = ReceitasLaboratoriaisTipos::orderBy('nome', 'asc')->get();

        return view('admin.receitas_laboratoriais.create')
        ->with('unidades_medidas',             $unidades_medidas)
        ->with('receitas_laboratoriais_tipos', $receitas_laboratoriais_tipos);
    }

    public function storeReceitasLaboratoriais(ReceitasLaboratoriaisCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $data['nome'] = mb_strtoupper($data['nome']);

        $data['filial_id'] = session('_filial')->id;

        $insert = ReceitasLaboratoriais::create($data);

        if ($insert) {
            return redirect()->route('admin.receitas_laboratoriais')
            ->with('success', 'Receita laboratorial cadastrada com sucesso!');
        }

        return redirect()->back()
        ->with('nome',                         $data['nome'])
        ->with('descricao',                    $data['descricao'])
        ->with('unidade_medida_id',            $data['unidade_medida_id'])
        ->with('receita_laboratorial_tipo_id', $data['receita_laboratorial_tipo_id'])
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar a receita laboratorial. Tente novamente!');
    }

    public function editReceitasLaboratoriais(int $id)
    {
        $data = ReceitasLaboratoriais::find($id);

        $unidades_medidas             = UnidadesMedidas::orderBy('nome', 'asc')->get();
        $receitas_laboratoriais_tipos = ReceitasLaboratoriaisTipos::orderBy('nome', 'asc')->get();

        return view('admin.receitas_laboratoriais.edit')
        ->with('id',                           $id)
        ->with('nome',                         $data['nome'])
        ->with('descricao',                    $data['descricao'])
        ->with('unidade_medida_id',            $data['unidade_medida_id'])
        ->with('receita_laboratorial_tipo_id', $data['receita_laboratorial_tipo_id'])
        ->with('unidades_medidas',             $unidades_medidas)
        ->with('receitas_laboratoriais_tipos', $receitas_laboratoriais_tipos);
    }

    public function updateReceitasLaboratoriais(ReceitasLaboratoriaisCreateFormRequest $request, int $id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $data['nome'] = mb_strtoupper($data['nome']);

        $update = ReceitasLaboratoriais::where('id', $id)->update($data);

        if ($update) {
            return redirect()->route('admin.receitas_laboratoriais')
            ->with('success', 'Receita laboratorial atualizada com sucesso!');
        }

        return redirect()->back()
        ->with('id', $id)
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar a receita laboratorial. Tente novamente!');
    }

    public function removeReceitasLaboratoriais(int $id)
    {
        $data = ReceitasLaboratoriais::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->route('admin.receitas_laboratoriais')
            ->with('success', 'Receita laboratorial excluido com sucesso!');
        } 

        return redirect()->route('admin.receitas_laboratoriais')
        ->with('error', 'Ocorreu um erro ao excluir a receita laboratorial. Tente novamente!');
    }

    public function turnReceitasLaboratoriais(int $id)
    {
        $data = ReceitasLaboratoriais::find($id);

        if ($data->situacao == 'ON') {

            $data->situacao = 'OFF';
            $data->save();

            return redirect()->route('admin.receitas_laboratoriais')
            ->with('success', 'Receita laboratorial desabilitada com sucesso!');

        }

        $data->situacao = 'ON';
        $data->save();

        return redirect()->route('admin.receitas_laboratoriais')
        ->with('success', 'Receita laboratorial habilitada com sucesso!');
    }
}
