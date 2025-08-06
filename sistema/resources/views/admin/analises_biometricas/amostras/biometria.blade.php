@php
//Cabeçalho que define a codificação dos dados
//UTF-8 é o Padrão
header("Content-Type: text/html; utf-8",true);

// Determina que o arquivo é uma planilha do Excel
header("Content-type: application/vnd.ms-excel");   
 // Força o download do arquivo
header("Content-type: application/force-download");  
// Seta o nome do arquivo
header("Content-Disposition: attachment; filename=biometria_parcial.xls");
header("Pragma: no-cache");
@endphp
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sem título</title>	
</head>

<body>
<table width="100%" border="1">
  <tbody>
    <tr>
      <td colspan="23" align="center"><strong>Acompanhamento de Biometrias</strong></td>
    </tr>
    @foreach ($analises as $viveiro => $analise)
    
      <tr>
        <td>&nbsp;</td>
        <td align="center" bgcolor="#3F9CE9"><strong>Semanas →</strong></td>
        <td align="center" bgcolor="#3F9CE9"><strong>1ª</strong></td>
        <td align="center" bgcolor="#3F9CE9"><strong>2ª</strong></td>
        <td align="center" bgcolor="#3F9CE9"><strong>3ª</strong></td>
        <td align="center" bgcolor="#3F9CE9"><strong>4ª</strong></td>
        <td align="center" bgcolor="#3F9CE9"><strong>5ª</strong></td>
        <td align="center" bgcolor="#3F9CE9"><strong>6ª</strong></td>
        <td align="center" bgcolor="#3F9CE9"><strong>7ª</strong></td>
        <td align="center" bgcolor="#3F9CE9"><strong>8ª</strong></td>
        <td align="center" bgcolor="#3F9CE9"><strong>9ª</strong></td>
        <td align="center" bgcolor="#3F9CE9"><strong>10ª</strong></td>
        <td align="center" bgcolor="#3F9CE9"><strong>11ª</strong></td>
        <td align="center" bgcolor="#3F9CE9"><strong>12ª</strong></td>
        <td align="center" bgcolor="#3F9CE9"><strong>13ª</strong></td>
        <td align="center" bgcolor="#3F9CE9"><strong>14ª</strong></td>
        <td align="center" bgcolor="#3F9CE9"><strong>15ª</strong></td>
        <td align="center" bgcolor="#3F9CE9"><strong>16ª</strong></td>
        <td align="center" bgcolor="#3F9CE9"><strong>17ª</strong></td>
        <td align="center" bgcolor="#3F9CE9"><strong>18ª</strong></td>
        <td align="center" bgcolor="#3F9CE9"><strong>19ª</strong></td>
        <td align="center" bgcolor="#3F9CE9"><strong>20ª</strong></td>
        <td align="center" bgcolor="#3F9CE9"><strong>Observações</strong></td>
      </tr>
      <tr>
        <td rowspan="11" align="center" valign="center" class="pesomedio"><strong>{{$viveiro}}</strong></td>
        <td align="center" class="data"><strong><font face="Arial" color="#EFA428">Data</font></strong></td>
        @php
            $crescimento = [];
            $contador = 0;
            $crescimento[0] = 0;
            $contcells = 0;
            $class = "#FFFFFF"
        @endphp
        @foreach ($analise as $amostra)
        
          <td align="center" bgcolor="{{$class}}">{{$amostra->data_analise('d/m/Y')}}</td>
          @php
            $contcells++;
            if($class == "#FFB6C1"){
              $class = "#FFFFFF";
            }else{
              $class = "#FFB6C1";
            }
          @endphp
        @endforeach
        @for ($i=1;$i<=(20-$contcells);$i++) 
          <td align="center" bgcolor="{{$class}}">&nbsp;</td>
          @php
              if($class == "#FFB6C1"){
                $class = "#FFFFFF";
              }else{
                $class = "#FFB6C1";
              }
          @endphp
        @endfor
        @php
            $class = "#FFFFFF";
        @endphp
        <td rowspan="11">&nbsp;</td>
      </tr>
      <tr>
        <td align="center"><strong>DDC</strong></td>
        @foreach ($analise as $amostra)
          <td align="center" bgcolor="{{$class}}">{{$ciclos[$viveiro]->dias_cultivo($amostra->data_analise)}}</td>
          @php
            if($class == "#FFB6C1"){
              $class = "#FFFFFF";
            }else{
              $class = "#FFB6C1";
            }
          @endphp
        @endforeach
        @for ($i=1;$i<=(20-$contcells);$i++) 
          <td align="center" bgcolor="{{$class}}">&nbsp;</td>
          @php
              if($class == "#FFB6C1"){
                $class = "#FFFFFF";
              }else{
                $class = "#FFB6C1";
              }
          @endphp
        @endfor
        @php
            $class = "#FFFFFF";
        @endphp
      </tr>
      <tr>
        <td align="center" class="pesomedio"><strong><font face="Arial" color="#CB1414">Peso Médio</font></strong></td>
        @foreach ($analise as $amostra)
          @php
            
              $pesomedio = $amostra->peso_medio();
              $crescimento[$contador] = $pesomedio;
          @endphp
          <td align="center" bgcolor="{{$class}}">{{round($pesomedio, 2)." g"}}</td>
          @php
              if($class == "#FFB6C1"){
                $class = "#FFFFFF";
              }else{
                $class = "#FFB6C1";
              }
              $contador++;
          @endphp
        @endforeach
        @for ($i=1;$i<=(20-$contcells);$i++) 
          <td align="center" bgcolor="{{$class}}">&nbsp;</td>
          @php
              if($class == "#FFB6C1"){
                $class = "#FFFFFF";
              }else{
                $class = "#FFB6C1";
              }
          @endphp
        @endfor
        @php
            $class = "#FFFFFF";
            $contador = 0;
        @endphp
      </tr>
      <tr>
        <td align="center" class="crescimento"><strong><font face="Arial" color="#2738EF">Crescimento</font></strong></td>
        @foreach ($analise as $amostra)
          @if ($contador == 0)
            <td align="center" bgcolor="{{$class}}">{{round((0), 2)." g"}}</td>
          @else
            <td align="center" bgcolor="{{$class}}">{{round(($crescimento[$contador] - $crescimento[$contador-1]), 2)." g"}}</td>
          @endif
          @php
              if($class == "#FFB6C1"){
                $class = "#FFFFFF";
              }else{
                $class = "#FFB6C1";
              }
              $contador++
          @endphp
        @endforeach
        @for ($i=1;$i<=(20-$contcells);$i++) 
          <td align="center" bgcolor="{{$class}}">&nbsp;</td>
          @php
              if($class == "#FFB6C1"){
                $class = "#FFFFFF";
              }else{
                $class = "#FFB6C1";
              }
          @endphp
        @endfor
        @php
            $class = "#FFFFFF";
        @endphp
      </tr>
      <tr>
        <td align="center" class="coeficiente"><strong><font face="Arial" color="#5AA467">Coeficiente de variação</font></strong></td>
        @foreach ($analise as $amostra)
        
          <td align="center" bgcolor="{{$class}}">{{$amostra->coeficiente_variacao()."%"}}</td>
          @php
              if($class == "#FFB6C1"){
                $class = "#FFFFFF";
              }else{
                $class = "#FFB6C1";
              }
          @endphp
        @endforeach
        @for ($i=1;$i<=(20-$contcells);$i++) 
          <td align="center" bgcolor="{{$class}}">&nbsp;</td>
          @php
              if($class == "#FFB6C1"){
                $class = "#FFFFFF";
              }else{
                $class = "#FFB6C1";
              }
          @endphp
        @endfor
        @php
            $class = "#FFFFFF";
        @endphp
      </tr>
      <tr>
        <td align="center"><strong>Mole(%)</strong></td>
        @foreach ($analise as $amostra)
        @php
            $qualificacao = $amostra->qualificacoes();
            $mole          = qualifica($qualificacao['mole'], $qualificacao['total']);
        @endphp
          <td align="center" bgcolor="{{$class}}">{{ $mole['valor'].' - ('.$mole['percentual'].'%)' }}</td>
          @php
              if($class == "#FFB6C1"){
                $class = "#FFFFFF";
              }else{
                $class = "#FFB6C1";
              }
          @endphp
        @endforeach
        @for ($i=1;$i<=(20-$contcells);$i++) 
          <td align="center" bgcolor="{{$class}}">&nbsp;</td>
          @php
              if($class == "#FFB6C1"){
                $class = "#FFFFFF";
              }else{
                $class = "#FFB6C1";
              }
          @endphp
        @endfor
        @php
            $class = "#FFFFFF";
        @endphp
      </tr>
      <tr>
        <td align="center"><strong>Semi – Mole(%)</strong></td>
        @foreach ($analise as $amostra)
        @php
            $qualificacao = $amostra->qualificacoes();
            $semimole          = qualifica($qualificacao['semimole'], $qualificacao['total']);
        @endphp
          <td align="center" bgcolor="{{$class}}">{{ $semimole ['valor'].' - ('.$semimole ['percentual'].'%)' }}</td>
          @php
              if($class == "#FFB6C1"){
                $class = "#FFFFFF";
              }else{
                $class = "#FFB6C1";
              }
          @endphp
        @endforeach
        @for ($i=1;$i<=(20-$contcells);$i++) 
          <td align="center" bgcolor="{{$class}}">&nbsp;</td>
          @php
              if($class == "#FFB6C1"){
                $class = "#FFFFFF";
              }else{
                $class = "#FFB6C1";
              }
          @endphp
        @endfor
        @php
            $class = "#FFFFFF";
        @endphp
      </tr>
      <tr>
        <td align="center"><strong>Necrose 1(%)</strong></td>
        @foreach ($analise as $amostra)
        @php
            $qualificacao = $amostra->qualificacoes();
            $necrose1      = qualifica($qualificacao['necrose1'], $qualificacao['total']);
        @endphp
          <td align="center" bgcolor="{{$class}}">{{ $necrose1 ['valor'].' - ('.$necrose1 ['percentual'].'%)' }}</td>
          @php
              if($class == "#FFB6C1"){
                $class = "#FFFFFF";
              }else{
                $class = "#FFB6C1";
              }
          @endphp
        @endforeach
        @for ($i=1;$i<=(20-$contcells);$i++) 
          <td align="center" bgcolor="{{$class}}">&nbsp;</td>
          @php
              if($class == "#FFB6C1"){
                $class = "#FFFFFF";
              }else{
                $class = "#FFB6C1";
              }
          @endphp
        @endfor
        @php
            $class = "#FFFFFF";
        @endphp
      </tr>
      <tr>
        <td align="center"><strong>Necrose 2(%)</strong></td>
        @foreach ($analise as $amostra)
        @php
            $qualificacao = $amostra->qualificacoes();
            $necrose2      = qualifica($qualificacao['necrose2'], $qualificacao['total']);
        @endphp
          <td align="center" bgcolor="{{$class}}">{{ $necrose2 ['valor'].' - ('.$necrose2 ['percentual'].'%)' }}</td>
          @php
              if($class == "#FFB6C1"){
                $class = "#FFFFFF";
              }else{
                $class = "#FFB6C1";
              }
          @endphp
        @endforeach
        @for ($i=1;$i<=(20-$contcells);$i++) 
          <td align="center" bgcolor="{{$class}}">&nbsp;</td>
          @php
              if($class == "#FFB6C1"){
                $class = "#FFFFFF";
              }else{
                $class = "#FFB6C1";
              }
          @endphp
        @endfor
        @php
            $class = "#FFFFFF";
        @endphp
      </tr>
      <tr>
        <td align="center"><strong>Necrose 3(%)</strong></td>
        @foreach ($analise as $amostra)
        @php
            $qualificacao = $amostra->qualificacoes();
            $necrose3      = qualifica($qualificacao['necrose3'], $qualificacao['total']);
        @endphp
          <td align="center" bgcolor="{{$class}}">{{ $necrose3 ['valor'].' - ('.$necrose3 ['percentual'].'%)' }}</td>
          @php
              if($class == "#FFB6C1"){
                $class = "#FFFFFF";
              }else{
                $class = "#FFB6C1";
              }
          @endphp
        @endforeach
        @for ($i=1;$i<=(20-$contcells);$i++) 
          <td align="center" bgcolor="{{$class}}">&nbsp;</td>
          @php
              if($class == "#FFB6C1"){
                $class = "#FFFFFF";
              }else{
                $class = "#FFB6C1";
              }
          @endphp
        @endfor
        @php
            $class = "#FFFFFF";
        @endphp
      </tr>
      <tr>
        <td align="center"><strong>Necrose 4(%)</strong></td>
        @foreach ($analise as $amostra)
        @php
            $qualificacao = $amostra->qualificacoes();
            $necrose4      = qualifica($qualificacao['necrose4'], $qualificacao['total']);
        @endphp
          <td align="center" bgcolor="{{$class}}">{{ $necrose4 ['valor'].' - ('.$necrose4 ['percentual'].'%)' }}</td>
          @php
              if($class == "#FFB6C1"){
                $class = "#FFFFFF";
              }else{
                $class = "#FFB6C1";
              }
          @endphp
        @endforeach
        @for ($i=1;$i<=(20-$contcells);$i++) 
          <td align="center" bgcolor="{{$class}}">&nbsp;</td>
          @php
              if($class == "#FFB6C1"){
                $class = "#FFFFFF";
              }else{
                $class = "#FFB6C1";
              }
          @endphp
        @endfor
        @php
            $class = "#FFFFFF";
        @endphp
      </tr>
      <tr>
        <td colspan="23">&nbsp;</td>
      </tr>
    @endforeach 
  </tbody>
</table>
</body>
</html>
@php
    function qualifica($qua,$total){

        $resultado = [
            'valor'  => 0,
            'percentual'   => 0
    ];

        if(($qua != 0) || ($total != 0)){
            $resultado['valor'] = $qua;
            $resultado['percentual'] = round((($qua/$total)*100), 2);
        }

        return $resultado;
    }
@endphp