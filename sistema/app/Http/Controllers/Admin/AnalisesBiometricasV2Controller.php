<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AnalisesBiometricasV2;
use App\Models\AnalisesBiometricasGramaturas;
use App\Models\Ciclos;
use App\Models\Povoamentos;
use App\Http\Requests\AnalisesBiometricasV2CreateFormRequest;
use App\Http\Requests\AnalisesBiometricasV2SobrevivenciaCreateFormRequest;
use App\Http\Requests\AnalisesBiometricasAmostrasCreateFormRequest;
use App\Http\Controllers\Util\DataPolisher;
use App\Exports\TabelaVariacaoCoeficiente;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class AnalisesBiometricasV2Controller extends Controller
{
    private $rowsPerPage = 10;
    
    public function listingAnalisesBiometricasV2()
    {
        $analises_biometricas = AnalisesBiometricasV2::listing($this->rowsPerPage, [
            'filial_id' => session('_filial')->id
        ]);

        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->get();

        return view('admin.analises_biometricas_v2.list')
        ->with('ciclos',               $ciclos)
        ->with('analises_biometricas', $analises_biometricas);
    }
    
    public function searchAnalisesBiometricasV2(Request $request)
    {
        $formData = $request->except(['_token']);
        $formData['filial_id'] = session('_filial')->id;

        $analises_biometricas = AnalisesBiometricasV2::search($formData, $this->rowsPerPage);

        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->get();

        return view('admin.analises_biometricas_v2.list')
        ->with('formData',             $formData)
        ->with('ciclos',               $ciclos)
        ->with('analises_biometricas', $analises_biometricas);
    }

    public function createAnalisesBiometricasV2()
    {   
        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->where('situacao', 6)   // Engorda
        ->orWhere('situacao', 7) // Despesca
        ->orWhere('tipo', 2)
        ->orWhere('tipo', 3)
        ->get();

        return view('admin.analises_biometricas_v2.create')
        ->with('ciclos', $ciclos);
    }

    public function storeAnalisesBiometricasV2(AnalisesBiometricasV2CreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        if(isset($data['situacao'])){
            $data['situacao'] = 'N';
        }

        $data = DataPolisher::toPolish($data, ['EMPTY_TO_ZERO']);

        $data['filial_id'] = session('_filial')->id;
        $data['usuario_id'] = auth()->user()->id;

        

        $ciclo = Ciclos::find($data['ciclo_id']);

        $message = 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.';

        if ($ciclo['tipo'] == 1){
            if ($ciclo instanceof Ciclos) {

                $povoamento = $ciclo->povoamento;

                if ($povoamento instanceof Povoamentos) {

                    $data_analise = Carbon::createFromFormat('d/m/Y', $data['data_analise'])->format('Y-m-d');
                    $data_analise .= ' 23:59:59';

                    if (
                        $povoamento->sucedeDataFim($data_analise) && 
                        $ciclo->sucedeDataInicio($data_analise) && 
                        strtotime($data_analise) <= strtotime(date('Y-m-d 23:59:59'))
                    ) {

                        $analise_biometrica = AnalisesBiometricasV2::create($data);

                        if ($analise_biometrica instanceof AnalisesBiometricasV2) {
                            return redirect()->route('admin.analises_biometricas_v2.amostras.to_create',[$analise_biometrica->id])
                            ->with('success', 'Registro salvo com sucesso!');
                        }

                    } else {

                        $message = 'A data da análise deve suceder as datas de fim do povoamento e do início do ciclo.';

                    }

                }

            }
        }else{

            $analise_biometrica = AnalisesBiometricasV2::create($data);
            return redirect()->route('admin.analises_biometricas_v2')
            ->with('success', 'Biometria salva com sucesso!');

        }

        return redirect()->back()
        ->withInput()
        ->with('error', $message);
    }

    public function editAnalisesBiometricasV2(int $id)
    {
        $data = AnalisesBiometricasV2::find($id);

        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->where('situacao', 6)   // Engorda
        ->orWhere('situacao', 7) // Despesca
        ->get();

        $situacao     = $data->ciclo->verificarSituacao([6, 7]);
        $tanque_sigla = $data->ciclo->tanque->sigla;
        $total_amostras = $data->total_amostras;
        $peso_total     = $data->peso_total;

        return view('admin.analises_biometricas_v2.edit')
        ->with('id',                $id)
        ->with('ciclo_id',          $data['ciclo_id'])
        ->with('data_analise',      $data->data_analise())
        ->with('sobrevivencia',     $data['sobrevivencia'])
        ->with('total_amostras',    $total_amostras)
        ->with('peso_total',        $peso_total)
        ->with('tanque_sigla',      $tanque_sigla)
        ->with('situacao',          $situacao)
        ->with('ciclos',            $ciclos);
    }

    public function editSobrevivenciaAnalisesBiometricasV2(int $id)
    {
        $data = AnalisesBiometricasV2::find($id);

        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->where('situacao', 6)   // Engorda
        ->orWhere('situacao', 7) // Despesca
        ->get();
        $situacao       = $data->ciclo->verificarSituacao([6, 7]);
        $tanque_sigla   = $data->ciclo->tanque->sigla;
        

        return view('admin.analises_biometricas_v2.sobrevivencia')
        ->with('id',                $id)
        ->with('ciclo_id',          $data['ciclo_id'])
        ->with('data_analise',      $data->data_analise())
        ->with('sobrevivencia',     $data['sobrevivencia'])
        ->with('tanque_sigla',      $tanque_sigla)
        ->with('situacao',          $situacao)
        ->with('ciclos',            $ciclos);
    }

    public function updateAnalisesBiometricasV2(AnalisesBiometricasV2CreateFormRequest $request, int $id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data, ['EMPTY_TO_ZERO']);

        $data['usuario_id'] = auth()->user()->id;

        $analise_biometrica = AnalisesBiometricasV2::find($id);

        $ciclo = $analise_biometrica->ciclo;

        $message = 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.';

        if ($ciclo instanceof Ciclos) {

            $povoamento = $ciclo->povoamento;
            
            if ($povoamento instanceof Povoamentos) {

                $data_analise = Carbon::createFromFormat('d/m/Y', $data['data_analise'])->format('Y-m-d');
                $data_analise .= ' 23:59:59';
                    
                if (
                    $povoamento->sucedeDataFim($data_analise) && 
                    $ciclo->sucedeDataInicio($data_analise) && 
                    strtotime($data_analise) <= strtotime(date('Y-m-d 23:59:59'))
                ) {

                    if ($analise_biometrica->update($data)) {
                        return redirect()->route('admin.analises_biometricas_v2')
                        ->with('success', 'Registro salvo com sucesso!');
                    }

                } else {

                    $message = 'A data da análise deve suceder as datas de fim do povoamento e do início do ciclo.';

                }

            }
        
        }

        return redirect()->back()
        ->with('id', $id)
        ->with('error', $message);
    }

    public function updateSobrevivenciaAnalisesBiometricasV2(AnalisesBiometricasV2SobrevivenciaCreateFormRequest $request, int $id)
    {
        $data = $request->except(['_token']);

        $data['usuario_id'] = auth()->user()->id;

        $analise_biometrica = AnalisesBiometricasV2::find($id);

        $message = 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.';

        if ($data['sobrevivencia'] > 0){

            if ($analise_biometrica->update($data)) {
                return redirect()->route('admin.analises_biometricas_v2')
                ->with('success', 'Sobrevivência atualizada com sucesso!');
            }

        } else {
    
            $message = 'A sobrevivência não pode ser 0.';

        }

        return redirect()->back()
        ->with('id', $id)
        ->with('error', $message);
        
        
    }

    public function removeAnalisesBiometricasV2(int $id)
    {

        $analise_biometrica = AnalisesBiometricasV2::find($id);

        if($analise_biometrica->situacao == 'S'){
            $gramaturas = AnalisesBiometricasGramaturas::where('analise_biometrica_id', $analise_biometrica->id)
            ->get(); 
            
            if($analise_biometrica->gramaturas->count() > 0){
                foreach ($gramaturas as $amostra){
                    $amostra->delete();
                }
                if ($analise_biometrica->delete()) {            
                    return redirect()->back()
                    ->with('success', 'Registro excluído com sucesso!');
                }
                return redirect()->back()
                ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
            
            }else{
            
                if ($analise_biometrica->delete()) {            
                    return redirect()->back()
                    ->with('success', 'Registro excluído com sucesso!');
                }
                return redirect()->back()
                ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
                    
            }
        }else{
            return redirect()->back()
            ->with('error', 'Não é possivel excluir uma biometria que foi validada! em caso de dúvidas, entre em contato com o setor de tecnologia.');
        }
    }

    public function viewAnalisesBiometricasV2(int $id)
    {
        return $this->editAnalisesBiometricasV2($id);
    }

    public function createAnalisesBiometricasAmostras(int $id)
    {
        $analise_biometrica = AnalisesBiometricasV2::find($id);

        $siglaclassificacoes = [
            11  => 'Total' ,
            10  => 'Necrose4',
            9   => 'Necrose3',
            8   => 'Necrose2',
            7   => 'Necrose1',
            6   => 'Semimole',
            5   => 'Mole',
            4   => 'Flacido',
            3   => 'Cauda Vermelha',
            2   => 'Deformado',
            1   => 'Opaco',
            0   => 'Sem Classificação',
        ];

        $cont = 1; 
        //dd($analise_biometrica->gramaturas->where('analise_biometrica_id', $id)->sortByDesc('id')->take(3));
        return view('admin.analises_biometricas_v2.amostras.create')
        ->with('classificacoes',     $analise_biometrica->classificacoes())
        ->with('classificacao',      $analise_biometrica->classificacao())
        ->with('amostras',           $analise_biometrica->gramaturas->where('analise_biometrica_id', $id)->sortByDesc('id')->take(3))
        ->with('siglas',             $siglaclassificacoes)
        ->with('cont',               $cont)
        ->with('analise_biometrica', $analise_biometrica);
    }

    public function storeAnalisesBiometricasAmostras(AnalisesBiometricasAmostrasCreateFormRequest $request, int $analise_biometrica_id)
    {
        $data = $request->except(['_token']);
        
        $data['usuario_id'] = auth()->user()->id;

        $data['analise_biometrica_id'] = $analise_biometrica_id;

        $message = 'Ocorreu um erro durante a tentativa de incluir a Gramatura! Tente novamente.';
        
        $gramatura = AnalisesBiometricasGramaturas::create($data);

        if ($gramatura instanceof AnalisesBiometricasGramaturas) {
            return redirect()->back()
            ->with('success', 'Registro salvo com sucesso!');
        }
        return redirect()->back()
        ->withInput()
        ->with('error', $message);
    }

    public function removeAnalisesBiometricasAmostras(int $id)
    {
        $amostra = AnalisesBiometricasGramaturas::find($id);

        if ($amostra->delete()) {            
            return redirect()->back()
            ->with('success', 'Amostra excluída com sucesso!');
        }

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }

    public function closeAnalisesBiometricasAmostras(int $id)
    {
        $analise_biometrica = AnalisesBiometricasV2::find($id);
        
        $total_amostras = AnalisesBiometricasGramaturas::where('analise_biometrica_id', $id)
        ->count();

        $analise_biometrica['situacao'] = 'S';

        if(!$analise_biometrica['total_amostras']){
            $analise_biometrica['total_amostras'] = $total_amostras;
        }
        if ($analise_biometrica instanceof AnalisesBiometricasV2) {
            $analise_biometrica->save();
            return redirect()->route('admin.analises_biometricas_v2')
            ->with('success', 'Coleta de Gramatura encerrada com sucesso!');
        }
        return redirect()->back()
        ->withInput()
        ->with('error', 'Ocorreu um erro ao tentar encerrar as coletas de gramaturas, caso persista, contate o suporte!');
    
    }
    public function tableAnalisesBiometricasAmostras()
    {
        return Excel::download(new TabelaVariacaoCoeficiente(), 'BiometriaParcial.xls');
    }
}