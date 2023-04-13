@extends('layouts.index')

@section('content')   

<div class ="divpagina">
    <div class ="titulosagregar">
        <h3 class="titulos"> CREAR NUEVO USUARIO FACTURA</h3>
    </div>
   
    <form method="POST" action="{{ url('usuario_facturacion') }}" autocomplete="off">
    @csrf
        <div  class="divformulario"> 
            <div class="row">
               
                <div class="form-group col-md-6">
                    <label for="name">{{ __('Nombre:') }}</label>
                    <input name="nombre" type="text" class="form-control"  maxlength="50"  required>
                </div>
                <div class="form-group col-md-6">
                    <label for="name">{{ __('Apellido') }}</label>
                    <input name="apellido" type="text" class="form-control"  maxlength="50"  required>
                </div> 
                <div class="form-group col-md-4">
                    <label for="name">{{ __('Contraseña') }}</label>
                    <input name="password" type="password" class="form-control"  maxlength="10"  required>
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