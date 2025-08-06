<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ColetasParametrosCreateFormRequest;
use App\Http\Controllers\Controller;
use App\Models\Setores;
use App\Models\ColetasParametrosNew;
use App\Models\ColetasParametrosAmostras;
use App\Models\ColetasParametrosTipos;
use App\Http\Controllers\Util\DataPolisher;

class ColetasParametrosController extends Controller
{
    private $rowsPerPage = 10;

    public function listingColetaParametros()
    {
        $coletas_parametros_tipos = ColetasParametrosTipos::listing();

        $coletas_parametros = ColetasParametrosNew::listing($this->rowsPerPage);

        $setores = Setores::where('filial_id', session('_filial')->id)->get();

        return view('admin.coletas_parametros.list')
        ->with('coletas_parametros',           $coletas_parametros)
        ->with('coletas_parametros_tipos',     $coletas_parametros_tipos)
        ->with('setores',                      $setores);
    }
    
    public function searchColetaParametros(Request $request)
    {
              
        $formData = $request->except(['_token']);
        $formData['filial_id'] = session('_filial')->id;

        $coletas_parametros = ColetasParametrosNew::search($formData, $this->rowsPerPage);

        $coletas_parametros_tipos = ColetasParametrosTipos::listing($this->rowsPerPage);

        $setores = Setores::where('filial_id', session('_filial')->id)->get();

        return view('admin.coletas_parametros.list')
        ->with('formData',                     $formData)
        ->with('coletas_parametros',           $coletas_parametros)
        ->with('coletas_parametros_tipos',     $coletas_parametros_tipos)
        ->with('setores',                      $setores);
    }

    public function createColetaParametros()
    {
        $parametros = ColetasParametrosTipos::where('ativo', 't')->get();
        $setores = Setores::where('filial_id', session('_filial')->id)->get();

        return view('admin.coletas_parametros.create')
        ->with('parametros', $parametros)
        ->with('setores',       $setores);
    }

    public function storeColetaParametros(ColetasParametrosCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);
        $data['usuario_id'] = auth()->user()->id;
        
        $coletas_parametros = ColetasParametrosNew::where('setor_id', $data['setor_id'])
        ->where('coletas_parametros_tipos_id', $data['coletas_parametros_tipos_id'])
        ->orderBy('id', 'desc');


        $todas_coletas = $coletas_parametros
        ->get();

        $ultima_coleta = $coletas_parametros
        ->where('data_coleta', $data['data_coleta'])
        ->first();

        if(!is_null($ultima_coleta)){
            if($ultima_coleta->verifica_amostras->isEmpty()){
                return redirect()->back()
                ->with('data_coleta',                  $data['data_coleta'])
                ->with('data_coletaA',                 $ultima_coleta->data_coleta)
                ->with('medicao',                      $ultima_coleta->medicao)
                ->with('setor_id',                     $data['setor_id'])
                ->with('coletas_parametros_tipos_id',  $data['coletas_parametros_tipos_id'])
                ->with('error', 'A coleta '.$ultima_coleta->medicao.' do dia '.$ultima_coleta->data_coleta().' ainda não possui amostras, não é possivel cadastrar uma nova coleta com coletas pendentes no cadastro de amostras!');
            }
        }

        $data['medicao'] = 1;
        if(isset($ultima_coleta)){
            $data['medicao'] = $ultima_coleta->medicao + 1;
        }

        $insert = ColetasParametrosNew::create($data); 

        if ($insert) {
            return redirect()->route('admin.coletas_parametros.amostras.to_create', ['coletas_parametros_id' => $insert->id])
            ->with('success', 'Coleta cadastrada com sucesso!');
        }

