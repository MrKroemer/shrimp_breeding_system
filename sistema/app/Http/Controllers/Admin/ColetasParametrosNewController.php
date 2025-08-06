<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\ColetasParametrosNewCreateFormRequest;
use App\Models\TanquesTipos;
use App\Models\SondasLaboratoriais;
use App\Models\ColetasParametrosNew;
use App\Models\ColetasParametrosAmostrasNew;
use App\Models\ColetasParametrosTipos;
use App\Http\Controllers\Util\DataPolisher;

class ColetasParametrosNewController extends Controller
{
    private $rowsPerPage = 10;

    public function listing()
    {
        $coletas_parametros = ColetasParametrosNew::listing($this->rowsPerPage);
        
        $coletas_parametros_tipos = ColetasParametrosTipos::where('ativo', true)
        ->orderBy('descricao')
        ->get();
        
        $tanques_tipos = TanquesTipos::orderBy('nome')
        ->get();

        return view('admin.coletas_parametros_new.list')
        ->with('coletas_parametros',       $coletas_parametros)
        ->with('coletas_parametros_tipos', $coletas_parametros_tipos)
        ->with('tanques_tipos',            $tanques_tipos);
    }

    public function search(Request $request)
    {
        $formData = $request->except(['_token']);

        $coletas_parametros = ColetasParametrosNew::search($formData, $this->rowsPerPage);

        $coletas_parametros_tipos = ColetasParametrosTipos::where('ativo', true)
        ->orderBy('descricao')
        ->get();
        
        $tanques_tipos = TanquesTipos::orderBy('nome')
        ->get();

        return view('admin.coletas_parametros_new.list')
        ->with('formData',                 $formData)
        ->with('coletas_parametros',       $coletas_parametros)
        ->with('coletas_parametros_tipos', $coletas_parametros_tipos)
        ->with('tanques_tipos',            $tanques_tipos);
    }

    public function create()
    {
        $coletas_parametros_tipos = ColetasParametrosTipos::where('ativo', true)
        ->orderBy('descricao')
        ->get();
        
        $tanques_tipos = TanquesTipos::orderBy('nome')
        ->get();

        $sondas_laboratorias = SondasLaboratoriais::orderBy('nome')
        ->get();

        return view('admin.coletas_parametros_new.create')
        ->with('coletas_parametros_tipos', $coletas_parametros_tipos)
        ->with('sondas_laboratorias',      $sondas_laboratorias)
        ->with('tanques_tipos',            $tanques_tipos);
    }

    public function store(ColetasParametrosNewCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);
        
        $data['filial_id']  = session('_filial')->id;
        $data['usuario_id'] = auth()->user()->id;
        
        $coletas_parametros = ColetasParametrosNew::where('data_coleta', $data['data_coleta'])
        ->where('coleta_parametro_tipo_id', $data['coleta_parametro_tipo_id'])
        ->where('tanque_tipo_id', $data['tanque_tipo_id'])
        ->where('filial_id', $data['filial_id'])
        ->orderBy('id', 'desc')
        ->get();

        

        if ($coletas_parametros->isEmpty()) {
            $coleta_parametro = ColetasParametrosNew::create($data);
        } else {
            $coleta_parametro = $coletas_parametros->first();
        }

        if ($coleta_parametro instanceof ColetasParametrosNew) {
            return redirect()->route('admin.coletas_parametros_new.amostras.to_create', [
                'coleta_parametro_id' => $coleta_parametro->id
            ])
            ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->back()
        ->with('data_coleta',              $data['data_coleta'])
        ->with('tanque_tipo_id',           $data['tanque_tipo_id'])
        ->with('coleta_parametro_tipo_id', $data['coleta_parametro_tipo_id'])
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function remove(int $id)
    {
        $coleta_parametro = ColetasParametrosNew::find($id);

        $amostras = $coleta_parametro->amostras();

        if ($amostras->count() > 0) {
            $amostras->delete();
        }

        if ($coleta_parametro->delete()) {
            return redirect()->back()
            ->with('success', 'Registro excluído com sucesso!');
        }

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }
}
