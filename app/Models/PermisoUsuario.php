<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermisoUsuario extends Model
{
    protected $table = 'permisosusuarios';
    protected $fillable = ['idUsuario', 'idPermiso', 'estatus'];
    public $timestamps = false;
    public $primaryKey = 'id';
}
