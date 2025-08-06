<table style="undefined;table-layout: fixed; width: 1329px">
<colgroup>
<col style="width: 55px">
<col style="width: 85px">
<col style="width: 79px">
<col style="width: 156px">
<col style="width: 21px">
<col style="width: 61px">
<col style="width: 131px">
<col style="width: 119px">
<col style="width: 152px">
<col style="width: 124px">
<col style="width: 20px">
<col style="width: 64px"> 
<col style="width: 73px">
<col style="width: 77px">
<col style="width: 112px">
</colgroup>
    <tr>
    <th colspan="15" style="vertical-align:middle;text-align:center;background-color:#E2EC36">Coeficiente de Variação Semanal</th>
    </tr>
    <tr>
    <td colspan="4" rowspan="2"></td>
    <td rowspan="91"></td>
    <td colspan="2">PESO MÉDIO</td>
    <td>DDC:</td>
    <td >PESO TOTAL (g)</td>
    <td >AMOSTRA</td>
    <td rowspan="91"></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    </tr>
    <tr>
    <td >ANTES</td>
    <td >ATUAL</td>
    <td >CRESCIMENTO</td>
    <td rowspan="2">{{$analises_biometricas->pesototal()}}</td>
    <td rowspan="2">{{$analises_biometricas->gramaturas->count()}}</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    </tr>
    <tr>
    <td colspan="2">VIVEIRO: {{$analises_biometricas->ciclo->tanque->sigla}}</td>
    <td colspan="2">DATA: {{$analises_biometricas->data_analise()}}</td>
    <td>$pesomedio_anterior</td>
    <td>{{$analises_biometricas->pesomedio()}}</td>
    <td>$crescimento</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#2339E5"><strong><em>Classe</em></strong></td>
    <td style="vertical-align:middle;text-align:center;background-color:#2339E5"><strong><em>Total</em></strong></td>
    <td style="vertical-align:middle;text-align:center;background-color:#2339E5"><strong><em>Gramatura</em></strong></td>
    <td style="vertical-align:middle;text-align:center;background-color:#2339E5"><strong><em>Contagem</em></strong></td>
    <td style="vertical-align:middle;text-align:center;background-color:#2339E5"><strong><em>Classe</em></strong></td>
    <td style="vertical-align:middle;text-align:center;background-color:#2339E5"><strong><em>Total</em></strong></td>
    <td style="vertical-align:middle;text-align:center;background-color:#2339E5"><strong><em>Gramatura</em></strong></td>
    <td colspan="2" style="vertical-align:middle;text-align:center;background-color:#2339E5"><strong><em>Contagem</em></strong></td>
    <td style="vertical-align:middle;text-align:center;background-color:#2339E5"><strong><em>Classe</em></strong></td>
    <td style="vertical-align:middle;text-align:center;background-color:#2339E5"><strong><em>Total</em></strong></td>
    <td style="vertical-align:middle;text-align:center;background-color:#2339E5"><strong><em>Gramatura</em></strong></td>
    <td style="vertical-align:middle;text-align:center;background-color:#2339E5"><strong><em>Contagem</em></strong></td>
    </tr>
    <tr>
    <td colspan="2" rowspan="17">{{$analises_biometricas->classe_variacao()}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>0,1</strong></em></td>
    <td>{{$gramaturas['_01']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>9,0</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(9.0)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>8,8</strong></em></td>
    <td colspan="2">{{$gramaturas['_88']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>17,5</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(17.5)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>17,3</strong></em></td>
    <td>{{$gramaturas['_173']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>0,2</strong></em></td>
    <td>{{$gramaturas['_02']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>8,9</strong></em></td>
    <td colspan="2">{{$gramaturas['_89']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>17,4</strong></em></td>
    <td>{{$gramaturas['_174']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>0,3</strong></em></td>
    <td>{{$gramaturas['_03']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>9,0</strong></em></td>
    <td colspan="2">{{$gramaturas['_90']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>17,5</strong></em></td>
    <td>{{$gramaturas['_175']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>0,4</strong></em></td>
    <td>{{$gramaturas['_04']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>9,1</strong></em></td>
    <td colspan="2">{{$gramaturas['_91']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>17,6</strong></em></td>
    <td>{{$gramaturas['_176']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>0,5</strong></em></td>
    <td>{{$gramaturas['_05']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>9,2</strong></em></td>
    <td colspan="2">{{$gramaturas['_92']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>17,7</strong></em></td>
    <td>{{$gramaturas['_177']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>0,6</strong></em></td>
    <td>{{$gramaturas['_06']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>9,5</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(9.5)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>9,3</strong></em></td>
    <td colspan="2">{{$gramaturas['_93']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>18,0</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(18.0)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>17,8</strong></em></td>
    <td>{{$gramaturas['_178']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>0,7</strong></em></td>
    <td>{{$gramaturas['_07']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>9,4</strong></em></td>
    <td colspan="2">{{$gramaturas['_94']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>17,9</strong></em></td>
    <td>{{$gramaturas['_179']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>0,8</strong></em></td>
    <td>{{$gramaturas['_08']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>9,5</strong></em></td>
    <td colspan="2">{{$gramaturas['_95']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>18,0</strong></em></td>
    <td>{{$gramaturas['_180']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>0,9</strong></em></td>
    <td>{{$gramaturas['_09']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>9,6</strong></em></td>
    <td colspan="2">{{$gramaturas['_96']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>18,1</strong></em></td>
    <td>{{$gramaturas['_181']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>1,0</strong></em></td>
    <td>{{$gramaturas['_10']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>9,7</strong></em></td>
    <td colspan="2">{{$gramaturas['_97']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>18,2</strong></em></td>
    <td>{{$gramaturas['_182']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>1,1</strong></em></td>
    <td>{{$gramaturas['_11']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>10,0</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(10.0)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>9,8</strong></em></td>
    <td colspan="2">{{$gramaturas['_98']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>18,5</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(18.5)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>18,3</strong></em></td>
    <td>{{$gramaturas['_183']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>1,2</strong></em></td>
    <td>{{$gramaturas['_12']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>9,9</strong></em></td>
    <td colspan="2">{{$gramaturas['_99']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>18,4</strong></em></td>
    <td>{{$gramaturas['_184']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>1,3</strong></em></td>
    <td>{{$gramaturas['_13']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>10,0</strong></em></td>
    <td colspan="2">{{$gramaturas['_10']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>18,5</strong></em></td>
    <td>{{$gramaturas['_185']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>1,4</strong></em></td>
    <td>{{$gramaturas['_11']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>10,1</strong></em></td>
    <td colspan="2">{{$gramaturas['_101']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>18,6</strong></em></td>
    <td>{{$gramaturas['_186']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>1,5</strong></em></td>
    <td>{{$gramaturas['_15']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>10,2</strong></em></td>
    <td colspan="2">{{$gramaturas['_102']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>18,7</strong></em></td>
    <td>{{$gramaturas['_187']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>1,6</strong></em></td>
    <td>{{$gramaturas['_16']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>10,5</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(10.5)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>10,3</strong></em></td>
    <td colspan="2">{{$gramaturas['_103']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>19,0</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(19.0)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>18,8</strong></em></td>
    <td>{{$gramaturas['_188']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>1,7</strong></em></td>
    <td>{{$gramaturas['_17']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>10,4</strong></em></td>
    <td colspan="2">{{$gramaturas['_104']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>18,9</strong></em></td>
    <td>{{$gramaturas['_189']}}</td>
    </tr>
    <tr>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>2,0</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(2.0)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>1,8</strong></em></td>
    <td>{{$gramaturas['_18']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>10,5</strong></em></td>
    <td colspan="2">{{$gramaturas['_105']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>19,0</strong></em></td>
    <td>{{$gramaturas['_190']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>1,9</strong></em></td>
    <td>{{$gramaturas['_190']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>10,6</strong></em></td>
    <td colspan="2">{{$gramaturas['_106']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>19,1</strong></em></td>
    <td>{{$gramaturas['_191']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>2,0</strong></em></td>
    <td>{{$gramaturas['_20']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>10,7</strong></em></td>
    <td colspan="2">{{$gramaturas['_107']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>19,2</strong></em></td>
    <td>{{$gramaturas['_192']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>2,1</strong></em></td>
    <td>{{$gramaturas['_21']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>11,0</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(11.0)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>10,8</strong></em></td>
    <td colspan="2">{{$gramaturas['_21']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>19,5</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(19.5)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>19,3</strong></em></td>
    <td>{{$gramaturas['_193']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>2,2</strong></em></td>
    <td>{{$gramaturas['_22']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>10,9</strong></em></td>
    <td colspan="2">{{$gramaturas['_109']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>19,4</strong></em></td>
    <td>{{$gramaturas['_194']}}</td>
    </tr>
    <tr>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>2,5</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(2.5)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>2,3</strong></em></td>
    <td>{{$gramaturas['_23']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>11,0</strong></em></td>
    <td colspan="2">{{$gramaturas['_110']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>19,5</strong></em></td>
    <td>{{$gramaturas['_195']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>2,4</strong></em></td>
    <td>{{$gramaturas['_24']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>11,1</strong></em></td>
    <td colspan="2">{{$gramaturas['_111']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>19,6</strong></em></td>
    <td>{{$gramaturas['_195']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>2,5</strong></em></td>
    <td>{{$gramaturas['_25']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>11,2</strong></em></td>
    <td colspan="2">{{$gramaturas['_112']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>19,7</strong></em></td>
    <td>{{$gramaturas['_197']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>2,6</strong></em></td>
    <td>{{$gramaturas['_26']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>11,5</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(11.5)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>11,3</strong></em></td>
    <td colspan="2">{{$gramaturas['_113']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>20,0</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(20.0)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>19,8</strong></em></td>
    <td>{{$gramaturas['_198']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>2,7</strong></em></td>
    <td>{{$gramaturas['_27']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>11,4</strong></em></td>
    <td colspan="2">{{$gramaturas['_114']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>19,9</strong></em></td>
    <td>{{$gramaturas['_199']}}</td>
    </tr>
    <tr>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>3,0</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(3.0)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>2,8</strong></em></td>
    <td>{{$gramaturas['_28']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>11,5</strong></em></td>
    <td colspan="2">{{$gramaturas['_115']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>20,0</strong></em></td>
    <td>{{$gramaturas['_200']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>2,9</strong></em></td>
    <td>{{$gramaturas['_29']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>11,6</strong></em></td>
    <td colspan="2">{{$gramaturas['_116']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>20,1</strong></em></td>
    <td>{{$gramaturas['_201']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>3,0</strong></em></td>
    <td>{{$gramaturas['_30']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>11,7</strong></em></td>
    <td colspan="2">{{$gramaturas['_117']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>20,2</strong></em></td>
    <td>{{$gramaturas['_202']}}</td>
  </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>3,1</strong></em></td>
    <td>{{$gramaturas['_31']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>12,0</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(12.0)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>11,8</strong></em></td>
    <td colspan="2">{{$gramaturas['_118']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>20,5</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(20.5)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>20,3</strong></em></td>
    <td>{{$gramaturas['_203']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>3,2</strong></em></td>
    <td>{{$gramaturas['_32']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>11,9</strong></em></td>
    <td colspan="2">{{$gramaturas['_119']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>20,4</strong></em></td>
    <td>{{$gramaturas['_204']}}</td>
    </tr>
    <tr>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>3,5</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(3.5)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>3,3</strong></em></td>
    <td>{{$gramaturas['_33']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>12,0</strong></em></td>
    <td colspan="2">{{$gramaturas['_120']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>20,5</strong></em></td>
    <td>{{$gramaturas['_205']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>3,4</strong></em></td>
    <td>{{$gramaturas['_34']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>12,1</strong></em></td>
    <td colspan="2">{{$gramaturas['_121']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>20,6</strong></em></td>
    <td>{{$gramaturas['_206']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>3,5</strong></em></td>
    <td>{{$gramaturas['_35']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>12,2</strong></em></td>
    <td colspan="2">{{$gramaturas['_122']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>20,7</strong></em></td>
    <td>{{$gramaturas['_207']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>3,6</strong></em></td>
    <td>{{$gramaturas['_36']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>12,5</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(12.5)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>12,3</strong></em></td>
    <td colspan="2">{{$gramaturas['_123']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>21,0</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(21.0)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>20,8</strong></em></td>
    <td>{{$gramaturas['_208']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>3,7</strong></em></td>
    <td>{{$gramaturas['_37']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>12,4</strong></em></td>
    <td colspan="2">{{$gramaturas['_124']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>20,9</strong></em></td>
    <td>{{$gramaturas['_209']}}</td>
    </tr>
    <tr>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>4,0</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(4.0)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>3,8</strong></em></td>
    <td>{{$gramaturas['_38']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>12,5</strong></em></td>
    <td colspan="2">{{$gramaturas['_125']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>21,0</strong></em></td>
    <td>{{$gramaturas['_210']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>3,9</strong></em></td>
    <td>{{$gramaturas['_39']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>12,6</strong></em></td>
    <td colspan="2">{{$gramaturas['_126']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>21,1</strong></em></td>
    <td>{{$gramaturas['_211']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>4,0</strong></em></td>
    <td>{{$gramaturas['_40']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>12,7</strong></em></td>
    <td colspan="2">{{$gramaturas['_127']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>21,2</strong></em></td>
    <td>{{$gramaturas['_212']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>4,1</strong></em></td>
    <td>{{$gramaturas['_41']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>13,0</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(13.0)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>12,8</strong></em></td>
    <td colspan="2">{{$gramaturas['_128']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>21,5</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(21.5)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>21,3</strong></em></td>
    <td>{{$gramaturas['_213']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>4,2</strong></em></td>
    <td>{{$gramaturas['_42']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>12,9</strong></em></td>
    <td colspan="2">{{$gramaturas['_129']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>21,4</strong></em></td>
    <td>{{$gramaturas['_214']}}</td>
    </tr>
    <tr>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>4,5</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(4.5)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>4,3</strong></em></td>
    <td>{{$gramaturas['_43']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>13,0</strong></em></td>
    <td colspan="2">{{$gramaturas['_130']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>21,5</strong></em></td>
    <td>{{$gramaturas['_215']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>4,4</strong></em></td>
    <td>{{$gramaturas['_44']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>13,1</strong></em></td>
    <td colspan="2">{{$gramaturas['_131']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>21,6</strong></em></td>
    <td>{{$gramaturas['_216']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>4,5</strong></em></td>
    <td>{{$gramaturas['_45']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>13,2</strong></em></td>
    <td colspan="2">{{$gramaturas['_132']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>21,7</strong></em></td>
    <td>{{$gramaturas['_217']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>4,6</strong></em></td>
    <td>{{$gramaturas['_46']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>13,5</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(13.5)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>13,3</strong></em></td>
    <td colspan="2">{{$gramaturas['_133']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>22,0</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(22.0)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>21,8</strong></em></td>
    <td>{{$gramaturas['_218']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>4,7</strong></em></td>
    <td>{{$gramaturas['_47']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>13,4</strong></em></td>
    <td colspan="2">{{$gramaturas['_134']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>21,9</strong></em></td>
    <td>{{$gramaturas['_219']}}</td>
    </tr>
    <tr>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>5,0</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(22.0)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>4,8</strong></em></td>
    <td>{{$gramaturas['_48']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>13,5</strong></em></td>
    <td colspan="2">{{$gramaturas['_135']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>22,0</strong></em></td>
    <td>{{$gramaturas['_220']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>4,9</strong></em></td>
    <td>{{$gramaturas['_49']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>13,6</strong></em></td>
    <td colspan="2">{{$gramaturas['_136']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>22,1</strong></em></td>
    <td>{{$gramaturas['_221']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>5,0</strong></em></td>
    <td>{{$gramaturas['_50']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>13,7</strong></em></td>
    <td colspan="2">{{$gramaturas['_137']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>22,2</strong></em></td>
    <td>{{$gramaturas['_222']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>5,1</strong></em></td>
    <td>{{$gramaturas['_51']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>14,0</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(14.0)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>13,8</strong></em></td>
    <td colspan="2">{{$gramaturas['_138']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>22,5</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(22.5)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>22,3</strong></em></td>
    <td>{{$gramaturas['_223']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>5,2</strong></em></td>
    <td>{{$gramaturas['_52']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>13,9</strong></em></td>
    <td colspan="2">{{$gramaturas['_139']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>22,4</strong></em></td>
    <td>{{$gramaturas['_224']}}</td>
    </tr>
    <tr>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>5,5</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(5.5)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>5,3</strong></em></td>
    <td>{{$gramaturas['_53']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>14,0</strong></em></td>
    <td colspan="2">{{$gramaturas['_140']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>22,5</strong></em></td>
    <td>{{$gramaturas['_225']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>5,4</strong></em></td>
    <td>{{$analises_biometricas->classe_variacao(5.4)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>14,1</strong></em></td>
    <td colspan="2">{{$gramaturas['_141']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>22,6</strong></em></td>
    <td>{{$gramaturas['_226']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>5,5</strong></em></td>
    <td>{{$gramaturas['_55']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>14,2</strong></em></td>
    <td colspan="2">{{$gramaturas['_142']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>22,7</strong></em></td>
    <td>{{$gramaturas['_227']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>5,6</strong></em></td>
    <td>{{$gramaturas['_56']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>14,5</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(14.5)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>14,3</strong></em></td>
    <td colspan="2">{{$gramaturas['_143']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>23,0</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(23.0)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>22,8</strong></em></td>
    <td>{{$gramaturas['_228']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>5,7</strong></em></td>
    <td>{{$gramaturas['_57']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>14,4</strong></em></td>
    <td colspan="2">{{$gramaturas['_144']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>22,9</strong></em></td>
    <td>{{$gramaturas['_229']}}</td>
    </tr>
    <tr>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>6,0</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(6.0)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>5,8</strong></em></td>
    <td>{{$gramaturas['_58']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>14,5</strong></em></td>
    <td colspan="2">{{$gramaturas['_145']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>23,0</strong></em></td>
    <td>{{$gramaturas['_230']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>5,9</strong></em></td>
    <td>{{$gramaturas['_59']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>14,6</strong></em></td>
    <td colspan="2">{{$gramaturas['_146']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>23,1</strong></em></td>
    <td>{{$gramaturas['_231']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>6,0</strong></em></td>
    <td>{{$gramaturas['_60']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>14,7</strong></em></td>
    <td colspan="2">{{$gramaturas['_147']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>23,2</strong></em></td>
    <td>{{$gramaturas['_232']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>6,1</strong></em></td>
    <td>{{$gramaturas['_61']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>15,0</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(15.0)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>14,8</strong></em></td>
    <td colspan="2">{{$gramaturas['_148']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>23,5</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(23.5)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>23,3</strong></em></td>
    <td>{{$gramaturas['_233']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>6,2</strong></em></td>
    <td>{{$gramaturas['_62']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>14,9</strong></em></td>
    <td colspan="2">{{$gramaturas['_149']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>23,4</strong></em></td>
    <td>{{$gramaturas['_234']}}</td>
    </tr>
    <tr>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>6,5</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(6.5)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>6,3</strong></em></td>
    <td>{{$gramaturas['_63']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>15,0</strong></em></td>
    <td colspan="2">{{$gramaturas['_150']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>23,5</strong></em></td>
    <td>{{$gramaturas['_235']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>6,4</strong></em></td>
    <td>{{$gramaturas['_64']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>15,1</strong></em></td>
    <td colspan="2">{{$gramaturas['_151']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>23,6</strong></em></td>
    <td>{{$gramaturas['_236']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>6,5</strong></em></td>
    <td>{{$gramaturas['_65']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>15,2</strong></em></td>
    <td colspan="2">{{$gramaturas['_152']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>23,7</strong></em></td>
    <td>{{$gramaturas['_237']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>6,6</strong></em></td>
    <td>{{$gramaturas['_66']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>15,5</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(15.5)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>15,3</strong></em></td>
    <td colspan="2">{{$gramaturas['_153']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>24,0</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(24.0)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>23,8</strong></em></td>
    <td>{{$gramaturas['_238']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>6,7</strong></em></td>
    <td>{{$gramaturas['_67']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>15,4</strong></em></td>
    <td colspan="2">{{$gramaturas['_154']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>23,9</strong></em></td>
    <td>{{$gramaturas['_239']}}</td>
    </tr>
    <tr>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>7,0</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(7.0)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>6,8</strong></em></td>
    <td>{{$gramaturas['_68']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>15,5</strong></em></td>
    <td colspan="2">{{$gramaturas['_155']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>24,0</strong></em></td>
    <td>{{$gramaturas['_240']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>6,9</strong></em></td>
    <td>{{$gramaturas['_69']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>15,6</strong></em></td>
    <td colspan="2">{{$gramaturas['_156']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>24,1</strong></em></td>
    <td>{{$gramaturas['_241']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>7,0</strong></em></td>
    <td>{{$gramaturas['_70']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>15,7</strong></em></td>
    <td colspan="2">{{$gramaturas['_157']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>24,2</strong></em></td>
    <td>{{$gramaturas['_242']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>7,1</strong></em></td>
    <td>{{$gramaturas['_71']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>16,0</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(16.0)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>15,8</strong></em></td>
    <td colspan="2">{{$gramaturas['_158']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>24,5</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(24.5)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>24,3</strong></em></td>
    <td>{{$gramaturas['_243']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>7,2</strong></em></td>
    <td>{{$gramaturas['_72']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>15,9</strong></em></td>
    <td colspan="2">{{$gramaturas['_159']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>24,4</strong></em></td>
    <td>{{$gramaturas['_244']}}</td>
    </tr>
    <tr>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>7,5</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(7.5)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>7,3</strong></em></td>
    <td>{{$gramaturas['_73']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>16,0</strong></em></td>
    <td colspan="2">{{$gramaturas['_160']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>24,5</strong></em></td>
    <td>{{$gramaturas['_245']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>7,4</strong></em></td>
    <td>{{$gramaturas['_74']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>16,1</strong></em></td>
    <td colspan="2">{{$gramaturas['_161']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>24,6</strong></em></td>
    <td>{{$gramaturas['_246']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>7,5</strong></em></td>
    <td>{{$gramaturas['_75']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>16,2</strong></em></td>
    <td colspan="2">{{$gramaturas['_162']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>24,7</strong></em></td>
    <td>{{$gramaturas['_247']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>7,6</strong></em></td>
    <td>{{$gramaturas['_76']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>16,5</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(16.5)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>16,3</strong></em></td>
    <td colspan="2">{{$gramaturas['_163']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>25,0</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(25.0)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>24,8</strong></em></td>
    <td>{{$gramaturas['_248']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>7,7</strong></em></td>
    <td>{{$gramaturas['_77']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>16,4</strong></em></td>
    <td colspan="2">{{$gramaturas['_164']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>24,9</strong></em></td>
    <td>{{$gramaturas['_249']}}</td>
    </tr>
    <tr>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>8,0</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(25.0)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>7,8</strong></em></td>
    <td>{{$gramaturas['_78']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>16,5</strong></em></td>
    <td colspan="2">{{$gramaturas['_165']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>25,0</strong></em></td>
    <td>{{$gramaturas['_250']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>7,9</strong></em></td>
    <td>{{$gramaturas['_79']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>16,8</strong></em></td>
    <td colspan="2">{{$gramaturas['_168']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>25,1</strong></em></td>
    <td>{{$gramaturas['_251']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>8,0</strong></em></td>
    <td>{{$gramaturas['_80']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>16,9</strong></em></td>
    <td colspan="2">{{$gramaturas['_169']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>25,2</strong></em></td>
    <td>{{$gramaturas['_252']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>8,1</strong></em></td>
    <td>{{$gramaturas['_81']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>17,0</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(17.0)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>17,0</strong></em></td>
    <td colspan="2">{{$gramaturas['_249']}}</td>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5"><em><strong>25,5</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(25.5)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>25,3</strong></em></td>
    <td>{{$gramaturas['_249']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>8,2</strong></em></td>
    <td>{{$gramaturas['_82']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>17,1</strong></em></td>
    <td colspan="2">{{$gramaturas['_171']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>25,4</strong></em></td>
    <td>{{$gramaturas['_254']}}</td>
    </tr>
    <tr>
    <td rowspan="5" style="vertical-align:middle;text-align:center;background-color:#2339E5" class="tg-u6fw"><em><strong>8,5</strong></em></td>
    <td rowspan="5" style="vertical-align:middle;text-align:center">{{$analises_biometricas->classe_variacao(25.5)}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>8,3</strong></em></td>
    <td>{{$gramaturas['_254']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>17,2</strong></em></td>
    <td colspan="2">{{$gramaturas['_254']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>25,5</strong></em></td>
    <td>{{$gramaturas['_254']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>8,4</strong></em></td>
    <td>{{$gramaturas['_84']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>17,3</strong></em></td>
    <td colspan="2">{{$gramaturas['_173']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>25,6</strong></em></td>
    <td>{{$gramaturas['_256']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>8,5</strong></em></td>
    <td>{{$gramaturas['_85']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>17,2</strong></em></td>
    <td colspan="2">{{$gramaturas['_172']}}</td>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>25,7</strong></em></td>
    <td>{{$gramaturas['_257']}}</td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>8,6</strong></em></td>
    <td>{{$gramaturas['_86']}}</td>
    <td colspan="5" rowspan="2"></td>
    <td colspan="4" rowspan="2"></td>
    </tr>
    <tr>
    <td style="vertical-align:middle;text-align:center;background-color:#E2EC36"><em><strong>8,7</strong></em></td>
    <td>{{$gramaturas['_87']}}</td>
    </tr>
    <tr>
    <td colspan="15"></td>
    </tr>
</table>