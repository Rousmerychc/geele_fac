@extends('layouts.index')

@section('content')   
<div class ="divpagina">
    <div class ="titulosagregar">
        <h3 class="titulos"> EDITAR PRODUCTO</h3>
    </div>
 
     
<form method="POST" action=" {{ url('/productos/'.$producto->id) }} " autocomplete="off">
@csrf
@method('PATCH')
<div  class="divformulario"> 
    <div  class="row">
        <div class="form-group col-md-3">
            <label for="name">{{ __('Grupo') }}</label>
            <select name="id_grupo"  type = "number" class="form-control"  required>
                
                @foreach($grupos as $gru)
                    @if($producto->id_grupo == $gru->id)
                        <option value=" {{$gru->id}} " selected>{{$gru->descripcion}}</option>
                    @else
                        <option value=" {{$gru->id}} " >{{$gru->descripcion}}</option>
                    @endif
                @endforeach
            </select>
        </div> 
        <div class="form-group col-md-3">
            <label for="name">{{ __('Codigo Empresa') }}</label>
            <input name="codigo_empresa" type="text" class="form-control"  maxlength="20"  value ="{{$producto->codigo_empresa}}" required>
        </div>
        <div class="form-group col-md-3">
            <label for="name">{{ __('Descripcion Propia') }}</label>
            <input name="descripcion" type="text" class="form-control"  maxlength="100" value ="{{$producto->descripcion}}" required>
        </div>                
    
        <div class="form-group col-md-3">
            <label for="name">{{ __('Descripcion Impuestos') }}</label>
            <select name="codigo_producto_impuestos"  type = "number" class="form-control" id = "descripcion_impuestos"  required>
                
                @foreach($productos_imp as $pro)
                    @if($producto->id_producto_impuesto == $pro->id)
                        <option value=" {{$pro->id}} " selected>{{$pro->descripcion_producto}}</option>
                    @else
                        <option value=" {{$pro->id}} " >{{$pro->descripcion_producto}}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="form-group col-md-3">
            <label for="name">{{ __('Unidad Medida') }}</label>
            <select name="codigo_medida" type = "number" class="form-control" required>
                
                @foreach($unidad_medida as $uni)
                    @if($producto->codigo_unidad_medida == $uni->id)
                        <option value=" {{$uni->id}} " selected>{{$uni->descripcion}}</option>
                    @else
                        <option value=" {{$uni->id}} ">{{$uni->descripcion}}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="form-group col-md-3">
            <label for="name">{{ __( 'Precio Unitario') }}</label>
            <input name="precio" type="docuble" class="form-control" value ="{{$producto->precio}}" required>
        </div>

        
        <div class="form-group col-md-4">
            <label for="password-confirm" >{{ __('Estado') }}</label>
            @if($producto->estado == 1 )
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="estado" value ="1" checked>  
                    <label class="form-check-label" for="exampleRadios1">Activo</label>
                </div>
            @else    
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="estado" value="1">
                    <label class="form-check-label" for="exampleRadios2">Activo</label>
                </div>
            @endif    
        </div> 

    </div>
</div>
<div class="botones_atras_guardar">          
        <div class="botonatras">
            <a class="btn btn-outline-danger" href="{{  action('ProductosController@index') }}" role="button">Cancelar &nbsp <i class="fas fa-times"></i></a>
        </div>

        <div>
            <button type="submit" class="btn btn-primary"> Guardar Cambios </button>
        </div>
    </div>       
</form>
</div>                              
        
@endsection

@section('js')
<script>
$(document).ready(function() {

$("#descripcion_impuestos").change(function(){
    var pro = document.getElementById("descripcion_impuestos").value; 
    var parametros={
       "dato": pro,
    };
    $("#nandina").empty();
    $.ajaxSetup({
         headers: {
              'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
          }
      });


    $.ajax({
         method:'GET',
         url:"{{ url('nandina_producto') }}",
         data:parametros
       
      }).done(function(res){
         
          var arreglo = JSON.parse(res);
          var sw= 1;
          for (var x = 0; x < arreglo.prueba.length; x++){
              var todo = '<option value="'+arreglo.prueba[x].id+'">'+arreglo.prueba[x].nandina+'</option>';
            //alert(arreglo.prueba[x].id);
            $('#nandina').append(todo);
            sw=0;
          }
          if(sw==1){
              var todo = '<option value="0"> </option>';
              $('#nandina').append(todo);
          }
    });
    
});

});

</script>
@endsection 