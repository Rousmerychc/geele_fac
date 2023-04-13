<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grupos extends Model
{
    //
    protected $table = 'grupos';
        
    protected $primarykey = 'id';

    protected $fillable = ['descripcion','usuario','fecha'];

    public $timestamps = false;
}
