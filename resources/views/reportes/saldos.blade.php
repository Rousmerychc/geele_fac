
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
        font-size:0.9rem;
        border:solid 1px black;
        margin-top: 10px;
    }
    .t1col1{
        width:60px;
    }
    .t1col2{
        width:280px;
    }
    .t1col3{
        width:100px;
    }
   
    td{
        padding-left:10px;
        padding-right:10px;
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
    .datosnumericos{
        font-size:0.7rem !important;
    }
</style>

</head>

<body>


<div>
    <center>
        <h4 class = "titulo2">
            CARSUR <br>
            SALDOS DE PRODUCTOS <br>
            Hasta la Fecha: {{$fechaini}} <br>
            Sucursal : {{$sucursal->descripcion}}
    </center>
    
</div>

<div>
    <table border = 1px>
        <thead>
            <tr>
                <th class = "t1col1">Codigo Producto</th>
                <th class = "t1col2">Descripcion</th>
                <th class = "t1col3">Cantidad </th>
                
            </tr>    
        </thead>
        <tbody>
            @php
                $sum =0;
            @endphp

            @foreach($consulta as $c)
            @php
                $sum = $sum + ($c->ingresos - $c->salida);
            @endphp
           
            <tr class = "datosnumericos">
                <td class = "textoizq">{{$c->codigo_empresa}}</td>
                <td>{{$c->descripcion}}</td>
                <td class = "textoizq">{{$c->ingresos - $c->salida}}</td>
            </tr>
                
            @endforeach
            <tr>
                <td colspan = "2">Total</td>
                <td class = "textoizq"> {{$sum}}</td>
            </tr>
        </tbody>
    </table>
</div>
</body>
</html>

