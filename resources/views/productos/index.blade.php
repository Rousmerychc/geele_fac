@extends('layouts.index')

@section('content')
<div class="divpagina">
    <div class = "titulobotonagregar">
        <div>    
        <h3 class='titulos'><i class="fas fa-angle-double-right"></i> PRODUCTOS</h3>
        </div>
            <div>
            @if (session('status'))
                <div class="alert alert-success"  id="midiv">
                    {{ session('status') }}
                </div>
            @endif
            </div>
 
        <div class="divbotonagregar">
            <a href="{{ action('ProductosController@create') }}" class="btn btn-primary">Agregar Producto +</a>
        </div>    
    </div>

    <div class="table-responsive">
        <table class ="table table-sm table-striped table-bordered" id="producto">
            <thead class="table-success">
                <tr class="tr1">
                    <td>Codigo</td>
                    <td>Descripcion</td>
                    <td>Codigo Impuestos</td>
                    <td>Descripcion Impuestos</td>
                    <td>Unidad Medida</td> 
                    <td>Precios Unitario</td>
                    <td>Acccions</td>                   
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    setTimeout(function() {
		// Declaramos la capa mediante una clase para ocultarlo
        $("#midiv").fadeOut(1500);
    },5000);
});

$(function () {
 
 var table = $('#producto').DataTable({
     
     "language": {
         "lengthMenu": "Filas por pagina  _MENU_",
         "zeroRecords": "Ningun elempento coincide con la busqueda",
         "info":           "_END_ de _TOTAL_ ",
         "infoEmpty": "No hay registros disponibles",     
         "search":         "Buscar:",
         
     "searchPlaceholder": "Busqueda items",
    
         "paginate": {
             "next":       "<i class='fas fa-arrow-alt-circle-right'></i>",
             "previous":   "<i class='fas fa-arrow-alt-circle-left'></i>",
         },
     },
     "pagingType": "simple",
     
     
     responsive: true,
     dom: '<"top"f><t><"ajaxdatables" <"tamañomenuajax" l> i p>',
     //dom: '<"top" l f>t<"bottom" i p>',
     
     processing: true,
     serverSide: true,
     ordering: false,
     
     ajax: "{{ url('productosajax') }}",
     columns: [
         
         {data: 'codigo_empresa', name: 'codigo_empresa'},
         {data: 'descripcion', name: 'descripcion'},
         {data: 'codigo_impuestos', name: 'codigo_impuesto'},
         {data: 'descripcion_impuestos', name: 'descripcion_impuestos'},
         {data: 'unidad_medida', name: 'unidad_medida'},
         {data: 'precio', name: 'precio'},
         
         {
             data: 'btn', 
             name: 'btn', 
             orderable: true, 
             searchable: true
         },
     ]
 });
 $('#myInputTextField').keyup(function(){
   table.search($(this).val()).draw() ;
});
 
});
</script>
@endsection