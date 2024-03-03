<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PermisoUsuario;

class PermisoUsuarioController extends Controller
{

    public function getPermisosUsuario($idUsuario){

        $permisos = PermisoUsuario::table('usuariospermisos')
        ->select('idUsuario')
        ->where('idUdsuario','=', $idUsuario)
        ->get();

        foreach ($permisos as $permiso) {
            $array =[
                'nombres' => $permiso->idUsuario
            ];
        };

        return response()->json(['estatus' => true, 'data' => $array]);

    }
    
}
