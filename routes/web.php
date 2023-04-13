<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (Auth::check()  ) {
       
        return view('hola');
    }
    
    return view('hola');//hola//auth.login
})->middleware('activo');


//Route::Resource('login','LoginController');
//Route::get('ingresosaldo','SaldoIngresosController@index')->name('cis');

/*cambios momentanios*/
Route::get('inicio','IndexController@index')->middleware('activo');

/** */

/** menu vista gasto */
Route::get('menugasto','IndexController@menugasto')->middleware('operador');

//Auth::routes();
Auth::routes(['register' => false]);

Route::get('index','IndexController@index')->middleware('activo');

Route::Resource('usuarios','UsuarioController')->middleware('admin');
Route::get('usuariosajax','UsuarioController@usuariosajax');

/* Codificacion*/
 

 /*CRUD Sucursal*/
 Route::Resource('sucursal','SucursalController')->middleware('admin');
 Route::get('sucursalajax','SucursalController@sucursalajax');

 /*CRUD Punto Venta*/
//  Route::Resource('punto/venta','PuntoVentaController')->middleware('adminjr');
//  Route::get('punto_ventaajax','PuntoVentaController@punto_ventaajax');

/**CRUD Clientes */
Route::Resource('clientes','ClientesController')->middleware('admin');
Route::get('clientesajax','ClientesController@clientesajax')->middleware('admin');
Route::get('validacion_nit','ClientesController@VerificarNIT')->middleware('admin');

/**CRUD Productos */
Route::Resource('productos','ProductosController')->middleware('admin');
Route::get('productosajax','ProductosController@productosajax')->middleware('admin');
Route::get('nandina_producto','ProductosController@nandina_producto')->middleware('admin');

// CRUD Grupos
Route::Resource('grupos', 'GruposController')->middleware('admin');
Route::get('gruposajax','GruposController@gruposajax')->middleware('admin');

//CRUD 
Route::Resource('cafc','CafcController')->middleware('admin');
Route::get('ajaxcafc','CafcController@ajaxcafc')->middleware('admin');

//CRUD INGRESO
Route::resource('ingreso','IngresosController')->middleware('admin');
Route::get('ingresoajax','IngresosController@ingresoajax')->middleware('admin');
Route::get('anular_ingreso/{id}','IngresosController@anular_ingreso')->middleware('admin');
Route::get('pdf_ingreso/{id}','IngresosController@pdf_ingreso')->middleware('admin');

//CRUD PROVEEDORES
Route::resource('proveedor','ProveedorController')->middleware('admin');
Route::get('proveedorajax','ProveedorController@proveedorajax')->middleware('admin');

/**PREFACTURACION */
// Route::Resource('prefactura','PreFacturaController');
// Route::get('cliente_fac','PreFacturaController@cliente_fac');
// Route::get('producto_fac','PreFacturaController@producto_fac');
// Route::get('facturaajax','PreFacturaController@facturaajax');
// Route::get('pre_pdf_factura/{id}','PreFacturaController@pre_pdf_factura');
//Route::post('prefactura','FacturacionController@prefactura');
Route::get('verificanit','FacturacionController@verificar_nit')->middleware('operador');
Route::get('es_cliente','FacturacionController@es_cliente')->middleware('operador');
 /**facturacion */
Route::Resource('facturacion','FacturacionController')->middleware('operador');
Route::post('anular_fac','FacturacionController@anular_fac')->middleware('operador');
Route::get('enviarpaquete','FacturacionController@emisionFueraLinea')->middleware('operador');
Route::get('codigoQR/{id}','FacturacionController@codigoQR')->middleware('operador');
Route::get('codigoQR_modal','FacturacionController@codigoQR_modal')->middleware('operador');
Route::get('pdf_factura/{id}','FacturacionController@pdf')->middleware('operador');
Route::get('borrar','FacturacionController@pruebaborrado')->middleware('operador');
Route::get('correo','FacturacionController@correo')->middleware('operador');
Route::get('ajaxfactura','FacturacionController@ajaxfactura')->middleware('operador');
Route::get('seleciona_prefactura','FacturacionController@seleciona_prefactura')->middleware('operador');
Route::get('pdf_clientes/{id}','FacturacionController@pdf_clientes')->middleware('operador');
Route::get('pdf_clientes_portatil/{id}','FacturacionController@pdf_clientes_portatil')->middleware('operador');
Route::get('producto_fac','FacturacionController@producto_fac')->middleware('operador');
Route::get('cuis','FacturacionController@cuis')->middleware('operador');
Route::get('cufd','FacturacionController@cufd')->middleware('operador');
Route::get('pendiente','FacturacionController@pendiente')->middleware('operador');
Route::get('verificarComunucacion','FacturacionController@verificarComunucacion')->middleware('operador');
Route::get('porducto_grupo','FacturacionController@porducto_grupo')->middleware('operador');
Route::post('usuario_password','FacturacionController@usuario_password')->middleware('operador');

