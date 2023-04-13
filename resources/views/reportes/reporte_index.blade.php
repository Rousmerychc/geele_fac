@extends('layouts.index')

@section('content')

<div class="divpagina">

        <div class ="titulosagregar">
            <h3 class="titulos"> <i class="fas fa-angle-double-right"></i> REPORTES</h3>
        </div>
   
        <div class="divformulario  "> 
            <div class ="row">
            @auth 
                 @if(1 == auth()->user()->rol)   
                <div class="form-group col-md-6">
                <button class="btn btn-outline-primary  col-md-12" type="button" data-toggle="collapse" data-target="#collapseE1" aria-expanded="false" aria-controls="multiCollapseExample2"> SALDOS</button>
                </div>

                <div class="form-group col-md-6">
                <button class="btn btn-outline-primary  col-md-12" type="button" data-toggle="collapse" data-target="#collapseE2" aria-expanded="false" aria-controls="multiCollapseExample2"> KARDEX POR PRODUCTO</button>
                </div> 
                
                <div class="form-group col-md-6">
                <button class="btn btn-outline-primary  col-md-12" type="button" data-toggle="collapse" data-target="#collapseE3" aria-expanded="false" aria-controls="multiCollapseExample2"> RESUMEN MENSUAL</button>
                </div>

                <div class="form-group col-md-6">
                <button class="btn btn-outline-primary  col-md-12" type="button" data-toggle="collapse" data-target="#collapseE6" aria-expanded="false" aria-controls="multiCollapseExample2"> VENTAS DIARIAS </button>
                </div>

                <div class="form-group col-md-6">
                <button class="btn btn-outline-primary  col-md-12" type="button" data-toggle="collapse" data-target="#collapseE7" aria-expanded="false" aria-controls="multiCollapseExample2"> VENTAS DIARIAS PRODUCTOS</button>
                </div>

                @endif                  
                @endauth  

                <div class="form-group col-md-6">
                <button class="btn btn-outline-primary  col-md-12" type="button" data-toggle="collapse" data-target="#collapseE4" aria-expanded="false" aria-controls="multiCollapseExample2"> MOVIMIENTO DIARIO OPERADOR</button>
                </div>
                
                <div class="form-group col-md-6">
                <button class="btn btn-outline-primary  col-md-12" type="button" data-toggle="collapse" data-target="#collapseE5" aria-expanded="false" aria-controls="multiCollapseExample2"> MOVIMIENTO DIARIO PRODUCTOS OPERADOR</button>
                </div>
            </div>
        </div>

<div class="collapse" id="collapseE1">
  <div class="card card-body">

    <h5  class = "resportestitulos"> SALDOS</h5>
        <form method="POST" action="{{ url('saldos') }}" autocomplete="off" id ="formulario1"  target="_blank">
            @csrf
            <div class ="row">
                <div class="form-group col-md-3 ">
                    <label  for="name">{{ __('Hasta Fecha') }}</label>
                    <input name="fechai1" id ="fechai1" type="date" class="form-control" value ="{{$fecha}}"  maxlength="50"  required>
                </div> 

                <div class="form-group col-md-4 ">
                    <label  for="name">{{ __('Sucursal') }}</label>
                    <select type= "number" name="sucursal" class="form-control">
                        @foreach($sucursal as $s)
                            <option value="{{$s->nro_sucursal}}">{{$s->nro_sucursal}} - {{$s->descripcion}}</option>
                        @endforeach
                    </select>
                </div>

               
            </div> 
                <input type="hidden" name ="proceso1" id = "proceso1" value = "0">
                <div class=" col-md-3 flex ">
                    <label  for="name"> &nbsp;</label></br>
                    <!-- Button trigger modal -->
                    
                    <button type="button" class="btn btn-outline-danger" title = "PDF"  onclick="envio1();">
                        PDF &nbsp;<i class="far fa-file-pdf"></i>
                    </button>
            
                    &nbsp; 
                    <button type="button" class="btn btn-outline-success" title = "EXCEL" onclick="excel1();">
                    EXCEL &nbsp;<i class="far fa-file-excel"></i>
                    </button>
                
                </div> 
            </form>
        </div>
  </div>
</div>

