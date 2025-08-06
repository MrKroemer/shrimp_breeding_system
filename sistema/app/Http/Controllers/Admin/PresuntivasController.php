<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Util\DataPolisher;
use App\Models\Povoamento;
use App\Models\Presuntivas;
use App\Models\PresuntivasTemp;
use App\Models\PresuntivasBranquias;
use App\Models\PresuntivasHepatopancreas;
use App\Models\PresuntivasMacroscopica;
use App\Models\PresuntivasInformacoesComplementares;
use App\Models\Ciclos;
use App\Models\VwCiclosPorSetor;
use App\Http\Requests\PresuntivaCreateFormRequest;

class PresuntivasController extends Controller
{
    private $rowsPerPage = 10;

    public function listingPresuntiva()
    {
        $presuntivas = Presuntivas::listing($this->rowsPerPage, [
            'filial_id' => session('_filial')->id
        ]);

        return view('admin.presuntivas.list')
        ->with('presuntivas',       $presuntivas);
    }
    
    public function searchPresuntiva(Request $request)
    {
        $formData = $request->except(['_token']);

        $presuntivas = Presuntivas::search($formData, $this->rowsPerPage);

        return view('admin.presuntivas.list')
        ->with('formData', $formData)
        ->with('presuntivas',  $presuntivas);
    }

    public function createPresuntiva()
    {
        $presuntivas = Presuntivas::all();

        $ciclos = VwCiclosPorSetor::where('filial_id', session('_filial')->id)
        ->whereBetween('ciclo_situacao', [6, 7])
        ->orderBy('tanque_sigla') // Engorda e despesca
        // ->whereBetween('tipo', [2, 3])     // Cultivo e reprodução de peixes
        ->get();

        
        //dd($ciclos);       

        return view('admin.presuntivas.create')
        ->with('ciclos', $ciclos)
        ->with('presuntivas', $presuntivas);
    }

    public function storePresuntiva(Request $request)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $data['usuario_id']  = auth()->user()->id;

        $data['filial_id'] = session('_filial')->id;

        $insert = Presuntivas::create($data);
    
        if ($insert) {

           return redirect()->route('admin.presuntivas.amostras.to_create', ['presuntiva_id' => $insert->id])
           ->with('success', 'Análise cadastrada com sucesso!');

        }

