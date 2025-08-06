<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lotes;
use App\Models\LotesLarvicultura;
use App\Models\Especies;
use App\Models\VwPoslarvasEstocadas;
use App\Http\Controllers\Util\DataPolisher;
use App\Http\Controllers\Util\RouteManipulator;
use App\Http\Requests\LotesCreateFormRequest;
use App\Http\Requests\LotesLarviculturaCreateFormRequest;
use App\Http\Requests\LotesEditFormRequest;
use Carbon\Carbon;

class LotesController extends Controller
{
    private $rowsPerPage = 10;

    public function listingLotes()
    {
        $lotes = Lotes::listing($this->rowsPerPage, [
            'filial_id' => session('_filial')->id
        ]);

        return view('admin.lotes.list')
        ->with('lotes', $lotes);
    }
    
    public function searchLotes(Request $request)
    {
        $formData = $request->except(['_token']);
        $formData['filial_id'] = session('_filial')->id;

        $lotes = Lotes::search($formData, $this->rowsPerPage);

        return view('admin.lotes.list')
        ->with('formData', $formData)
        ->with('lotes',    $lotes);
    }

    public function createLotes(Request $request)
    {
        $data = $request->only([
            'redirectBack',
            'povoamento_id',
        ]);

        $redirectBack = 'no';
        $povoamento_id = 0;

        if (isset($data['redirectBack'])) {
           
            $redirectBack  = $data['redirectBack'];
            $povoamento_id = $data['povoamento_id'];
           
        }

        $especies = Especies::all();

        $poslarvas_estocadas = VwPoslarvasEstocadas::all();

        return view('admin.lotes.create')
        ->with('poslarvas_estocadas', $poslarvas_estocadas)
        ->with('especies',            $especies)
        ->with('redirectBack',        $redirectBack)
        ->with('povoamento_id',       $povoamento_id);
    }

    public function storeLotes(LotesCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $redirectBack = 'no';
        $povoamento_id = 0;

        if (isset($data['redirectBack'])) {
            $redirectBack  = $data['redirectBack'];
            $povoamento_id = $data['povoamento_id'];
        }

        $data = DataPolisher::toPolish($data);
        
        $data['classe_idade'] = mb_strtoupper($data['classe_idade']);
        $data['filial_id']    = session('_filial')->id;
        $data['usuario_id']   = auth()->user()->id;

        $poslarva_estocada = VwPoslarvasEstocadas::find($data['estoque_entrada_id']);

        $data_saida   = Carbon::createFromFormat('d/m/Y H:i', $data['data_saida'])->format('Y-m-d H:i');
        $data_entrada = Carbon::createFromFormat('d/m/Y H:i', $data['data_entrada'])->format('Y-m-d H:i');

        $message = 'A data de entrada deve suceder a data de saída e anteceder a data de hoje';

        if (strtotime($data_saida) < strtotime($data_entrada) && strtotime($data_entrada) <= strtotime(date('Y-m-d H:i'))) {

            $message = 'A quantidade de pós-larvas informada é maior que a disponível em estoque';

            if ((int) $data['quantidade'] <= (int) $poslarva_estocada->quantidade) {

                $lote = Lotes::create($data);

                if ($lote instanceof Lotes) {

                    $param = ['lote_id' => $lote->id];

                    if ($redirectBack == 'yes') {
                        $param['redirectBack']  = $redirectBack;
                        $param['povoamento_id'] = $povoamento_id;
                    }

                    /* return redirect()->route('admin.lotes.biometrias.to_edit', $param)
                    ->with('success', 'Por favor preencha as informações da larvicultura'); */

                    return redirect()->route('admin.lotes.to_edit', $param)
                    ->with('success', 'Por favor preencha as informações da larvicultura');
                    
                }

                $message = 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.';

            }

        }

        return redirect()->back()->withInput()
        ->with('error', $message);
    }