<div class="collapse" id="collapseE2">
  <div class="card card-body">
    <h5  class = "resportestitulos"> Kardex Por Producto</h5>
    <form method="POST" action="{{ url('kardex/producto') }}" autocomplete="off" id ="formulario2"  target="_blank">     
    @csrf
        <div class ="row">
            <div class="form-group col-md-3 ">
                    <label  for="name">{{ __('Sucursal') }}</label>
                    <select type= "number" name="sucursal" class="form-control">
                        @foreach($sucursal as $s)
                            <option value="{{$s->nro_sucursal}}">{{$s->nro_sucursal}} - {{$s->descripcion}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-3 ">
                    <label  for="name">{{ __('Producto') }}</label>
                    <select type= "number" name="producto" class="form-control">
                        @foreach($productos as $p)
                            <option value="{{$p->codigo_empresa}}">{{$p->codigo_empresa}} - {{$p->descripcion}}</option>
                        @endforeach
                    </select>
                </div>
          
            <div class="form-group col-md-2 ">
                <label  for="name">{{ __('Fecha Inicio') }}</label>
                <input name="fechai2" type="date" class="form-control" value ="{{$fecha}}"  maxlength="50"  required>
            </div> 
            <div class="form-group col-md-2 ">
                <label  for="name">{{ __('Fecha Fin') }}</label>
                <input name="fechaf2" type="date" class="form-control" value ="{{$fecha}}" maxlength="50"  required>
            </div> 
            <div class=" col-md-3 flex ">
                <input type="hidden" name ="proceso2" id = "proceso2" value = "0">
                <label  for="name"> &nbsp;</label></br>
                <button type="button" class="btn btn-outline-danger" title = "PDF"  onclick="envio2();">
                    PDF &nbsp;<i class="far fa-file-pdf"></i>
                </button>
        
                &nbsp; 
                <button type="button" class="btn btn-outline-success" title = "EXCEL" onclick="excel2();">
                EXCEL &nbsp;<i class="far fa-file-excel"></i>
                </button>
            </div> 
            
        </div>
    </form>
  </div>

</div>

<div class="collapse" id="collapseE3">
  <div class="card card-body">
    <h5  class = "resportestitulos"> Resumen Mensual</h5>
    <form method="POST" action="{{ url('resumen/mensual') }}" autocomplete="off" id ="formulario3"  target="_blank">
    @csrf
        <div class ="row">
          
            <div class="form-group col-md-3 ">
                <label  for="name">{{ __('Sucursal') }}</label>
                <select type= "number" name="sucursal" class="form-control">
                    @foreach($sucursal as $s)
                        <option value="{{$s->nro_sucursal}}">{{$s->nro_sucursal}} - {{$s->descripcion}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-3 ">
                <label  for="name">{{ __('Fecha Inicio') }}</label>
                <input name="fechai3" type="date" class="form-control"  value ="{{$fecha}}"  maxlength="50"  required>
            </div> 
            <div class="form-group col-md-3 ">
                <label  for="name">{{ __('Fecha Fin') }}</label>
                <input name="fechaf3" type="date" class="form-control"  value ="{{$fecha}}"  maxlength="50"  required>
            </div> 
            <div class=" col-md-3 flex ">
                <input type="hidden" name ="proceso3" id = "proceso3" value = "0">
                <label  for="name"> &nbsp;</label>
                <button type="button" class="btn btn-outline-danger" title = "PDF"  onclick="envio3();">
                    PDF &nbsp;<i class="far fa-file-pdf"></i>
                </button>
        
                &nbsp; 
                <button type="button" class="btn btn-outline-success" title = "EXCEL" onclick="excel3();">
                EXCEL &nbsp;<i class="far fa-file-excel"></i>
                </button>
               
            </div> 
            
        </div>
   </form>
  </div>
  
</div>

<div class="collapse" id="collapseE4">
  <div class="card card-body">
    <h5  class = "resportestitulos"> Movimiento Diario Operador</h5>
    <form method="POST" action="{{ url('reporte/ventas') }}" autocomplete="off" id ="formulario4"  target="_blank">
    @csrf
        <div class ="row">
          
            <div class="form-group col-md-3 ">
                <label  for="name">{{ __('Sucursal') }}</label>
                <select type= "number" name="sucursal" class="form-control">
                    @foreach($sucursal as $s)
                        <option value="{{$s->nro_sucursal}}">{{$s->nro_sucursal}} - {{$s->descripcion}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-3 ">
                <label  for="name">{{ __('Fecha Inicio') }}</label>
                <input name="fechai4" type="date" class="form-control"  value ="{{$fecha}}"  maxlength="50"  required>
            </div> 
            <div class="form-group col-md-3 ">
                <label  for="name">{{ __('Fecha Fin') }}</label>
                <input name="fechaf4" type="date" class="form-control"  value ="{{$fecha}}"  maxlength="50"  required>
            </div>
            <div class="form-group col-md-3 ">
                <label  for="name">{{ __('Operador') }}</label>
                <select type= "number" name="id_operador" class="form-control">
                    
                    @foreach($usuario_fac as $uf)
                        <option value="{{$uf->id}}">{{$uf->id}} - {{$uf->nombre}}  {{$uf->apellido}}</option>
                    @endforeach
                </select>
            </div>
            <div class=" col-md-3 flex ">
                <input type="hidden" name ="proceso4" id = "proceso4" value = "0">
                <label  for="name"> &nbsp;</label>
                <button type="button" class="btn btn-outline-danger" title = "PDF"  onclick="envio4();">
                    PDF &nbsp;<i class="far fa-file-pdf"></i>
                </button>
            </div> 
        </div>
   </form>
  </div>
</div>

<div class="collapse" id="collapseE5">
  <div class="card card-body">
    <h5  class = "resportestitulos"> Movimiento Diario Productos Operador</h5>
    <form method="POST" action="{{ url('reporte/movimiento/productos') }}" autocomplete="off" id ="formulario5"  target="_blank">
    @csrf
        <div class ="row">
          
            <div class="form-group col-md-3 ">
                <label  for="name">{{ __('Sucursal') }}</label>
                <select type= "number" name="sucursal" class="form-control">
                    @foreach($sucursal as $s)
                        <option value="{{$s->nro_sucursal}}">{{$s->nro_sucursal}} - {{$s->descripcion}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-3 ">
                <label  for="name">{{ __('Fecha Inicio') }}</label>
                <input name="fechai5" type="date" class="form-control"  value ="{{$fecha}}"  maxlength="50"  required>
            </div> 
            <div class="form-group col-md-3 ">
                <label  for="name">{{ __('Fecha Fin') }}</label>
                <input name="fechaf5" type="date" class="form-control"  value ="{{$fecha}}"  maxlength="50"  required>
            </div>
            <div class="form-group col-md-3 ">
                <label  for="name">{{ __('Operador') }}</label>
                <select type= "number" name="id_operador" class="form-control">
                    
                    @foreach($usuario_fac as $uf)
                        <option value="{{$uf->id}}">{{$uf->id}} - {{$uf->nombre}}  {{$uf->apellido}}</option>
                    @endforeach
                </select>
            </div>
            <div class=" col-md-3 flex ">
                <input type="hidden" name ="proceso5" id = "proceso5" value = "0">
                <label  for="name"> &nbsp;</label>
                <button type="button" class="btn btn-outline-danger" title = "PDF"  onclick="envio5();">
                    PDF &nbsp;<i class="far fa-file-pdf"></i>
                </button>
            </div> 
        </div>
   </form>
  </div>
</div>

<div class="collapse" id="collapseE6">
  <div class="card card-body">
    <h5  class = "resportestitulos"> Ventas Diarias </h5>
    <form method="POST" action="{{ url('reporte/ventas/diarias') }}" autocomplete="off" id ="formulario6"  target="_blank">
    @csrf
        <div class ="row">
          
            <div class="form-group col-md-3 ">
                <label  for="name">{{ __('Sucursal') }}</label>
                <select type= "number" name="sucursal" class="form-control">
                    @foreach($sucursal as $s)
                        <option value="{{$s->nro_sucursal}}">{{$s->nro_sucursal}} - {{$s->descripcion}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-3 ">
                <label  for="name">{{ __('Fecha Inicio') }}</label>
                <input name="fechai6" type="date" class="form-control"  value ="{{$fecha}}"  maxlength="50"  required>
            </div> 
            <div class="form-group col-md-3 ">
                <label  for="name">{{ __('Fecha Fin') }}</label>
                <input name="fechaf6" type="date" class="form-control"  value ="{{$fecha}}"  maxlength="50"  required>
            </div>
            <div class="form-group col-md-3 ">
                <label  for="name">{{ __('Operador') }}</label>
                <select type= "number" name="id_operador" class="form-control">
                    <option value="0">Todos</option>
                    @foreach($usuario_fac as $uf)
                        <option value="{{$uf->id}}">{{$uf->id}} - {{$uf->nombre}}  {{$uf->apellido}}</option>
                    @endforeach
                </select>
            </div>
            
            <div class=" col-md-3 flex ">
                <input type="hidden" name ="proceso6" id = "proceso6" value = "0">
                <label  for="name"> &nbsp;</label>
                <button type="button" class="btn btn-outline-danger" title = "PDF"  onclick="envio6();">
                    PDF &nbsp;<i class="far fa-file-pdf"></i>
                </button>
            </div> 
        </div>
   </form>
  </div>
</div>

<div class="collapse" id="collapseE7">
  <div class="card card-body">
    <h5  class = "resportestitulos"> Ventas Diarias Producto</h5>
    <form method="POST" action="{{ url('reporte/ventas/diarias/productos') }}" autocomplete="off" id ="formulario7"  target="_blank">
    @csrf
        <div class ="row">
          
            <div class="form-group col-md-3 ">
                <label  for="name">{{ __('Sucursal') }}</label>
                <select type= "number" name="sucursal" class="form-control">
                    @foreach($sucursal as $s)
                        <option value="{{$s->nro_sucursal}}">{{$s->nro_sucursal}} - {{$s->descripcion}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-3 ">
                <label  for="name">{{ __('Fecha Inicio') }}</label>
                <input name="fechai7" type="date" class="form-control"  value ="{{$fecha}}"  maxlength="50"  required>
            </div> 
            <div class="form-group col-md-3 ">
                <label  for="name">{{ __('Fecha Fin') }}</label>
                <input name="fechaf7" type="date" class="form-control"  value ="{{$fecha}}"  maxlength="50"  required>
            </div>
            <div class="form-group col-md-3 ">
                <label  for="name">{{ __('Operador') }}</label>
                <select type= "number" name="id_operador" class="form-control">
                    <option value="0">Todos</option>
                    @foreach($usuario_fac as $uf)
                        <option value="{{$uf->id}}">{{$uf->id}} - {{$uf->nombre}}  {{$uf->apellido}}</option>
                    @endforeach
                </select>
            </div>
            
            <div class=" col-md-3 flex ">
                <input type="hidden" name ="proceso7" id = "proceso7" value = "0">
                <label  for="name"> &nbsp;</label>
                <button type="button" class="btn btn-outline-danger" title = "PDF"  onclick="envio7();">
                    PDF &nbsp;<i class="far fa-file-pdf"></i>
                </button>
            </div> 
        </div>
   </form>
  </div>
  </div>

  <div class="collapse" id="collapseE4">
    <div class="card card-body">
    <h5  class = "resportestitulos"> Movimiento Diario Operador</h5>
    <form method="POST" action="{{ url('reporte/ventas') }}" autocomplete="off" id ="formulario4"  target="_blank">
    @csrf
        <div class ="row">
          
            <div class="form-group col-md-3 ">
                <label  for="name">{{ __('Sucursal') }}</label>
                <select type= "number" name="sucursal" class="form-control">
                    @foreach($sucursal as $s)
                        <option value="{{$s->nro_sucursal}}">{{$s->nro_sucursal}} - {{$s->descripcion}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-3 ">
                <label  for="name">{{ __('Fecha Inicio') }}</label>
                <input name="fechai4" type="date" class="form-control"  value ="{{$fecha}}"  maxlength="50"  required>
            </div> 
            <div class="form-group col-md-3 ">
                <label  for="name">{{ __('Fecha Fin') }}</label>
                <input name="fechaf4" type="date" class="form-control"  value ="{{$fecha}}"  maxlength="50"  required>
            </div>
            <div class="form-group col-md-3 ">
                <label  for="name">{{ __('Operador') }}</label>
                <select type= "number" name="id_operador" class="form-control">
                    <option value="0">Todos</option>
                    @foreach($usuario_fac as $uf)
                        <option value="{{$uf->id}}">{{$uf->id}} - {{$uf->nombre}}  {{$uf->apellido}}</option>
                    @endforeach
                </select>
            </div>
            <div class=" col-md-3 flex ">
                <input type="hidden" name ="proceso4" id = "proceso4" value = "0">
                <label  for="name"> &nbsp;</label>
                <button type="button" class="btn btn-outline-danger" title = "PDF"  onclick="envio4();">
                    PDF &nbsp;<i class="far fa-file-pdf"></i>
                </button>
            </div> 
        </div>
   </form>
  </div>
    </div>


  @endsection

  @section('js')
<script>

function excel1(){

    $("#proceso1").val(1);
    form1= document.getElementById("formulario1");
    form1.submit();
}
    
function envio1(){
    
    $("#proceso1").val(0);
    form1= document.getElementById("formulario1");
    form1.submit();
}

    
function excel2(){
$("#proceso2").val(1);
form2= document.getElementById("formulario2");
form2.submit();
}

function envio2(){
$("#proceso2").val(0);
form2= document.getElementById("formulario2");
form2.submit();
}

function excel3(){
$("#proceso3").val(1);
form3= document.getElementById("formulario3");
form3.submit();
}

function envio3(){
$("#proceso3").val(0);
form3= document.getElementById("formulario3");
form3.submit();
}

function envio4(){
$("#proceso4").val(0);
form4= document.getElementById("formulario4");
form4.submit();
}
function envio5(){
$("#proceso5").val(0);
form5= document.getElementById("formulario5");
form5.submit();
}
function envio6(){
$("#proceso6").val(0);
form6= document.getElementById("formulario6");
form6.submit();
}

function envio7(){
    //alert('holi');
$("#proceso7").val(0);
form7= document.getElementById("formulario7");
form7.submit();
}

</script>


@endsection 