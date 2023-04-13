
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
<style>
     @page {
		margin-left:1cm;
		margin-right: 1cm;
        margin-top:1cm;
	}
    table {
        border-collapse: collapse;
        width:100%;  
        font-size:0.7rem;
       
        margin-top: 10px;
        page-break-inside: avoid;
       
    }
    .t1col1{
        width:30px;
    }
    .t1col11{
        width:55px;
    }
    .t1col2{
        width:220px;
    }
    .t1col3{
        width:55px;
    }
    .t1col4{
        width:50px;
    }
    .t1col5{
        width:55px;
       
    }
    .t1col6{
        width:60px;
    }
    td{
        padding-left:5px;
        padding-right:5px;
    }
    .titulo{
        padding-top:2px !important;
        padding-bottom: 5px !important;
        margin: 0px;
    }
    .titulo2{
        padding-top:2px !important;
        padding-bottom: 5px !important;
        margin: 0px;
    }
    .textoizq{
        text-align: right;
    }
    .negrita{
        font-weight: bold;
    }
    table th{
        border:none;
            border-top: 0px;
            border-right: 0px;
            border-bottom: 0px ;
            border-left: 0px;
        }
    table td{
        border:solid 1px black;
    }
    .sinborde{
        border-top: 0px;
            border-right: 0px;
            border-bottom: 0px ;
            border-left: 0px;
    }
</style>

</head>

<body>



<table>
    <thead>
        <tr>
            <th colspan = "9"><h3  class = "titulo">CARSUR</h3></th>
        </tr>
        <tr>
            <th colspan = "9">
                <center>
                <h4 class = "titulo2">
                    KARDEX POR PRODUCTO<br>
                    Fecha inicial: {{$fechaini}} Fecha Final: {{$fechafin}} <br>
                    Sucursal: {{$sucursal->descripcion}} <br>
                    Producto: {{$producto->descripcion}}
                </h4>
                </center>
            </th>
        </tr>
   
        <tr>
            <td Rowspan ="2" class = "t1col1">Nro Registro</td>
            <td Rowspan ="2" class = "t1col11">Fecha</td>
            <td Rowspan ="2" class = "t1col2" >Proveedor / Cliente</td>
            <td Rowspan ="2" class = "t1col4">Precio <br> Unitario</td>
            <td colspan = "3"> Cantidades</td>
            <td colspan = "2" > Bolivianos</td>
        </tr>
        <tr>
            
            <td class = "t1col3">Cantidad <br> Ingreso</td>
            <td class = "t1col3">Cantidad <br> Salida</td>
            <td class = "t1col6">Saldo</td>
            <td class = "t1col5">Saldo <br> Ingreso</td>
            <td class = "t1col5">Saldo <br> Salida</td>
        </tr>    
    </thead>
    <tbody>
        <tr>
            <td colspan = "6">Saldo anterior</td>
            <td class ="textoizq">{{$resul}}</td>
            <td colspan = "2"></td>
        </tr>
        @php
            $sum =$resul;
        @endphp
        @foreach($consulta as $c)
            <tr>
                @if($c->id_factura != 0)
                    <td class ="textoizq">{{$c->id_factura}}</td>
                @else
                    @if($c->nro_fac_manual != 0)
                        <td class ="textoizq">{{$c->nro_fac_manual}}</td>
                    @else 
                        <td class ="textoizq">{{$c->nro_nota_venta}}</td>
                        @endif   
                @endif
                <td>{{$c->fecha}}</td>
                <td>{{$c->descrip_rp}}</td>
                <td class ="textoizq">{{number_format($c->precio,2, '.',',')}}</td>
                @if($c->tipo == "I")
                    <td class ="textoizq">{{number_format($c->cantidad,2, '.',',')}}</td>
                    <td></td>
                    @php
                        $resul = $resul + $c->cantidad;
                    @endphp
                @else
                    <td></td>
                    <td class ="textoizq">{{number_format($c->cantidad,2,'.',',')}}</td>
                    @php
                        $resul = $resul - $c->cantidad;
                    @endphp
                @endif
                <td class ="textoizq">{{number_format($resul,2, '.',',')}}</td>
                @if($c->tipo == "I")
                    <td class ="textoizq">{{number_format($c->cantidad * $c->precio,2, '.',',')}}</td>
                    <td></td>
                @else
                <td></td>
                <td class ="textoizq">{{number_format($c->cantidad * $c->precio, 2,'.',',')}}</td>
                @endif
            </tr>
            
        @endforeach
        
    </tbody>
</table>

</body>
</html>

