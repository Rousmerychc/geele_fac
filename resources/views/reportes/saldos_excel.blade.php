<table>
<thead>


<tr>
    <th colspan = "3" style ="text-align: center; font-size: 16; font-weight: bold;">CARSUR</th>
</tr>  

<tr>
    <th colspan = "3" style ="text-align: center; font-size: 14; font-weight: bold;"> SALDOS DE PRODUCTOS </th>
</tr>
<tr>
    <th colspan = "3" style ="text-align: center; font-size: 12; font-weight: bold;"> Hasta la Fecha: {{$fechaini}}</th>
</tr>
<tr>
    <th colspan = "3" style ="text-align: center; font-size: 12; font-weight: bold;"> Sucursal: {{$sucursal->descripcion}}</th>
</tr>

         
<tr>
    <th style="width:100px; text-align: center; background-color: #98DCC2; border: 1 solid #000000; font-weight: bold;">Codigo Producto</th>
    <th style="width:300px; text-align: center; background-color: #98DCC2; border: 1 solid #000000; font-weight: bold;">Descripcion</th>
    <th style="width:200px; text-align: center; background-color: #98DCC2; border: 1 solid #000000;  font-weight: bold;">Cantidad</th>
    
</tr>    

<tbody>
    @php
         $sum =0;
    @endphp
    @foreach($consulta as $c)
    @php
        $sum = $sum + ($c->ingresos - $c->salida);
    @endphp 
    <tr>
        <td style ="border: 1 solid #000000;">{{$c->codigo_empresa}}</td>
        <td style ="border: 1 solid #000000;">{{$c->descripcion}}</td>
        <td style = " text-align: right; border: 1 solid #000000;">{{$c->ingresos - $c->salida}}</td>
        
    </tr>
    @endforeach
    <tr>
        <td colspan = "2" style ="border: 1 solid #000000;">Total</td>
        <td style = " text-align: right; border: 1 solid #000000;"> {{$sum}}</td>
    </tr>
     
</tbody>

  </table>