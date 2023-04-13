<table>
<thead>

    <tr>
        <th colspan = "9" style ="text-align: center; font-size: 18; font-weight: bold;">CARSUR </th>
    </tr>  

    <tr>
        <th colspan = "9" style ="text-align: center; font-size: 14; font-weight: bold;"> KARDEX POR PRODUCTO </th>
    </tr>
    <tr>
        <th colspan = "9" style ="text-align: center; font-size: 10; font-weight: bold;"> Fecha inicial: {{$fechaini}} Fecha Final: {{$fechafin}}</th>
    </tr>
    <tr>
        <th colspan = "9" style ="text-align: center; font-size: 10; font-weight: bold;">Sucursal: {{$sucursal->descripcion}}</th>
    </tr>
    <tr>
        <th colspan = "9" style ="text-align: center; font-size: 10; font-weight: bold;">Producto: {{$producto->descripcion}}</th>
    </tr>       
    <tr>
        <th rowspan ="2" style="width:90px; text-align: center; background-color: #98DCC2; border: 1 solid #000000; font-weight: bold;">Nro Registro</th>
        <th rowspan ="2" style="width:100px; text-align: center; background-color: #98DCC2; border: 1 solid #000000; font-weight: bold;">Fecha</th>
        <th rowspan ="2" style="width:250px; text-align: center; background-color: #98DCC2; border: 1 solid #000000; font-weight: bold;">Proveedor / Cliente</th>
        <th rowspan ="2" style="width:100px; text-align: center; background-color: #98DCC2; border: 1 solid #000000;  font-weight: bold;">Precio <br> Unitario </th>
        <th colspan = "3" style=" text-align: center; background-color: #98DCC2; border: 1 solid #000000;  font-weight: bold;">Cantidades</th>
        <th colspan = "2" style=" text-align: center; background-color: #98DCC2; border: 1 solid #000000;  font-weight: bold;">Bolivianos</th>
    </tr>    
    <tr>
                    
        <th style ="width:100px; text-align: center; background-color: #98DCC2; border: 1 solid #000000; font-weight: bold;">Cantidad <br> Ingreso</th>
        <th style ="width:100px; text-align: center; background-color: #98DCC2; border: 1 solid #000000; font-weight: bold;">Cantidad <br> Salida</th>
        <th style ="width:100px; text-align: center; background-color: #98DCC2; border: 1 solid #000000; font-weight: bold;">Saldo</th>
        <th style ="width:100px; text-align: center; background-color: #98DCC2; border: 1 solid #000000; font-weight: bold;">Saldo <br> Ingreso</th>
        <th style ="width:100px; text-align: center; background-color: #98DCC2; border: 1 solid #000000; font-weight: bold;">Saldo <br> Salida</th>
    </tr>    
</thead>
<tbody>
    <tr>
        <td colspan = "6">Saldo anterior</td>
        <td style = " text-align: right; border: 1 solid #000000;">{{$resul}}</td>
        <td style = " text-align: right; border: 1 solid #000000;" colspan = "2"></td>
    </tr>
    @php
        $sum =$resul;
    @endphp
    @foreach($consulta as $c)
        <tr>
            @if($c->id_factura != 0)
                <td style = " text-align: right; border: 1 solid #000000;">{{$c->id_factura}}</td>
            @else
                @if($c->nro_fac_manual != 0)
                    <td style = " text-align: right; border: 1 solid #000000;">{{$c->nro_fac_manual}}</td>
                @else 
                    <td style = " text-align: right; border: 1 solid #000000;">{{$c->nro_nota_venta}}</td>
                @endif   
            @endif
            <td style ="border: 1 solid #000000;">{{$c->fecha}}</td>
            <td style ="border: 1 solid #000000;">{{$c->descrip_rp}}</td>
            <td style = " text-align: right; border: 1 solid #000000;">{{number_format($c->precio,2, '.',',')}}</td>
            @if($c->tipo == "I")
                <td style = " text-align: right; border: 1 solid #000000;">{{number_format($c->cantidad,2, '.',',')}}</td>
                <td style ="border: 1 solid #000000;"></td>
                @php
                    $resul = $resul + $c->cantidad;
                @endphp
            @else
                <td style ="border: 1 solid #000000;"></td>
                <td style = " text-align: right; border: 1 solid #000000;">{{number_format($c->cantidad,2,'.',',')}}</td>
                @php
                    $resul = $resul - $c->cantidad;
                @endphp
            @endif
            <td style = " text-align: right; border: 1 solid #000000;">{{number_format($resul,2, '.',',')}}</td>
            @if($c->tipo == "I")
                <td style = " text-align: right; border: 1 solid #000000;">{{number_format($c->cantidad * $c->precio,2, '.',',')}}</td>
                <td style ="border: 1 solid #000000;"></td>
            @else
            <td style ="border: 1 solid #000000;"></td>
            <td style = " text-align: right; border: 1 solid #000000;">{{number_format($c->cantidad * $c->precio, 2,'.',',')}}</td>
            @endif
        </tr>
        
    @endforeach
           
    
</tbody>

  </table>
  