<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ColetasParametrosTiposCreateFormRequest;
use App\Http\Controllers\Util\DataPolisher;
use App\Models\ColetasParametrosTipos;
use App\Models\UnidadesMedidas;

class ColetasParametrosTiposController extends Controller
{
    private $rowsPerPage = 10;

    public function listingColetasParametrosTipos()
    {
        $ColetasParametrosTipos = ColetasParametrosTipos::listing($this->rowsPerPage);

        return view('admin.coletas_parametros_tipos.list')
        ->with('ColetasParametrosTipos', $ColetasParametrosTipos);
    }

    public function searchColetasParametrosTipos(Request $request)
    {
        $formData = $request->except(['_token']);

        $ColetasParametrosTipos = ColetasParametrosTipos::search($formData, $this->rowsPerPage);

        return view('admin.coletas_parametros_tipos.list')
        ->with('formData',   $formData)
        ->with('ColetasParametrosTipos', $ColetasParametrosTipos);
    }

    public function createColetasParametrosTipos()
    {
        $unidades_medidas = UnidadesMedidas::all();

        return view('admin.coletas_parametros_tipos.create')
        ->with('unidades_medidas', $unidades_medidas);
    }

    public function storeColetasParametrosTipos(ColetasParametrosTiposCreateFormRequest $request)
    {
        $data = $request->except(['_token']);
        
        $data['ativo'] = 1;

        if(!($data['alerta'])){
            $data['alerta'] = 0;
        }

        $insert = ColetasParametrosTipos::create($data);

        if ($insert) {
            return redirect()->route('admin.coletas_parametros_tipos')
            ->with('success', 'Parâmetro cadastrado com sucesso!');
        }

        return redirect()->back()->withInput()
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o Parâmetro. Tente novamente!');
    }

    public function editColetasParametrosTipos(int $id)
    {
        $data = ColetasParametrosTipos::find($id);

        $unidades_medidas = UnidadesMedidas::all();

        return view('admin.coletas_parametros_tipos.edit')
        ->with('id',                $id)
        ->with('sigla',             $data['sigla'])
        ->with('descricao',         $data['descricao'])
        ->with('minimo',            $data['minimo'])
        ->with('maximo',            $data['maximo'])
        ->with('minimov',           $data['minimov'])
        ->with('maximov',           $data['maximov'])
        ->with('minimoy',           $data['minimoy'])
        ->with('maximoy',           $data['maximoy'])
        ->with('intervalo',         $data['intervalo'])
        ->with('cor',               $data['cor'])
        ->with('alerta',            $data['alerta'])
        ->with('unidade_medida_id', $data['unidade_medida_id'])
        ->with('unidades_medidas',  $unidades_medidas);
    }

    public function updateColetasParametrosTipos(ColetasParametrosTiposCreateFormRequest $request, int $id)
    {
        $data = $request->except(['_token']);
        
        $data = DataPolisher::toPolish($data);

        if (! isset($data['alerta'])) {
            $data['alerta'] = false;
        }

        $update = ColetasParametrosTipos::where('id', $id)->update($data);

        if ($update) {
            return redirect()->route('admin.coletas_parametros_tipos')
            ->with('success', 'Parametro atualizado com sucesso!');
        }

        return redirect()->back()
        ->with('id', $id)
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o parâmetro. Tente novamente!');
    }

    public function removeColetasParametrosTipos(int $id)
    {
        $data = ColetasParametrosTipos::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->back()
            ->with('success', 'Parâmetro excluído com sucesso!');
        } 
        
        return redirect()->back()
        ->with('error', 'Ocorreu um erro ao excluir o parâmetro. Tente novamente!');
    }

    public function turnColetasParametrosTipos(int $id)
    {
        $data = ColetasParametrosTipos::find($id);

        if ($data->ativo == 'ON') {

            $data->ativo = 'OFF';
            $data->save();

            return redirect()->back()
            ->with('success', 'Parametro desabilitada com sucesso!');

        }

        $data->ativo = 'ON';
        $data->save();

        return redirect()->back()
        ->with('success', 'Parametro habilitado com sucesso!');
    }

    public function turnColetasParametrosTiposAlertas(int $id)
    {
        $data = ColetasParametrosTipos::find($id);

        if ($data->alerta == 'ON') {

            $data->alerta = 'OFF';
            $data->save();

            return redirect()->back()
            ->with('success', 'Alerta de Parâmetro desabilitada com sucesso!');

        }

        $data->alerta = 'ON';
        $data->save();

        return redirect()->back()
        ->with('success', 'Alerta de Parâmetro habilitado com sucesso!');
    }
}
