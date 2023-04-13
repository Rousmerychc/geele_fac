@extends('layouts.index')

@section('content')

<div class="divpagina">
    <div class = "titulobotonagregar">
        <div>    
        <h3 class='titulos'><i class="fas fa-angle-double-right"></i> INGRESOS</h3>
        </div>

          <center>
            @if (session('status'))
                    <!-- Button trigger modal -->
                    <button style= "display:none;" type="button" class="btn btn-primary" id ="modal_respuesta_servidor" data-bs-toggle="modal" data-bs-target="#exampleModal2">
                      Launch demo modal
                    </button>
                
            @endif
           
          </center>
         
          <div class="divbotonagregar">
          <a href="{{ action('IngresosController@create') }}" type="button" class="btn btn-primary"> NUEVO INGRESO</a>
          </div>
          
    </div>

    <div class="table-responsive">
        <table class ="table table-sm table-striped table-bordered" id="ingreso">
            <thead class="table-success">
                <tr class="tr1">
                    <td>PDF</td>
                    <td>Sucursal</td>
                    <td>Nro. Ingreso<br>  por Sucursal</td>
                    <td>Fecha</td>
                    <td>Proveedor</td>
                    <td>Importe Bs.</td> 
                    <td>Estado</td>
                    <td>Acción</td>                   
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>




<!-- Modal MENSAJE SISTEMA-->
<div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Mensaje del Sistema</h5>
       
      </div>
      <div class="modal-body">
        {{ session('status') }}
      </div>
      <div class="modal-footer">
        <button type="button " class="btn btn-secondary btn-sm" data-bs-dismiss="modal">cerrar</button>
      </div>
    </div>
  </div>
</div>


@endsection

@section('js')
<script>
  


$(document).ready(function() {
  
    $('#modal_respuesta_servidor').trigger('click');
   

});


$(function () {
 
 var table = $('#ingreso').DataTable({
     
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
     
     ajax: "{{ url('ingresoajax') }}",
     columns: [
        {
             data: 'pdf', 
             name: 'pdf', 
             orderable: true, 
             searchable: true
         },
         {data: 'sucursal', name: 'sucursal'},
         {data: 'nro_por_sucursal', name: 'nro_por_sucursal'},
         {data: 'fecha', name: 'fecha'},
         {data: 'descripcion', name: 'descripcion'},
         {data: 'monto_total', name: 'monto_total'},
         {data: 'estado', //, name: 'anulado'},
                       render: function (data,type,row) {
                           if (data == 1) {
                             return '<span class="badge badge-pill badge-danger"><i class="fas fa-times"></i></span>';
                           } else {
                             return '<span class="badge badge-pill badge-success"><i class="fas fa-check"></i></span>';
                           }
                         return data;
                       } 
                     },  
         
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