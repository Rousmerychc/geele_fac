@extends('layouts.index')

@section('content')   
<div class ="divpagina">
<form method="POST" action="{{ url('/ingreso/'.$ingreso->id) }}" autocomplete="off" id ="formulario">
@csrf
    @method('PATCH')
<div class ="divpagina">
    <div class ="titulobotonagregar">
        <div><h3 class="titulos"><i class="fas fa-angle-double-right"></i> EDITAR INGRESO</h3></div> 
    </div>
   


    <div  class="divformulario">
        <div class = "titulo_form_fac">
            <h6>Datos:</h6>
        </div>
        <div class ="datosingreso row ">
            
        {{$ingreso->id_sucursal}}
                <div class=" form-group col-md-2 labelliquidaciones">
                    <label for="name" class="titulonrofac ">{{ __('Fecha:') }}</label>
                    <input name="fecha" id = "fecha" type="date" class="form-control " value="{{$ingreso->fecha}}"  required>
                </div>

                <div class="form-group col-md-5 labelliquidaciones">
                    <label for="name">{{ __('Sucursal:') }}</label>
                    <select name="id_sucursal" id = "id_sucursal" type = "number" class="form-control" required>
                        <option value=""></option>
                        @foreach($sucursal as $sucu)
                            @if($sucu->nro_sucursal == $ingreso->id_sucursal)
                                <option value=" {{$sucu->nro_sucursal}} "selected>{{$sucu->descripcion}}  </option>
                            @else
                            <option value=" {{$sucu->nro_sucursal}} ">{{$sucu->descripcion}}  </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-5 labelliquidaciones">
                    <label for="name">{{ __('Prveedor:') }}</label>
                    <select name="id_proveedor" id = "id_proveedor" type = "number" class="form-control" required>
                        <option value=""></option>
                        @foreach($proveedor as $pro)
                            @if($pro->id == $ingreso->id_proveedor)
                                <option value=" {{$pro->id}} " selected>{{$pro->descripcion}}  </option>
                            @else
                            <option value=" {{$pro->id}} " >{{$pro->descripcion}}  </option>
                            @endif
                        
                        @endforeach
                    </select>
                </div>

        </div>
    </div>

    <div class="divformulario">
        <div class = "titulo_form_fac">
            <h6>Detalle Productos:</h6>
        </div> 
        <div class = "row">
            <div class=" block form-group col-md-10">
                <label for="name">{{ __('Producto:') }}</label>
                <select name="id_producto" type = "number" class="form-control  select2" id = "id_producto">
                    <option value=""></option>
                    @foreach($productos as $pro)
                    <option value=" {{$pro->id}} ">{{$pro->codigo_empresa}} &nbsp; - &nbsp;{{$pro->descripcion}}</option>
                    @endforeach
                </select>
            </div>
            
            <div class = "boton_agregar_producto">
                <button type="button" onclick="agregar();" class="btn btn-outline-info btn-sm" > Agregar</button> 
            </div>
            
        </div>   
            
        <div class="table-responsive">
            <table id ="detalle" class="table table-bordered">
                <thead  class="table-success">
                    <tr>
                        <th>Accion</th>
                        <th style="display:none;">id</th>
                        <th style="width : 100px;">Codigo</th>
                        <th style="width : 150px;">Cantidad</th>
                        <th style="width : 200px;">Unida Medida</th>
                        <th>Descripcion</th>                        
                        <th style="width : 150px;">Precio Unitario</th>
                        <th style="width : 150px;">Subtotal</th>                        
                    </tr>
                </thead>
                <tbody>
                    @php
                        $cont = 0;
                    @endphp 
                    @foreach($ingreso_detalle as $ide)
                    <tr id ="fila{{$cont}}">
                        <td style="display:none;"><input type="text"  name="codigo_pro[]" readonly value="{{$ide->id}}" ></td>
                        <td style="display:none;"><input type="text"  name="codigo_impuestos[]" readonly value="{{$ide->id_producto_impuetos}}" ></td>
                        <td class = "padding_tabla" style="width : 60px;"><button type="button" class="borrar btn btn-outline-danger btn-sm texto_tabla" onClick="eliminarfila()" ><i class="fas fa-times"></i></i></button></td>
                        <td class = "padding_tabla"><input type="text" class="sinborde" style="width : 35px; " name="codigo[]" value="{{$ide->codigo_empresa}}"  readonly></td>
                        <td class = "padding_tabla"><input type="double" class ="alinacionderecha1 cantidad"  style="width :130px;" name="cantidad[]" id ="cantidad{{$cont}}" onblur="calcula()"value="{{$ide->cantidad}}" onkeypress="return (event.charCode >= 46 && event.charCode <= 57)" ></td>
                        <td style="display:none;" class = "padding_tabla" ><input type="text" style="width :55px;" class=" sinborde alinacionderecha1" id = "codigo_unidadmedida{{$cont}}" name="codigo_unidad_medida[]"  value="{{$ide->codigo_unidad_medida}}" readonly></td>
                        <td class = "padding_tabla" ><input type="text" style="width :150px;" class=" sinborde alinacionderecha1" id = "unidadmedida{{$cont}}" name="unidad_medida[]"  value="{{$ide->unidad_medida}}" readonly></td>
                        <td class = "padding_tabla"><input style="width : 190px;" type="text" class=" input_desc_fac sinborde" id = "descripcion{{$cont}}" name="descripcion[]"  value="{{$ide->descripcion}}" readonly>
                        <td class = "padding_tabla"><input style="width : 120px;" type="double" class = "alinacionderecha1 input_uni_subt_fac preciounitario" id = "precio_unitario{{$cont}}" name="precio_uni[]"  onblur="calcula()" value="{{$ide->precio}}" onkeypress="return (event.charCode >= 46 && event.charCode <= 57)"></td>
                        <td class = "padding_tabla"><input style="width : 120px;" type="double" class=" alinacionderecha1  input_uni_subt_fac sinborde subtotal" id = "subtotal{{$cont}}" name="subtotal[]" onblur="calcula(this.form)" value="{{$ide->subtotal}}" readonly></td></tr>
                    
                        @php
                            $cont++;
                        @endphp 
                    @endforeach
                    <input type="hidden"id = "contador" value = "{{$cont}}">
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan ="5"></td><td>TOTAL</td>
                        
                        <td ><input type="double" class="sinborde alinacionderecha1  input_uni_subt_fac" id ="total_detalle" name="total_detalle" readonly ></td>
                        
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

      
     <div class="botones_atras_guardar">          
        <div class="botonatras">
            <a class="btn btn-outline-danger" href="{{  action('IngresosController@index') }}" role="button">Cancelar &nbsp <i class="fas fa-times"></i></a>
        </div>

        <div>
            <button type="button" class="btn btn-primary" onclick="validar();"> Guardar </button>
        </div>
        <div style = "display:none">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop" id ="modal_seguro_enviar"> Guardar modal </button>
        </div>
    </div>
    </div>
