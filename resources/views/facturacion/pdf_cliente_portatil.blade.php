<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura</title>
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
            border-collapse: collapse;
        }
        .datosnegrita{
            font-weight: bold;
        }
        .centro{
            text-align: center;
        }
        .mitad{
            width:50%; 
        }
        .detalle1{
            width:70%; 
        }
        .detalle2{
            width:30%; 
        }
        .entro{
            width:100%; 
        }
        .textoder{
            text-align: right !important;
        }
        html {
            height: 100%;
            width:35%;
        }
        body {
            min-height: 100%;
            min-width:35%;
        }
    </style>    
</head>

<body>
<div id = "oculatar">
    <table class = "centro" >
        <tr><td class = "datosnegrita">FACTURA</td></tr>
        <tr><td class = "datosnegrita">CON DERECHO A CREDITO FISCAL</td></tr>
        <tr><td>{{$datos_empresa->razon_social}}</td></tr>
        <tr><td>{{$factura->descripcion}}</td></tr>
        <tr><td>Nro Punto de Venta {{$factura->punto_venta}}</td></tr>
        <tr><td>{{$factura->direccion}}</td></tr>
        <tr><td>Tel.: {{$factura->telefono}} </td></tr>
        <tr><td>{{$factura->municipio}}</td></tr>
        <tr><td class = "datosnegrita">- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</td></tr>

        <tr><td class = "datosnegrita"> NIT</td></tr>
        <tr><td>{{$datos_empresa->nit}}</td></tr>
        <tr><td  class = "datosnegrita">FACTURA Nro.</td></tr>
        <tr><td>@php
                    if($factura->id_factura == 0){
                        $nro_fac = $factura->nro_fac_manual;
                    }else{
                        $nro_fac = $factura->id_factura;
                    }

                @endphp
                {{$nro_fac}}
        </td></tr>
        <tr><td>COD. AUTORIZACIÓN</td></tr>
        <tr><td>  
            <?php
                $str = "Hello world!";
                echo chunk_split($factura->cuf,30, "<br>");
            ?> 
            </td></tr>
        <tr><td class = "datosnegrita">- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</td></tr>

        <tr>
            <td>
                <table class = "entro">
                    <tr>
                        <td class = "datosnegrita mitad">NOMBRE/RAZON SOCIAL:</td>
                        <td class = "mitad">{{$factura->razon_social}}</td>
                    </tr>
                    <tr>
                        <td class = "datosnegrita mitad">NIT/CI/CEX:</td>
                        <td class = "mitad">{{$factura->nro_documento}} &nbsp; {{$factura->complemento}}</td>
                    </tr>
                    <tr>
                        <td class = "datosnegrita mitad">COD. CLIENTE:</td>
                        <td class = "mitad">{{$factura->codigo_cliente}}</td>
                    </tr>
                    <tr>
                        <td class = "datosnegrita mitad">FECHA DE EMISION:</td>
                        <td class = "mitad">{{$factura->fecha_hora}}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr><td class = "datosnegrita">- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</td></tr>
        <tr><td class = "datosnegrita">DETALLE</td></tr>
        <tr>
            <td>
                <table class = "entro">
                @foreach($detalle as $d)
                    <tr>
                        <td colspan = "2" class = "datosnegrita">{{$d->codigo_empresa}}&nbsp;- &nbsp;{{$d->descripcion}}</td>
                        
                    </tr>
                    <tr>
                        <td class = "detalle1">{{ number_format($d->cantidad,2) }} &nbsp;X &nbsp; {{number_format($d->precio,2)}}</td>
                        <td class = "detalle2 textoder">{{number_format($d->subtotal,2)}}</td>
                    </tr>                
                @endforeach
                </table>
            </td>
        </tr>
        <tr><td class = "datosnegrita">...................................................................................................</td></tr>
        <tr>
            <td>
                <table class = "entro">
                    <tr>
                        <td class = "detalle1 textoder">SUB TOTAL Bs</td>
                        <td class = "detalle2 textoder">{{number_format($factura->monto_total,2)}}</td>
                    </tr>
                    <tr>
                        <td class = "detalle1 textoder">DESCUENTO Bs</td>
                        <td class = "detalle2 textoder">0.00</td>
                    </tr>
                    <tr>
                        <td class = "detalle1 textoder">TOTAL Bs</td>
                        <td class = "detalle2 textoder">{{number_format($factura->monto_total,2)}}</td>
                    </tr>
                    <tr>
                        <td class = "detalle1 textoder">MONTO GIFT CARD Bs</td>
                        <td class = "detalle2 textoder">0.00</td>
                    </tr>
                    <tr>
                        <td class = "detalle1 textoder datosnegrita">MONTO A PAGAR Bs</td>
                        <td class = "detalle2 textoder">{{number_format($factura->monto_total,2)}}</td>
                    </tr>
                    <tr>
                        <td class = "detalle1 textoder datosnegrita">IMPORTE BASE CREDITO FISCAL Bs.</td>
                        <td class = "detalle2 textoder">{{number_format($factura->monto_total,2)}}</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td>Son : {{$literalb}} Bolivianos</td></tr>
        <tr><td class = "datosnegrita">- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - </td></tr>
        <tr><td>ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS, EL USO ILÍCITO SERÁ SANCIONADO PENALMENTE DE ACUERDO A LEY </td></tr>
        <tr></tr>
        <tr><td>{{$leyenda2->descripcion_leyenda}}</td></tr>
        <tr></tr>
        <tr><td>{{$leyenda3}}</td></tr>
        <tr><td>&nbsp;</td></tr>
        <tr><td><img  src="data:image/png;base64,'.{{$qr}}.'"></td></tr>
        <tr>
            <td>
                <table class = "entro">
                    <tr>
                        <td class = "textoder"><b>Usuario:</b>&nbsp;{{$dato_usuario}} </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
