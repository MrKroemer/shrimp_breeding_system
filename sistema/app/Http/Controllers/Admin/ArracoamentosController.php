<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setores;
use App\Models\Arracoamentos;
use App\Models\VwArracoamentos;
use App\Models\Ciclos;
use App\Models\Tanques;
use App\Models\Povoamentos;
use App\Http\Requests\ArracoamentosCreateFormRequest;
use App\Http\Controllers\Util\DataPolisher;
use Carbon\Carbon;

class ArracoamentosController extends Controller
{
    private $rowsPerPage = 50;

    public function listingArracoamentos()
    {
        $arracoamentos = VwArracoamentos::listing($this->rowsPerPage, [
            'filial_id'      => session('_filial')->id,
            'data_aplicacao' => date('Y-m-d'),
        ]);

        $setores = Setores::whereIn('id', 
            Tanques::where('filial_id', session('_filial')->id)
            ->where('tanque_tipo_id', 1)   // Viveiro de camarões
            ->orWhere('tanque_tipo_id', 6) // Viveiro de peixes
            ->groupBy('setor_id')
            ->get(['setor_id']))
        ->orderBy('nome', 'desc')
        ->get();

        return view('admin.arracoamentos.list')
        ->with('arracoamentos', $arracoamentos)
        ->with('setores',       $setores);
    }

    public function searchArracoamentos(Request $request)
    {
        $formData = $request->except(['_token']);
        $formData = DataPolisher::toPolish($formData); // TODO: adicionar toPolish em todas as funções search do sistema
        
        $formData['filial_id'] = session('_filial')->id;

        $arracoamentos = VwArracoamentos::search($formData, $this->rowsPerPage);

        $setores = Setores::whereIn('id', 
            Tanques::where('filial_id', session('_filial')->id)
            ->where('tanque_tipo_id', 1)   // Viveiro de camarões
            ->orWhere('tanque_tipo_id', 6) // Viveiro de peixes
            ->groupBy('setor_id')
            ->get(['setor_id']))
        ->orderBy('nome', 'desc')
        ->get();

        return view('admin.arracoamentos.list')
        ->with('formData',      $formData)
        ->with('arracoamentos', $arracoamentos)
        ->with('setores',       $setores);
    }

    public function createArracoamentos()
    {
        $setores = Setores::whereIn('id', 
            Tanques::tanquesAtivos()
            ->where('tanque_tipo_id', 1)   // Viveiro de camarões
            ->orWhere('tanque_tipo_id', 6) // Viveiro de peixes
            ->groupBy('setor_id')
            ->get(['setor_id']))
        ->get();

        $ciclos = new CiclosController;

        return view('admin.arracoamentos.create')
        ->with('setores', $setores)
        ->with('ciclos',  $ciclos);
    }

    public function storeArracoamentos(ArracoamentosCreateFormRequest $request)
    {
        $data = $request->except(['_token']);
        
        $data = DataPolisher::toPolish($data);

        $message = '';

        $redirect = redirect()->route('admin.arracoamentos.to_search', [
            'setor_id'       => $data['setor_id'],
            'data_aplicacao' => $data['data_aplicacao'],
        ]);

        foreach ($data as $key => $value) {

            if (strpos($key, 'ciclo_') === 0) {

                $ciclo_id = str_replace('ciclo_', '', $key);

                $ciclo = Ciclos::find($ciclo_id);

                if ($ciclo instanceof Ciclos) {

                    $povoamento = $ciclo->povoamento;

                    if (! $povoamento instanceof Povoamentos) {
                        continue;
                    }

                    $data_aplicacao = Carbon::createFromFormat('d/m/Y', $data['data_aplicacao'])->format('Y-m-d 23:59:59');

                    if ($povoamento->sucedeDataFim($data_aplicacao) && $ciclo->sucedeDataInicio($data_aplicacao)) {

                        $data['ciclo_id']   = $ciclo->id;
                        $data['tanque_id']  = $ciclo->tanque_id;
                        $data['filial_id']  = session('_filial')->id;
                        $data['usuario_id'] = auth()->user()->id;

                        $data_aplicacao = Carbon::createFromFormat('d/m/Y', $data['data_aplicacao']);

                        $arracoamento = Arracoamentos::where('data_aplicacao', $data_aplicacao->format('Y-m-d'))
                        ->where('tanque_id', $data['tanque_id'])
                        ->where('ciclo_id',  $data['ciclo_id'])
                        ->first();

                        if ($arracoamento instanceof Arracoamentos) {
                            continue;
                        }

                        if ($ciclo->situacao == 6) { // Engorda
                            
                            $arracoamento = Arracoamentos::create($data);

                            if ($arracoamento instanceof Arracoamentos) {
                                continue;
                            }

                            $message .= 'Ocorreu um erro ao tentar registrar o arracoamento para o tanque ' . $ciclo->tanque->sigla . ' (Ciclo Nº ' . $ciclo->numero . ').\\n';
                            $redirect->with('error', $message);

                            break;
                        }

                        $message .= 'O tanque ' . $ciclo->tanque->sigla . ' (Ciclo Nº ' . $ciclo->numero . '), não está em perodo de engorda.\\n';
                        $redirect->with('error', $message);

                        break;
                    }

                    $message .= 'A data de arraçoamento para o tanque ' . $ciclo->tanque->sigla . ' (Ciclo Nº ' . $ciclo->numero . '), deve suceder a data de fim do povoamento e início do ciclo.\\n';
                    $redirect->with('error', $message);

                    break;
                }

                $message .= 'O ciclo Nº ' . $ciclo_id . ' não foi encontrado.\\n';
                $redirect->with('error', $message);

                break;

            }

        }

        return $redirect;
    }

