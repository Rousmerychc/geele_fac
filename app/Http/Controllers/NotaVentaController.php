<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Datos;
use App\Facturacion;
use App\FacturaDetalle;
use Carbon\Carbon;
use App\UnidadMedida;
use App\ParametricaTipoMetodoPago;
use App\ParametricaDocumentoTipoIdentidad;

use App\Productos;

use App\Clientes;
use App\Sucursal;

use DataTables;
use Illuminate\Support\Facades\DB;
use Luecano\NumeroALetras\NumeroALetras;
use PDF;




class NotaVentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //FUNCION PARA VALIDAR LOGIN
    public function __construct() {
        $this->middleware('auth');
    }
    public function anular_nota_entrega($id){
        $ingreso = Facturacion::where('id','=',$id)->first();
        $ingreso->estado = 1;
        $ingreso->save();
        return redirect('nota_venta')->with('status', 'REGISTRO ANULADO');;
    }
    public function pdf_clientes($id){
        
        $datos_empresa = Datos::first();
        $factura = Facturacion::where('factura.id','=',$id)
        ->join('sucursal','sucursal.nro_sucursal','=','factura.id_sucursal')
        ->select('factura.*','sucursal.municipio','sucursal.direccion','sucursal.telefono','sucursal.descripcion')
        ->first();
        //dd($factura);
       
        $detalle = FacturaDetalle::where('id_tabla_factura','=',$factura->id)
        ->join('productos','productos.codigo_empresa', '=','codigo_producto_empresa')
        ->select('factura_detalle.*','productos.codigo_empresa','productos.descripcion','unidad_medida','factura_detalle.id')
        ->orderby('factura_detalle.id','ASC')
        ->get();
       

        //PARA LITERAL EN MONEDA BOLIVIANOS
        $formatterb = new NumeroALetras();
        $reb= (int) ($factura->monto_total / 1000);
        $literalb= $formatterb->toInvoice($factura->monto_total, 2,'');
        if($reb==1){
            $literalb = 'UN '.$literalb;
        }
      
        $data =['factura'=>$factura, 'detalle'=>$detalle,  'datos_empresa'=>$datos_empresa ,
             'literalb'=>$literalb,];
            $pdf = PDF::loadView('nota_venta.pdf_cliente',$data); 
        //return view('facturacion.pdf_vista',$data);
        $pdf->setPaper("letter", "portrait");
        return $pdf->stream('NotaEntrega.pdf');
    }
    public function nota_ventaajax(){
        
        $data = Facturacion::where('id_sucursal','=',auth()->user()->id_sucursal)
        ->where('punto_venta','=',auth()->user()->punto_venta)
        ->where('id_factura', '=',0)
        ->where('nro_fac_manual', '=',0)
        ->orderBy('id', 'DESC')->get();
        return Datatables::of($data)
            ->addColumn('btn','nota_venta.actions')
            ->addColumn('pdf','nota_venta.pdf')
            ->rawColumns(['btn','pdf'])
            ->make(true);
        
    }
    public function index()
    {
        //
        return view('nota_venta.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $productos = Productos::all();
        $tipo_doc = ParametricaDocumentoTipoIdentidad::all();
        $fecha=Carbon::now(-4)->format('Y-m-d');
        $tipo_pago = ParametricaTipoMetodoPago::where('estado','=',1)->get();
        $id_fac = Facturacion::where('id_sucursal','=',auth()->user()->id_sucursal)
        ->where('punto_venta','=',auth()->user()->punto_venta)
        ->where('id_factura', '=',0)
        ->where('nro_fac_manual', '=',0)
        ->orderby('id','DESC')
        ->get();
        if($id_fac->isEmpty()){
            $id_fac->nro_nota_venta = 0;
        }else{
            $id_fac = Facturacion::where('id_sucursal','=',auth()->user()->id_sucursal)
            ->where('punto_venta','=',auth()->user()->punto_venta)
            ->orderby('id','DESC')
            ->where('id_factura', '=',0)
            ->where('nro_fac_manual', '=',0)
            ->first();
        }
        $sucu = Sucursal::where('nro_sucursal','=',auth()->user()->id_sucursal)
        ->first();
        //dd($sucu);
         return view('nota_venta.facturacion',['id_fac'=>$id_fac, 'sucu'=>$sucu,'productos'=>$productos,'tipo_doc'=>$tipo_doc, 'fecha'=>$fecha, 'tipo_pago'=>$tipo_pago]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //        return ('nota venta store');
        $fecha_hora3 = Carbon::now(-4)->format('Y-m-d/H:i:s.z');
        $fecha_hora3 = str_replace("/", "T", $fecha_hora3);
        $dato = auth()->user()->id;
       ;
        $sucursal = Sucursal::where('nro_sucursal','=',auth()->user()->id_sucursal)->first();

         //Datos del cliente
         $razonSocialCli =  $request->get('razon_social');
         //$nroDocID =  $request->get('nro_documento');
       
        
        
         // montos  TOTALGENERAL
         $total_final  = (double) str_replace(',', '', $request->get('total_detalle'));
         $tipoCambio = 1; //taza de cambio
         $descuentoAdicional = 0; //no cambia   
         $montoTotalMoneda =  $total_final;
         $montoTotal =  $total_final;
         $montoTotalSujetoIva =  $total_final;  
 
         //detalle productos
         $codigo_p = $request->get('codigo');//codigo_empresa
         $codigo_impuestos_p = $request->get('codigo_impuestos');//ide correlativo
         $cantidad_p = $request->get('cantidad');
         $unidad_medida_p = $request->get('unidad_medida');
         $id_unidad_medida_p = $request->get('codigo_unidad_medida'); // pendiente
         $descripcion_p = $request->get('descripcion');
         $precio_unitario_p = $request->get('precio_uni');
         $subtotal_p = $request->get('subtotal');

         $factura_bd = new Facturacion;

         //DATOS GUARDADOS EN BD TABLA FACTURA
         
         $fechabd =  str_replace('T', ' ', $fecha_hora3);
         $fecha_hora1 = substr($fechabd, 0, -4);
         $fecha1 = substr($fecha_hora1, 0, -9);
          
         $factura_bd->nro_nota_venta = (int)$request->{'nro_factura'} ;
         $factura_bd->id_sucursal = auth()->user()->id_sucursal;
         $factura_bd->punto_venta = auth()->user()->punto_venta;
         $factura_bd->fecha = $fecha1;
         $factura_bd->fecha_hora = $fecha_hora1;
         $factura_bd->razon_social = $razonSocialCli;
         $factura_bd->cuf = 0;
         $factura_bd->cufd = 0;
         $factura_bd->tipo_documento_identidad = 0; 
         $factura_bd->nro_documento = 0 ;

         $factura_bd->complemento = $request->get('complemento');
         
         $factura_bd->codigo_cliente = 0;
        
         $factura_bd->id_metodo_pago = 0;
         $factura_bd->nro_tarjeta = 0;
         //$factura_bd->codigo_moneda = $codMoneda1; // todo es bolivianos
         $factura_bd->tipo_emision_n = 0 ;
         $factura_bd->id_leyenda = 0;

         $factura_bd->monto_total = $total_final;
         $factura_bd->monto_total_sujeto_iva =  $total_final;
         //$factura_bd->tipo_cambio = $tipoCambio; todo es en bolivianos
         $factura_bd->monto_total_moneda =   $total_final ; //suma de gastos nacionales + suma del total detalle -- particularmente el dato es igual al total detalle por que fob es cero
        
         $factura_bd->codigo_excepcion = 0 ;
         $factura_bd->descuento_adicional = 0 ; // no cambia
         $factura_bd->cafc = 0; //no cambia - no se emitira manuales
         
         //$factura_bd->codigo_documento_sector =  $codSector1 ;// no cambia
        $factura_bd->id_usuario = $dato;

        $factura_bd->hora_impuestos = $fecha_hora3;
    
        $factura_bd->fac_manual = 0;
        $factura_bd->fuera_linea = 0;
        
         $factura_bd->save();

        //variables necesarias declaradas mas arriba           
        $cont = 0; 
        //dd($factura_bd->id);
             
        while($cont < count($codigo_p)){

             $cantidad = (double) str_replace(',', '', $cantidad_p[$cont]);
             $precio_u = (double) str_replace(',', '', $precio_unitario_p[$cont]);
             $subtotal = (double) str_replace(',', '', $subtotal_p[$cont]);
             
             $producto_detalle = new FacturaDetalle;
             $producto_detalle->id_tabla_factura = $factura_bd->id;
             $producto_detalle->id_factura = (int)$request->{'nro_factura'};
             $producto_detalle->id_sucursal = auth()->user()->id_sucursal;
             $producto_detalle->punto_venta =auth()->user()->punto_venta;

             $producto_detalle->codigo_producto_sin = $codigo_impuestos_p[$cont];
             $producto_detalle->codigo_producto_empresa =$codigo_p[$cont];
             $producto_detalle->cantidad = $cantidad;
             $producto_detalle->precio = $precio_u;

             $producto_detalle->subtotal = $subtotal;
  
             $producto_detalle->save();  

             $cont++;
         }

         return redirect('nota_venta')->with('status', 'REGISTRO GUARDADO CON EXITO');         
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
