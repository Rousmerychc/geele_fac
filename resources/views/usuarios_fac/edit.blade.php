@extends('layouts.index')

@section('content')   

<div class ="divpagina">
    <div class ="titulosagregar">
        <h3 class="titulos"> EDITAR GRUPO</h3>
    </div>
   
    <form method="POST" action="{{ url('/usuario_facturacion/'.$usuarios_fac->id) }}" autocomplete="off">
    @csrf
    @method('PATCH')
        <div  class="divformulario"> 
            <div class="row">
               
                <div class="form-group col-md-6">
                    <label for="name">{{ __('Nombre:') }}</label>
                    <input name="nombre" type="text" class="form-control" value = "{{$usuarios_fac->nombre}}" maxlength="50"  required>
                </div>
                <div class="form-group col-md-6">
                    <label for="name">{{ __('Apellido') }}</label>
                    <input name="apellido" type="text" class="form-control" value = "{{$usuarios_fac->apellido}}" maxlength="50"  required>
                </div> 
                <div class="form-group col-md-4">
                    <label for="name">{{ __('Contraseña') }}</label>
                    <input name="password" type="password" class="form-control" value = "{{$usuarios_fac->password}}" maxlength="10"  required>
                </div>                                     
                <div class="form-group col-md-4">
                <label for="password-confirm" >{{ __('Estado') }}</label>
                @if($usuarios_fac->estado == 1 )
                    
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
                <a class="btn btn-outline-danger" href="{{  action('UsuarioFacturaController@index') }}" role="button">Cancelar &nbsp <i class="fas fa-times"></i></a>
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