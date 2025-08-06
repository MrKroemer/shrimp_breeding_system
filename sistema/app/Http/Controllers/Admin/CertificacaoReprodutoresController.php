<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CertificacaoReprodutores;
use App\Models\Tanques;
use App\Models\Reprodutores;
use App\Models\ReprodutoresAnalises;
use App\Http\Requests\CertificacaoReprodutoresCreateFormRequest;
use App\Http\Requests\ReprodutoresCreateFormRequest;
use App\Http\Controllers\Util\DataPolisher;
use App\Models\ReprodutoresAnalisesAmostras;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class CertificacaoReprodutoresController extends Controller
{
    private $rowsPerPage = 10;
    
    public function listingCertificacaoReprodutores()
    {
        $tanques = Tanques::where('filial_id', session('_filial')->id)
        ->orderBy('sigla')
        ->get();
        
        $certificacao_reprodutores = CertificacaoReprodutores::listing($this->rowsPerPage, [
            'filial_id' => session('_filial')->id
        ]);

        return view('admin.certificacao_reprodutores.list')
        ->with('certificacao_reprodutores',  $certificacao_reprodutores)
        ->with('tanques', $tanques);
    }
    
    public function searchCertificacaoReprodutores(Request $request)
    {
        $tanques = Tanques::where('filial_id', session('_filial')->id)
        ->orderBy('sigla')
        ->get();
        
        $formData = $request->except(['_token']);
        $formData['filial_id'] = session('_filial')->id;

        $certificacao_reprodutores = CertificacaoReprodutores::search($formData, 30);

        return view('admin.certificacao_reprodutores.list')
        ->with('formData',             $formData)
        ->with('certificacao_reprodutores',  $certificacao_reprodutores)
        ->with('tanques', $tanques);
    }

    public function createCertificacaoReprodutores()
    {   
        $tanques = Tanques::where('filial_id', session('_filial')->id)
        ->orderBy('sigla')
        ->get();

        return view('admin.certificacao_reprodutores.create')
        ->with('tanques', $tanques);
    }

    public function storeCertificacaoReprodutores(CertificacaoReprodutoresCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $data['filial_id']  = session('_filial')->id;
        $data['usuario_id'] = auth()->user()->id;
        $data['situacao'] = 1; //Seta a situação como em análise

        $message = 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.';

        $insert = CertificacaoReprodutores::create($data);

        if ($insert) {
            return redirect()->route('admin.certificacao_reprodutores')
            ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->back()->withInput()
        ->with('error', $message);
    }

    public function editCertificacaoReprodutores(int $id)
    {
        $data = CertificacaoReprodutores::find($id);

        $tanques = Tanques::where('filial_id', session('_filial')->id)
        ->orderBy('sigla')
        ->get();

        $data_estresse     = Carbon::parse($data['data_estresse'])->format('d/m/Y');
        $data_coleta       = Carbon::parse($data['data_coleta'])->format('d/m/Y');
        $data_certificacao = Carbon::parse($data['data_certificacao'])->format('d/m/Y');
        
        
        return view('admin.certificacao_reprodutores.edit')
        ->with('id',             $id)
        ->with('numero_certificacao',   $data['numero_certificacao'])
        ->with('plantel',               $data['plantel'])
        ->with('familia',               $data['familia'])
        ->with('data_estresse',         $data_estresse)
        ->with('data_coleta',           $data_coleta)
        ->with('data_certificacao',     $data_certificacao)    
        ->with('tanque_origem_id',      $data['tanque_origem_id'])
        ->with('tanque_maturacao_id',   $data['tanque_maturacao_id'])
        ->with('tanques',               $tanques);
    }

    public function updateCertificacaoReprodutores(CertificacaoReprodutoresCreateFormRequest $request, int $id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $data['usuario_id'] = auth()->user()->id;

        $update = CertificacaoReprodutores::find($id);

        if ($update->update($data)) {
            return redirect()->back()
            ->with('success', 'Certificacao atualizada com sucesso!');
        }

        return redirect()->back()
        ->with('id', $id)
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }

    public function removeCertificacaoReprodutores(int $id)
    {
        $certificacao_reprodutores = CertificacaoReprodutores::find($id);

        //dd($certificacao_reprodutores->reprodutores->isEmpty());

        if (($certificacao_reprodutores->reprodutores->isEmpty()) && (! is_null($certificacao_reprodutores))) {

            if ($certificacao_reprodutores->delete()) {
                return redirect()->back()
                ->with('sucess', 'Certificação removida com sucesso!.');
            }

        }

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Entre em contato com o administrador.');
    }

    public function createReprodutores(int $id)
    {   
        $certificacao_reprodutores = CertificacaoReprodutores::find($id);

        $analises = ReprodutoresAnalises::where('ativo', 'ON')->get();

        $analises_amostras = ReprodutoresAnalisesAmostras::all();

        $reprodutores = Reprodutores::where('certificacao_reprodutores_id',$certificacao_reprodutores->id)
        ->orderBy('item_tubo')
        ->get();

        $reprodutores_ultimo = Reprodutores::where('certificacao_reprodutores_id',$certificacao_reprodutores->id)
        ->orderByDesc('item_tubo')
        ->first();


        if(is_null($reprodutores_ultimo))
        {
            $item_tubo = 0;

        }else{

            $item_tubo = $reprodutores_ultimo->item_tubo;

        }

        return view('admin.certificacao_reprodutores.reprodutores.create')
        ->with('certificacao_reprodutores', $certificacao_reprodutores)
        ->with('analises', $analises)
        ->with('analises_amostras', $analises_amostras)
        ->with('item_tubo', $item_tubo)
        ->with('reprodutores', $reprodutores);
    }

    public function searchReprodutores(Request $request, int $id)
    { 
        $formData = $request->except(['_token']);

        $certificacao_reprodutores = CertificacaoReprodutores::find($id);

        $analises = ReprodutoresAnalises::where('ativo', 'ON')->get();

        $analises_amostras = ReprodutoresAnalisesAmostras::all();

        $reprodutores = Reprodutores::search($formData)
        ->get();

        $reprodutores_ultimo = Reprodutores::where('certificacao_reprodutores_id',$certificacao_reprodutores->id)
        ->orderByDesc('item_tubo')
        ->first();

        return view('admin.certificacao_reprodutores.reprodutores.create')
        ->with('certificacao_reprodutores', $certificacao_reprodutores)
        ->with('analises', $analises)
        ->with('analises_amostras', $analises_amostras)
        ->with('item_tubo', $reprodutores_ultimo->item_tubo)
        ->with('reprodutores', $reprodutores);
    }

    public function storeReprodutores(ReprodutoresCreateFormRequest $request, int $id)
    {   
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $data['filial_id']  = session('_filial')->id;
        $data['usuario_id'] = auth()->user()->id;
        $data['certificacao_reprodutores_id'] = $id;
        
        
        $message = 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.';

        $insert = Reprodutores::create($data);

        if ($insert) {
            return redirect()->route('admin.certificacao_reprodutores.reprodutores.to_create', ['id' => $id])
            ->with('success', 'Registro salvo com sucesso!');
        }

        return redirect()->back()->withInput()
        ->with('error', $message);
    }

    public function updateReprodutores(Request $request, int $id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);


        foreach($data as $amostra => $analise){

            $amostra = explode('_',$amostra);

            $dados['valor'] = $analise;
            $dados['reprodutor_id'] = $amostra[0];
            $dados['reprodutor_analise_id'] = $amostra[1];
            $dados['usuario_id'] = auth()->user()->id;

            $teste = ReprodutoresAnalisesAmostras::where('reprodutor_id',$amostra[0])
                      ->where('reprodutor_analise_id',$amostra[1])->get();
            $update = ReprodutoresAnalisesAmostras::where('reprodutor_id',$amostra[0])
                      ->where('reprodutor_analise_id',$amostra[1]);
            

            if($teste->isNotEmpty()){
                $update->update($dados);
            }else{
                $update = ReprodutoresAnalisesAmostras::create($dados);
            }
        }
        
        return redirect()->back()
        ->with('success', 'Certificacao atualizada com sucesso!');
    }

    public function removeReprodutores(int $id)
    {
        $reprodutores = Reprodutores::find($id);
        
        if ($reprodutores->amostras->isNotEmpty()) 
        {

            $amostras = ReprodutoresAnalisesAmostras::where('reprodutor_id', $reprodutores->id);
            
            if (! $amostras->delete()) {
                return redirect()->back()
                ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
            }
        }

        if ($reprodutores->delete()) 
        {
            return redirect()->back()
            ->with('sucess', 'Reprodutor removido com sucesso!.');
        }

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }

    public function importCertificacaoReprodutores(Request $request, int $id)
    {

        $certificacao_reprodutores = CertificacaoReprodutores::find($id);

        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data, ['RM_EMPTY_STRING', 'TO_LOWERCASE']);
        
        unset($data['imagem_certificacao']);

        /**
         * Salva o arquivo em '/storage/app/public/images/users/<username>.png'
         * com link simbólico para '/public/storage/images/users/<username>.png'
         */
        if ($request->hasFile('imagem_certificacao') && $request->file('imagem_certificacao')->isValid()) {
            $data['imagem_certificacao'] = $request->imagem_certificacao->storeAs('images/certificacoes', "{$certificacao_reprodutores->id}.png");
        }

        ;
        if ($certificacao_reprodutores->update($data)) {
            return redirect()->route('admin.certificacao_reprodutores')
            ->with('success', 'Imagem cadastrada com sucesso!');
        }
    }

}