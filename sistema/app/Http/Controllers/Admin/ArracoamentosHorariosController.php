<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VwEstoqueDisponivel;
use App\Models\Arracoamentos;
use App\Models\ArracoamentosHorarios;
use App\Models\ArracoamentosAplicacoes;
use App\Models\ArracoamentosAplicacoesTipos;
use App\Models\ArracoamentosAplicacoesProdutos;
use App\Models\ArracoamentosAplicacoesReceitas;
use App\Http\Controllers\Admin\ArracoamentosAplicacoesController;
use App\Models\ArracoamentosClimas;
use App\Models\ArracoamentosPerfis;
use App\Models\ArracoamentosReferenciais;
use App\Models\ReceitasLaboratoriais;
use App\Models\VwAnalisesPresuntivas;
use App\Http\Requests\ArracoamentosHorariosCreateFormRequest;
use App\Http\Controllers\Util\DataPolisher;

class ArracoamentosHorariosController extends Controller
{
    private $rowsPerPage = 10;

    public function listingArracoamentosHorarios(int $arracoamento_id)
    {
        $arracoamento = Arracoamentos::find($arracoamento_id);

        $arracoamentos_horarios = ArracoamentosHorarios::listing($this->rowsPerPage, [
            'arracoamento_id' => $arracoamento_id,
        ]);

        $ultimos_arracoamentos = Arracoamentos::where('ciclo_id', $arracoamento->ciclo_id)
        ->where('data_aplicacao', '<', $arracoamento->data_aplicacao)
        ->orderBy('data_aplicacao', 'desc')
        ->limit(6)
        ->get();

        $arracoamentos_perfis = ArracoamentosPerfis::ativos()
        ->orderBy('nome')
        ->get();

        $receitas_laboratoriais = ReceitasLaboratoriais::orderBy('nome')->get();

        $produtos = VwEstoqueDisponivel::produtosAtivos(session('_filial')->id)
        ->where('em_estoque', '>', 0)
        ->get();

        $arracoamentos_aplicacoes_tipos = ArracoamentosAplicacoesTipos::orderBy('nome')->get();
        $arracoamentos_aplicacoes_itens = new ArracoamentosAplicacoesController;

        $dias_cultivo = $arracoamento->ciclo->dias_cultivo($arracoamento->data_aplicacao);
        $biomassa     = $arracoamento->ciclo->biomassa($arracoamento->data_aplicacao);

        $arracoamento_clima = ArracoamentosClimas::where('situacao', 'ON')->first();

        $arracoamento_referencial = $arracoamento_clima->arracoamentos_referenciais->where('dias_cultivo', $dias_cultivo)->first();

        $racao_estimada_qtd = 0;

        if ($arracoamento_referencial instanceof ArracoamentosReferenciais) {

            if (is_null($biomassa)) {

                $poslarvas = $arracoamento->ciclo->povoamento->qtd_poslarvas();
                $sobrevivencia = 100;
                $peso_medio  = 0.008;

                $biomassa = (($sobrevivencia / 100) * ($poslarvas / 1000)) * $peso_medio;

            }

            $racao_estimada_qtd = ($arracoamento_referencial->porcentagem / 100) * $biomassa;

        }

        $add_aditivo = false;

        $analise_presuntiva = $arracoamento->ciclo->analises_presuntivas
        ->sortByDesc('analise_presuntiva_id')
        ->first();

        if ($analise_presuntiva instanceof VwAnalisesPresuntivas) {

            if (mb_strtoupper($analise_presuntiva->aditivo) == 'SIM') {
                $add_aditivo = true;
            }

        }

        return view('admin.arracoamentos_horarios.list')
        ->with('arracoamento',                   $arracoamento)
        ->with('arracoamentos_horarios',         $arracoamentos_horarios)
        ->with('arracoamentos_perfis',           $arracoamentos_perfis)
        ->with('ultimos_arracoamentos',          $ultimos_arracoamentos)
        ->with('receitas_laboratoriais',         $receitas_laboratoriais)
        ->with('produtos',                       $produtos)
        ->with('arracoamentos_aplicacoes_tipos', $arracoamentos_aplicacoes_tipos)
        ->with('arracoamentos_aplicacoes_itens', $arracoamentos_aplicacoes_itens)
        ->with('racao_estimada_qtd',             $racao_estimada_qtd)
        ->with('add_aditivo',                    $add_aditivo);
    }

