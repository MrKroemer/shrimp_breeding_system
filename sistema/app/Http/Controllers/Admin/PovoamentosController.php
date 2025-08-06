<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Povoamentos;
use App\Models\Ciclos;
use App\Models\Lotes;
use App\Models\LotesBiometrias;
use App\Models\EstoqueSaidas;
use App\Models\EstoqueSaidasPovoamentos;
use App\Http\Controllers\Util\DataPolisher;
use App\Http\Controllers\Util\RouteManipulator;
use App\Http\Requests\PovoamentosCreateFormRequest;
use App\Models\PovoamentosLotes;
use Carbon\Carbon;

class PovoamentosController extends Controller
{
    private $rowsPerPage = 10;

    public function listingPovoamentos()
    {
        $povoamentos = Povoamentos::listing($this->rowsPerPage, [
            'filial_id' => session('_filial')->id
        ]);

        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->whereBetween('situacao', [5, 8]) // Povoamento, Engorda, Despesca, Encerrado
        ->where('tipo', 1)                 // Camarão
        ->orderBy('numero', 'desc')
        ->get();

        return view('admin.povoamentos.list')
        ->with('ciclos',      $ciclos)
        ->with('povoamentos', $povoamentos);
    }

    public function searchPovoamentos(Request $request)
    {
        $formData = $request->except(['_token']);
        $formData['filial_id'] = session('_filial')->id;

        $povoamentos = Povoamentos::search($formData, $this->rowsPerPage);

        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->whereBetween('situacao', [5, 8]) // Povoamento, Engorda, Despesca, Encerrado
        ->where('tipo', 1)                 // Camarão
        ->orderBy('numero', 'desc')
        ->get();

        return view('admin.povoamentos.list')
        ->with('formData',    $formData)
        ->with('ciclos',      $ciclos)
        ->with('povoamentos', $povoamentos);
    }

    public function createPovoamentos()
    {
        $povoamentos = Povoamentos::where('filial_id', session('_filial')->id);

        $ciclos = Ciclos::where('filial_id', session('_filial')->id)
        ->whereNotIn('id', $povoamentos->groupBy('ciclo_id')->get(['ciclo_id']))
        ->where('situacao', 5) // Povoamento
        ->where('tipo', 1)     // Camarão
        ->orderBy('numero', 'desc')
        ->get();

        /* $lotes  = Lotes::where('filial_id',  session('_filial')->id)
        ->whereNotIn('id', $povoamentos->groupBy('lote_id')->get(['lote_id']))
        ->get(); */

        return view('admin.povoamentos.create')
        ->with('ciclos', $ciclos);
        // ->with('lotes',  $lotes);
    }

    public function storePovoamentos(PovoamentosCreateFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $ciclo = Ciclos::find($data['ciclo_id']);

        $message = 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.';

        if ($ciclo instanceof Ciclos) {

            $preparacao = $ciclo->preparacao;

            $data_inicio = Carbon::createFromFormat('d/m/Y H:i', $data['data_inicio'])->format('Y-m-d H:i');

            if ($preparacao->sucedeDataFim($data_inicio) && $ciclo->sucedeDataInicio($data_inicio)) {

                $data['filial_id']  = session('_filial')->id;
                $data['usuario_id'] = auth()->user()->id;

                $povoamento = Povoamentos::create($data);

                if ($povoamento instanceof Povoamentos) {

                    return redirect()->route('admin.povoamentos')
                    ->with('success', 'Registro salvo com sucesso!');

                }

            } else {

                $message = 'A data de início do povoamento deve suceder as datas de fim da preparação e do início do ciclo.';

            }

        }

        return redirect()->back()->withInput()
        ->with('error', $message);
    }

    public function removePovoamentos(int $id)
    {
        $povoamento = Povoamentos::find($id);

        if ($povoamento instanceof Povoamentos) {

            if ($povoamento->delete()) {
                return redirect()->route('admin.povoamentos')
                ->with('success', 'Registro excluido com sucesso!');
            }

        }

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }

