<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tanques;
use App\Models\AnalisesLaboratoriaisNew;
use App\Models\AnalisesCalcicasNew;
use App\Http\Requests\AnalisesLaboratoriaisCreateFormRequest;
use App\Http\Controllers\Util\DataPolisher;

class AnalisesCalcicasAmostrasController extends Controller
{
    private $rowsPerPage = 10;
    private $analisesLaboratoriaisTipos = [4];

    public function create(int $analise_laboratorial_id)
    {
        $analise_laboratorial = AnalisesLaboratoriaisNew::find($analise_laboratorial_id);

        $tanques = $analise_laboratorial->tanque_tipo->tanques
        ->where('filial_id', $analise_laboratorial->filial_id)
        ->where('situacao','ON')
        ->sortBy('nome');

        if ($analise_laboratorial->calcicas_por_tanque->count() > 0) {
            $tanques = $analise_laboratorial->calcicas_por_tanque;
        }

        return view('admin.analises_calcicas.amostras.create')
        ->with('analise_laboratorial', $analise_laboratorial)
        ->with('tanques',              $tanques);
    }

    public function store(Request $request, int $analise_laboratorial_id)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data, ['EMPTY_TO_NULL']);

        $analise_laboratorial = AnalisesLaboratoriaisNew::find($analise_laboratorial_id);

        $tanques = $analise_laboratorial->tanque_tipo->tanques
        ->where('filial_id', $analise_laboratorial->filial_id)
        ->where('situacao','ON')
        ->sortBy('nome');

        $amostras = $analise_laboratorial->calcicas;

        $registros = [];

        foreach ($tanques as $tanque) {

            $dados = [
                'dureza'    => null,
                'moleculas' => null,
            ];

            foreach ($dados as $campo => $valor) {

                if (isset($data["{$campo}_{$tanque->id}"])) {
                    $dados[$campo] = $data["{$campo}_{$tanque->id}"];
                }

            }

            if ($tanque instanceof Tanques) {

                $amostra = $amostras->where('tanque_id', $tanque->id)->first();

                if ($amostra instanceof AnalisesCalcicasNew) {

                    $salvo = $amostra->update($dados);

                    $registros[] = $salvo ? 1 : 0;

                } else {

                    $dados['tanque_id']               = $tanque->id;
                    $dados['analise_laboratorial_id'] = $analise_laboratorial->id;

                    $salvo = AnalisesCalcicasNew::create($dados);

                    $registros[] = ($salvo instanceof AnalisesCalcicasNew) ? 1 : 0;

                }

            }

        }

        $erros = count($registros) - array_sum($registros);

        if ($erros == 0) {
            return redirect()->route('admin.analises_calcicas.amostras.to_create', [
                'analise_laboratorial_id' => $analise_laboratorial_id
            ])
            ->with('success', 'Registros salvos com sucesso!');
        }

        return redirect()->back()->withInput()
        ->with('error', "Ocorreu um erro durante a tentativa de salvar {$erros} dos registros! Tente novamente.");
    }

    public function remove(int $analise_laboratorial_id, int $id)
    {
        $amostra = AnalisesCalcicasNew::find($id);

        $dados = [
            'dureza'    => null,
            'moleculas' => null,
        ];

        if ($amostra->update($dados)) {
            return redirect()->back()
            ->with('success', 'Registros excluidos com sucesso!');
        }

        return redirect()->back()
        ->with('error', 'Ocorreu um erro durante a tentativa de excluir os registros! Tente novamente.');
    }
}
