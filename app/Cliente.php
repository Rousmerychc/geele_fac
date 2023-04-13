<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    //
    protected $table = 'clientes';
        
    protected $primarykey = 'id';

    protected $fillable = ['razon_social_cli','email','tipo_documento','nro_documento','complemento','direccion','incoterm','incoterm_detalle','puerto_destino','lugar_destino','codigo_pais','codigo_metodo_pago','tipo_moneda','descripcion_moneda','texto','estado','usuario'];

    public $timestamps = false;
}
