<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Productos;
use App\Sucursal;
use App\Ingresos;
use App\IngresosDetalle;
use App\Proveedor;
use App\Datos;

use DataTables;
use Illuminate\Support\Facades\DB;
use Luecano\NumeroALetras\NumeroALetras;
use PDF;


class IngresosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware('auth');
    }
    public function ingresoajax(Request $request){
        if ($request->ajax()) {
            $data = Ingresos::join('proveedor','proveedor.id','=','ingresos.id_proveedor')
            ->join('sucursal','sucursal.nro_sucursal','ingresos.id_sucursal')
            ->select('ingresos.id','ingresos.nro_por_sucursal','ingresos.estado','ingresos.fecha','proveedor.descripcion','ingresos.monto_total','sucursal.descripcion as sucursal')
            ->orderBy('ingresos.id', 'DESC')
            ->get();
                return Datatables::of($data)
                ->addColumn('btn','ingresos.actions')
                ->addColumn('pdf','ingresos.pdf')
                ->rawColumns(['btn','pdf'])
                ->make(true);
        }
    }
    public function anular_ingreso($id){
        $ingreso = Ingresos::where('id','=',$id)->first();
        $ingreso->estado = 1;
        $ingreso->save();

        return redirect('ingreso')->with('status', 'REGISTRO ANULADO');;
    }
    public function pdf_ingreso($id){
        
        $datos_empresa = Datos::first();

        $ingreso = Ingresos::where('ingresos.id','=',$id)
        ->join('proveedor','proveedor.id','=','ingresos.id_proveedor')
        ->join('sucursal','sucursal.nro_sucursal','ingresos.id_sucursal')
        
        ->select('ingresos.id','ingresos.nro_por_sucursal','ingresos.fecha','proveedor.descripcion','ingresos.monto_total','sucursal.descripcion as sucursal','sucursal.telefono','sucursal.municipio')
        ->first();

        $detalle = IngresosDetalle::where('id_ingreso','=',$id)
        ->join('productos','productos.id','=','ingresos_detalle.id_producto')
        ->get();
        //dd($detalle);
        //PARA LITERAL EN MONEDA BOLIVIANOS
        $formatterb = new NumeroALetras();
        $reb= (int) ($ingreso->monto_total / 1000);
        $literalb= $formatterb->toInvoice($ingreso->monto_total, 2,'');
        if($reb==1){
            $literalb = 'UN '.$literalb;
        }
        //dd($ingreso);
        //dd($datos_empresa,$ingreso,$detalle);
        $data =['ingreso'=>$ingreso, 'detalle'=>$detalle,  'datos_empresa'=>$datos_empresa, 'literalb' =>$literalb];

        $pdf = PDF::loadView('ingresos.pdf_cliente',$data);

        $pdf->setPaper("letter", "portrait");
        return $pdf->stream('Factura.pdf');
    }
    public function index()
    {
        //
        return view('ingresos.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $proveedor = Proveedor::all();
        $sucursal = Sucursal::all();
        $productos = Productos::all();
        $fecha =Carbon::now(-4)->format('Y-m-d');

        return  view('ingresos.ingreso',['proveedor' =>$proveedor, 'sucursal'=>$sucursal, 'productos'=>$productos, 'fecha'=>$fecha]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $fecha = $request->get('fecha');
       // dd($fecha);
        $id_proveedor  = $request->get('id_proveedor');
        $id_sucursal = $request->get('id_sucursal');
        $fechahoyhora=Carbon::now(-4)->format('Y-m-d H:i:s');
        $mt = (double) str_replace(',', '', $request->get('total_detalle'));
        $monto_total = $mt;
      
        $cantidad_p = $request->get('cantidad');
        $codigo_p = $request->get('codigo_pro');//codigo_empresa
        $precio_unitario_p = $request->get('precio_uni');
        $subtotal_p = $request->get('subtotal');
        $fechahoyhora = Carbon::now(-4)->format('Y-m-d H:i:s');
        $nro_ingreso = IngresoS::where('id_sucursal','=',$id_sucursal)
                        ->orderby('id','DESC')
                        ->first();
                       
        if($nro_ingreso == null){
            $n = 1;
           
        } else{
            $n = $nro_ingreso->nro_por_sucursal+1;
           
        }

        $ingreso = new Ingresos;
        $ingreso->id_sucursal = $id_sucursal;
        $ingreso->nro_por_sucursal = $n;
        $ingreso->fecha = $fecha;
        $ingreso->fecha_hora = $fechahoyhora;
        $ingreso->id_proveedor = $id_proveedor;
        $ingreso->monto_total = $monto_total;
        $ingreso->usuario = auth()->user()->id;
        $ingreso->save();

        $cont = 0;
        while ($cont < count($codigo_p) ) {
            
            $cantidad = (double) str_replace(',', '', $cantidad_p[$cont]);
            $precio_u = (double) str_replace(',', '', $precio_unitario_p[$cont]);
            $subtotal = (double) str_replace(',', '', $subtotal_p[$cont]);
            $ingreso_detalle = new IngresosDetalle;
            $ingreso_detalle->id_ingreso = $ingreso->id;
            $ingreso_detalle->id_sucursal = $id_sucursal;
            $ingreso_detalle->id_producto = $codigo_p[$cont];
            $ingreso_detalle->cantidad = $cantidad;
            $ingreso_detalle->precio = $precio_u;
            $ingreso_detalle->subtotal = $subtotal;

            $ingreso_detalle->save();
            $cont++;

        }
        return redirect('ingreso')->with('status', 'REGISTRO GUARDADO CON EXITO');

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
        $ingreso  = Ingresos::where('id','=',$id)->first();
        //dd($ingreso);
        $ingreso_detalle = IngresosDetalle::join('productos','productos.id','=','ingresos_detalle.id_producto')
            ->where('id_ingreso','=',$ingreso->id)
            ->select('productos.id','productos.codigo_empresa','productos.descripcion','productos.id_producto_impuesto','productos.codigo_unidad_medida','productos.unidad_medida','ingresos_detalle.*')
            ->get();
        $proveedor = Proveedor::all();
        $sucursal = Sucursal::all();
        $productos = Productos::all();
        $fecha =Carbon::now(-4)->format('Y-m-d');

        return  view('ingresos.edit',['proveedor' =>$proveedor, 'sucursal'=>$sucursal, 'productos'=>$productos, 'fecha'=>$fecha, 'ingreso'=>$ingreso, 'ingreso_detalle'=>$ingreso_detalle]);
       
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
        $fecha = $request->get('fecha');
       // dd($fecha);
        $id_proveedor  = $request->get('id_proveedor');
        $id_sucursal = $request->get('id_sucursal');
        $fechahoyhora=Carbon::now(-4)->format('Y-m-d H:i:s');
        $mt = (double) str_replace(',', '', $request->get('total_detalle'));
        $monto_total = $mt;
      
        $cantidad_p = $request->get('cantidad');
        $codigo_p = $request->get('codigo_pro');//codigo_empresa
        $precio_unitario_p = $request->get('precio_uni');
        $subtotal_p = $request->get('subtotal');
        $fechahoyhora = Carbon::now(-4)->format('Y-m-d H:i:s');
        $nro_ingreso = IngresoS::where('id_sucursal','=',$id_sucursal)
                        ->orderby('id','DESC')
                        ->first();
                       
        if($nro_ingreso == null){
            $n = 1;
           
        } else{
            $n = $nro_ingreso->nro_por_sucursal+1;
           
        }

        $ingreso = Ingresos::findOrFail($id);
        $ingreso->id_sucursal = $id_sucursal;
        $ingreso->nro_por_sucursal = $n;
        $ingreso->fecha = $fecha;
        $ingreso->fecha_hora = $fechahoyhora;
        $ingreso->id_proveedor = $id_proveedor;
        $ingreso->monto_total = $monto_total;
        $ingreso->usuario = auth()->user()->id;
        $ingreso->save();

        $borrar_detalle = DB::select(DB::raw('DELETE FROM ingresos_detalle WHERE ingresos_detalle.id_ingreso = ?'),[ $ingreso->id]);
        $cont = 0;
        while ($cont < count($codigo_p) ) {
            
            $cantidad = (double) str_replace(',', '', $cantidad_p[$cont]);
            $precio_u = (double) str_replace(',', '', $precio_unitario_p[$cont]);
            $subtotal = (double) str_replace(',', '', $subtotal_p[$cont]);
            $ingreso_detalle = new IngresosDetalle;
            $ingreso_detalle->id_ingreso = $ingreso->id;
            $ingreso_detalle->id_sucursal = $id_sucursal;
            $ingreso_detalle->id_producto = $codigo_p[$cont];
            $ingreso_detalle->cantidad = $cantidad;
            $ingreso_detalle->precio = $precio_u;
            $ingreso_detalle->subtotal = $subtotal;

            $ingreso_detalle->save();
            $cont++;

        }
        return redirect('ingreso')->with('status', 'REGISTRO GUARDADO CON EXITO');
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
