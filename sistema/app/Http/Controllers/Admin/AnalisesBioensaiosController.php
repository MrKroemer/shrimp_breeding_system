<?php 


namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Ciclos;
use App\Models\AnalisesBioensaios;
use App\Models\Povoamentos;
use App\Http\Controllers\Util\DataPolisher;
use App\Http\Request\AnalisesBioensaiosCreateFormRequest;
use App\Models\VwCiclosPorSetor;

class AnalisesBioensaiosController extends Controller
{
        private $rowsPerPage = 10;

        public function listingAnalisesBioensaios(){
            
            //SQl para listar o conteúdo.
            $analisesBioensaios = AnalisesBioensaios::listing($this->rowsPerPage, 
            [
                'filial_id' => session('_filial')->id
            ]);

            //Listar os ciclos por filial.
            $ciclos = Ciclos::where('filial_id', session('_filial')->id)
                              ->orderBy('numero', 'desc')
                              ->get();

                      return view('admin.analises_bioensaios')
                             ->with('ciclos', $ciclos)
                             ->with('analisesBioensaios', $analisesBioensaios);

        }

        public function searchAnaliseBioensaios(Request $request){
           
            $formData = $request->except(['_token']);
            $formData['filial_id'] = session('filial_id')->id;

           //
           $analisesBioensaios = AnalisesBioensaios::search($formData, 20);

            $ciclos = Ciclos::where('filial_id', session('_filial')->id)
                              ->orderBy('numero', 'dssc')
                              ->get();
                      
                         return view('admin.analises_bioensaios.to_search')
                                ->with('formData',          $formData)
                                ->with('ciclos',            $ciclos)
                                ->with('anlisesBioensaios', $analisesBioensaios);                     
           
        }

        public function createAnalisesBioensaios(){

            $ciclos = VwCiclosPorSetor::where('filial_id', session('_filial_id')->id)
                                      ->whereBeteween('ciclo_situtacao', [1, 7])
                                      ->orderBy('tanque_sigla')
                                      ->whereBeteween('tipo', [2, 3])
                                      ->get();

                      return view('admin.analises_bioensaios.create')
                           ->with('ciclos', $ciclos);                
        }

        public function storeAnalisesBioensaios(AnalisesBioensaiosCreateFormRequest $request){

            $data = $request->except(['_token']);
            $data = DataPolisher::topolish($data);
            $data['filial_id'] = session('_filial')->id;
            $data['usuario_id'] = auth()->user()->id;

            //SQL para encontrar ciclo pelo id.
            $ciclo = Ciclos::find($data['ciclo_id']);

            if(isset($data['sobrevivencia'])){
                    $data['situacao'] = 3;
            }
            
            //Retorna uma mesangem de erro quando o sistema identificar inconsistencias.
            $message = 'Ocorreu um erro ao tentar salvar um registro! Tente novamente';

            //instancia o ciclo.
            if($ciclo instanceof Ciclos){
                if($ciclo->tipo == 1){
                    $povoamento = $ciclo->povoamento;
                    //Instancia o povoamento.
                    if($povoamento instanceof Povoamentos){
                        //formata o valor data para o padrão brassileiro.
                        $data_analise = Carbon::createFromFormat('d/m/Y', $data['data_analise'])->format('Y-m-d 23:59:59');

                            if(!(
                                $povoamento->sucedeDataFim($data_analise) && $ciclo->sucedeDataInicio($data_analise) &&
                                 strtotime($data_analise) <= strtotime('+1 day', strtotime(date('Y-m-d 23:59:59'))))){
                                    
                                    //Retorna uma mensagem de erro caso o sistema identifique inconsistencias.    
                                    $message = 'A data da análise deve suceder as datas de fim do povoamento e do inicio do ciclo.';

                                    return redirect()->back()->withInput()
                                           ->with('error', $message); 
                               }
                            }
                        }

                        $analise_bioensaio = AnalisesBioensaios::create($data);
                     
                        //Instancia o bioensaio.
                        if($analise_bioensaio instanceof AnalisesBioensaios){
                              return redirect()->route('admin.analises_bioensaios.to_create', ['analise_bioensaios' => $analise_bioensaio->id])
                                               ->with('success', 'Registro salvo com sucesso!');
                        }
                    }
                        return redirect()->back()->withInput()
                                         ->with('error', $message);
            }

            public function editAnalisesBioensaios(int $id, Request $request){

                $redirectParam = $request->only(['ciclo_id', 'data_analise']);
                                 
                                //Realizar busca por id da análise(?)
                                 $analises_bioensaios = AnalisesBioensaios::find($id);

                                 //Realizar buscas pelos ciclos que estão em situaçoes de povoamento e engorda.
                                 $ciclos = Ciclos::where('filial_id', session('_filial')->id)
                                                 ->whereBetween('situacao', [5, 7])
                                                 ->get();

                                return view('admin.analises_bioensaios.to_edit')
                                     ->with('id',                              $id)
                                     ->with('redirect_param',      $redirectParam)
                                     ->with('analise_bioensaio', $analises_bioensaios)
                                     ->with('ciclo_situacao', $analises_bioensaios
                                     ->ciclo->veriricarSituacao([5, 7]))
                                     ->with('ciclos',           $ciclos);
                                                     

            }

         public function updateAnalisesBioensaios(Request $request, int $id){ 

                //Realiza busca pelos id de cada analise.
                $analises_bioensaios = AnalisesBioensaios::find($id);
                $redirectParam = $request->only(['ciclos_id', 'data-analise']);

                        if ($analises_bioensaios instanceof AnalisesBioensaios){
                           
                            $data = $request->except(['_token', 'data_analise', 'ciclo_id', 'povoamento_id']);
                            $data['situacao'] = $analises_bioensaios->situacao;
                            $data = $request->with(['coleta_24', 'coloeta_48', 'coleta,72']);
            
                        }

                       //Enquanto  a situação do ciclo estiver abaixo de 5(povoamento) nehuma coleta será mostrada dou registrada.
                       while($analises_bioensaios->situacao < 5){

                            $data = $request->with(['coleta_24', 'coleta_48', 'coelta_72']);
                       }
                        
                            if(isset($data['sobrevivencia'])){
                                $data['sobrevivencia'] = '3';
                            }
                
               
         }  
            
}

