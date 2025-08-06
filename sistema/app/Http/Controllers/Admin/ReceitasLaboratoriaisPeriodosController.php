<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReceitasLaboratoriaisPeriodosCreateFormRequest;
use App\Models\ReceitasLaboratoriaisPeriodos;
use App\Models\ReceitasLaboratoriais;
use App\Http\Controllers\Util\DataPolisher;

class ReceitasLaboratoriaisPeriodosController extends Controller
{
    private $rowsPerPage = 10;

    private $periodos = [
        1 => 'ATÉ O',
        2 => 'ACIMA DO',
        3 => 'ABAIXO DO',
    ];

    public function listingReceitasLaboratoriaisPeriodos(int $receita_laboratorial_id)
    {
        $receitas_laboratoriais_periodos = ReceitasLaboratoriaisPeriodos::listing($this->rowsPerPage, [
            'receita_laboratorial_id' => $receita_laboratorial_id
        ]);

        $receita_laboratorial = ReceitasLaboratoriais::find($receita_laboratorial_id);
        
        return view('admin.receitas_laboratoriais_periodos.list')
        ->with('receita_laboratorial',            $receita_laboratorial)
        ->with('receitas_laboratoriais_periodos', $receitas_laboratoriais_periodos);
    }

    public function searchReceitasLaboratoriaisPeriodos(Request $request, int $receita_laboratorial_id)
    {
        $formData = $request->except(['_token']);
        $formData['receita_laboratorial_id'] = $receita_laboratorial_id;

        $receitas_laboratoriais_periodos = ReceitasLaboratoriaisPeriodos::search($formData, $this->rowsPerPage);

        $receita_laboratorial = ReceitasLaboratoriais::find($receita_laboratorial_id);

        return view('admin.receitas_laboratoriais_periodos.list')
        ->with('formData',                        $formData)
        ->with('receita_laboratorial',            $receita_laboratorial)
        ->with('receitas_laboratoriais_periodos', $receitas_laboratoriais_periodos);
    }

    public function createReceitasLaboratoriaisPeriodos(int $receita_laboratorial_id)
    {
        $receita_laboratorial = ReceitasLaboratoriais::find($receita_laboratorial_id);

        return view('admin.receitas_laboratoriais_periodos.create')
        ->with('periodos', $this->periodos)
        ->with('receita_laboratorial', $receita_laboratorial);
    }

    public function storeReceitasLaboratoriaisPeriodos(ReceitasLaboratoriaisPeriodosCreateFormRequest $request, int $receita_laboratorial_id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);
        
        $data['receita_laboratorial_id'] = $receita_laboratorial_id;

        $insert = ReceitasLaboratoriaisPeriodos::create($data);

        if ($insert) {
            return redirect()->route('admin.receitas_laboratoriais.receitas_laboratoriais_periodos', ['receita_laboratorial_id' => $receita_laboratorial_id])
            ->with('success', 'Período de utilização cadastrado com sucesso!');
        }

        return redirect()->back()
        ->with('periodo',    $data['periodo'])
        ->with('dia_base',   $data['dia_base'])
        ->with('quantidade', $data['quantidade'])
        ->with('receita_laboratorial_id', $receita_laboratorial_id)
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o período de utilização. Tente novamente!');
    }

    public function editReceitasLaboratoriaisPeriodos(int $receita_laboratorial_id, int $id)
    {
        $data = ReceitasLaboratoriaisPeriodos::find($id);

        $receita_laboratorial = ReceitasLaboratoriais::find($receita_laboratorial_id);

        return view('admin.receitas_laboratoriais_periodos.edit')
        ->with('id',         $id)
        ->with('periodo',    $data['periodo'])
        ->with('dia_base',   $data['dia_base'])
        ->with('quantidade', $data['quantidade'])
        ->with('periodos',   $this->periodos)
        ->with('receita_laboratorial', $receita_laboratorial);
    }

    public function updateReceitasLaboratoriaisPeriodos(ReceitasLaboratoriaisPeriodosCreateFormRequest $request, int $receita_laboratorial_id, int $id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $update = ReceitasLaboratoriaisPeriodos::where('id', $id)->update($data);

        if ($update) {
            return redirect()->route('admin.receitas_laboratoriais.receitas_laboratoriais_periodos', ['receita_laboratorial_id' => $receita_laboratorial_id])
            ->with('success', 'Período de utilização atualizado com sucesso!');
        }

        return redirect()->back()
        ->with('id', $id)
        ->with('receita_laboratorial_id', $receita_laboratorial_id)
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o período de utilização. Tente novamente!');
    }

    public function removeReceitasLaboratoriaisPeriodos(int $receita_laboratorial_id, int $id)
    {
        $data = ReceitasLaboratoriaisPeriodos::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->route('admin.receitas_laboratoriais.receitas_laboratoriais_periodos', ['receita_laboratorial_id' => $receita_laboratorial_id])
            ->with('success', 'Período de utilização excluido com sucesso!');
        } 

        return redirect()->route('admin.receitas_laboratoriais.receitas_laboratoriais_periodos', ['receita_laboratorial_id' => $receita_laboratorial_id])
        ->with('error', 'Ocorreu um erro ao excluir o período de utilização. Tente novamente!');
    }
}