</form>
</div>                              
<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <div class="modal-body">
        Seguro de Guardar
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal" id = "boton_cancelar">CANCELAR</button>
        <button type="button" class="btn btn-primary"  data-bs-dismiss="modal" onclick="enviar();" id = "boton_enviar">ACEPTAR</button>
      </div>
    </div>
  </div>
</div>        


<!-- Button trigger modal -->
<div style = "display:none">
<button type="button" class="btn btn-primary" id ="modal_respuesta_servidor" data-toggle="modal" data-target="#staticBackdrop1">
  Launch static backdrop modal
</button>
</div>


<!-- Modal -->
<div class="modal fade" id="staticBackdrop1" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Mensaje del Sistema</h5>
      </div>
      <div class="modal-body">
      <label for="" id = "res_nit"></label>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick ="no_continua()"><label for=""  id = "cerrar" ></label></button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" onclick ="si_continua()" id = "boton_si">Si</button>
      
      </div>
    </div>
  </div>
</div>
@endsection


@section('js')
<script>

    var cont =  document.getElementById("contador").value; ;
    var total_detalle = 0;

function  si_continua() {
    $("#validanit").val(1);
}
function no_continua(){
    $("#validanit").val(0); 
}
function validar() {
     sw = 0;

    fecha = document.getElementById("fecha").value;

    f = new Date (fecha);
   
    var d = new Date();
   

    if((f.getTime() > d.getTime())){
        alert("LA FECHA DE EMISION ESTA ENCIMA LA FECHA ACTUAL");
         sw = 1;
         document.getElementById("fecha").focus();
         return;
    }
     
    subtotal = document.getElementsByName("subtotal[]");
    id_sucursal = document.getElementById("id_sucursal").value;
    id_proveedor = document.getElementById("id_proveedor").value;
    

    if(id_sucursal === ""){
        alert("NO SELECCIONO SUCURSAL");
         sw = 1;
         document.getElementById("id_sucursal").focus();
        return
    }
    
    if(id_proveedor === ""){
        alert("NO SELECIONO PROVEEDOR");
         sw = 1;
         document.getElementById("id_proveedor").focus();
        return
    }
    
  

    if(subtotal.length>0){
        for(var j=0; j<subtotal.length;j++){
            cadena =String(subtotal[j].value)
            if( cadena === "NaN.NaN" || cadena === "0.00000" || cadena === "0"){
                alert('VALORES DE PRECIO O CANTIDAD INVALIDO')
                sw=1;
                document.getElementById("cantidad0").focus();
                return
            }       
        }
    }else{
        sw = 1;
        alert('NO INTRODUJO PRODUCTOS')
        document.getElementById("id_producto").focus();
        return
    }

    if(sw == 0){
        document.getElementById("modal_seguro_enviar").click();
    }
}

