<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Permisos extends Model
{
    protected $table = 'permisos';
    protected $fillable = ['nombre', 'clave', 'accion'];
    public $timestamps = false;
    public $primaryKey = 'id';
}
