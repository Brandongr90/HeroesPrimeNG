<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\PermisoUsuario;
use Illuminate\Http\Request;

class PermisoUsuarioController extends Controller
{
    public function getPermisosUsuario($idUsuario)
    {

        $id = PermisoUsuario::find($idUsuario);

        if ($id) {
            $permisos = PermisoUsuario::select('permisosusuarios.idUsuario', 'permisosusuarios.idPermiso','permisosusuarios.estatus as estatus', 'users.name as nombre', 'permisos.nombre as permiso')
                ->join('permisos', 'permisos.id', '=', 'permisosusuarios.idPermiso')
                ->join('users', 'users.id', '=', 'permisosusuarios.idUsuario')
                ->where('users.id', '=', $idUsuario)
                ->where('permisosusuarios.estatus', 1)
                ->get();

            $array = [];

            foreach ($permisos as $permiso) {

                $salida = [
                    'idUsuario' => $permiso->idUsuario,
                    'idPermiso' => $permiso->idPermiso,
                    'nombre' => $permiso->nombre,
                    'permiso' => $permiso->permiso,
                    'estatus' => $permiso->estatus,
                ];

                array_push($array, $salida);
            };

            return response()->json(['estatus' => true, 'data' => $array]);
        } 
        else {
            return response()->json(['estatus' => false, 'msg' => 'Usuario no encontrado']);
        }
    }
    public function getNoPermisosUsuario($idUsuario)
    {

        $id = PermisoUsuario::find($idUsuario);

        if ($id) {
            $permisos = PermisoUsuario::select('permisosusuarios.idUsuario', 'permisosusuarios.idPermiso','permisosusuarios.estatus as estatus', 'users.name as nombre', 'permisos.nombre as permiso')
                ->join('permisos', 'permisos.id', '=', 'permisosusuarios.idPermiso')
                ->join('users', 'users.id', '=', 'permisosusuarios.idUsuario')
                ->where('users.id', '=', $idUsuario)
                ->where('permisosusuarios.estatus', 0)
                ->get();

            $array = [];

            foreach ($permisos as $permiso) {

                $salida = [
                    'idUsuario' => $permiso->idUsuario,
                    'idPermiso' => $permiso->idPermiso,
                    'nombre' => $permiso->nombre,
                    'permiso' => $permiso->permiso,
                    'estatus' => $permiso->estatus,
                ];

                array_push($array, $salida);
            };

            return response()->json(['estatus' => true, 'data' => $array]);
        } 
        else {
            return response()->json(['estatus' => false, 'msg' => 'Usuario no encontrado']);
        }
    }

    public function updateInsert(Request $request)
    {
        $data = $request->only(['idUsuario', 'idPermiso', 'estatus']);
        $permisoUsuario = PermisoUsuario::upsert($data, ['id_user', 'id_permiso'], ['estatus']);
        if (!$permisoUsuario) {
            return response()->json(['estatus' => false]);
        }
        return response()->json(['estatus' => true]);
    }
}
