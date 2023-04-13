<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacturaDetalle extends Model
{
    //
    protected $table = 'factura_detalle';
        
    protected $primarykey = 'id';

    protected $fillable = ['id_factura','id_tabla_factura','id_sucursal','punto_venta','id_producto_sin','codigo_producto_empresa','cantidad','precio',
                            'subtotal'];

    public $timestamps = false;
}
