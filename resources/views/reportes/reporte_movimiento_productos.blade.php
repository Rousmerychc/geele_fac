        

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
<style>
     @page {
            margin-left:0.5cm;
            margin-right:0.5cm;
            margin-top:0cm;
            margin-bottom:2cm;
        }
        body{
            font-size: 50%;
            margin:0px;
            padding:0px;
            font-family:sans-serif !important;
        }
        table {
            width:100%;
            border-collapse: collapse;
            page-break-inside: avoid;
        }
    
        .td1{
            width:50%;
        }
        .td2{
            width:20%;
        }
        .td3{
            width:65%;
        }
        .td4{
            width:8%;
        }
        .td5{
            width:35%;
        }
        .textoizq{
            text-align: right;
        }
        .negrita{
            font-weight: bold;
        }
        td{
        padding-left:3px;
        padding-right:3px;
        }

</style>

</head>

<body>
<table >
    <tr><td class = "negrita"><center>CARSUR</center></td></tr> 
    <tr><td class = "negrita"><center>Movimiento Diario Productos</center></td></tr>
    <tr><td class = "negrita"> <center>Fecha inicial: {{$fechaini}} Fecha Final: {{$fechafin}}</center></td></tr>
       
    @php
        $sum =0;
    @endphp
    @foreach($operadores as $ope)
        @php
            $sum_ope =0;
        @endphp
        <tr>
            <td> <b>Operador:</b> {{$ope->id}} - {{$ope->nombre}} {{$ope->apellido}}</td>
        </tr>
        
        <tr>
            <table border = 1px>
                <tr>
                    <td class = "negrita">Cantidad / Producto</td>
                    <td class = "negrita">Monto Bs.</td>
                </tr>

        @foreach($consulta as $con)
            @if($con->id_usuario == $ope->id)
            
                <tr>
                    <td colspan = "2" class = "negrita">{{$con->codigo_producto_empresa}} - {{$con->descripcion}}</td>
                </tr>
                 <tr>
                    <td>{{$con->cantidad}}</td>
                    <td class = "textoizq">{{number_format($con->precio,2)}}</td>
                 </tr>   
                @php
                    $sum_ope =$sum_ope + $con->precio ;
                @endphp
            @endif
        @endforeach
        </table>
        </tr>
        <tr>
            <table>
                <tr>
                    <td class = "textoizq td1 negrita">Subtotal Bs.:</td>
                    <td class = "textoizq td1">{{number_format($sum_ope,2)}}</td>
                </tr>
            </table>
            
            @php
                $sum = $sum + $sum_ope;
            @endphp
        </tr>
       
    @endforeach
    <tr>
        <table>
            <tr>
                <td class = "textoizq td1 negrita">Total General Bs.:</td>
                <td class = "textoizq td1"> {{number_format($sum,2)}}</td>
            </tr>
            
        </table>
        
    </tr>
        
    </table>
</div>
</body>
</html>

