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
        border:solid 1px black;
        margin-top: 10px;
    }
    .t1col1{
        width:35px;
    }
    .t1col11{
        width:280px;
    }
    .t1col2{
        width:60px;
    }
    .t1col3{
        width:220px;
    }
    
    .t1col5{
        width:90px;
    }
    td{
        padding-left:7px;
        padding-right:7px;
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
        font-size:0.51rem !important;
    }
</style>
</head>
<body>
<div>
    <h3  class = "titulo">CARSUR</h3>
</div>
<div>
    <center>
        <h4 class = "titulo2">
            RESUMEN MENSUAL<br>
            Fecha inicial: {{$fechaini}} Fecha Final: {{$fechafin}} <br>
            Sucursal: {{$sucu->descripcion}}
        </h4>
         
    </center>
    
</div>
<div>
    <table border = 1px>
        <thead>
            <tr>
                <th Rowspan ="2" class = "t1col1">Codigo</th>
                <th Rowspan ="2" class = "t1col11">Descripcion</th>
                <th Rowspan ="2" class = "t1col2">Saldo Inicial</th>
                <th colspan = "3" class = "t1col3">Cantidad </th>
                <th Rowspan ="2" class = "t1col2">Importe Inicial</th>
                <th colspan = "3" class = "t1col3">Bolivianos </th>
            </tr>
            <tr>
                <th>Ingresos</th>
                <th>Egresos</th>
                <th>Saldo</th>


                <th>Ingresos</th>
                <th>Egresos</th>
                <th>Saldo</th>
            </tr>    
        </thead>
        <tbody>
            @php
                $sum = 0;
                $sum1 = 0;
            @endphp
            @foreach($consulta as $c)
            <tr class ="datosnumericos">
                <td class = "textoizq">{{$c->codi}} </td>
                <td>{{$c->descripcion}}</td>
                <td class = "textoizq">{{number_format(($c->ingreso_ant - $c->salida_ant),2,'.',',')}}</td>
                <td class = "textoizq">{{number_format($c->ingreso,2, '.',',')}}</td>
                <td class = "textoizq">{{number_format($c->salida,2, '.',',')}}</td>
                <td class = "textoizq">{{number_format($c->ingreso_ant + $c->ingreso - $c->salida_ant  - $c->salida,2,'.',',')}}</td>
                <td class = "textoizq">{{number_format(($c->total_ingreso_ant - $c->total_salida_ant) ,2, '.',',')}}</td>
                <td class = "textoizq">{{number_format($c->total_ingreso ,2, '.',',')}}</td>
                <td class = "textoizq">{{number_format($c->total_salida,2, '.',',')}}</td>
                <td class = "textoizq">{{number_format(($c->total_ingreso +$c->total_ingreso_ant  - $c->total_salida_ant  - $c->total_salida),2, '.',',')}}</td>

            </tr>    
            @php
                $sum = $sum + $c->ingreso_ant + $c->ingreso - $c->salida_ant  - $c->salida;
                $sum1 = $sum1 + $c->total_ingreso +$c->total_ingreso_ant  - $c->total_salida_ant  - $c->total_salida;
            @endphp
            @endforeach

        </tbody>
        <tfoot>
            <tr class ="datosnumericos">
                <td colspan = "5"></td>
                <td class = "textoizq">{{number_format($sum,2)}}</td>
                <td colspan = "3"></td>
                <td class = "textoizq">{{number_format($sum1,2)}}</td>
            </tr>
        </tfoot>
    </table>
</div>

    
          
            
    

          
    
    
  
</body>
</html>