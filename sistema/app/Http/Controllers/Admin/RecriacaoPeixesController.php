<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RecriacaoPeixes;
use App\Models\Tanques;
use App\Http\Requests\RecriacaoPeixesFormRequest;
use App\Http\Controllers\Util\DataPolisher;

class RecriacaoPeixesController extends Controller
{
    private $rowsPerPage = 10;

    public function listing()
    {
        $recriacoes = RecriacaoPeixes::listing($this->rowsPerPage);

        $tanques = Tanques::where('filial_id', session('_filial')->id)
        ->where('tanque_tipo_id', 6) // Viveiro de peixes
        ->orderBy('sigla')
        ->get();

        return view('admin.recriacao_peixes.list')
        ->with('recriacoes', $recriacoes)
        ->with('tanques',    $tanques);
    }

    public function search(Request $request)
    {
        $data = $request->except('_token');

        $recriacoes = RecriacaoPeixes::search($data, $this->rowsPerPage);

        $tanques = Tanques::where('filial_id', session('_filial')->id)
        ->where('tanque_tipo_id', 6) // Viveiro de peixes
        ->orderBy('sigla')
        ->get();

        return view('admin.recriacao_peixes.list')
        ->with('recriacoes', $recriacoes)
        ->with('tanques',    $tanques);
    }

    public function create()
    {
        $tanques = Tanques::where('filial_id', session('_filial')->id)
        ->where('tanque_tipo_id', 6) // Viveiro de peixes
        ->whereNotIn('id', 
            RecriacaoPeixes::where('filial_id', session('_filial')->id)
            ->where('situacao', '<>', 4) // Encerrado
            ->groupBy('tanque_id')
            ->get(['tanque_id'])
        )
        ->orderBy('sigla')
        ->get();

        return view('admin.recriacao_peixes.create')
        ->with('tanques', $tanques);
    }

    public function store(RecriacaoPeixesFormRequest $request)
    {
        $data = $request->except(['_token']);

        $data = DataPolisher::toPolish($data);

        $data['filial_id']  = session('_filial')->id;
        $data['usuario_id'] = auth()->user()->id;

        $recriacao_peixes = RecriacaoPeixes::create($data);

        if ($recriacao_peixes instanceof RecriacaoPeixes)  {
            // return redirect()->route('admin.recriacao_peixes.lotes.to_create', ['recriacao_peixes_id' => $recriacao_peixes->id]);
            return redirect()->route('admin.recriacao_peixes');
        }

        return redirect()->back()->withInput()
        ->with('error', 'Ocorreu um erro durante a tentativa de salvar o registro! Tente novamente.');
    }
}