    public function updateArracoamentos(Request $request, int $id)
    {
        $data = $request->only(['mortalidade']);

        $data['usuario_id'] = auth()->user()->id;

        $arracoamento = Arracoamentos::find($id);

        $redirect = redirect()->back();

        if ($arracoamento instanceof Arracoamentos) {

            if ($arracoamento->update($data)) {
                return $redirect;
            }

        }

        return $redirect->with('error', 'Não foi possível registrar a mortalidade para o arraçoamento! Tente novamente.');
    }

    public function removeArracoamentos(int $id)
    {
        $arracoamento = Arracoamentos::find($id);

        $redirectParam = [
            'data_aplicacao' => $arracoamento->data_aplicacao(),
            'setor_id'       => $arracoamento->tanque->setor_id,
        ];

        if ($arracoamento->situacao == 'N' && $arracoamento->horarios->isNotEmpty()) {
            
            $arracoamentoHorarios = $arracoamento->horarios;
            
            foreach ($arracoamentoHorarios as $arracoamentoHorario) {

                $arracoamentoAplicacoes = $arracoamentoHorario->aplicacoes;
                
                foreach ($arracoamentoAplicacoes as $arracoamentoAplicacao){
                    
                    if (! empty($arracoamentoAplicacao->arracoamento_aplicacao_produto)) {

                        if (! $arracoamentoAplicacao->arracoamento_aplicacao_produto->delete()) {
                            return redirect()->route('admin.arracoamentos.to_search', $redirectParam)
                            ->with('error', 'Ocorreu um erro ao tentar excluir os produtos do arracoamento!');
                        }

                    }

                    if (! empty($arracoamentoAplicacao->arracoamento_aplicacao_receita)) {
                        
                        if (! $arracoamentoAplicacao->arracoamento_aplicacao_receita->delete()) {
                            return redirect()->route('admin.arracoamentos.to_search', $redirectParam)
                            ->with('error', 'Ocorreu um erro ao tentar excluir as receitas do arraçoamento!');
                        }

                    }

                    if (! $arracoamentoAplicacao->delete()) {
                        return redirect()->route('admin.arracoamentos.to_search', $redirectParam)
                        ->with('error', 'Ocorreu um erro ao tentar excluir a aplicação');
                    }
                
                }

                if (! $arracoamentoHorario->delete()) {
                    return redirect()->route('admin.arracoamentos.to_search', $redirectParam)
                    ->with('error', 'Ocorreu algum problema ao excluir os Horários!');
                }

            }

        }

        if ($arracoamento->delete()) {
            return redirect()->route('admin.arracoamentos.to_search', $redirectParam)
            ->with('success', 'Registro excluido com sucesso!');
        }

        return redirect()->route('admin.arracoamentos.to_search', $redirectParam)
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }
}
