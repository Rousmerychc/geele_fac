<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;



use Carbon\Carbon;

use App\Facturacion;
use App\Productos;
use App\Datos;
use App\Sucursal;


use Illuminate\Support\Facades\DB;

use PDF;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\Saldos;
use App\Exports\KardexProducto;
use App\Exports\ResumenMensual;
use App\UsuarioFactura;

class ReportesController extends Controller
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
    public function index()
    {
        //
        $fecha = Carbon::now(-4)->format('Y-m-d');
        $sucursal = Sucursal::all();
        $productos = Productos::orderBy('codigo_empresa')->get();
        $usuario_fac = UsuarioFactura::all();

        return view('reportes.reporte_index',['fecha'=>$fecha, 'sucursal'=>$sucursal, 'productos'=>$productos, 'usuario_fac' =>$usuario_fac]);
    }
    public function saldos(Request $request){
        $fechaini = $request->{'fechai1'};
        $nro_sucursal = $request->{'sucursal'};
        $documento = $request->{'proceso1'};
        $sucursal = Sucursal::where('nro_sucursal','=',$nro_sucursal)->first();
        
        $consulta = DB::select("SELECT * FROM 
        (
        SELECT productos.codigo_empresa, productos.descripcion, t11.* FROM 
        (
            SELECT factura_detalle.codigo_producto_empresa, SUM(factura_detalle.cantidad) as salida FROM factura JOIN 
            factura_detalle on factura.id = 
                    factura_detalle.id_tabla_factura WHERE factura.id_sucursal = ? AND factura.fecha <= ? AND 
            factura.estado = 0 GROUP BY 
                    factura_detalle.codigo_producto_empresa
        )as t11
        RIGHT JOIN
        productos ON productos.codigo_empresa = t11.codigo_producto_empresa
        ORDER BY productos.codigo_empresa
        )as t33
        JOIN
        (
            SELECT productos.codigo_empresa , t22.* FROM
        (
        SELECT ingresos_detalle.id_producto, SUM(ingresos_detalle.cantidad) as ingresos FROM ingresos JOIN ingresos_detalle ON ingresos.id = 	
                    ingresos_detalle.id_ingreso WHERE ingresos.id_sucursal = ? AND ingresos.fecha <= ? AND ingresos.estado = 0 GROUP BY 
                    ingresos_detalle.id_producto
            )as t22
        RIGHT JOIN productos ON productos.id = t22.id_producto
        ORDER BY productos.codigo_empresa
        )as t44
        ON t33.codigo_empresa = t44.codigo_empresa  
        ORDER BY t33.codigo_empresa ASC",[$nro_sucursal,$fechaini,  $nro_sucursal,$fechaini]);

        //dd($consulta);
       
        if($documento == 0){
            $data =[ 'fechaini' =>$fechaini, 'consulta' => $consulta ,'sucursal'=>$sucursal];
            $pdf = PDF::loadView('reportes.saldos',$data);
            $pdf->setPaper("letter", "portrait");
            return $pdf->stream('Reporte.pdf');
        }
        else{
            return (new  Saldos($fechaini,$consulta,$sucursal))->download('SaldosProducto.xlsx');
        }      
    }

    public function karde_producto(Request $request){
        
        //return ('hola');
        
        $fechaini = $request->{'fechai2'};
        $fechafin = $request->{'fechaf2'};
        $documento = $request->{'proceso2'};
        $id_sucursal= $request->{'sucursal'};
        $id_producto = $request->{'producto'};
        //dd($fechaini,$fechafin,$id_sucursal,$id_producto);

        $sucursal = sucursal::where('nro_sucursal','=',$id_sucursal)->first();
        $producto = Productos::where('codigo_empresa','=',$id_producto)->first();

        $salida_saldo_anterior = DB::select("SELECT factura_detalle.codigo_producto_empresa, SUM(factura_detalle.cantidad) as cantidad 
        FROM factura JOIN factura_detalle ON factura.id = factura_detalle.id_tabla_factura 
        WHERE factura.fecha < ? AND factura_detalle.codigo_producto_empresa = ?
         AND factura.id_sucursal = ? AND factura.estado = 0 GROUP BY factura_detalle.codigo_producto_empresa",
         [ $fechaini, $id_producto,$id_sucursal]);

        $ingreso_saldo_anterior = DB::select("SELECT productos.codigo_empresa, t1.* 
        FROM ( SELECT ingresos_detalle.id_producto, SUM(ingresos_detalle.cantidad) as cantidad 
            FROM ingresos JOIN ingresos_detalle ON ingresos.id = ingresos_detalle.id_ingreso 
            WHERE ingresos.fecha < ? AND ingresos.id_sucursal = ? AND ingresos.estado = 0 
            GROUP BY ingresos_detalle.id_producto ) as t1
        JOIN productos ON t1.id_producto = productos.id WHERE productos.codigo_empresa = ?",
        [ $fechaini, $id_sucursal,$id_producto,]);

        if ($salida_saldo_anterior == null) {
            $salida_cant = 0;
        }else{
            foreach ($salida_saldo_anterior as $sal) {
                $salida_cant = $sal->cantidad;
            }
        }
        
        if ($ingreso_saldo_anterior == null) {
            $ingreso_cant = 0;
        }else {
            foreach ($ingreso_saldo_anterior as $ingr) {
                $ingreso_cant = $ingr->cantidad;
            }
        }

        $resul = $ingreso_cant - $salida_cant;
        //dd($salida_saldo_anterior,  $ingreso_saldo_anterior);

        //dd($resul);
        //dd($salida_cant,$ingreso_cant);

        $consulta = DB::select("SELECT * FROM 
        (
            SELECT  'S' as tipo, factura.fecha, factura.id_factura, factura.nro_fac_manual, factura.nro_nota_venta, factura.razon_social as descrip_rp, factura_detalle.codigo_producto_empresa, factura_detalle.cantidad, factura_detalle.precio 
        FROM factura JOIN factura_detalle ON factura.id = factura_detalle.id_tabla_factura 
        WHERE factura.fecha >= ? AND factura.fecha <= ?
        AND factura_detalle.codigo_producto_empresa = ? AND factura.id_sucursal = ? AND factura.estado = 0
        
        UNION 
         
       SELECT t2.tipo, t2.fecha, t2.nro_por_sucursal ,0 ,1 ,t2.descrip_rp, productos.codigo_empresa ,t2.cantidad,t2.precio  FROM
        (
            SELECT 'I' as tipo, ingresos.fecha, ingresos.nro_por_sucursal, 0,1, proveedor.descripcion as descrip_rp, ingresos_detalle.id_producto, ingresos_detalle.cantidad, ingresos_detalle.precio
                FROM  ingresos JOIN ingresos_detalle ON ingresos.id = ingresos_detalle.id_ingreso  JOIN proveedor ON ingresos.id_proveedor = proveedor.id
                WHERE ingresos.fecha >= ? AND ingresos.fecha <= ? 
                AND ingresos.id_sucursal = ? AND ingresos.estado = 0
            ) as t2
            JOIN
            productos ON productos.id = t2.id_producto
            WHERE productos.codigo_empresa = ?
        ) as t1

        ORDER BY   t1.fecha,t1.tipo,t1.id_factura, t1.nro_fac_manual, t1.nro_nota_venta ",
        [$fechaini,$fechafin,$id_producto, $id_sucursal, $fechaini,$fechafin,$id_sucursal,$id_producto ]);

        //dd($consulta,$fechaini,$fechafin,$id_sucursal,$id_producto);
       
        if($documento == 0){
            $data =[ 'fechaini' =>$fechaini, 'fechafin' => $fechafin ,'consulta'=>$consulta, 'resul'=>$resul,'sucursal'=>$sucursal, 'producto'=>$producto];
            $pdf = PDF::loadView('reportes.kardex_producto',$data);
            $pdf->setPaper("letter", "portrait");
            return $pdf->stream('Reporte.pdf');
        }
        else{
           
            return (new KardexProducto($fechaini,$fechafin,$consulta,$resul,$sucursal,$producto))->download('kardexproducto.xlsx');
        }
        //dd($factura);
    }

    public function resumen_mensual(Request $request){
        $fechaini = $request->{'fechai3'};
        $fechafin = $request->{'fechaf3'};
        $documento = $request->{'proceso3'};
        $sucursal = $request->{'sucursal'};

        $sucu = sucursal::where('nro_sucursal','=',$sucursal)->first();
        
        $consulta = DB::select("SELECT * FROM  
        (
          SELECT productos.id, productos.codigo_empresa as codi , productos.descripcion,t11.ingreso_ant,t11.total_ingreso_ant FROM
            (
               SELECT  ingresos_detalle.id_producto,SUM(ingresos_detalle.cantidad) as ingreso_ant ,SUM(ingresos_detalle.subtotal) as 				total_ingreso_ant 
                FROM ingresos 
                JOIN ingresos_detalle ON ingresos.id = ingresos_detalle.id_ingreso
                WHERE ingresos.fecha < ? AND ingresos.estado = 0 AND ingresos.id_sucursal = ? GROUP BY 			
                ingresos_detalle.id_producto
                ) AS t11
                RIGHT JOIN 
                productos ON productos.id = t11.id_producto 
           ) as t2
          
           LEFT JOIN
           (
             SELECT ingresos_detalle.id_producto,SUM(ingresos_detalle.cantidad) as ingreso ,SUM(ingresos_detalle.subtotal) as 
               total_ingreso FROM ingresos 
             JOIN ingresos_detalle ON ingresos.id = ingresos_detalle.id_ingreso 
             WHERE ingresos.fecha >= ?  AND ingresos.fecha <= ? AND ingresos.estado = 0 AND ingresos.id_sucursal = ? GROUP BY ingresos_detalle.id_producto  
           )as t4
           ON t4.id_producto = t2.id
           LEFT JOIN 
           (
                SELECT factura_detalle.codigo_producto_empresa, SUM(factura_detalle.cantidad) as salida_ant, SUM(factura_detalle.subtotal) as total_salida_ant FROM factura 
              JOIN factura_detalle ON factura.id = factura_detalle.id_tabla_factura 
              WHERE factura.fecha < ? AND factura.estado = 0 AND factura.id_sucursal = ? GROUP BY factura_detalle.codigo_producto_empresa
           )as t3
           ON t2.codi = t3.codigo_producto_empresa
           
           LEFT JOIN
          (
            SELECT factura_detalle.codigo_producto_empresa, SUM(factura_detalle.cantidad) as salida, SUM(factura_detalle.subtotal) as total_salida FROM factura 
            JOIN factura_detalle ON factura.id = factura_detalle.id_tabla_factura 
            WHERE factura.fecha >= ? AND factura.fecha <= ? AND factura.estado = 0 AND factura.id_sucursal = ? GROUP BY 				
            factura_detalle.codigo_producto_empresa 
          )as t5
          ON t5.codigo_producto_empresa = t2.codi
          
          ORDER BY codi",[$fechaini, $sucursal,   $fechaini, $fechafin,$sucursal    ,$fechaini, $sucursal,       $fechaini, $fechafin,$sucursal]);
        
        //dd($consulta,$fechaini,$fechafin);
        if($documento == 0){
            $data =[ 'fechaini' =>$fechaini, 'fechafin' => $fechafin, 'consulta'=>$consulta,'sucu'=>$sucu];
            $pdf = PDF::loadView('reportes.resumen_mensual',$data);
            $pdf->setPaper("letter", "portrait");
            return $pdf->stream('ResumenMensual.pdf');
        }
        else{
            return (new  ResumenMensual($fechaini,$fechafin,$sucu,$consulta, $sucursal))->download('ResumenMensual.xlsx');
        }
    }

    public function reporte_ventas(Request $request){
        $fechaini = $request->{'fechai4'};
        $fechafin = $request->{'fechaf4'};
        $id_operador = $request->{'id_operador'};
        $documento = $request->{'proceso4'};
        $sucursal = $request->{'sucursal'};
        $sucu = Sucursal::where('nro_sucursal','=',$sucursal)->first();
        
        if($id_operador == 0){
            $consulta =  DB::select('SELECT * FROM factura WHERE factura.fecha <= ? AND factura.fecha >= ? AND factura.id_sucursal = ? ORDER BY factura.id ASC',[$fechafin,$fechaini,$sucursal]);
            $operadores = UsuarioFactura::all();
        }else{
            $consulta =  DB::select('SELECT * FROM factura WHERE factura.fecha <= ? AND factura.fecha >= ? AND factura.id_usuario = ? AND factura.id_sucursal = ? ORDER BY factura.id ASC',[$fechafin,$fechaini,$id_operador,$sucursal]);
            $operadores = UsuarioFactura::where('id','=',$id_operador)->get();
        }

        if($documento == 0){
            $data =[ 'fechaini' =>$fechaini, 'fechafin' => $fechafin, 'consulta'=>$consulta,'sucu'=>$sucu,'operadores'=>$operadores];
            $pdf = PDF::loadView('reportes.reporte_diario_ventas',$data);
            $pdf->setPaper(array(0, 0, 200, 800), 'portrait');
            return $pdf->stream('ReporteDiarioVentas.pdf');
        }
    }

    public function reporte_movimiento_productos(Request $request){
        
        $fechaini = $request->{'fechai5'};
        $fechafin = $request->{'fechaf5'};
        $id_operador = $request->{'id_operador'};
        $documento = $request->{'proceso5'};
        $sucursal = $request->{'sucursal'};
        $sucu = Sucursal::where('nro_sucursal','=',$sucursal)->first();
        
        if($id_operador == 0){
            $consulta =  DB::select('SELECT t1.*, productos.descripcion FROM
            (
            SELECT factura.id_usuario, factura_detalle.codigo_producto_empresa, SUM(factura_detalle.cantidad) as cantidad, SUM(factura_detalle.subtotal) as precio 
            FROM factura JOIN factura_detalle ON factura.id = factura_detalle.id_tabla_factura
             WHERE factura.fecha <= ? AND factura.fecha >= ? AND factura.id_sucursal = ? AND factura.estado =0
             GROUP BY factura_detalle.codigo_producto_empresa, factura.id_usuario) 
             as t1
             JOIN
             productos
             ON productos.codigo_empresa = t1.codigo_producto_empresa ORDER BY t1.id_usuario',[$fechafin,$fechaini,$sucursal]);
             
            $operadores = UsuarioFactura::all();
        }else{
            $consulta =  DB::select('SELECT t1.*, productos.descripcion FROM
            (
            SELECT factura.id_usuario, factura_detalle.codigo_producto_empresa, SUM(factura_detalle.cantidad) as cantidad, SUM(factura_detalle.subtotal) as precio 
            FROM factura JOIN factura_detalle ON factura.id = factura_detalle.id_tabla_factura
             WHERE factura.fecha <= ? AND factura.fecha >= ? AND factura.id_usuario = ? AND factura.id_sucursal = ? AND factura.estado =0
             GROUP BY factura_detalle.codigo_producto_empresa, factura.id_usuario) 
             as t1
             JOIN
             productos
             ON productos.codigo_empresa = t1.codigo_producto_empresa ORDER BY t1.id_usuario',[$fechafin,$fechaini,$id_operador,$sucursal]);
             //dd($consulta);
            $operadores = UsuarioFactura::where('id','=',$id_operador)->get();
        }

        if($documento == 0){
            $data =[ 'fechaini' =>$fechaini, 'fechafin' => $fechafin, 'consulta'=>$consulta,'sucu'=>$sucu,'operadores'=>$operadores];
            $pdf = PDF::loadView('reportes.reporte_movimiento_productos',$data);
            $pdf->setPaper(array(0, 0, 200, 800), 'portrait');
            return $pdf->stream('ReporteDiarioVentas.pdf');
        }
    }
    
    public function reporte_ventas_diarias(Request $request){

        //return ('hola');
        $fechaini = $request->{'fechai6'};
        $fechafin = $request->{'fechaf6'};
        $id_operador = $request->{'id_operador'};
        $documento = $request->{'proceso6'};
        $sucursal = $request->{'sucursal'};
        $sucu = Sucursal::where('nro_sucursal','=',$sucursal)->first();
        
        if($id_operador == 0){
            $consulta =  DB::select('SELECT * FROM factura WHERE factura.fecha <= ? AND factura.fecha >= ? AND factura.id_sucursal = ? ORDER BY factura.id ASC',[$fechafin,$fechaini,$sucursal]);
            $operadores = UsuarioFactura::where('id','=',-1)->get();
        }else{
            $consulta =  DB::select('SELECT * FROM factura WHERE factura.fecha <= ? AND factura.fecha >= ? AND factura.id_usuario = ? AND factura.id_sucursal = ? ORDER BY factura.id ASC',[$fechafin,$fechaini,$id_operador,$sucursal]);
            $operadores = UsuarioFactura::where('id','=',$id_operador)->get();
        }

        //dd($consulta);
        if($documento == 0){
            $data =[ 'fechaini' =>$fechaini, 'fechafin' => $fechafin, 'consulta'=>$consulta,'sucu'=>$sucu,'operadores'=>$operadores];
            $pdf = PDF::loadView('reportes.reporte_diario_ventas_carta',$data);
            $pdf->setPaper("letter", "portrait");
            return $pdf->stream('ReporteDiarioVentas.pdf');
        }
    }
    

    public function reporte_ventas_diarias_productos(Request $request){
        
        $fechaini = $request->{'fechai7'};
        $fechafin = $request->{'fechaf7'};
        $id_operador = $request->{'id_operador'};
        $documento = $request->{'proceso7'};
        $sucursal = $request->{'sucursal'};
        $sucu = Sucursal::where('nro_sucursal','=',$sucursal)->first();
        
        if($id_operador == 0){
            $operadores = null;
            $consulta = DB::select("SELECT factura.id as id_fac, factura.fecha ,factura.razon_social, factura.monto_total, factura.estado,factura_detalle.*, productos.descripcion 
            FROM factura 
            JOIN factura_detalle ON factura.id = factura_detalle.id_tabla_factura 
            JOIN productos ON factura_detalle.codigo_producto_empresa = productos.codigo_empresa 
            WHERE factura.fecha <= ? AND factura.fecha >= ? AND factura.id_sucursal = ?"
            ,[$fechafin,$fechaini,$sucursal]);
        }else{
            $operadores = UsuarioFactura::where('id','=',$id_operador)->first();
            $consulta = DB::select("SELECT factura.id as id_fac, factura.fecha ,factura.razon_social, factura.monto_total, factura.estado,factura_detalle.*, productos.descripcion 
            FROM factura 
            JOIN factura_detalle ON factura.id = factura_detalle.id_tabla_factura 
            JOIN productos ON factura_detalle.codigo_producto_empresa = productos.codigo_empresa 
            WHERE factura.fecha <= ? AND factura.fecha >= ? AND factura.id_usuario = $id_operador AND factura.id_sucursal = $sucursal"
            ,[$fechafin,$fechaini,$id_operador,$sucursal]);
        }
        //dd($operadores);
        //dd($operadores, $consulta);
        if($documento == 0){
            $data =[ 'fechaini' =>$fechaini, 'fechafin' => $fechafin, 'consulta'=>$consulta,'sucu'=>$sucu,'operadores'=>$operadores];
            $pdf = PDF::loadView('reportes.reporte_diario_ventas_productos_carta',$data);
            $pdf->setPaper("letter", "portrait");
            return $pdf->stream('ReporteDiarioVentas.pdf');
        }
        
        
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
