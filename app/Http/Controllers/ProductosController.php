<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Productos;

use App\UnidadMedida;
use App\ParametricaProductosServicios;
use App\Grupos;

use DataTables;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function productosajax(Request $request)
    {
        if ($request->ajax()) {
            $data = Productos::orderBy('id', 'DESC')->get();
                return Datatables::of($data)
                ->addColumn('btn','productos.actions')
                ->rawColumns(['btn'])
                ->make(true);
        }
    }


    public function index()
    {
        //
        return view('productos.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $unidad_medida = UnidadMedida::all();
        $productos_imp = ParametricaProductosServicios::where('codigo_actividad','=',473000)->get();
        $grupos = Grupos::all();

        return view('productos.create',['unidad_medida' => $unidad_medida , 'productos_imp'=>$productos_imp, 'grupos'=>$grupos]);
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
        $dato = auth()->user()->id;
        $fechahoyhora=Carbon::now(-4)->format('Y-m-d H:i:s');
        $producto = new Productos;
        $producto->id_grupo = $request->{"id_grupo"};
        $producto->codigo_empresa = $request->{"codigo_empresa"};
        $producto->descripcion = $request->{"descripcion"};

        $producto->id_producto_impuesto = $request->{"codigo_producto_impuestos"};
        $descripcion_impuestos = ParametricaProductosServicios::findOrFail($request->{"codigo_producto_impuestos"}); 
        $producto->codigo_impuestos =  $descripcion_impuestos->codigo_producto;
        $producto->descripcion_impuestos =  $descripcion_impuestos->descripcion_producto;

        $producto->codigo_unidad_medida = $request->{"codigo_medida"};
        $unidad_medida = UnidadMedida::where('codigo','=',$request->{"codigo_medida"})->first();
        //dd($unidad_medida);
        $producto->unidad_medida = $unidad_medida->descripcion;
        
        $producto->precio = $request->{"precio"};
        $producto->estado = (int)$request->{"estado"};
        $producto->usuario = $dato;
        $producto->fecha = $fechahoyhora;

        $producto->save();

        return redirect('productos')->with('status', 'REGISTRO SE GUARDO CON EXITO');

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
        $producto = Productos::findOrFail($id);
        $unidad_medida = UnidadMedida::all();
        $productos_imp = ParametricaProductosServicios::where('codigo_actividad','=',473000)->get();
        $grupos = Grupos::all();

        return view('productos.edit',['producto' =>$producto, 'unidad_medida' =>$unidad_medida, 'productos_imp'=>$productos_imp, 'grupos'=>$grupos]);
        
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
        $dato = auth()->user()->id;
        $fechahoyhora=Carbon::now(-4)->format('Y-m-d H:i:s');
        $producto = Productos::findOrFail($id);
        $producto->id_grupo = $request->{"id_grupo"};
        $producto->codigo_empresa = $request->{"codigo_empresa"};
        $producto->descripcion = $request->{"descripcion"};

        $producto->id_producto_impuesto = $request->{"codigo_producto_impuestos"};
        $descripcion_impuestos = ParametricaProductosServicios::findOrFail($request->{"codigo_producto_impuestos"}); 
        $producto->codigo_impuestos =  $descripcion_impuestos->codigo_producto;
        $producto->descripcion_impuestos =  $descripcion_impuestos->descripcion_producto;

        $producto->codigo_unidad_medida = $request->{"codigo_medida"};
        $unidad_medida = UnidadMedida::where('codigo','=',$request->{"codigo_medida"})->first();
        //dd($unidad_medida);
        $producto->unidad_medida = $unidad_medida->descripcion;
        
        $producto->precio = $request->{"precio"};
        $producto->estado = (int)$request->{"estado"};
        $producto->usuario = $dato;
        $producto->fecha = $fechahoyhora;

        $producto->save();

        return redirect('productos')->with('status', 'REGISTRO SE GUARDO CON EXITO');
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