        return redirect()->back()
        ->with('data_coleta',                  $data['data_coleta'])
        ->with('setor_id',                     $data['setor_id'])
        ->with('coletas_parametros_tipos_id',  $data['coletas_parametros_tipos_id'])
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar a Coleta. Tente novamente!');
    }

    public function editColetasParametros(int $id)
    {
        $data =ColetasParametrosNew::find($id);

        $coletas_parametros_tipos = ColetasParametrosTipos::whereIn('id', $this->coletas_parametros)
        ->orderBy('nome', 'desc')
        ->get();

        $setores = Setores::where('filial_id', session('_filial')->id)->get();

        return view('admin.analises_bacteriologicas.edit')
        ->with('id',                           $id)
        ->with('data_analise',                 $data->data_analise())
        ->with('setor_id',                     $data['setor_id'])
        ->with('coletas_parametros_tipos_id',  $data['coletas_parametros_tipos_id'])
        ->with('coletas_parametros_tipos',     $coletas_parametros_tipos)
        ->with('setores',                      $setores);
    }

    public function updateColetaParametros(ColetasParametrosCreateFormRequest $request, int $id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $update = ColetasParametrosNew::where('id', $id)->update($data);

        if ($update) {
            return redirect()->route('admin.coletas_parametros')
            ->with('success', 'Coleta atualizada com sucesso!');
        }

        return redirect()->back()
        ->with('id', $id)
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar a Coleta. Tente novamente!');
    }

    public function removeColetaParametros(int $id)
    {
                
        $coleta = ColetasParametrosNew::find($id);
        $amostras = $coleta->coletas_parametros_amostras();

        if ($amostras->count() == 0) {

            if ($coleta->delete()) {
                return redirect()->back()
                ->with('success', 'Coleta de Parâmetros excluida com sucesso!');
            }

        } else {

            if ($amostras->delete()) {
                if ($coleta->delete()) {
                    return redirect()->back()
                    ->with('success', 'Coleta de Parâmetros excluida com sucesso!');
                }
            }

        }
        return redirect()->back()
        ->with('error', 'Ocorreu um erro ao excluir a Coleta de Parâmetros. Tente novamente!');
    }

    public function createColetaParametrosAmostras(int $coleta_parametro_id)
    {
        $coleta = ColetasParametrosNew::find($coleta_parametro_id);

        $tanques = $coleta->setor->tanques
        ->where('situacao','ON')
        ->sortBy('sigla');
        
        if ($coleta->coletas_por_tanque->count() == 0) {
            $alt = 0; 
            foreach ($tanques as $tanque) {

                $amostras[$tanque->id] = $tanque->amostras->where('coletas_parametros_id' ,'<', $coleta->id)
                ->where('coletas_parametros_tipos_id',$coleta->coletas_parametros_tipos_id)
                ->sortByDesc('coletas_parametros_amostras_id')
                ->take(2);
                
            }

        }else{
            $alt = 1; 
            foreach ($tanques as $tanque) {

                $amostras[$tanque->id] = $tanque->amostras->where('coletas_parametros_id' , $coleta->id)
                ->where('coletas_parametros_tipos_id',$coleta->coletas_parametros_tipos_id)
                ->sortByDesc('coletas_parametros_amostras_id');
                
            }

        }
        return view('admin.coletas_parametros.amostras.create')
        ->with('coleta',                $coleta)
        ->with('amostras',              $amostras)
        ->with('alt',                   $alt)
        ->with('tanques',               $tanques);
    }

    public function storeColetaParametrosAmostras(Request $request, int $coleta_parametro_id)
    {
        $data = $request->except(['_token']);
        
        $data = DataPolisher::toPolish($data, ['EMPTY_TO_NULL']);

        //dd($data);

        $coleta_parametro = ColetasParametrosNew::find($coleta_parametro_id);

        $parametro = ColetasParametrosTipos::find($coleta_parametro->coletas_parametros_tipos_id);

        $tanques = $coleta_parametro->setor->tanques->where('situacao','ON');
        
        foreach ($tanques as $tanque) {
            
            if (! is_null($data["valor_{$tanque->id}"])) {

                if (! is_null($parametro->minimo)) {
                    if ($data["valor_{$tanque->id}"] < $parametro->minimo) {
                        return redirect()->back()->withInput()
                        ->with('error', 'O Valor inserido para o tanque '.$tanque->sigla.' é menor que o limite mínimo! Por favor, corrija o valor e tente novamente.');
                    }
                }

                if (! is_null($parametro->maximo)) {
                    if ($data["valor_{$tanque->id}"] > $parametro->maximo) {
                        
                        return redirect()->back()->withInput()
                        ->with('error', 'O Valor inserido para o tanque '.$tanque->sigla.' é maior que o limite máximo! Por favor, corrija o valor e tente novamente.');
                    }
                }

            }

        }

        foreach ($tanques as $tanque) {
            
            if ($data['alt'] == 0) {
                $data['valor'] = $data["valor_{$tanque->id}"];
            } else {
                if(isset($data["zera_valor{$tanque->id}"])){
                    $data['valor'] = null;  
                }else{
                    $data['valor'] = $data["valor_{$tanque->id}"] != 0 ? $data["valor_{$tanque->id}"] : $data["valor_anterior_{$tanque->id}"];
                }
            }

            $coletas_parametros_amostras = ColetasParametrosAmostras::where('coletas_parametros_id', $coleta_parametro->id)
            ->where('tanque_id', $tanque->id);

            if ($coletas_parametros_amostras->count() == 0) {

                $data['coletas_parametros_id']       = $coleta_parametro->id;
                $data['coletas_parametros_tipos_id'] = $coleta_parametro->coletas_parametros_tipos_id;
                $data['tanque_id']                   = $tanque->id;
                $data['usuario_id'] = auth()->user()->id;

                $insert = ColetasParametrosAmostras::create($data);

                if (! $insert) {
                    return redirect()->back()
                    ->with('error', 'Não foi possível salvar algumas das amostras! Por favor, tente novamente.');
                }

            } elseif ($coletas_parametros_amostras->count() == 1) {

                $amostra = $coletas_parametros_amostras->first();

                $amostra->valor   = $data['valor'];

                $update = $amostra->save();

                if (! $update) {
                    return redirect()->back()
                    ->with('error', 'Não foi possível alterar algumas das amostras! Por favor, tente novamente.');
                }

            } else {

                return redirect()->back()
                ->with('error', 'Provavelmente existem registro de amostras duplicados! Por favor, entre em contato com o suporte.');

            }

        }

        return redirect()->back()
        ->with('success', 'Amostras registradas com sucesso!');
    }
}
