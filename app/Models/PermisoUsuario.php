<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermisoUsuario extends Model
{
    protected $table = 'permisos_usuarios';
    protected $fillable = ['id_user', 'id_permiso', 'estatus'];
    public $timestamps = false;
    public $primaryKey = 'id';
}
