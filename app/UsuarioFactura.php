<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsuarioFactura extends Model
{
    //
    protected $table = 'usuario_fac';
        
    protected $primarykey = 'id';

    protected $fillable = ['nombre','apellido','password','estado','uso'];

    public $timestamps = false;
}
