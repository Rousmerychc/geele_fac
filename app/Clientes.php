<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    //
    protected $table = 'cliente';
        
    protected $primarykey = 'id';

    protected $fillable = ['razon_social','nro_documento','email'];

    public $timestamps = false;
}
