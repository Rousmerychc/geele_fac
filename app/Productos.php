<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
    //
    protected $table = 'productos';
        
    protected $primarykey = 'id';

    protected $fillable = ['id_grupo','codigo_empresa','descripcion','id_producto_impuesto','codigo_impuestos','descripcion_impuestos',
    'codigo_unidad_medida','unidad_medida','precio','estado','usuario','fecha'];

    public $timestamps = false;
    
}
