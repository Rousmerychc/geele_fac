<table>
<thead>
<tr>
    <th colspan = "10" style ="text-align: center; font-size: 18; font-weight: bold;">CARSUR</th>
</tr>  
<tr>
    <th colspan = "10" style ="text-align: center; font-size: 14; font-weight: bold;"> RESUMEN MENSUAL </th>
</tr>
<tr>
    <th colspan = "10" style ="text-align: center; font-size: 10; font-weight: bold;"> Fecha inicial: {{$fechaini}} Fecha Final: {{$fechafin}}</th>
</tr>
<tr>
    <th colspan = "10" style ="text-align: center; font-size: 12; font-weight: bold;">Scursal: {{$sucu->descripcion}}</th>
</tr>
         
<tr>
    <th Rowspan ="2" style="width:90px; text-align: center; background-color: #98DCC2; border: 1 solid #000000; font-weight: bold;">Codigo</th>
    <th Rowspan ="2" style="width:200px;px; text-align: center; background-color: #98DCC2; border: 1 solid #000000; font-weight: bold;">Descripcion</th>
    <th Rowspan ="2" style="width:110px; text-align: center; background-color: #98DCC2; border: 1 solid #000000; font-weight: bold;">Saldo Inicial</th>
    <th colspan ="3" style="width:130px; text-align: center; background-color: #98DCC2; border: 1 solid #000000;  font-weight: bold;">Cantidad</th>
    <th Rowspan ="2" style="width:110px; text-align: center; background-color: #98DCC2; border: 1 solid #000000;  font-weight: bold;">Importe Inicial</th>
    <th colspan ="3" style="width:130px; text-align: center; background-color: #98DCC2; border: 1 solid #000000;  font-weight: bold;">Bolivianos</th>
</tr>  
<tr>
    <th style="width:90px; text-align: center; background-color: #98DCC2; border: 1 solid #000000; font-weight: bold;">Ingresos</th>
    <th style="width:90px; text-align: center; background-color: #98DCC2; border: 1 solid #000000; font-weight: bold;">Egresos</th>
    <th style="width:90px; text-align: center; background-color: #98DCC2; border: 1 solid #000000; font-weight: bold;">Saldo</th>
    
    <th style="width:90px; text-align: center; background-color: #98DCC2; border: 1 solid #000000; font-weight: bold;">Ingresos</th>
    <th style="width:90px; text-align: center; background-color: #98DCC2; border: 1 solid #000000; font-weight: bold;">Egresos</th>
    <th style="width:90px; -align: center; background-color: #98DCC2; border: 1 solid #000000; font-weight: bold;">Saldo</th>
</tr>  
<tbody>
    @php    
        $sum =0;
        $sum1 =0;
    @endphp
    @foreach($consulta as $c)
        <tr>
                <td style = "border: 1 solid #000000;">{{$c->codi}} </td>
                <td style = "border: 1 solid #000000;">{{$c->descripcion}}</td>
                <td style = " text-align: right; border: 1 solid #000000;">{{number_format($c->ingreso_ant -$c->salida_ant,2,'.',',')}}</td>
                <td style = " text-align: right; border: 1 solid #000000;">{{number_format($c->ingreso,2, '.',',')}}</td>
                <td style = " text-align: right; border: 1 solid #000000;">{{number_format($c->salida,2, '.',',')}}</td>
                <td style = " text-align: right; border: 1 solid #000000;">{{number_format($c->ingreso_ant - $c->salida_ant + $c->ingreso - $c->salida,2,'.',',')}}</td>
                <td style = " text-align: right; border: 1 solid #000000;">{{number_format(($c->total_ingreso_ant - $c->total_salida_ant) ,2, '.',',')}}</td>
                <td style = " text-align: right; border: 1 solid #000000;">{{number_format($c->total_ingreso ,2, '.',',')}}</td>
                <td style = " text-align: right; border: 1 solid #000000;">{{number_format($c->total_salida,2, '.',',')}}</td>
                <td style = " text-align: right; border: 1 solid #000000;">{{number_format(($c->total_ingreso_ant - $c->total_salida_ant + $c->total_ingreso - $c->total_salida),2, '.',',')}}</td>
            
            </tr>    
            @php
                $sum = $sum + ($c->ingreso_ant + $c->ingreso - $c->salida_ant  - $c->salida);
                $sum1 = $sum1 + ($c->total_ingreso +$c->total_ingreso_ant  - $c->total_salida_ant  - $c->total_salida);
            @endphp
        @endforeach
</tbody>
<tfoot>
<tr>
            <td style = "border: 1 solid #000000;" colspan = "5">Total</td>
            <td style = " text-align: right; border: 1 solid #000000;">{{number_format($sum,2,'.',',')}}</td>
            <td style = "border: 1 solid #000000;" colspan = "3"></td>
            <td style = " text-align: right; border: 1 solid #000000;">{{number_format($sum1,2,'.',',')}}</td>
        </tr>
</tfoot>
  </table>