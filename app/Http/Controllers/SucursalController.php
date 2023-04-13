<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Sucursal;
use DataTables;
use Carbon\Carbon;
class SucursalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }
    public function index()
    {
        //
        return view('sucursal.index');
    }
    public function sucursalajax(Request $request)
    {
        if ($request->ajax()) {
            $data = Sucursal::all();
            return Datatables::of($data)
                ->addColumn('btn','sucursal.actions')
                ->rawColumns(['btn'])
                ->make(true);
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
        return view('sucursal.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

   
    public function store(Request $request)
    {   
        $dato = auth()->user()->id;
        $fechahoyhora=Carbon::now(-4)->format('Y-m-d H:i:s');
        $sucursal = new Sucursal;
        $sucursal->descripcion = $request->get('descripcion');
        $sucursal->direccion = $request->get('direccion');
        $sucursal->telefono = $request->get('telefono');
        $sucursal->municipio = $request->get('municipio');
        $sucursal->estado =  (int)$request->get('estado');
        $sucursal->nro_sucursal =  (int)$request->get('nro_sucursal');
        //dd((int)$request->get('estado'));
        $sucursal->usuario = $dato;
        $sucursal->fecha = $fechahoyhora;
        $sucursal->save();
        
        return redirect('sucursal')->with('status', 'registro guardado con exito');
    
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
        $sucursales =Sucursal::findOrFail($id);

        //dd($sucursales);
        return view('sucursal.edit')
        ->with('sucursales',$sucursales);
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
        $fechahoyhora=Carbon::now(-4)->format('Y-m-d H:i:s');
       $dato = auth()->user()->id;
       $sucursal =  Sucursal::where('id','=',$id)->first();
       $sucursal->descripcion = $request->get('descripcion');
        $sucursal->direccion = $request->get('direccion');
        $sucursal->telefono = $request->get('telefono');
        $sucursal->municipio = $request->get('municipio');
        $sucursal->estado =  (int)$request->get('estado');
        $sucursal->nro_sucursal =  (int)$request->get('nro_sucursal');
        $sucursal->usuario = $dato;
        $sucursal->fecha = $fechahoyhora;
        $sucursal->save();

       return redirect('sucursal');
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
        Sucursal::destroy($id);
        return redirect('sucursal');
    }
}
