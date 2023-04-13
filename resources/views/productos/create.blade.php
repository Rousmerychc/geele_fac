@extends('layouts.index')

@section('content')   
<div class ="divpagina">

<div class ="divpagina">
    <div class ="titulosagregar">
        <h3 class="titulos"> CREAR NUEVO PRODUCTO</h3>
    </div>
   
<form method="POST" action="{{ url('productos') }}" autocomplete="off">
@csrf
    <div  class="divformulario"> 
        <div class="row">
            <div class="form-group col-md-3">
                <label for="name">{{ __('Grupo') }}</label>
                <select name="id_grupo"  type = "number" class="form-control"   required>
                   
                    @foreach($grupos as $gru)
                    <option value=" {{$gru->id}} " >{{$gru->descripcion}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="name">{{ __('Codigo Empresa') }}</label>
                <input name="codigo_empresa" type="text" class="form-control"  maxlength="20"  required>
            </div>
            <div class="form-group col-md-3">
                <label for="name">{{ __('Descripcion Propia') }}</label>
                <input name="descripcion" type="text" class="form-control"  maxlength="100"  required>
            </div>                
        
            <div class="form-group col-md-3">
                <label for="name">{{ __('Descripcion Impuestos') }}</label>
                <select name="codigo_producto_impuestos"  type = "number" class="form-control" id = "descripcion_impuestos"  required>
                   
                    @foreach($productos_imp as $pro)
                    <option value=" {{$pro->id}} " >{{$pro->descripcion_producto}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-3">
                <label for="name">{{ __('Unidad Medida') }}</label>
                <select name="codigo_medida" type = "number" class="form-control" required>
                   
                    @foreach($unidad_medida as $uni)
                    <option value=" {{$uni->id}} ">{{$uni->descripcion}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-md-3">
                <label for="name">{{ __( 'Precio Unitario') }}</label>
                <input name="precio" type="docuble" class="form-control" required>
            </div>

            <div class="form-group col-md-2">
                <label for="password-confirm" >{{ __('Estado') }}</label>
               
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="estado" value="1" checked  >  
                    <label class="form-check-label" for="exampleRadios1">Activo</label>
                </div>
            </div>
        </div>
    </div>
        
     <div class="botones_atras_guardar">          
        <div class="botonatras">
            <a class="btn btn-outline-danger" href="{{  action('ProductosController@index') }}" role="button">Cancelar &nbsp <i class="fas fa-times"></i></a>
        </div>

        <div>
            <button type="submit" class="btn btn-primary"> Guardar </button>
        </div>
    </div>
         
</form>
</div>                              
        
@endsection
@section('js')
<script>



</script>
@endsection 