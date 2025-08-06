<table cellspacing="0" cellpading="0">
  @php
      $colunas = count($ciclos) * 2;
  @endphp
  <tr style="border-color: 000000;border-style: solid;border-width: 1px;">
  <th colspan="{{$colunas}}" style="border-color: 000000;border-style: solid;border-width: 1px 1px 1px 1px;border-color: 000000;border-style: solid;border-width: 1px;vertical-align:middle;text-align:center;background-color:#FFFFFF">Indices de Coeficiente de Variação Semanal por Viveiros</th>
  </tr>
  <tr>
    @foreach($ciclos as $ciclo)
      <th colspan="2" style="border-color: 000000;border-style: solid;border-width: 1px 1px 0px 1px;vertical-align:middle;text-align:center;background-color:#{{$ciclo->tanque->setor->cor}}">{{$ciclo->tanque->sigla}}</th>   
    @endforeach
  </tr>
  @foreach($analises as $analise)
    <tr>
      <th style="border-color: 000000;border-style: solid;border-width: 1px 0px 1px 1px;vertical-align:middle;text-align:center;background-color:#{{$analise->ciclo->tanque->setor->cor}}">Gramatura</th>
      <th style="border-color: 000000;border-style: solid;border-width: 1px 1px 1px 1px;vertical-align:middle;text-align:center;background-color:#{{$analise->ciclo->tanque->setor->cor}}">Qtd. Ind.</th>
    </tr>
    @php
      $i = 0.1;  
    @endphp
    @while ($i <= 26.0)
      @if($i < 1.8)
        @php
          $gr = '_'.str_replace('.', '', $i);
          if($gr == "_1"){
            $gr = "_10";
          }
        @endphp
        <tr>
          <td style="border-color: 000000;border-style: solid;border-width: 1px 0px 0px 1px;vertical-align:middle;text-align:center;background-color:#ffffff;font-weight: bold">{{number_format($i, 1, '.', ',')}}</td>
          <td style="border-color: 000000;border-style: solid;border-width: 1px 1px 0px 1px;vertical-align:middle;text-align:center;background-color:#FFBEF9">{{$analise->coeficiente_variacao()[$gr]}}</td>
        </tr>
        @php 
          $i+=0.1;
        @endphp
      @else
        @if($i < 2)
          @php
           $i = 2;   
          @endphp
        @endif
        <tr>
          <td style="border-color: 000000;border-style: solid;border-width: 1px 0px 0px 1px;vertical-align:middle;text-align:center;background-color:#ffffff;font-weight: bold">{{number_format($i, 1, '.', ',')}}</td>
          <td style="border-color: 000000;border-style: solid;border-width: 1px 1px 0px 1px;vertical-align:middle;text-align:center;background-color:#FFBEF9">{{$analise->classe_variacao(number_format($i, 1, '.', ','))}}</td>
        </tr>
        @php 
          $i+=0.5;
        @endphp
      @endif
    @endwhile
    <tr>
      <td style="border-color: 000000;border-style: solid;border-width: 1px 0px 0px 1px;vertical-align:middle;text-align:center;background-color:#ffffff;font-weight: bold">Média</td>
      <td style="border-color: 000000;border-style: solid;border-width: 1px 1px 0px 1px;vertical-align:middle;text-align:center;background-color:#FFBEF9">{{number_format($analise->peso_medio(), 1, '.', ',')}}</td>
    </tr>
    <tr>
      <td style="border-color: 000000;border-style: solid;border-width: 1px 0px 0px 1px;vertical-align:middle;text-align:center;background-color:#ffffff;font-weight: bold">Des.Padrão</td>
      <td style="border-color: 000000;border-style: solid;border-width: 1px 1px 0px 1px;vertical-align:middle;text-align:center;background-color:#FFBEF9">{{$analise->desvio_padrao()}}</td>
    </tr>
    <tr>
      <td style="border-color: 000000;border-style: solid;border-width: 1px 0px 0px 1px;vertical-align:middle;text-align:center;background-color:#ffffff;font-weight: bold">Coe. Var.</td>
      <td style="border-color: 000000;border-style: solid;border-width: 1px 1px 0px 1px;vertical-align:middle;text-align:center;background-color:#FFBEF9">{{number_format(($analise->desvio_padrao()/$analise->peso_medio())*100, 1, '.', ',')."%"}}</td>
    </tr>
    <tr>
        <td style="border-color: 000000;border-style: solid;border-width: 1px 0px 0px 1px;vertical-align:middle;text-align:center;background-color:#ffffff;font-weight: bold">Total Ind.</td>
        <td style="border-color: 000000;border-style: solid;border-width: 1px 1px 0px 1px;vertical-align:middle;text-align:center;background-color:#FFBEF9">{{$analise->amostras->count()}}</td>
      </tr>
  @endforeach
  
</table>