    public function storeArracoamentosHorarios(int $arracoamento_id)
    {
        $store = static::addArracoamentosHorarios($arracoamento_id);

        if (! empty($store)) {
            return redirect()->route('admin.arracoamentos.arracoamentos_horarios', ['arracoamento_id' => $arracoamento_id,]);
        }

        return redirect()->route('admin.arracoamentos.arracoamentos_horarios', ['arracoamento_id' => $arracoamento_id])
        ->with('error', 'Ocorreu um erro ao salvar o horário. Tente novamente!');
    }

    public function generateArracoamentosHorarios(ArracoamentosHorariosCreateFormRequest $request, int $arracoamento_id)
    {
        $formData = $request->except(['_token']);

        $formData = DataPolisher::toPolish($formData, ['EMPTY_TO_ZERO']);

        $arracoamento = Arracoamentos::find($arracoamento_id);

        $arracoamento_anterior = Arracoamentos::where('ciclo_id', $arracoamento->ciclo_id)
        ->where('data_aplicacao', '<', $arracoamento->data_aplicacao)
        ->orderBy('data_aplicacao', 'desc')
        ->first();

        $racoes       = [];
        $alimentacoes = [];

        if ($formData['perfil'] > 0) {

            $dias_cultivo = $arracoamento->ciclo->dias_cultivo($arracoamento->data_aplicacao);

            $arracoamento_perfil = ArracoamentosPerfis::find($formData['perfil']);

            $esquemas = $arracoamento_perfil->arracoamentos_esquemas
            ->where('dia_inicial', '<=', $dias_cultivo)
            ->where('dia_final', '>=', $dias_cultivo);

            if ($esquemas->count() == 1) {

                $i = 1;

                foreach ($esquemas->first()->racoes as $racao) {

                    $racoes[$i]['produto_id']  = $racao->produto_id;
                    $racoes[$i]['porcentagem'] = $racao->porcentagem;

                    $i ++;

                }

                $i = 1;

                foreach ($esquemas->first()->alimentacoes->sortBy('alimentacao_inicial') as $alimentacao) {

                    $alimentacoes[$i]['horarios'] = $alimentacao->alimentacao_final - $alimentacao->alimentacao_inicial + 1;
                    $alimentacoes[$i]['porcentagem']  = $alimentacao->porcentagem / $alimentacoes[$i]['horarios'];

                    $i ++;

                }

            }

        } else {

            $porRacao       = 0;
            $porAlimentacao = 0;
            $qtdAlimentacao = 0;

            foreach ($formData as $key => $value) {

                $racao_utilizada   = strrpos($key, 'racao_utilizada_');
                $racao_porcentagem = strrpos($key, 'racao_porcentagem_');
                $alime_quantidade  = strrpos($key, 'alime_quantidade_');
                $alime_porcentagem = strrpos($key, 'alime_porcentagem_');

                if (! ($racao_utilizada === false)) {
                    $racoes[substr($key, -1)]['produto_id'] = $value;
                }

                if (! ($racao_porcentagem === false)) {
                    $racoes[substr($key, -1)]['porcentagem'] = $value;
                    $porRacao += $value;
                }

                if (! ($alime_quantidade === false)) {
                    $alimentacoes[substr($key, -1)]['horarios'] = $value;
                    $qtdAlimentacao = $value;
                }

                if (! ($alime_porcentagem === false)) {
                    $alimentacoes[substr($key, -1)]['porcentagem'] = $value;
                    $porAlimentacao += ($value * $qtdAlimentacao);
                }

            }

            if ($porRacao != 100 || $porAlimentacao != 100) {
                return redirect()->route('admin.arracoamentos.arracoamentos_horarios', ['arracoamento_id' => $arracoamento_id])
                ->with('warning', "Os percentuais informados devem totalizar 100%, tanto para rações quanto para alimentações");
            }

        }

        $formData['add_racao_borda'] = (isset($formData['add_racao_borda']) && $formData['add_racao_borda'] == 'on');
        $formData['add_probiotico']  = (isset($formData['add_probiotico'])  && $formData['add_probiotico']  == 'on');
        $formData['add_aditivo']     = (isset($formData['add_aditivo'])     && $formData['add_aditivo']     == 'on');
        $formData['add_vitamina']    = (isset($formData['add_vitamina'])    && $formData['add_vitamina']    == 'on');

        if ($formData['add_racao_borda'] || $formData['add_probiotico'] || $formData['add_aditivo'] || $formData['add_vitamina']) {

            $message = '';

            if ($formData['add_racao_borda'] && (! isset($formData['racao_borda_id']) || empty($formData['racao_borda_id']))) {
                $message .= '[RAÇÃO PARA AS BORDAS] ';
            }
    
            if ($formData['add_probiotico'] && (! isset($formData['probiotico_id']) || empty($formData['probiotico_id']))) {
                $message .= '[RECEITA DE PROBIÓTICO] ';
            }
    
            if ($formData['add_aditivo'] && (! isset($formData['aditivo_id']) || empty($formData['aditivo_id']))) {
                $message .= '[RECEITA DE ADITIVO] ';
            }
    
            if ($formData['add_vitamina'] && (! isset($formData['vitamina_id']) || empty($formData['vitamina_id']))) {
                $message .= '[RECEITA DE VITAMINA] ';
            }

            if (! empty($message)) {
                return redirect()->back()->with('warning', 'Os seguintes itens devem ser informados: ' . $message);
            }

        }

        if ($this->generate($arracoamento, $alimentacoes, $racoes, $formData)) {

            $redirect = redirect()->route('admin.arracoamentos.arracoamentos_horarios', ['arracoamento_id' => $arracoamento_id]);
            
            if (! empty($formData['observacoes'])) {

                $arracoamento->observacoes = $formData['observacoes'];

                if (! $arracoamento->save()) {
                    $redirect->with('warning', 'As observações não foram salvas');
                }

            }

            if ($arracoamento_anterior instanceof Arracoamentos) {

                if (($formData['racao_estimada_qtd'] - 70) > $arracoamento_anterior->qtdTotalRacaoProgramada()) {
                    $redirect->with('warning', 'Aviso! O total de ração programada, excedeu o limite de aumento diário de 70Kg.');
                }

            }

            return $redirect;

        }

        return redirect()->route('admin.arracoamentos.arracoamentos_horarios', ['arracoamento_id' => $arracoamento_id])
        ->with('error', 'Ocorreu um erro ao salvar o horário. Tente novamente!');
    }

