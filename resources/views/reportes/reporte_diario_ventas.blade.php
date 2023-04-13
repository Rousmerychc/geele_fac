
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
            margin-bottom:0.5cm;
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
            
        }
        .tabla{
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
        table th{
            border-top: 0px;
            border-right: 0px;
            border-bottom: 0px ;
            border-left: 0px;
        }
        table td{
            border:solid 1px black;
            padding-left:5px;
            padding-right:5px;
        }

</style>

</head>

<body>
<table class = "tabla" >
    <tr><th colspan = "3" class = "negrita"><center>CARSUR</center></th></tr> 
    <tr><th colspan = "3" class = "negrita"><center>Movimiento Diario</center></th></tr>
    <tr><th colspan = "3" class = "negrita"> <center>Fecha inicial: {{$fechaini}} Fecha Final: {{$fechafin}}</center></th></tr>
       
    @php
        $sum =0;
    @endphp
    @foreach($operadores as $ope)
        @php
            $sum_ope =0;
        @endphp
        <tr>
            <td colspan = "3"> <b>Operador:</b> {{$ope->id}} - {{$ope->nombre}} {{$ope->apellido}}</td>
        </tr>
    
        <tr>
            <td class = "td3 negrita">Nro Fac. / Razon Social</td>
            <td colspan = "2" class = "td5 negrita">Fecha / Monto /Estado</td>
            <!-- <td>&nbsp;</td> -->
        </tr>

        @foreach($consulta as $con)
            @if($con->id_usuario == $ope->id)
                <tr>
                    <td class = "td1">{{$con->id_factura}}</td>
                    <td class = "td5" colspan = "2"> {{$con->fecha}}</td>
                </tr>            
                <tr>
                    <td class = "td1">{{$con->razon_social}}</td>
                    <td class = "td5 textoizq">{{number_format($con->monto_total,2)}}</td>
                    @if($con->estado == 1)
                        <td class = "td4"> <b>A</b></td>
                    @else
                    <td class = "td4"></td>
                        @php
                            $sum_ope =$sum_ope + $con->monto_total ;
                        @endphp
                    @endif
                </tr>
                
            @endif
        @endforeach
        
        </tr>
        <tr>
            <td class = "textoizq td1 negrita">Subtotal Bs.:</td>
            <td class = "textoizq td1">{{number_format($sum_ope,2)}}</td>
            <td></td>
            @php
                $sum = $sum + $sum_ope;
            @endphp
        </tr>
       
    @endforeach
    <tr>
        <td class = "textoizq td1 negrita">Total General Bs.:</td>
        <td class = "textoizq td1"> {{number_format($sum,2)}}</td>
        <td></td>
    </tr>
        
    </table>
</div>
</body>
</html>

