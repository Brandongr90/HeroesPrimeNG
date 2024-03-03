<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Permisos;
use Illuminate\Http\Request;

class PermisosController extends Controller
{

    public function getPermisos(Request $request)
    {
        $valores = $request->input();
        $permisos = [];
        $total = 0;

        if (isset($valores['first'])) {
            $sort = isset($valores['sortOrder']) && $valores['sortOrder'] == 1 ? 'asc' : 'desc';
            $sortField = isset($valores['sortField']) ? $valores['sortField'] : 'nombre';
            $condicion = [];
            
            if (!empty($valores['globalFilter'])) {
                $filtro =  '%' . $valores['globalFilter'] . '%';
                $condicion = function ($query) use ($filtro) {
                    $query->where('permiso', 'like', $filtro);
                };
            }
            $permisos = Permisos::where($condicion)
                ->orderBy($sortField, $sort)
                ->offset($valores['first'])
                ->limit($valores['rows'])
                ->get()
                ->toArray();

            $total = Permisos::where($condicion)->count();
        }
        return response()->json(['data' => $permisos, 'count' => $total, 'parametros' => $valores]);
    }
    public function getPermisoById($permisoId)
    {
        $permiso = Permisos::find($permisoId);

        $array = $permiso ? [
            'id' => (int)$permiso->id,
            'nombre' => $permiso->nombre,
            'clave' => $permiso->clave,
            'accion' => $permiso->accion,
        ] : [];

        return response()->json(['data' => $array]);
    }

    public function insertPermiso(Request $request)
    {
        $data = $request->only(['nombre', 'clave', 'accion']);

        $permiso = Permisos::create($data);

        return response()->json(['estatus' => true, 'id' => $permiso->id]);
    }

    public function updatePermiso(Request $request, $permiso_id)
    { 
        $data = $request->only(['nombre', 'clave', 'accion']);

        $permiso = Permisos::find($permiso_id);

        if (!$permiso) {
            return response()->json(['estatus' => false]);
        }

        $permiso->update($data);

        return response()->json(['estatus' => true]);
    }

    public function deletePermiso($producto_id)
    {
        $producto = Permisos::find($producto_id);

        if (!$producto) {
            return response()->json(['estatus' => false]);
        }

        $producto->delete();

        return response()->json(['estatus' => true]);
    }
}
