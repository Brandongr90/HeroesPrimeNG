<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\PermisoUsuario;
use App\Models\Permisos;
use Illuminate\Http\Request;

class PermisoUsuarioController extends Controller
{
    public function getPermisosUsuario($idUsuario)
    {

        $id = PermisoUsuario::find($idUsuario);

        if ($id) {
            $permisos = PermisoUsuario::select('permisosusuarios.idUsuario', 'permisosusuarios.idPermiso', 'permisosusuarios.estatus as estatus', 'users.name as nombre', 'permisos.nombre as permiso')
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
        } else {
            return response()->json(['estatus' => false, 'msg' => 'Usuario no encontrado']);
        }
    }
    public function getNoPermisosUsuario($idUsuario)
    {

        $id = PermisoUsuario::find($idUsuario);

        if ($id) {
            $permisos = PermisoUsuario::select('permisosusuarios.idUsuario', 'permisosusuarios.idPermiso', 'permisosusuarios.estatus as estatus', 'users.name as nombre', 'permisos.nombre as permiso')
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
        } else {
            return response()->json(['estatus' => false, 'msg' => 'Usuario no encontrado']);
        }
    }

    public function updateInsert(Request $request)
    {
        $data = $request->only(['id_user', 'id_permiso', 'estatus']);
        $permisoUsuario = PermisoUsuario::upsert($data, ['id_user', 'id_permiso'], ['estatus']);

        if (!$permisoUsuario) {
            return response()->json(['estatus' => false]);
        }
        return response()->json(['estatus' => true]);
    }

    public function showAsignados($idUsuario)
    {
        $permisosUsuario = PermisoUsuario::select('permisos.id', 'nombre', 'clave', 'accion')
            ->join('permisos', 'permisos_usuarios.id_permiso', '=', 'permisos.id')
            ->where('permisos_usuarios.id_user', $idUsuario)
            ->where('permisos_usuarios.estatus', 1)
            ->get();

        return response()->json($permisosUsuario);
    }

    public function showDisponibles($idUsuario)
    {
        $permisos = Permisos::select('permisos.id', 'nombre', 'clave', 'accion')
            ->leftJoin('permisos_usuarios', 'permisos_usuarios.id_permiso', '=', 'permisos.id')
            ->get();
        $permisosAsignados = PermisoUsuario::select('permisos.id', 'nombre', 'clave', 'accion')
            ->join('permisos', 'permisos_usuarios.id_permiso', '=', 'permisos.id')
            ->where('permisos_usuarios.id_user', $idUsuario)
            ->where('permisos_usuarios.estatus', 1)
            ->get();

        $permisosDisponibles = $permisos->diff($permisosAsignados);

        return response()->json($permisosDisponibles);
    }

    public function ActualizarPermisos(Request $request)
    {
        $data = $request->only(['id_user', 'id_permiso', 'estatus']);
        $permisoUsuario = PermisoUsuario::upsert($data, ['id_user', 'id_permiso'], ['estatus']);
        if (!$permisoUsuario) {
            return response()->json(['estatus' => false]);
        }
        return response()->json(['estatus' => true]);
    }

    public function updatePermisoUsuario(Request $request, $id)
    {
        $data = $request->only(['id_user', 'id_permiso', 'estatus']);

        $permiso = PermisoUsuario::find($id);

        if (!$permiso) {
            return response()->json(['estatus' => false]);
        }

        $permiso->update($data);

        return response()->json(['estatus' => true]);
    }
}