    public function storeLotesLarvicultura(LotesLarviculturaCreateFormRequest $request, int $lote_id){

        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $data['lote_id'] = $lote_id;
        $data['povoamento_id'] = 0;

        //dd($data);

        $redirectBack = 'no';
        $povoamento_id = 0;

        //DD($data);

        if (isset($data['redirectBack'])) {
            $redirectBack  = $data['redirectBack'];
            $povoamento_id = $data['povoamento_id'];
        }

        $lote_larvicultura = LotesLarvicultura::create($data);

        if ($lote_larvicultura instanceof LotesLarvicultura) {

            $route = 'admin.lotes.to_edit';

            $param = ['id' => $lote_id];

            if ($redirectBack == 'yes') {
                $param['redirectBack']  = $redirectBack;
                $param['povoamento_id'] = $povoamento_id;
            }

            $redirect = redirect()->route($route, $param);
            return $redirect->with('success', 'Lote registrado com sucesso!');

        }
        return redirect()->back()->withInput()
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o lote! Tente novamente.');

    } 

    public function editLotes(int $id)
    {
        $data = Lotes::find($id);

        $especies = Especies::all();

        $redirectBack = 'no';
        $povoamento_id = 0;

        $lotes_larvicultura = LotesLarvicultura::where('lote_id', $id)
        ->get();

        $poslarva_estocada = VwPoslarvasEstocadas::find($data['estoque_entrada_id']);

        return view('admin.lotes.edit')
        ->with('id',                 $id)
        ->with('lote_fabricacao',    $data['lote_fabricacao'])
        ->with('data_saida',         $data->data_saida())
        ->with('data_entrada',       $data->data_entrada())
        ->with('quantidade',         $data['quantidade'])
        ->with('classe_idade',       $data['classe_idade'])
        ->with('temperatura',        $data['temperatura'])
        ->with('salinidade',         $data['salinidade'])
        ->with('ph',                 $data['ph'])
        ->with('especie_id',         $data['especie_id'])
        ->with('genetica',          $data['genetica']) 
        ->with('poslarva_estocada',  $poslarva_estocada)
        ->with('lotes_larvicultura', $lotes_larvicultura)
        ->with('redirectBack',       $redirectBack)
        ->with('povoamento_id',      $povoamento_id)
        ->with('especies',           $especies);
    }

    public function updateLotes(LotesEditFormRequest $request, int $id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        if (! isset($data['quantidade'])) {
            $data['quantidade'] = 0;
        }

        $data['classe_idade'] = strtoupper($data['classe_idade']);
        $data['usuario_id']   = auth()->user()->id;

        $lote = Lotes::find($id);

        $poslarva_estocada = VwPoslarvasEstocadas::find($lote->estoque_entrada_id);

        $data_saida   = Carbon::createFromFormat('d/m/Y H:i', $data['data_saida'])->format('Y-m-d H:i');
        $data_entrada = Carbon::createFromFormat('d/m/Y H:i', $data['data_entrada'])->format('Y-m-d H:i');

        $message = '';

            //if ((int) $data['quantidade'] <= (int) $poslarva_estocada->quantidade) {

                if ($lote->update($data)) {
                    return redirect()->back()
                    ->with('success', 'Registro salvo com sucesso!');
                }

                $message = 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.';

            //}

       // }

        return redirect()->back()
        ->with('id',    $id)
        ->with('error', $message);
    }

    public function removeLotes(int $id)
    {
        $lote = Lotes::find($id);

        if ($lote->delete()) {
            return redirect()->route('admin.lotes')
            ->with('success', 'Registro excluído com sucesso!');
        } 
        
        return redirect()->route('admin.lotes')
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }

    public function removeLotesLarvicultura(int $lote_id,int $id )
    {
        $lote = LotesLarvicultura::find($id);
        
        $route = 'admin.lotes.to_edit';
        $redirect = redirect()->route($route, ['id' => $lote_id]);

        if ($lote->delete()) {
            
            return $redirect->with('success', 'Lote excluido com sucesso!');
        } 
        
        return $redirect->with('error', 'Ocorreu um erro durante a tentativa de excluir o lote! Tente novamente.');
    }

    public function redirectToCreatePovoamentos(Request $request)
    {
        $povoamento_id = $request->get('povoamento_id');

        // Redirecionamento para o módulo de CULTIVO
        return RouteManipulator::redirectTo($request, 2, 'admin.povoamentos.lotes.to_edit', ['povoamento_id' => $povoamento_id]);
    }

    public function redirectToCreateNotasFiscaisEntradas(Request $request)
    {
        // Redirecionamento para o módulo de ALMOXARIFADO
        return RouteManipulator::redirectTo($request, 4, 'admin.notas_fiscais_entradas.to_create', ['redirectBack' => 'yes']);
    }
}
