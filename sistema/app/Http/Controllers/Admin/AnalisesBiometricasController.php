<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VwAnalisesBiometricas;
use App\Models\AnalisesBiometricas;
use App\Models\AnalisesBiometricasAmostras;
use App\Models\Ciclos;
use App\Models\Povoamentos;
use App\Http\Requests\AnalisesBiometricasCreateFormRequest;
use App\Http\Requests\AnalisesBiometricasEditFormRequest;
use App\Http\Requests\AnalisesBiometricasAmostrasCreateFormRequest;
use App\Http\Controllers\Util\DataPolisher;
use App\Exports\TabelaVariacaoCoeficiente;
use App\Models\VwCiclosPorSetor;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class AnalisesBiometricasController extends Controller
{
    private $rowsPerPage = 10;
    
    public function listingAnalisesBiometricas()
    {
        $analises_biometricas = VwAnalisesBiometricas::listing($this->rowsPerPage, [
            'filial_id' => session('_filial')->id
        ]);

        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->orderBy('numero', 'desc')
        ->get();

        return view('admin.analises_biometricas.list')
        ->with('ciclos',               $ciclos)
        ->with('analises_biometricas', $analises_biometricas);
    }
    
    public function searchAnalisesBiometricas(Request $request)
    {
        $formData = $request->except(['_token']);
        $formData['filial_id'] = session('_filial')->id;

        $analises_biometricas = VwAnalisesBiometricas::search($formData, 30);

        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->orderBy('numero', 'desc')
        ->get();

        return view('admin.analises_biometricas.list')
        ->with('formData',             $formData)
        ->with('ciclos',               $ciclos)
        ->with('analises_biometricas', $analises_biometricas);
    }

    public function createAnalisesBiometricas()
    {   
        $ciclos = VwCiclosPorSetor::where('filial_id', session('_filial')->id)
        ->whereBetween('ciclo_situacao', [1, 7])
        ->orderBy('tanque_sigla') // Engorda e despesca
        // ->whereBetween('tipo', [2, 3])     // Cultivo e reprodução de peixes
        ->get();

        return view('admin.analises_biometricas.create')
        ->with('ciclos', $ciclos);
    }

    public function storeAnalisesBiometricas(AnalisesBiometricasCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $data['filial_id']  = session('_filial')->id;
        $data['usuario_id'] = auth()->user()->id;

        $ciclo = Ciclos::find($data['ciclo_id']);

        if(isset($data['sobrevivencia'])){
            $data['situacao'] == 3;
        }


        $message = 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.';

        if ($ciclo instanceof Ciclos) {

            if ($ciclo->tipo == 1) {

                $povoamento = $ciclo->povoamento;

                if ($povoamento instanceof Povoamentos) {

                    $data_analise = Carbon::createFromFormat('d/m/Y', $data['data_analise'])->format('Y-m-d 23:59:59');

                    if (! (
                        $povoamento->sucedeDataFim($data_analise) && 
                        $ciclo->sucedeDataInicio($data_analise) && 
                        strtotime($data_analise) <= strtotime('+1 day',strtotime(date('Y-m-d 23:59:59')))
                    )) {
                        
                        $message = 'A data da análise deve suceder as datas de fim do povoamento e do início do ciclo.';

                        return redirect()->back()->withInput()
                        ->with('error', $message);

                    }

                }

            } 

            $analise_biometrica = AnalisesBiometricas::create($data);

            if ($analise_biometrica instanceof AnalisesBiometricas) {
                return redirect()->route('admin.analises_biometricas.amostras.to_create', ['analise_biometrica_id' => $analise_biometrica->id])
                ->with('success', 'Registro salvo com sucesso!');
            }

        }

        return redirect()->back()->withInput()
        ->with('error', $message);
    }

    public function editAnalisesBiometricas(int $id, Request $request)
    {
        $redirectParam = $request->only(['ciclo_id', 'data_analise']);

        $analise_biometrica = AnalisesBiometricas::find($id);

        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->whereBetween('situacao', [6, 7]) // Engorda e despesca
        ->get();

        return view('admin.analises_biometricas.edit')
        ->with('id',                 $id)
        ->with('redirectParam'     , $redirectParam)
        ->with('analise_biometrica', $analise_biometrica)
        ->with('situacao_ciclo',     $analise_biometrica->ciclo->verificarSituacao([6, 7]))
        ->with('ciclos',             $ciclos);
    }

    public function updateAnalisesBiometricas(Request $request, int $id)
    {
        $analise_biometrica = AnalisesBiometricas::find($id);

        $redirectParam = $request->only(['ciclo_id', 'data_analise']);

        if ($analise_biometrica instanceof AnalisesBiometricas) {

            $data = $request->except(['_token', 'data_analise', 'ciclo_id']);

            $data['situacao'] = $analise_biometrica->situacao;
            
            if ($analise_biometrica->situacao == '3') {
                $data = $request->only(['sobrevivencia']);
            }
            
            if(isset($data['sobrevivencia'])){
                $data['situacao'] = '3';
            }

            $data = DataPolisher::toPolish($data, ['EMPTY_TO_NULL']);

            $data['usuario_id'] = auth()->user()->id;

            if ($analise_biometrica->update($data)) {
                return redirect()->route('admin.analises_biometricas.to_search', $redirectParam)
                ->with('success', 'Registro salvo com sucesso!');
            }

        }

        return redirect()->back()
        ->with('id', $id)
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function removeAnalisesBiometricas(int $id)
    {
        $analise_biometrica = AnalisesBiometricas::find($id);

        if ($analise_biometrica->situacao != 3) {  //Diferente de coleta encerrada
            
            if ($analise_biometrica->amostras->isNotEmpty()) {

                $amostras = AnalisesBiometricasAmostras::where('analise_biometrica_id', $analise_biometrica->id);

                if (! $amostras->delete()) {
                    return redirect()->back()
                    ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
                }

            }
            
            if ($analise_biometrica->delete()) {
                return redirect()->back()
                ->with('success', 'Registro excluído com sucesso!');
            }

        } 

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }

    public function closeAnalisesBiometricas(int $id)
    {
        $analise_biometrica = AnalisesBiometricas::find($id);
        
        if ($analise_biometrica instanceof AnalisesBiometricas) {

            /* if (empty((float) $analise_biometrica->sobrevivencia)) {
                return redirect()->back()
                ->with('error', 'Não é possível finalizar uma análise biométrica sem informar o percentual de sobrevivência.');
            } */
            
            $analise_biometrica->total_animais = $analise_biometrica->total_animais();
            $analise_biometrica->peso_total    = $analise_biometrica->peso_total();
            $analise_biometrica->peso_medio    = $analise_biometrica->peso_medio();
            $analise_biometrica->situacao      = '2';

            if ($analise_biometrica->save()) {
                return redirect()->route('admin.analises_biometricas')
                ->with('success', 'Análise biométrica encerrada com sucesso!');
            }

        }

        return redirect()->back()
        ->with('error', 'Ocorreu um erro ao tentar encerrar a análise.');
    }

    public function viewAnalisesBiometricas(int $id, Request $request)
    {
        return $this->editAnalisesBiometricas($id, $request);
    }

    public function createAnalisesBiometricasAmostras(int $id)
    {
        $siglas = [
            0   => 'Sem Classificação',
            1   => 'Opaco',
            2   => 'Deformado',
            3   => 'Cauda Vermelha',
            4   => 'Flacido',
            5   => 'Mole',
            6   => 'Semimole',
            7   => 'Necrose1',
            8   => 'Necrose2',
            9   => 'Necrose3',
            10  => 'Necrose4',
            11  => 'Total',
        ];

        $analise_biometrica = AnalisesBiometricas::find($id);

        return view('admin.analises_biometricas.amostras.create')
        ->with('analise_biometrica', $analise_biometrica)
        ->with('classificacoes',     $analise_biometrica->classificacoes())
        ->with('qualificacoes',      $analise_biometrica->qualificacoes())
        ->with('ultimas_amostras',   $analise_biometrica->ultimas_amostras())
        ->with('siglas',             $siglas);
    }

    public function storeAnalisesBiometricasAmostras(AnalisesBiometricasAmostrasCreateFormRequest $request, int $analise_biometrica_id)
    {
        $data = $request->except(['_token']);
        
        $data['analise_biometrica_id'] = $analise_biometrica_id;
        $data['usuario_id']            = auth()->user()->id;

        $gramatura = AnalisesBiometricasAmostras::create($data);

        if ($gramatura instanceof AnalisesBiometricasAmostras) {
            return redirect()->back();
        }

        return redirect()->back()->withInput()
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function removeAnalisesBiometricasAmostras(int $id)
    {
        $amostra = AnalisesBiometricasAmostras::find($id);

        if ($amostra->delete()) {
            return redirect()->back()
            ->with('success', 'Registro excluído com sucesso!');
        }

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }

    public function tableAnalisesBiometricasAmostras()
    {
        //return Excel::download(new TabelaVariacaoCoeficiente(), 'BiometriaParcial.xls');

        return Excel::download(new AnalisesBiometricasAmostras(), 'AmostrasBiometricas.xls');
         

        

    }

   
}