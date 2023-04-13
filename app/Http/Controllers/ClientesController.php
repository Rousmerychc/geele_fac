<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;

use DataTables;

use App\ParametricaPaisOrigen;
use App\ParametricaDocumentoTipoIdentidad;
use App\ParametricaTipoDocumentoSector;
use App\ParametricaTipoMetodoPago;
use App\ ParametricaTipoMoneda;
use Illuminate\Support\Facades\DB;


class ClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function __construct()  {
        $this->middleware('auth');
    }
    public function VerificarNIT(Request $request)
    {
        $nit =  $request->get('dato');
        //dd($nit);
        $wsdl = "https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionCodigos?wsdl";
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJvcmJvbHNhMSIsImNvZGlnb1Npc3RlbWEiOiI3MUNDQzlBQjhCMTREMThFQTgxRThBRSIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTBNREExTmpjM01MSUFBTmhBRmlnS0FBQUEiLCJpZCI6MTI4MjczLCJleHAiOjE2NzQyNTkyMDAsImlhdCI6MTY0Mjc4Mjc4OSwibml0RGVsZWdhZG8iOjEwMDUzNzcwMjgsInN1YnNpc3RlbWEiOiJTRkUifQ.g8G1KZjRpf0Z4BnLqjIRPBNQ6OQMgvBHfMPEdR4r6xuR0Hzgzt_DF4nVRH79_42dcwbCOPX_xw_ey-fBDWF_5g';

        $client = new \SoapClient($wsdl, [ 
            'stream_context' => stream_context_create([ 
                'http'=> [ 
                    'header' => "apikey: TokenApi $token",  
                ] 
            ]),

            'cache_wsdl' => WSDL_CACHE_NONE,
            'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE,
        ]);

        $SolicitudVerificarNit = array(
            'codigoAmbiente'        => 2,
            'codigoModalidad'       => 1,
            'codigoSistema'         => '71CCC9AB8B14D18EA81E8AE',
            'codigoSucursal'        => 0,
            'cuis'                  => 'B4450D89',
            // 'cuis'                  => 'EED58C49',
            'nit'                   => 1005377028,
            'nitParaVerificacion'   => $nit,
        );
        $verificarNit = $client->verificarNit(
            array(
                "SolicitudVerificarNit" => $SolicitudVerificarNit,
            )
        );

        //$prueba = $verificarNit->
        //dd($verificarNit);
        $p = $verificarNit->{'RespuestaVerificarNit'};
        $p1 = $p->{'mensajesList'};
        $prueba = $p1->{'descripcion'};
        //dd($prueba);
        return response(json_encode(array('prueba'=>$prueba)),200)->header('Content-type','text/plain');
    }

    public function index()
    {
        //
        return view('clientes.index');
    }
    public function clientesajax(Request $request)
    {
        if ($request->ajax()) {
           $data = DB::table('clientes')
           ->join('parametrica_pais_origen','parametrica_pais_origen.codigo_clasificador','=','clientes.codigo_pais')
           ->select('clientes.id','clientes.razon_social_cli','clientes.nro_documento','clientes.direccion','parametrica_pais_origen.descripcion')
           ->orderBy('id','DESC')
           ->get();
            return Datatables::of($data)
                ->addColumn('btn','clientes.actions')
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
        $pais = DB::table('parametrica_pais_origen')->where('id','=',91)
        ->orwhere('id','=',58)
        ->orwhere('id','=',53)
        ->get();

        $moneda = ParametricaTipoMoneda::where('id','=',1)
        ->orwhere('id','=',2)
        ->orwhere('id','=',7)
        ->get();

        $tipo_pago = ParametricaTipoMetodoPago::where('id','=',7)->get();

        $tipo_doc_identidad = ParametricaDocumentoTipoIdentidad::where('id','=',4)->get();

        return view('clientes.create',['pais'=>$pais,'moneda'=>$moneda, 'tipo_pago'=>$tipo_pago , 'tipo_doc_identidad'=>$tipo_doc_identidad]);

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
        $cliente = new Cliente;
        $cliente->razon_social_cli = $request->get('razon_social_cli');
        $cliente->tipo_documento = (int)$request->get('tipo_doc');
        $cliente->nro_documento = $request->get('nro_documento');
        $cliente->complemento = $request->get('complemento');
        $cliente->incoterm = $request->get('incoterm');
        $cliente->incoterm_detalle = $request->get('incoterm_detalle');
        $cliente->codigo_pais = (int)$request->get('codigo_pais');
        $cliente->direccion = $request->get('direccion');
        $cliente->lugar_destino = $request->get('lugar_destino');
        $cliente->puerto_destino = $request->get('puerto_destino');
        $cliente->codigo_metodo_pago = (int)$request->get('codigo_metodo_pago');
        $cliente->tipo_moneda = (int)$request->get('tipo_moneda');
        $cliente->email =$request->get('email');
        $cliente->texto =$request->get('texto');
        
        $moneda = ParametricaTipoMoneda::where('codigo_clasificador','=',(int)$request->get('tipo_moneda'))
        ->first();
        $cliente->descripcion_moneda = $moneda->descripcion;
   
        $cliente->estado =  (int)$request->get('estado');
        $cliente->usuario = $dato;
       
        $cliente->save();
        
        return redirect('clientes')->with('status', 'registro guardado con exito');
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

        //
        $pais = DB::table('parametrica_pais_origen')->where('id','=',91)
        ->orwhere('id','=',58)
        ->orwhere('id','=',53)
        ->get();

        $moneda = ParametricaTipoMoneda::where('id','=',1)
        ->orwhere('id','=',2)
        ->orwhere('id','=',7)
        ->get();

        $tipo_pago = ParametricaTipoMetodoPago::where('id','=',7)->get();

        $tipo_doc_identidad = ParametricaDocumentoTipoIdentidad::where('id','=',4)->get();

        $cliente =Cliente::findOrFail($id);
        //dd($cliente);
     
        return view('clientes.edit', ['pais'=>$pais,'moneda'=>$moneda, 'tipo_pago'=>$tipo_pago , 'tipo_doc_identidad'=>$tipo_doc_identidad,'cliente'=>$cliente]);
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
        $cliente =Cliente::findOrFail($id);

        $dato = auth()->user()->id;

        $cliente->razon_social_cli = $request->get('razon_social_cli');
        $cliente->tipo_documento = (int)$request->get('tipo_doc');
        $cliente->nro_documento = $request->get('nro_documento');
        $cliente->complemento = $request->get('complemento');
        $cliente->incoterm = $request->get('incoterm');
        $cliente->incoterm_detalle = $request->get('incoterm_detalle');
        $cliente->codigo_pais = (int)$request->get('codigo_pais');
        $cliente->direccion = $request->get('direccion');
        $cliente->lugar_destino = $request->get('lugar_destino');
        $cliente->puerto_destino = $request->get('puerto_destino');
        $cliente->codigo_metodo_pago = (int)$request->get('codigo_metodo_pago');
        $cliente->tipo_moneda = $request->get('tipo_moneda');
        $moneda = ParametricaTipoMoneda::where('codigo_clasificador','=',$request->get('tipo_moneda'))->first();
        $cliente->descripcion_moneda = $moneda->descripcion;
        $cliente->estado =  (int)$request->get('estado');
        $cliente->usuario = $dato;
        $cliente->email = $request->get('email');
        $cliente->texto =$request->get('texto');
        $cliente->save();
        
        return redirect('clientes')->with('status', 'registro guardado con exito');
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
