<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Datos extends Model
{
    //
    protected $table = 'datos_empresa';
        
    protected $primarykey = 'id';

    protected $fillable = ['razon_social','nit','codigo_sector','modalidad','codigo_ambiente','codigo_tipo_fac',
                            'token','fac_codigos','fac_sincronizacion','fac_compra_venta','fac_operaciones','envio_paquete'];

    public $timestamps = false;
}
