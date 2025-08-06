<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AnalisesBiometricasOld;
use App\Models\Ciclos;
use App\Models\Povoamentos;
use App\Http\Requests\AnalisesBiometricasOldCreateFormRequest;
use App\Http\Controllers\Util\DataPolisher;
use Carbon\Carbon;

class AnalisesBiometricasOldController extends Controller
{
    private $rowsPerPage = 10;
    
    public function listingAnalisesBiometricas()
    {
        $analises_biometricas = AnalisesBiometricasOld::listing($this->rowsPerPage, [
            'filial_id' => session('_filial')->id
        ]);

        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->get();

        return view('admin.analises_biometricas_old.list')
        ->with('ciclos',               $ciclos)
        ->with('analises_biometricas', $analises_biometricas);
    }
    
    public function searchAnalisesBiometricas(Request $request)
    {
        $formData = $request->except(['_token']);
        $formData['filial_id'] = session('_filial')->id;

        $analises_biometricas = AnalisesBiometricasOld::search($formData, $this->rowsPerPage);

        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->get();

        return view('admin.analises_biometricas_old.list')
        ->with('formData',             $formData)
        ->with('ciclos',               $ciclos)
        ->with('analises_biometricas', $analises_biometricas);
    }

    public function createAnalisesBiometricas()
    {   
        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->where('situacao', 6)   // Engorda
        ->orWhere('situacao', 7) // Despesca
        ->get();

        return view('admin.analises_biometricas_old.create')
        ->with('ciclos', $ciclos);
    }

    public function storeAnalisesBiometricas(AnalisesBiometricasOldCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data, ['EMPTY_TO_ZERO']);

        $data['filial_id'] = session('_filial')->id;
        $data['usuario_id'] = auth()->user()->id;

        $ciclo = Ciclos::find($data['ciclo_id']);

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

                    $analise_biometrica = AnalisesBiometricasOld::create($data);

                    if ($analise_biometrica instanceof AnalisesBiometricasOld) {
                        return redirect()->route('admin.analises_biometricas_old')
                        ->with('success', 'Registro salvo com sucesso!');
                    }

                } else {

                    $message = 'A data da análise deve suceder as datas de fim do povoamento e do início do ciclo.';

                }

            }

        }

        return redirect()->back()
        ->withInput()
        ->with('error', $message);
    }

    public function editAnalisesBiometricas(int $id)
    {
        $data = AnalisesBiometricasOld::find($id);

        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->where('situacao', 6)   // Engorda
        ->orWhere('situacao', 7) // Despesca
        ->get();

        $situacao     = $data->ciclo->verificarSituacao([6, 7]);
        $tanque_sigla = $data->ciclo->tanque->sigla;

        return view('admin.analises_biometricas_old.edit')
        ->with('id',                $id)
        ->with('ciclo_id',          $data['ciclo_id'])
        ->with('data_analise',      $data->data_analise())
        ->with('moles',             $data['moles'])
        ->with('semimoles',         $data['semimoles'])
        ->with('flacidos',          $data['flacidos'])
        ->with('opacos',            $data['opacos'])
        ->with('deformados',        $data['deformados'])
        ->with('caudas_vermelhas',  $data['caudas_vermelhas'])
        ->with('necroses_nivel1',   $data['necroses_nivel1'])
        ->with('necroses_nivel2',   $data['necroses_nivel2'])
        ->with('necroses_nivel3',   $data['necroses_nivel3'])
        ->with('necroses_nivel4',   $data['necroses_nivel4'])
        ->with('classe0to10',       $data['classe0to10'])
        ->with('classe10to20',      $data['classe10to20'])
        ->with('classe20to30',      $data['classe20to30'])
        ->with('classe30to40',      $data['classe30to40'])
        ->with('classe40to50',      $data['classe40to50'])
        ->with('classe50to60',      $data['classe50to60'])
        ->with('classe60to70',      $data['classe60to70'])
        ->with('classe70to80',      $data['classe70to80'])
        ->with('classe80to100',     $data['classe80to100'])
        ->with('classe100to120',    $data['classe100to120'])
        ->with('classe120to150',    $data['classe120to150'])
        ->with('classe150toUP',     $data['classe150toUP'])
        ->with('total_animais',     $data['total_animais'])
        ->with('peso_total',        $data['peso_total'])
        ->with('peso_medio',        $data['peso_medio'])
        ->with('sobrevivencia',     $data['sobrevivencia'])
        ->with('tanque_sigla',      $tanque_sigla)
        ->with('situacao',          $situacao)
        ->with('ciclos',            $ciclos);
    }

    public function updateAnalisesBiometricas(AnalisesBiometricasOldCreateFormRequest $request, int $id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data, ['EMPTY_TO_ZERO']);

        $data['usuario_id'] = auth()->user()->id;

        $analise_biometrica = AnalisesBiometricasOld::find($id);

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
                        return redirect()->route('admin.analises_biometricas_old')
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

    public function removeAnalisesBiometricas(int $id)
    {
        $analise_biometrica = AnalisesBiometricasOld::find($id);

        if ($analise_biometrica->delete()) {            
            return redirect()->back()
            ->with('success', 'Registro excluído com sucesso!');
        }

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }

    public function viewAnalisesBiometricas(int $id)
    {
        return $this->editAnalisesBiometricas($id);
    }

}