        return redirect()->back()
        ->with('ciclo_id',                    $data['ciclo_id'])
        ->with('data_analise',                $data['data_analise'])
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar a análise. Tente novamente!');
    }

    public function editPresuntiva(int $id)
    {        
        $data = Presuntivas::find($id);

        $ciclos = Ciclos::join('tanques', 'tanque_id', '=','tanques.id')->get(['ciclos.id', 'tanque_id', 'sigla']);
        

        return view('admin.presuntivas.edit')
        ->with('id',                          $id)
        ->with('ciclo_id',                    $data['ciclo_id'])
        ->with('data_analise',                $data->data_analise())
        ->with('ciclos',                      $ciclos);
    }

    public function updatePresuntiva(PresuntivaCreateFormRequest $request, int $id)
    {   
        $data = $request->only(['data_analise']);
        
        $data = DataPolisher::toPolish($data);
        $data['usuario_id'] = auth()->user()->id;
        $update = Presuntivas::where('id', $id)->update($data);
        
        if ($update) {
            
            return redirect()->route('admin.presuntivas')
            ->with('success', 'Presuntiva atualizada com sucesso!');

        }
        $data = $request->except(['_token']);
        return redirect()->back()
        ->with('ciclo_id',                    $data['ciclo_id'])
        ->with('data_analise',                $data['data_analise'])
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar a Presuntiva. Tente novamente!');
    }

    public function removePresuntiva(int $id)
    {
        $data = Presuntivas::find($id);

        $delete = $data->delete();

        if ($delete) {
            return redirect()->route('admin.presuntivas')
            ->with('success', 'Análise Presuntiva excluída com sucesso!');
        } 
        
        return redirect()->route('admin.presuntivas')
        ->with('success', 'Ocorreu um erro ao excluir o Análise. Tente novamente!');
    }

    public function storeTempPresuntiva(Request $request)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data, ['EMPTY_TO_NULL']);

        if ( isset($data['data_analise']) && isset($data['ciclo_id'])){
            $analise = PresuntivasTemp::where('ciclo_id', $data['ciclo_id'])
            ->where('data_analise', $data['data_analise'])->get();

            if ($analise->isEmpty()) {
                PresuntivasTemp::create($data);
            }else{
                PresuntivasTemp::where('ciclo_id',$data['ciclo_id'])
                ->where('data_analise',$data['data_analise'])->update($data);
            }
            
            return response()->json(['success'=>'Success, data saved!']);

            //return "deu certo";
        }
        return "deu ruim";
    }


    public function createPresuntivaAmostras(int $presuntiva_id)
    {
        $data = Presuntivas::find($presuntiva_id);
        
        $analise = PresuntivasTemp::where('ciclo_id', $data['ciclo_id'])
            ->where('data_analise', $data['data_analise'])->get();
        //dd($data->presuntivasHepatopancreas->media());
        if($analise->isEmpty()){
            $danos_tubulos = [];
            $cristais = [];
            $media = 0;
            if(! is_null($data->presuntivasHepatopancreas)){
                $danos_tubulos = $data->presuntivasHepatopancreas->danos_tubulos();
            }
            if(! is_null($data->presuntivasHepatopancreas)){
                $media = $data->presuntivasHepatopancreas->media();
            }
            if(! is_null($data->presuntivasInformacoesComplementares)){
                $cristais = $data->presuntivasInformacoesComplementares->cristais();
            }
            return view('admin.presuntivas.amostras.create')
            ->with('id',                                   $presuntiva_id)
            ->with('data_analise',                         $data->data_analise())
            ->with('dataanalise',                          $data->data_analise2())
            ->with('danos_tubulos',                        $danos_tubulos)
            ->with('cristais',                             $cristais)
            ->with('media',                                $media)
            ->with('presuntivashepatopancreas',            $data->presuntivasHepatopancreas)
            ->with('presuntivasmacroscopica',              $data->presuntivasMacroscopicas)
            ->with('presuntivasbranquias',                 $data->presuntivasBranquias)
            ->with('presuntivasinformacoescomplementares', $data->presuntivasInformacoesComplementares)
            ->with('ciclo',                                $data->ciclo);


        }else{
            $analise = PresuntivasTemp::where('ciclo_id', $data['ciclo_id'])
            ->where('data_analise', $data['data_analise'])->first();
            
            return view('admin.presuntivas.amostras.create')
            ->with('id',                                   $presuntiva_id)
            ->with('data_analise',                         $data->data_analise())
            ->with('dataanalise',                          $data->data_analise2())
            ->with('danos_tubulos',                        $analise->danos_tubulos())
            ->with('cristais',                             $analise->cristais())
            ->with('media',                                $analise->media())
            ->with('presuntivashepatopancreas',            $analise)
            ->with('presuntivasmacroscopica',              $analise)
            ->with('presuntivasbranquias',                 $analise)
            ->with('presuntivasinformacoescomplementares', $analise)
            ->with('ciclo',                                $data->ciclo);
        }
    }

    public function storePresuntivaAmostras(Request $request, int $presuntiva_id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data, ['RM_EMPTY_STRING']);

        $data['usuario_id']  = auth()->user()->id;

        $data['filial_id'] = session('_filial')->id;

        $data['presuntiva_id']  = $presuntiva_id;
        
        //Verifica a existencia de algum dado já preenchido
        $dataH = PresuntivasHepatopancreas::where('presuntiva_id', $presuntiva_id);        
        $dataM = PresuntivasMacroscopica::where('presuntiva_id', $presuntiva_id);        
        $dataB = PresuntivasBranquias::where('presuntiva_id', $presuntiva_id);        
        $dataIC = PresuntivasInformacoesComplementares::where('presuntiva_id', $presuntiva_id);

        if(($dataH)||($dataM)||($dataB)||($dataIC))
        {   
            ($dataH) ? $deleteH = $dataH->delete(): $deleteH =  '';
            ($dataM) ? $deleteM = $dataM->delete(): $deleteM =  '';
            ($dataB) ? $deleteB = $dataB->delete(): $deleteB = '';
            ($dataIC) ? $deleteIC = $dataIC->delete(): $deleteIC = '';

            $insertHepatopancreas             = PresuntivasHepatopancreas::create($data);
            $insertMacroscopica               = PresuntivasMacroscopica::create($data);
            $insertBranquias                  = PresuntivasBranquias::create($data);
            $insertInformacoesComplementares  = PresuntivasInformacoesComplementares::create($data);
    
            if (($insertHepatopancreas)||($insertMacroscopica )||($insertBranquias)||($insertInformacoesComplementares)) {

            return redirect()->route('admin.presuntivas')
            ->with('success', 'Análise cadastrada com sucesso!');

            }
        }

        $insertHepatopancreas             = PresuntivasHepatopancreas::create($data);
        $insertMacroscopica               = PresuntivasMacroscopica::create($data);
        $insertBranquias                  = PresuntivasBranquias::create($data);
        $insertInformacoesComplementares  = PresuntivasInformacoesComplementares::create($data);
    
        if (($insertHepatopancreas)||($insertMacroscopica )||($insertBranquias)||($insertInformacoesComplementares)) {

           return redirect()->route('admin.presuntivas')
           ->with('success', 'Análise cadastrada com sucesso!');

        }

        return redirect()->back()
        ->with('ciclo_id',                    $data['ciclo_id'])
        ->with('data_analise',                $data['data_analise'])
        ->with('error', 'Oops! Algo de errado ocorreu ao salvar a análise. Tente novamente!');
    }
}
