<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\UsuarioFactura;

class UsuarioFacturaController extends Controller
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

    public function index()
    {
        return view('usuarios_fac.index');
    }

    public function usuario_facturacionajax(Request $request)
    {
        if ($request->ajax()) {
            //$data = Productos::orderBy('id', 'DESC')->get();
            $data = UsuarioFactura::all();
                return Datatables::of($data)
                ->addColumn('btn','usuarios_fac.actions')
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
        return view('usuarios_fac.create');
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
        $usuario_fac = new UsuarioFactura;
       
        $usuario_fac->nombre = $request->get('nombre');
        $usuario_fac->apellido = $request->get('apellido');
        $usuario_fac->estado = (int)$request->get('estado');
        $usuario_fac->password = $request->get('password');
        
        $usuario_fac->save();

        return redirect('usuario_facturacion')->with('status', 'Registro guardado con exito');
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
        $usuarios_fac =UsuarioFactura::findOrFail($id);
        return view('usuarios_fac.edit',['usuarios_fac' =>$usuarios_fac]);
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
        
        $usuario_fac =UsuarioFactura::findOrFail($id);
       
        $usuario_fac->nombre = $request->get('nombre');
        $usuario_fac->apellido = $request->get('apellido');
        $usuario_fac->estado = (int)$request->get('estado');
        $usuario_fac->password = $request->get('password');
        
        $usuario_fac->save();

        return redirect('usuario_facturacion')->with('status', 'Registro guardado con exito');
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