    public function closePovoamentos(Request $request, int $id)
    {
        $data = $request->only(['data_fim']);

        $povoamento = Povoamentos::find($id);

        $redirect = redirect()->back();

        $msg_type = 'error';
        $message  = 'Registro de povoamento não encontrado.';

        if ($povoamento instanceof Povoamentos) {

            $ciclo = $povoamento->ciclo;

            $data_fim = DataPolisher::dateTimeRoundMinutes(5);

            if (isset($data['data_fim']) && ! empty($data['data_fim'])) {
                $data_fim = Carbon::createFromFormat('d/m/Y H:i', $data['data_fim'])->format('Y-m-d H:i');
            }

            $message  = 'A data de encerramento do povoamento não deve anteceder as datas de início dele mesmo e do ciclo.';

            if ($povoamento->sucedeDataInicio($data_fim) && $ciclo->sucedeDataInicio($data_fim)) {

                $result  = 0;

                foreach ($povoamento->lotes->where('situacao', 'N') as $povoamento_lote) {

                    $lote = $povoamento_lote->lote;

                    $message = 'Não foi possível encerrar! Um ou mais lotes não foram encontrados. Entre em contato com o suporte';

                    if ($lote instanceof Lotes) {

                        $produtos = [[
                            'quantidade' => $lote->quantidade,
                            'produto_id' => $lote->estoque_entrada->produto_id,
                        ]];

                        $saida = EstoqueSaidas::registrarSaida(EstoqueSaidasPovoamentos::class, $produtos, [
                            'tipo_destino'   => 2, // Saídas para povoamento
                            'data_movimento' => $povoamento_lote->data_vinculo('Y-m-d'),
                            'tanque_id'      => $ciclo->tanque_id,
                            'ciclo_id'       => $ciclo->id,
                            'lote_id'        => $lote->id,
                            'povoamento_id'  => $povoamento->id,
                        ]);

                        $result  = key($saida);
                        $message = current($saida);

                        $povoamento_lote->usuario_id = auth()->user()->id;

                        if ($result == 1) { // Registro de saída bem sucedido

                            $msg_type = 'success';

                            $povoamento_lote->situacao = 'V';
                            $povoamento_lote->save();

                            continue;

                        }

                    }

                    $msg_type = 'error';
                        
                    $povoamento_lote->situacao = 'P';
                    $povoamento_lote->save();

                    break;

                }

                if ($result == 1 && $ciclo->situacao == 5) {

                    $povoamento->data_fim = $data_fim;
                    $povoamento->save();

                    $ciclo->situacao = 6; // Engorda
                    $ciclo->save();

                }

            }

        }

        return $redirect->with($msg_type, $message);
    }

    public function editPovoamentosLotes(int $id)
    {
        $povoamento = Povoamentos::find($id);

        $lotes = Lotes::where('filial_id',  session('_filial')->id)
        ->whereNotIn('id', PovoamentosLotes::all()->pluck('lote_id'))
        ->get();

        return view('admin.povoamentos.lotes.edit')
        ->with('povoamento', $povoamento)
        ->with('lotes',      $lotes);
    }

    public function updatePovoamentosLotes(Request $request, int $povoamento_id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $povoamento = Povoamentos::find($povoamento_id);

        $redirect = redirect()->back();

        $msg_type = 'error';
        $message  = 'Registro de povoamento não encontrado.';

        if ($povoamento instanceof Povoamentos) {

            $ciclo = $povoamento->ciclo;

            $message = 'O ciclo não foi encontrado.'; 

            if ($ciclo instanceof Ciclos) {

                $lote = Lotes::find($data['lote_id']);

                $message = 'O lote não foi encontrado.';

                if ($lote instanceof Lotes) {

                    $lote_biometria = $lote->lote_biometria;

                    $message = 'Para iniciar um cultivo com este lote, é necessário inserir as informações biométricas.';

                    if ($lote_biometria instanceof LotesBiometrias) {

                        $preparacao = $ciclo->preparacao;

                        $data_inicio = $povoamento->data_inicio;

                        $message = 'A data de início do povoamento deve suceder as datas de fim da preparação e do início do ciclo.';

                        if ($preparacao->sucedeDataFim($data_inicio) && $ciclo->sucedeDataInicio($data_inicio)) {

                            $data['povoamento_id'] = $povoamento_id;
                            $data['usuario_id'] = auth()->user()->id;
                            $data['data_vinculo']  = date('Y-m-d');

                            $povoamento_lote = PovoamentosLotes::create($data);

                            $message = 'O lote não foi associado ao povoamento.';

                            if ($povoamento_lote instanceof PovoamentosLotes) {
                                return $redirect->with('success', 'Registro salvo com sucesso!');
                            }

                        }

                    }

                }

            }

        }

        return $redirect->withInput()->with($msg_type, $message);
    }

    public function removePovoamentosLotes(int $povoamento_id, int $id)
    {
        $lote = PovoamentosLotes::find($id);

        if ($lote instanceof PovoamentosLotes) {

            if ($lote->delete()) {
                return redirect()->back()
                ->with('success', 'Registro excluido com sucesso!');
            }

        }

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir o registro! Tente novamente.');
    }

    public function redirectToCreateLotes(Request $request)
    {
        $povoamento_id = $request->get('povoamento_id');

        // Redirecionamento para o módulo de LABORATÓRIO
        return RouteManipulator::redirectTo($request, 3, 'admin.lotes.to_create', ['redirectBack' => 'yes', 'povoamento_id' => $povoamento_id]);
    }
}
