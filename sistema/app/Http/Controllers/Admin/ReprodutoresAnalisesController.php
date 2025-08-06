<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReprodutoresAnalisesCreateFormRequest;
use App\Http\Controllers\Util\DataPolisher;
use App\Models\ReprodutoresAnalises;
use PhpParser\Node\Expr\Cast\Unset_;

class ReprodutoresAnalisesController extends Controller
{
    private $rowsPerPage = 10;

    public function listingReprodutoresAnalises()
    {
        $reprodutores_analises = ReprodutoresAnalises::listing($this->rowsPerPage);

        return view('admin.reprodutores_analises.list')
        ->with('reprodutores_analises', $reprodutores_analises);
    }

    public function searchReprodutoresAnalises(Request $request)
    {
        $formData = $request->except(['_token']);

        $reprodutores_analises = ReprodutoresAnalises::search($formData, $this->rowsPerPage);

        return view('admin.reprodutores_analises.list')
        ->with('formData',   $formData)
        ->with('reprodutores_analises', $reprodutores_analises);
    }

    public function createReprodutoresAnalises()
    {
        return view('admin.reprodutores_analises.create');
    }

    public function storeReprodutoresAnalises(ReprodutoresAnalisesCreateFormRequest $request)
    {
        $data = $request->except(['_token']);
        
        $data['ativo'] = 1;

        if(isset($data['alerta'])){
            $data['alerta'] = 0;
        }

        $insert = ReprodutoresAnalises::create($data);

        if ($insert) {
            return redirect()->route('admin.reprodutores_analises')
            ->with('success', 'Análise cadastrada com sucesso!');
        }

        return redirect()->back()->withInput()
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar a análise. Tente novamente!');
    }

    public function editReprodutoresAnalises(int $id)
    {
        $data = ReprodutoresAnalises::find($id);

        return view('admin.reprodutores_analises.edit')
        ->with('id',                $id)
        ->with('sigla',             $data['sigla'])
        ->with('descricao',         $data['descricao'])
        ->with('alerta',            $data['alerta']);
    }

    public function updateReprodutoresAnalises(ReprodutoresAnalisesCreateFormRequest $request, int $id)
    {
        $data = $request->except(['_token']);
        
        $data = DataPolisher::toPolish($data);

        if (! isset($data['alerta'])) {
            $data['alerta'] = false;
        }

        $update = ReprodutoresAnalises::where('id', $id)->update($data);

        if ($update) {
            return redirect()->route('admin.reprodutores_analises')
            ->with('success', 'Parametro atualizado com sucesso!');
        }

        return redirect()->back()
        ->with('id', $id)
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o parâmetro. Tente novamente!');
    }

    public function removeReprodutoresAnalises(int $id)
    {
        $data = ReprodutoresAnalises::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->back()
            ->with('success', 'Análise excluído com sucesso!');
        } 
        
        return redirect()->back()
        ->with('error', 'Ocorreu um erro ao excluir a Análise. Tente novamente!');
    }

    public function turnReprodutoresAnalises(int $id)
    {
        $data = ReprodutoresAnalises::find($id);

        if ($data->ativo == 'ON') {

            $data->ativo = 'OFF';
            $data->save();

            return redirect()->back()
            ->with('success', 'Análise desabilitada com sucesso!');

        }

        $data->ativo = 'ON';
        $data->save();

        return redirect()->back()
        ->with('success', 'Análise habilitada com sucesso!');
    }

    public function turnColetasParametrosTiposAlertas(int $id)
    {
        $data = ReprodutoresAnalises::find($id);

        if ($data->alerta == 'ON') {

            $data->alerta = 'OFF';
            $data->save();

            return redirect()->back()
            ->with('success', 'Alerta de Análise desabilitada com sucesso!');

        }

        $data->alerta = 'ON';
        $data->save();

        return redirect()->back()
        ->with('success', 'Alerta de Análise habilitada com sucesso!');
    }
}
