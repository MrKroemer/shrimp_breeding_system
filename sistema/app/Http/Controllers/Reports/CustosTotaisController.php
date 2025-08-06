<?php

    namespace App\Http\Controllers\Reports;

    use App\Http\Controllers\Controller;
    Use App\Http\Controllers\Reports\CustosTotaisReport;
    use App\Http\Requests\CustosTotaisReportCreateFormRequest;
    use App\Models\Ciclos;
    use Carbon\Carbon;

    class CustosTotaisController extends Controller
    {

        public function createCustosTotais(){

            $ciclos = Ciclos::where('filial_id', session('_filial')->id)
            ->whereBetween('situacao', [4, 8])
            ->orderBy('numero', 'desc')
            ->get();

            return view('report.custos_totais.create')
            ->with('ciclos', $ciclos);

        }

        public function generatesCustosTotais(CustosTotaisReportCreateFormRequest $request){
            
            $data = $request->except('_token');

            $ciclos = []; 
            
            $ciclos = Ciclos::whereIn('id', $data['ciclos'])
            ->get();

            $data_inicial =  Carbon::createFromFormat('d-m-Y', $data['data_inicial']);
            $data_final   =  Carbon::createFromFormat('d-m-Y', $data['data_final']);


            $document = new CustosTotaisReport;

            $document->Makedocument([$data_inicial, $data_final]);


            $fileName = 'custos_totais_' . date('Y-m-d_H:i:s') . 'pdf'; 



        }






    }