function enviar(){
    boton_enviar = document.getElementById("boton_enviar");
    boton_enviar.disabled = true;

    boton_cancelar = document.getElementById("boton_cancelar");
    boton_cancelar.disabled = true; 
    
    form1= document.getElementById("formulario");
    form1.submit();
    
    
}

function eliminarfila(){
    $(document).on('click', '.borrar', function (event) {
        event.preventDefault();
        $(this).closest('tr').remove();
    });

    setTimeout(function(){
       calcula();
    }, 1000);
   
}

function agregar(){
    var pro = document.getElementById("id_producto").value; 
    
    if(pro ==""){
        alert("No seleciono Producto");
    }else{

        var parametros={
       "dato": pro,
    };
    $.ajax({
         method:'GET',
         url:"{{ url('producto_fac') }}",
         data:parametros
       
      }).done(function(res){
          var arreglo = JSON.parse(res);
          for (var x = 0; x < arreglo.prueba.length; x++){
              //ROSMERY TIENES QUE QUITAR ESTA LINEA EN EL PROGRAMA DEL SERVIDOR
            // $("#razon_social_clie").val(arreglo.prueba[x].razon_social_cli);
            var linea0 = '<tr id ="fila'+cont+'"><td style="display:none;"><input type="text"  name="codigo_pro[]" readonly value="'+arreglo.prueba[x].id+'" ></td>';
            var linea001 = '<td style="display:none;"><input type="text"  name="codigo_impuestos[]" readonly value="'+arreglo.prueba[x].codigo_impuestos+'" ></td>';
            var linea01 ='<td class = "padding_tabla" style="width : 60px;"><button type="button" class="borrar btn btn-outline-danger btn-sm texto_tabla" onClick="eliminarfila()" ><i class="fas fa-times"></i></i></button></td>'
            var linea02 = '<td class = "padding_tabla"><input type="text" class="sinborde" style="width : 35px; " name="codigo[]" value="'+arreglo.prueba[x].codigo_empresa+'"  readonly></td>'; 
            var linea2 = '<td class = "padding_tabla"><input type="double" class ="alinacionderecha1 cantidad"  style="width :130px;" name="cantidad[]" id ="cantidad'+cont+'" onblur="calcula()"value="0" onkeypress="return (event.charCode >= 46 && event.charCode <= 57)" ></td>';
            var linea41 = '<td style="display:none;" class = "padding_tabla" ><input type="text" style="width :55px;" class=" sinborde alinacionderecha1" id = "codigo_unidadmedida'+cont+'" name="codigo_unidad_medida[]"  value="'+arreglo.prueba[x].codigo_unidad_medida+'" readonly></td>';
            var linea4 = '<td class = "padding_tabla" ><input type="text" style="width :150px;" class=" sinborde alinacionderecha1" id = "unidadmedida'+cont+'" name="unidad_medida[]"  value="'+arreglo.prueba[x].unidad_medida+'" readonly></td>';
            var linea3 = '<td class = "padding_tabla"><input style="width : 190px;" type="text" class=" input_desc_fac sinborde" id = "descripcion'+cont+'" name="descripcion[]"  value="'+arreglo.prueba[x].descripcion+'" readonly>';    
            var linea42 = '<td class = "padding_tabla"><input style="width : 120px; "type="double" class = "alinacionderecha1 input_uni_subt_fac preciounitario" id = "precio_unitario'+cont+'" name="precio_uni[]"  onblur="calcula()" value="" onkeypress="return (event.charCode >= 46 && event.charCode <= 57)"></td>';
            var linea6 = '<td class = "padding_tabla"><input style="width : 120px;type="double" class=" alinacionderecha1  input_uni_subt_fac sinborde subtotal" id = "subtotal'+cont+'" name="subtotal[]" onblur="calcula(this.form)" value="'+0+'" readonly></td></tr>';
            var linea = linea0+linea001+linea01+linea02+linea2+linea41+linea4+linea3+linea42+linea6;
            $('#detalle > tbody:first').append(linea); 
          }
          cont++; 
          console.log(cont);
    });
      
    }     
}


