<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IngresosDetalle extends Model
{
    //
    protected $table = 'ingresos_detalle';
        
    protected $primarykey = 'id';

    protected $fillable = ['id_ingreso','id_sucursal','id_producto','cantidad','precio','subtotal'];

    public $timestamps = false;
}
