<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\UnidadMedida;
use App\Actividades;
use App\ActiviadesSector;
use App\LeyendasFacturacion;
use App\MensajesServicios;
use App\ParametricaProductosServicios;
use App\ParametricasEventosSignificativos;
use App\ParametricaMotivoAnulacion;
use App\ParametricaPaisOrigen;
use App\ParametricaDocumentoTipoIdentidad;
use App\ParametricaTipoDocumentoSector;
use App\ParametricaTipoEmision;
use App\ParametricaTipoHabitacion;
use App\ParametricaTipoMetodoPago;
use App\ ParametricaTipoMoneda;
use App\ParametricaTipoPuntoVenta;
use App\ParametricaTiposFactura;
use App\Nandina;

class SincronizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function peticion(){
        $wsdl ="https://pilotosiatservicios.impuestos.gob.bo/v2/FacturacionSincronizacion?wsdl";
        
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJESVNFTCBTLlIuTCIsImNvZGlnb1Npc3RlbWEiOiI3MUY1NkIxMDVEQzJGMDg4RURGRDI0RSIsIm5pdCI6Ikg0c0lBQUFBQUFBQUFETTBNTEswTkRFSGtnRHhSSzg4Q2dBQUFBPT0iLCJpZCI6NzQ4MDYsImV4cCI6MTY4MjcyNjQwMCwiaWF0IjoxNjUxMjQzOTkwLCJuaXREZWxlZ2FkbyI6MTAyOTk0NzAyOSwic3Vic2lzdGVtYSI6IlNGRSJ9.kFz0ymYLikGYislB1l1Ce6NkHk3MP7nS6GKBhTa_rv_WcF7c4noX2bfOK3ES-OE2wWujjVKhuW6kCtLl9VQX1Q';

        $client = new \SoapClient($wsdl, [ 
            'stream_context' => stream_context_create([ 
                'http'=> [ 
                    'header' => "apikey: TokenApi $token",  
                ] 
            ]),

            'cache_wsdl' => WSDL_CACHE_NONE,
            'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP | SOAP_COMPRESSION_DEFLATE,
        ]);

        $SolicitudSincronizacion = array(
            'codigoAmbiente'   => 2,
            'codigoPuntoVenta' => 0 ,
            'codigoSistema'    => '71F56B105DC2F088EDFD24E',
            'codigoSucursal'   => 0,
            'cuis'             => '4218015C',
            // 'cuis'             => '6F5538D5',  
            'nit'              => 1029947029, 
        );