//CRUD USUARIO FACTURA
Route::Resource('usuario_facturacion','UsuarioFacturaController')->middleware('admin');
Route::get('usuario_facturacionajax','UsuarioFacturaController@usuario_facturacionajax')->middleware('admin');

//facturacion mannual
Route::get('create2','FacturacionController@create2')->middleware('admin');
Route::get('emisionFueraLinea','FacturacionController@emisionFueraLinea')->middleware('admin');
Route::post('emisionManuales','FacturacionController@emisionManuales')->middleware('admin');
Route::post('facuramanual','FacturacionController@facturasManuales')->middleware('admin');
Route::get('boradoarchivos','FacturacionController@borradoarchivos')->middleware('admin');

//NOTA DE VENTA
Route::Resource('nota_venta','NotaVentaController')->middleware('admin');
Route::get('nota_ventaajax','NotaVentaController@nota_ventaajax')->middleware('admin');
Route::get('pdf_clientes_nota_venta/{id}','NotaVentaController@pdf_clientes')->middleware('admin');
Route::get('anular_nota_entrega/{id}','NotaVentaController@anular_nota_entrega')->middleware('admin');
/**REPORTES */
Route::Resource('reportes','ReportesController');
Route::post('saldos','ReportesController@saldos');
Route::post('kardex/producto','ReportesController@karde_producto');
Route::post('resumen/mensual','ReportesController@resumen_mensual');
Route::post('reporte/ventas','ReportesController@reporte_ventas');
Route::post('reporte/movimiento/productos','ReportesController@reporte_movimiento_productos');
Route::post('reporte/ventas/diarias','ReportesController@reporte_ventas_diarias');
Route::post('reporte/ventas/diarias/productos','ReportesController@reporte_ventas_diarias_productos');

// /**FECHA DE SALIDA */
// Route::Resource('fecha/salida','FechaSalidaController');
// Route::get('fecha_salida_ajax','FechaSalidaController@fecha_salida_ajax');    
/**Sincronizacion */
Route::Resource('sincronizacion','SincronizacionController')->middleware('admin');
Route::get('actividades','SincronizacionController@actividades')->middleware('admin');
Route::get('documetosector','SincronizacionController@ListaActividadesDocumentoSector')->middleware('admin');
Route::get('leyenda','SincronizacionController@sincronizarListaLeyendasFactura')->middleware('admin');
Route::get('mensajes/servicios','SincronizacionController@ListaMensajesServicios')->middleware('admin');
Route::get('productos1/servicios','SincronizacionController@ListaProductosServicios')->middleware('admin');
Route::get('eventos/significativos','SincronizacionController@ParametricaEventosSignificativos')->middleware('admin');
Route::get('motivo/anulacion','SincronizacionController@ParametricaMotivoAnulacion')->middleware('admin');
Route::get('pais/origen','SincronizacionController@ParametricaPaisOrigen')->middleware('admin');
Route::get('ducumento/digital','SincronizacionController@ParametricaDocumentoTipoIdentidad')->middleware('admin');
Route::get('documento/sector','SincronizacionController@ParametricaTipoDocumentoSector')->middleware('admin');
Route::get('tipo/emision','SincronizacionController@ParametricaTipoEmision')->middleware('admin');
Route::get('tipo/habitacion','SincronizacionController@ParametricaTipoHabitacion')->middleware('admin');
Route::get('tipo/pago','SincronizacionController@ParametricaTipoMetodoPago')->middleware('admin');
Route::get('tipo/moneda','SincronizacionController@ParametricaTipoMoneda')->middleware('admin');
Route::get('tipo/punto/venta','SincronizacionController@ParametricaTipoPuntoVenta')->middleware('admin');
Route::get('tipo/factura','SincronizacionController@ParametricaTiposFactura')->middleware('admin');
Route::get('unidad/medida','SincronizacionController@ParametricaUnidadMedida')->middleware('admin');

Route::get('registrar_punto_venta','FacturacionController@ResgistrarPuntoVenta')->middleware('admin');
Route::get('prueba_veri_conec','FacturacionController@prueba_veri_conec')->middleware('admin');