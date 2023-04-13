
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
            font-size: 0.7rem;
            margin:15px;
            padding:15px;
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
            width:70px;
        }
        .td2{
            width:100px;
        }
        .td3{
            /* width:150px; */
        }
        .td4{
            width:120px;
        }
        .td5{
            width:50px;
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
        .titulo{
            font-size:0.8rem !important;
            /* border-style:none: */
        }
        .titulo2{
            padding-bottom : 20px !important;
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
    
<table class = "tabla">
    <thead>

    <tr> <th colspan ="5" class = "negrita titulo"><center>CARSUR</center></th></tr> 
    <tr> <th colspan ="5" class = "negrita titulo"><center>Movimiento Diario</center></th></tr>
    <tr> <th colspan ="5" class = "negrita titulo"> <center>Fecha inicial: {{$fechaini}} Fecha Final: {{$fechafin}}</center></th></tr>
       
    @php
        $sum =0;
        $sum_ope =0;
    @endphp
    <tr>
            <td class = "negrita td1">Nro Fac.</td>
            <td class = "negrita td2">Fecha</td>
            <td class = "negrita td3">Razon Social</td>
            <td class = "negrita td4">Monto</td>
            <td class = "negrita td5">Estado</td>
            
        </tr>
    </thead>
    @if($operadores->isEmpty())
        <tbody>
            @foreach($consulta as $con)
                <tr>
                    <td class = "td1">{{$con->id_factura}}</td>
                    <td class = "td2"> {{$con->fecha}}</td>
                    <td class = "td3">{{$con->razon_social}}</td>
                    <td class = "td4 textoizq">{{number_format($con->monto_total,2)}}</td>
                    @if($con->estado == 1)
                        <td class = "td5"> <b>A</b></td>
                    @else
                    <td class = "td5"></td>
                        @php
                            $sum_ope =$sum_ope + $con->monto_total ;
                        @endphp
                    @endif
                </tr>
            @endforeach
        </tbody>    
        <tfoot>  
            <tr>
                <td colspan = "2"></td>
                <td class = "textoizq negrita">Total General Bs.:</td>
                <td class = "textoizq "> {{number_format($sum_ope,2)}}</td>
                <td></td>
            </tr>
        </tfoot>
    @else
    <tbody>
    @foreach($operadores as $ope)
        <tr>
            <td colspan = "5"> <b>Operador:</b> {{$ope->id}} - {{$ope->nombre}} {{$ope->apellido}}</td>
        </tr>
        @php
            $sum_ope =0;
        @endphp
            @foreach($consulta as $con)

                <tr>
                    <td class = "td1">{{$con->id_factura}}</td>
                    <td class = "td2"> {{$con->fecha}}</td>
                    <td class = "td3">{{$con->razon_social}}</td>
                    <td class = "td4 textoizq">{{number_format($con->monto_total,2)}}</td>
                    @if($con->estado == 1)
                        <td class = "td5"> <b>A</b></td>
                    @else
                    <td class = "td5"></td>
                        @php
                            $sum_ope =$sum_ope + $con->monto_total ;
                        @endphp
                    @endif
                </tr>
            @endforeach
            <tr>
                <td colspan = "2"></td>
                <td class = "textoizq negrita">Total Operador en Bs.:</td>
                <td class = "textoizq "> {{number_format($sum_ope,2)}}</td>
                <td></td>
            </tr>
            @php
                $sum = $sum + $sum_ope;
            @endphp

    @endforeach
        </tbody>    
        <tfoot>  
            <tr>
                <td colspan = "2"></td>
                <td class = "textoizq negrita">Total General en Bs.:</td>
                <td class = "textoizq "> {{number_format($sum,2)}}</td>
                <td></td>
            </tr>
        </tfoot>
   
    

    @endif

</table>
    
</div>
</body>
</html>