        $peticion = [$client, $SolicitudSincronizacion];
        return  $peticion;

    }
     
    public function actividades()
    {
        $client = $this->peticion()[0];
        $SolicitudSincronizacion = $this->peticion()[1];

        $actividades = $client->sincronizarActividades(
            array(
                "SolicitudSincronizacion" => $SolicitudSincronizacion,
            )
        );
        $respListaActividades = $actividades->{'RespuestaListaActividades'};
        $lista_actividad=$respListaActividades->{'listaActividades'};
        $cont = 0;

        foreach( $lista_actividad as $acti){
            $actividad = new Actividades;
            
            $acti1 = $respListaActividades->{'listaActividades'}[$cont];

            $actividad->codigo = $acti1->{'codigoCaeb'};
            $actividad->descripcion = $acti1->{'descripcion'};
            $actividad->tipo_actividad = $acti1->{'tipoActividad'};
            $actividad->save();
            $cont ++;
        }
    }

    public function ListaActividadesDocumentoSector()
    {
        $client = $this->peticion()[0];
        $SolicitudSincronizacion = $this->peticion()[1];

        $objActivDocSector  = $client->sincronizarListaActividadesDocumentoSector(
            array(
                "SolicitudSincronizacion" => $SolicitudSincronizacion,
            )
        );

        $respActividadesSector = $objActivDocSector->{'RespuestaListaActividadesDocumentoSector'};
        $lista_respActividadesSector = $respActividadesSector->{'listaActividadesDocumentoSector'};

        $cont = 0;

        foreach( $lista_respActividadesSector as $actisector){
            $acti_docu_sector = new ActiviadesSector;

            $acti_sector = $respActividadesSector->{'listaActividadesDocumentoSector'}[$cont];

            $acti_docu_sector->codigo_actividad = $acti_sector->{'codigoActividad'};
            $acti_docu_sector->codigo_documento_sector = $acti_sector->{'codigoDocumentoSector'};
            $acti_docu_sector->tipo_documento_sector = $acti_sector->{'tipoDocumentoSector'};

            $acti_docu_sector->save();

            $cont++;
        }
    }

    public function sincronizarListaLeyendasFactura(){
        $client = $this->peticion()[0];
        $SolicitudSincronizacion = $this->peticion()[1];

        $objLeyendas = $client->sincronizarListaLeyendasFactura(
            array(
                "SolicitudSincronizacion" => $SolicitudSincronizacion,
            )
        );
        $parametricaLeyendas = $objLeyendas->{'RespuestaListaParametricasLeyendas'};
        $leyenda1 = $parametricaLeyendas->{'listaLeyendas'};

        $cont = 0;

        foreach( $leyenda1 as $leye){
            $leyenda = new LeyendasFacturacion;

            $resleyenda = $parametricaLeyendas->{'listaLeyendas'}[$cont];

            $leyenda->codigo_actividad = $resleyenda->{'codigoActividad'};
            $leyenda->descripcion_leyenda = $resleyenda->{'descripcionLeyenda'};
            
            $leyenda->save();

            $cont++;
        }
    }

    public function ListaMensajesServicios(){

        $client = $this->peticion()[0];
        $SolicitudSincronizacion = $this->peticion()[1];

        $objMensajes = $client->sincronizarListaMensajesServicios(
            array(
                "SolicitudSincronizacion" => $SolicitudSincronizacion,
            )
        );
        $parametricaMensajes = $objMensajes->{'RespuestaListaParametricas'};
        $listaMensajes = $parametricaMensajes->{'listaCodigos'};
        

        $cont = 0;

        foreach( $listaMensajes as $leye){
            $mensajes = new MensajesServicios;

            $resmensajes = $parametricaMensajes->{'listaCodigos'}[$cont];

            $mensajes->codigo_clasificador = $resmensajes->{'codigoClasificador'};
            $mensajes->descripcion = $resmensajes->{'descripcion'};
            
            $mensajes->save();

            $cont++;
        }
    }

    public function ListaProductosServicios(){

        $client = $this->peticion()[0];
        $SolicitudSincronizacion = $this->peticion()[1];

        $productos = $client->sincronizarListaProductosServicios(
            array(
                "SolicitudSincronizacion" => $SolicitudSincronizacion,
            )
        );

        $listaProductos = $productos->{'RespuestaListaProductos'};
        $codigosProd = $listaProductos->{'listaCodigos'};

        $cont = 0;

        foreach( $codigosProd as $cdp){
            $productos = new ParametricaProductosServicios;
            $productos1 = $listaProductos->{'listaCodigos'}[$cont];
            $productos->codigo_actividad = $productos1->{'codigoActividad'};
            $productos->codigo_producto = $productos1->{'codigoProducto'};
            $productos->descripcion_producto = $productos1->{'descripcionProducto'};
            $productos->save();
            $contn =0;
            
            if (property_exists($productos1, 'nandina')){
                $nandina = $productos1->{'nandina'};
                if(is_array($nandina)){
                        foreach ($nandina as $nan1){
                            $na =  $productos1->{'nandina'}[$contn];              
                            $nandina = new Nandina;
                            $nandina->codigo_producto = $productos1->{'codigoProducto'};
                            $nandina->nandina = $na;
                            $nandina->save();
                            $contn++;
                        }
                    }
                    else{
                        $nandina = new Nandina;
                        $nandina->codigo_producto = $productos1->{'codigoProducto'};
                        $nandina->nandina = $productos1->{'nandina'};
                        $nandina->save();
                    }
                }
            $cont++;
        }
    }

    public function ParametricaEventosSignificativos(){
        $client = $this->peticion()[0];
        $SolicitudSincronizacion = $this->peticion()[1];

        $objEventoSignificativo  = $client->sincronizarParametricaEventosSignificativos(
            array(
                "SolicitudSincronizacion" => $SolicitudSincronizacion,
            )
        );
        $parametricaEventos = $objEventoSignificativo->{'RespuestaListaParametricas'};
        $listaEventos = $parametricaEventos->{'listaCodigos'};
        

        $cont = 0;

        foreach( $listaEventos as $cdp){
            $eventos = new ParametricasEventosSignificativos;
            $listaEventos = $parametricaEventos->{'listaCodigos'}[$cont];

            $eventos->codigo_clasificador = $listaEventos->{'codigoClasificador'};
            $eventos->descripcion = $listaEventos->{'descripcion'};

            $eventos->save();

            $cont++;
        }
    }


    public function ParametricaMotivoAnulacion(){
        $client = $this->peticion()[0];
        $SolicitudSincronizacion = $this->peticion()[1];
        $objMotivoAnulacion  = $client->sincronizarParametricaMotivoAnulacion(
            array(
                "SolicitudSincronizacion" => $SolicitudSincronizacion,
            )
        );

        $parametricaMotivoAnulacion = $objMotivoAnulacion->{'RespuestaListaParametricas'};
        $listaMotivoAnulacion = $parametricaMotivoAnulacion->{'listaCodigos'};

        $cont = 0;

        foreach( $listaMotivoAnulacion as $lma){
            $motivoAnulacion = new ParametricaMotivoAnulacion;
            $listaMotivoAnulacion = $parametricaMotivoAnulacion->{'listaCodigos'}[$cont];

            $motivoAnulacion->codigo_clasificador = $listaMotivoAnulacion->{'codigoClasificador'};
            $motivoAnulacion->descripcion = $listaMotivoAnulacion->{'descripcion'};

            $motivoAnulacion->save();
            $cont++;

        }

    }

    public function ParametricaPaisOrigen(){
        $client = $this->peticion()[0];
        $SolicitudSincronizacion = $this->peticion()[1];

        $objPais  = $client->sincronizarParametricaPaisOrigen(
            array(
                "SolicitudSincronizacion" => $SolicitudSincronizacion,
            )
        );
        $parametricaPais = $objPais->{'RespuestaListaParametricas'};
        $listaPaises = $parametricaPais->{'listaCodigos'};
      

        $cont = 0;
        foreach($listaPaises as $lp){
            $pais = new ParametricaPaisOrigen;
            $listaPaises = $parametricaPais->{'listaCodigos'}[$cont];
            $pais->codigo_clasificador = $listaPaises ->{'codigoClasificador'};
            $pais->descripcion = $listaPaises->{'descripcion'};
            $pais->save();
            $cont++;
        }
    }

    public function ParametricaDocumentoTipoIdentidad(){
        $client = $this->peticion()[0];
        $SolicitudSincronizacion = $this->peticion()[1];
        
        $tipoFacDocID = $client->sincronizarParametricaTipoDocumentoIdentidad(
            array(
                "SolicitudSincronizacion" => $SolicitudSincronizacion,
            )
        );
        $tipoDocID= $tipoFacDocID->{'RespuestaListaParametricas'};
        $listaDocID = $tipoDocID->{'listaCodigos'};

        $cont = 0;

        foreach($listaDocID as $ldi){
            $documentoidentidad = new ParametricaDocumentoTipoIdentidad;
            $listaDocID = $tipoDocID->{'listaCodigos'}[$cont];
            $documentoidentidad->codigo_clasificador = $listaDocID->{'codigoClasificador'};
            $documentoidentidad->descripcion = $listaDocID->{'descripcion'};
            $documentoidentidad->save();
            $cont++;
        }
    }

    public function ParametricaTipoDocumentoSector(){
        $client = $this->peticion()[0];
        $SolicitudSincronizacion = $this->peticion()[1];

        $doc_sector = $client->sincronizarParametricaTipoDocumentoSector(
            array(
                "SolicitudSincronizacion" => $SolicitudSincronizacion,
            )
        );
        $parametricaSectores = $doc_sector->{'RespuestaListaParametricas'};
        $Sector1 = $parametricaSectores->{'listaCodigos'};
    
        $cont = 0;

        foreach($Sector1 as $sec){
            $tipodocsector = new ParametricaTipoDocumentoSector;
            $Sector1 = $parametricaSectores->{'listaCodigos'}[$cont];
            $tipodocsector->codigo_clasificador = $Sector1->{'codigoClasificador'};
            $tipodocsector->descripcion = $Sector1->{'descripcion'};

            $tipodocsector->save();
            $cont++;
        }
    }

    public function ParametricaTipoEmision(){
        
        $client = $this->peticion()[0];
        $SolicitudSincronizacion = $this->peticion()[1];

        $tipoEmision = $client->sincronizarParametricaTipoEmision(
            array(
                "SolicitudSincronizacion" => $SolicitudSincronizacion,
            )
        );
        $parametricaEmision = $tipoEmision->{'RespuestaListaParametricas'};
        $emision1 = $parametricaEmision->{'listaCodigos'};
        $cont =0;
        foreach($emision1 as $emi){
            $tipoemision = new ParametricaTipoEmision;
            $emision1 = $parametricaEmision->{'listaCodigos'}[$cont];
            $tipoemision->codigo_clasificador = $emision1->{'codigoClasificador'};
            $tipoemision->descripcion = $emision1->{'descripcion'};
            $tipoemision->save();
            $cont++;
        }
    }

    public function ParametricaTipoHabitacion(){
        $client = $this->peticion()[0];
        $SolicitudSincronizacion = $this->peticion()[1];
        
        $objHabitacion  = $client->sincronizarParametricaTipoHabitacion(
            array(
                "SolicitudSincronizacion" => $SolicitudSincronizacion,
            )
        );
        $parametricaHabitacion = $objHabitacion->{'RespuestaListaParametricas'};
        $listaHabitaciones = $parametricaHabitacion->{'listaCodigos'};
        $cont =0;
        foreach($listaHabitaciones as $lh){
            $habitacion = new ParametricaTipoHabitacion;

            $listaHabitaciones = $parametricaHabitacion->{'listaCodigos'}[$cont];
            $habitacion->codigo_clasificador = $listaHabitaciones->{'codigoClasificador'};
            $habitacion->descripcion = $listaHabitaciones->{'descripcion'};
            $habitacion->save();
            $cont++;
        }
    }

    public function ParametricaTipoMetodoPago(){
        $client = $this->peticion()[0];
        $SolicitudSincronizacion = $this->peticion()[1];
        $objMetodoPago = $client->sincronizarParametricaTipoMetodoPago(
            array(
                "SolicitudSincronizacion" => $SolicitudSincronizacion,
            )
        );
        $parametricaMetodPago = $objMetodoPago->{'RespuestaListaParametricas'};
        $MetodoPago1 = $parametricaMetodPago->{'listaCodigos'};
        
        $cont = 0;

        foreach($MetodoPago1 as $mp){
            $tipopago = new ParametricaTipoMetodoPago;
            $MetodoPago1 = $parametricaMetodPago->{'listaCodigos'}[$cont];
            $tipopago->codigo_clasificador = $MetodoPago1->{'codigoClasificador'};
            $tipopago->descripcion = $MetodoPago1->{'descripcion'};
            $tipopago->save();
            $cont++;
        }
    }

    public function ParametricaTipoMoneda(){
        
        $client = $this->peticion()[0];
        $SolicitudSincronizacion = $this->peticion()[1];
            $objTipoMoneda = $client->sincronizarParametricaTipoMoneda(
                array(
                    "SolicitudSincronizacion" => $SolicitudSincronizacion,
                )
            );
        $parametricaMonedas = $objTipoMoneda->{'RespuestaListaParametricas'};
        $Moneda1 = $parametricaMonedas->{'listaCodigos'};
        
        $cont = 0;
        foreach($Moneda1 as $m){
            $moneda = new ParametricaTipoMoneda;
            $Moneda1 = $parametricaMonedas->{'listaCodigos'}[$cont];
            $moneda->codigo_clasificador = $Moneda1->{'codigoClasificador'};
            $moneda->descripcion = $Moneda1->{'descripcion'};
            $moneda->save();
            $cont++;
        }
    }
    public function ParametricaTipoPuntoVenta(){
        $client = $this->peticion()[0];
        $SolicitudSincronizacion = $this->peticion()[1];
        $objPtoVenta  = $client->sincronizarParametricaTipoPuntoVenta(
            array(
                "SolicitudSincronizacion" => $SolicitudSincronizacion,
            )
        );
        $parametricaPtoVenta = $objPtoVenta->{'RespuestaListaParametricas'};
        $listaPtoVenta = $parametricaPtoVenta->{'listaCodigos'};
        $cont = 0;

        foreach($listaPtoVenta as $lpv){
            $puntoventa = new ParametricaTipoPuntoVenta;
            $listaPtoVenta = $parametricaPtoVenta->{'listaCodigos'}[$cont];
            $puntoventa->codigo_clasificador = $listaPtoVenta->{'codigoClasificador'};
            $puntoventa->descripcion = $listaPtoVenta->{'descripcion'};
            $puntoventa->save();
            $cont++;
        }
    }

    public function ParametricaTiposFactura(){
        $client = $this->peticion()[0];
        $SolicitudSincronizacion = $this->peticion()[1];

        $objTipoFactura  = $client->sincronizarParametricaTiposFactura(
            array(
                "SolicitudSincronizacion" => $SolicitudSincronizacion,
            )
        );
        $parametricaTipoFac = $objTipoFactura->{'RespuestaListaParametricas'};
        $listaTipoFac = $parametricaTipoFac->{'listaCodigos'};

        $cont =0;
        foreach($listaTipoFac as $ltf){
            $tipofac = new ParametricaTiposFactura;
            $listaTipoFac = $parametricaTipoFac->{'listaCodigos'}[$cont];

            $tipofac->codigo_clasificador = $listaTipoFac->{'codigoClasificador'};
            $tipofac->descripcion = $listaTipoFac->{'descripcion'};

            $tipofac->save();

            $cont++;

        }
    }
    public function ParametricaUnidadMedida(){
        $client = $this->peticion()[0];
        $SolicitudSincronizacion = $this->peticion()[1];

        $objUnidadMedida = $client->sincronizarParametricaUnidadMedida(
            array(
                "SolicitudSincronizacion" => $SolicitudSincronizacion,
            )
        );
        $parametricaUnidadMedida = $objUnidadMedida->{'RespuestaListaParametricas'};
        $arrayUnidadMedida = $parametricaUnidadMedida->{'listaCodigos'};

        $cont = 0;

        foreach( $arrayUnidadMedida as $uni){
            $unidadmedida = new UnidadMedida;

            $unidMedida58 = $parametricaUnidadMedida->{'listaCodigos'}[$cont];

            $codUnidadMedida58 = $unidMedida58->{'codigoClasificador'};
            $codUnidadMedida581 = $unidMedida58->{'descripcion'};
            $unidadmedida->codigo = $codUnidadMedida58;
            $unidadmedida->descripcion = $codUnidadMedida581;
            $unidadmedida->save();
            $cont ++;

        }
    }
    public function index()
    {

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