    private function generate(Arracoamentos $arracoamento, array $alimentacoes, array $racoes, array $formData)
    {
        $arracoamento->removeAplicacoes();

        $dias_cultivo = $arracoamento->ciclo->dias_cultivo($arracoamento->data_aplicacao);

        $acrescimos = [
            'racao_borda' => [
                'adicionar'  => $formData['add_racao_borda'],
                'aplicacoes' => $formData['racao_borda_num'],
                'horarios'   => $formData['racao_borda_hor'],
                'produto_id' => $formData['racao_borda_id'],
                'quantidade' => $formData['racao_borda_qtd'],
                'aplicacao_tipo_id' => 1, // RAÇÂO
            ],
            'probiotico' => [
                'adicionar'  => $formData['add_probiotico'],
                'aplicacoes' => $formData['probiotico_num'],
                'horarios'   => $formData['probiotico_hor'],
                'receita_id' => $formData['probiotico_id'],
                'aplicacao_tipo_id' => 2, // PROBIÓTICO
            ],
            'aditivo' => [
                'adicionar'  => $formData['add_aditivo'],
                'aplicacoes' => $formData['aditivo_num'],
                'horarios'   => $formData['aditivo_hor'],
                'receita_id' => $formData['aditivo_id'],
                'aplicacao_tipo_id' => 3, // ADITIVO
            ],
            'vitamina' => [
                'adicionar'  => $formData['add_vitamina'],
                'aplicacoes' => $formData['vitamina_num'],
                'horarios'   => $formData['vitamina_hor'],
                'receita_id' => $formData['vitamina_id'],
                'aplicacao_tipo_id' => 4, // VITAMINA
            ],
        ];

        if ($formData['add_racao_borda'] && $formData['racao_borda_qtd'] > 0) {
            $formData['racao_estimada_qtd'] -= $formData['racao_borda_qtd'];
        }

        $racao_total = 0;

        foreach ($alimentacoes as $alimentacao) {

            $alimentacao_horarios    = $alimentacao['horarios'];
            $alimentacao_porcentagem = $alimentacao['porcentagem'];

            for ($horario = 1; $horario <= $alimentacao_horarios; $horario ++) {

                $arracoamento_horario = static::addArracoamentosHorarios($arracoamento->id);

                if (empty($arracoamento_horario)) {
                    return false;
                }

                $racao_horario = 0;

                foreach ($racoes as $racao) {
    
                    $racao_id          = $racao['produto_id'];
                    $racao_porcentagem = $racao['porcentagem'];
    
                    $racao_qtd = ($formData['racao_estimada_qtd'] * ($alimentacao_porcentagem / 100)) * ($racao_porcentagem / 100);

                    $racao_qtd = (isset($formData['fracionar']) && $formData['fracionar'] == 'on') ? round($racao_qtd, 1) : round($racao_qtd);
    
                    $aplicacao = ArracoamentosAplicacoes::create([
                        'arracoamento_id'         => $arracoamento->id, 
                        'arracoamento_horario_id' => $arracoamento_horario->id, 
                        'arracoamento_aplicacao_tipo_id' => 1, // RAÇÂO
                    ]);
    
                    if (empty($aplicacao)) {
                        return false;
                    }
    
                    $aplicacao_produto = ArracoamentosAplicacoesProdutos::create([
                        'quantidade' => $racao_qtd,
                        'produto_id' => $racao_id,
                        'arracoamento_aplicacao_id' => $aplicacao->id,
                        'usuario_id' => auth()->user()->id,
                    ]);
    
                    if (empty($aplicacao_produto)) {
                        return false;
                    }
    
                    $racao_horario += $racao_qtd;
    
                }
    
                foreach ($acrescimos as $acrescimo) {
    
                    $horarios_apontados = explode(' ', $acrescimo['horarios']);
    
                    foreach ($horarios_apontados as $horario_apontado) {
    
                        if (! is_numeric($horario_apontado)) {
                            continue;
                        }
    
                        if (
                            $acrescimo['adicionar'] && (
                                $horario_apontado == $arracoamento_horario->ordinal || (
                                    $horario_apontado == 0 && $acrescimo['aplicacoes'] >= $arracoamento_horario->ordinal
                                )
                            )
                        ) {
    
                            $aplicacao = ArracoamentosAplicacoes::create([
                                'arracoamento_id'         => $arracoamento->id,
                                'arracoamento_horario_id' => $arracoamento_horario->id,
                                'arracoamento_aplicacao_tipo_id' => $acrescimo['aplicacao_tipo_id'],
                            ]);
    
                            if (! empty($aplicacao)) {
    
                                if ($acrescimo['aplicacao_tipo_id'] == 1 && $acrescimo['quantidade'] > 0) { // RAÇÃO NAS BORDA
    
                                    $racao_qtd = $acrescimo['quantidade'] / (($acrescimo['aplicacoes'] > 0) ? $acrescimo['aplicacoes'] : count($horarios_apontados));

                                    $racao_qtd = (isset($formData['fracionar']) && $formData['fracionar'] == 'on') ? round($racao_qtd, 1) : round($racao_qtd);

                                    $aplicacao_produto = ArracoamentosAplicacoesProdutos::create([
                                        'quantidade' => $racao_qtd,
                                        'produto_id' => $acrescimo['produto_id'],
                                        'arracoamento_aplicacao_id' => $aplicacao->id,
                                        'usuario_id' => auth()->user()->id,
                                    ]);
    
                                    if (empty($aplicacao_produto)) {
                                        return false;
                                    }
    
                                    $racao_horario += $racao_qtd;
    
                                } else if ($acrescimo['aplicacao_tipo_id'] != 1) { // DEMAIS INSUMOS
    
                                    $receita_laboratorial = ReceitasLaboratoriais::find($acrescimo['receita_id']);
    
                                    if (empty($receita_laboratorial)) {
                                        return false;
                                    }
    
                                    $periodo_utilizado = $receita_laboratorial->receitaPorDiasDeCultivo($dias_cultivo);
    
                                    $receita_qtd = $periodo_utilizado ? ($racao_horario * $periodo_utilizado->quantidade) : 0;
    
                                    $aplicacao_receita = ArracoamentosAplicacoesReceitas::create([
                                        'quantidade' => $this->roundOrTruncate($receita_qtd),
                                        'receita_laboratorial_id'   => $receita_laboratorial->id,
                                        'arracoamento_aplicacao_id' => $aplicacao->id,
                                        'usuario_id' => auth()->user()->id,
                                    ]);
    
                                    if (empty($aplicacao_receita)) {
                                        return false;
                                    }
    
                                }
    
                            }
    
                        }
    
                    }
    
                }

                $racao_total += $racao_horario;

            }

        }

        $racao_estimada = $formData['racao_estimada_qtd'] + $formData['racao_borda_qtd'];

        if ($racao_total != $racao_estimada) {

            $ultima_aplicacao = $arracoamento->aplicacoes
            ->where('arracoamento_aplicacao_tipo_id', 1) // RAÇÃO
            ->sortByDesc('id')
            ->first();

            if ($ultima_aplicacao instanceof ArracoamentosAplicacoes) {

                $aplicacao_racao = $ultima_aplicacao->arracoamento_aplicacao_produto;

                $aplicacao_racao->quantidade += ($racao_estimada - $racao_total);

                $aplicacao_racao->save();

            }

        }

        return true;
    }

