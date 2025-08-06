<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SondasFatoresCreateFormRequest;
use App\Models\ColetasParametrosTipos;
use App\Models\SondasLaboratoriais;
use App\Models\SondasFatores;
use App\Models\UnidadesMedidas;
use App\Http\Controllers\Util\DataPolisher;

class SondasFatoresController extends Controller
{
    private $rowsPerPage = 10;

    public function listingSondasFatores(int $sonda_laboratorial_id = 0)
    {
        $sondas_fatores = SondasFatores::listing($this->rowsPerPage);
        
        if($sonda_laboratorial_id){
            
            $sondas_fatores = SondasFatores::where('sonda_laboratorial_id', $sonda_laboratorial_id)
            ->orderBy('sonda_laboratorial_id')
            ->paginate($this->rowsPerPage);

        }

        $coletas_parametros_tipos = ColetasParametrosTipos::orderBy('sigla', 'asc')->get();

        $sondas_laboratoriais = SondasLaboratoriais::orderBy('nome', 'asc')->get();

        return view('admin.sondas_fatores.list')
        ->with('sonda_laboratorial_id',    $sonda_laboratorial_id)
        ->with('sondas_fatores',           $sondas_fatores)
        ->with('coletas_parametros_tipos', $coletas_parametros_tipos)
        ->with('sondas_laboratoriais',     $sondas_laboratoriais);
    }

    public function searchSondasFatores(Request $request)
    {
        $formData = $request->except(['_token']);

        $sondas_fatores = SondasFatores::search($formData, $this->rowsPerPage);
        $coletas_parametros_tipos = ColetasParametrosTipos::orderBy('sigla', 'asc')->get();
        $sondas_laboratoriais = SondasLaboratoriais::orderBy('nome', 'asc')->get();
       
        return view('admin.sondas_fatores.list')
        ->with('formData', $formData)
        ->with('sondas_fatores',           $sondas_fatores)
        ->with('coletas_parametros_tipos', $coletas_parametros_tipos)
        ->with('sondas_laboratoriais',     $sondas_laboratoriais);
    }

    public function createSondasFatores(int $sonda_laboratorial_id = 0)
    {
        
        $coletas_parametros_tipos = ColetasParametrosTipos::orderBy('sigla', 'asc')->get();
        $sondas_laboratoriais = SondasLaboratoriais::orderBy('nome', 'asc')->get();

        if($sonda_laboratorial_id){
            $sondas_laboratoriais = SondasLaboratoriais::where('id', $sonda_laboratorial_id)->get();
        }

        return view('admin.sondas_fatores.create')
        ->with('sondas_laboratoriais',     $sondas_laboratoriais)
        ->with('coletas_parametros_tipos', $coletas_parametros_tipos);
    }

    public function storeSondasFatores(SondasFatoresCreateFormRequest $request)
    {
        $data = $request->except(['_token']);
        
        $data['usuario_id'] = auth()->user()->id;

        $data = DataPolisher::toPolish($data);

        $insert = SondasFatores::create($data);

        if ($insert) {
            return redirect()->route('admin.sondas_fatores')
            ->with('success', 'Fator de Conversão cadastrado com sucesso!');
        }

        return redirect()->back()
        ->with('nome',                         $data['fator'])
        ->with('descricao',                    $data['situacao'])
        ->with('unidade_medida_id',            $data['sonda_laboratorial_id'])
        ->with('coletas_parametros_tipos_id',  $data['coletas_parametros_tipos_id'])
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o Fator de Conversão. Tente novamente!');
    }

    public function editSondasFatores(int $id)
    {
        $data = SondasFatores::find($id);

        $coletas_parametros_tipos = ColetasParametrosTipos::orderBy('sigla', 'asc')->get();

        $sondas_laboratoriais = SondasLaboratoriais::orderBy('nome', 'asc')->get();

        return view('admin.sondas_fatores.edit')
        ->with('id',                           $id)
        ->with('fator',                        round($data['fator'], 2))
        ->with('situacao',                     $data['situacao'])
        ->with('sonda_laboratorial_id',        $data['sonda_laboratorial_id'])
        ->with('coleta_parametro_tipo_id',     $data['coleta_parametro_tipo_id'])
        ->with('sondas_laboratoriais',         $sondas_laboratoriais)
        ->with('coletas_parametros_tipos',     $coletas_parametros_tipos);
    }

    public function updateSondasFatores(SondasFatoresCreateFormRequest $request, int $id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $update = SondasFatores::where('id', $id)->update($data);

        if ($update) {
            return redirect()->route('admin.sondas_fatores')
            ->with('success', 'Fator de Conversão atualizado com sucesso!');
        }

        return redirect()->back()
        ->with('id', $id)
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar o Fator de Conversão. Tente novamente!');
    }

    public function removeSondasFatores(int $id)
    {
        $data = SondasFatores::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->route('admin.sondas_fatores')
            ->with('success', 'Fator de Conversão excluido com sucesso!');
        } 

        return redirect()->route('admin.sondas_fatores')
        ->with('error', 'Ocorreu um erro ao excluir o Fator de conversão. Tente novamente!');
    }

    public function turnSondasFatores(int $id)
    {
        $data = SondasFatores::find($id);

        if ($data->situacao == 'ON') {

            $data->situacao = 'OFF';
            $data->save();

            return redirect()->route('admin.sondas_fatores')
            ->with('success', 'Fator de Conversão desabilitado com sucesso!');

        }

        $data->situacao = 'ON';
        $data->save();

        return redirect()->route('admin.sondas_fatores')
        ->with('success', 'Fator de Conversão habilitado com sucesso!');
    }
}