function decimales(cadena){
    posicion = cadena.indexOf('.');  
    decimal = cadena.slice(posicion+1);
    entero= cadena.substring(0,posicion);
    entero1 =  parseFloat(entero).toLocaleString('en')
    final= entero1+'.'+decimal;
    
    return final;   
}
function redondea(valor){
	
	r=(parseInt(valor*100 +0.501))/100

	return r;
}
function calcula(){
    var cantidad = document.getElementsByName("cantidad[]");
    var preciounitario = document.getElementsByName("precio_uni[]");
    var subtotal_a = document.getElementsByName("subtotal[]");
    var sumtot = 0;
    var subtot = 0;

    for(var j=0; j<cantidad.length;j++){
        cantidad0 = cantidad[j].value.replace(/,/g, '');
        cantidad1 = redondea(cantidad0);
        cantidad3= decimales(cantidad1.toFixed(2));
        $('#'+cantidad[j].id).val(cantidad3);

        preciounitario0 = preciounitario[j].value.replace(/,/g, '');
        preciounitario1 = redondea(preciounitario0);
        preciounitario3= decimales( preciounitario1.toFixed(2)); 
        $('#'+preciounitario[j].id).val( preciounitario3);

        subtot = cantidad1 * preciounitario1;
        
        subtot2 = redondea(subtot);
        subtotal= decimales(subtot2.toFixed(2)); 
        $('#'+subtotal_a[j].id).val(subtotal);

    
        sumtot =  sumtot+parseFloat(subtot2);
        sumtot1 = decimales(sumtot.toFixed(2)); 
       
    }
    $("#total_detalle").val(sumtot1);
    $("#subtotal_fob").val(sumtot1);
    $("#subtotal_moneda").val(sumtot1);
    $("#total_general_moneda").val(sumtot1);
    
}



</script>
@endsection 