    public function removeArracoamentosHorarios(int $arracoamento_id, int $id)
    {
        $data = ArracoamentosHorarios::find($id);

        if ($data->arracoamento->situacao == 'N' && $data->aplicacoes->count() == 0) {
            $delete = $data->delete();
        }

        if (! empty($delete)) {
            return redirect()->route('admin.arracoamentos.arracoamentos_horarios', ['arracoamento_id' => $arracoamento_id]);
        }

        return redirect()->route('admin.arracoamentos.arracoamentos_horarios', ['arracoamento_id' => $arracoamento_id])
        ->with('error', 'Ocorreu um erro ao excluir o horário. Tente novamente!');
    }

    public function addArracoamentosHorarios(int $arracoamento_id)
    {
        $arracoamentos_horarios = ArracoamentosHorarios::where('arracoamento_id', $arracoamento_id)
        ->orderBy('ordinal')->get();

        $ordinal = 1;

        if ($arracoamentos_horarios->isNotEmpty()) {

            foreach ($arracoamentos_horarios as $item) {

                if ($item->ordinal != $ordinal) {
                    break;
                }

                $ordinal ++;

            }

        }
        
        $data['ordinal']         = $ordinal;
        $data['arracoamento_id'] = $arracoamento_id;

        return ArracoamentosHorarios::create($data);
    }

    private function roundOrTruncate(float $number) : float
    {
        $number = round($number, 2);

        $end = substr($number, -1);

        switch ($end) {
            case 1: case 6: $number -= 0.01; break;
            case 2: case 7: $number -= 0.02; break;
            case 3: case 8: $number -= 0.03; break;
            case 4: case 9: $number += 0.01; break;
        }

        $number = explode('.', $number);

        $number[1] = isset($number[1]) ? $number[1] : 0;

        if ($number[0] > 0) {

            if ($number[1] >= 60) {
                $number[0] += 1;
            }

            $number[1] = 0;

        } else {

            $end = substr($number[1], -1);

            if ($end <= 5) {
                $number[1] = substr($number[1], 0, 1);
            } else {
                $number[1] = round($number[1], 1);
            }

            $number[0] = 0;

        }

        $number = (float) "{$number[0]}.{$number[1]}";

        return $number;
    }
}
