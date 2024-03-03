<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Permiso;

class PermisoController extends Controller
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
            $permisos = Permiso::where($condicion)
                ->orderBy($sortField, $sort)
                ->offset($valores['first'])
                ->limit($valores['rows'])
                ->get()
                ->toArray();

            $total = Permiso::where($condicion)->count();
        }
        return response()->json(['data' => $permisos, 'count' => $total, 'parametros' => $valores]);
    }

    public function getPermisoById($permisoId)
    {
        $permiso = Permiso::find($permisoId);

        $array = $permiso ? [
            'id' => (int)$permiso->id,
            'nombre' => $permiso->nombre,
            'clave' => $permiso->url,
            'acccion' => $permiso->categoria,
        ] : [];

        return response()->json(['data' => $array]);
    }

    public function insertPermiso(Request $request)
    {
        $data = $request->only(['nombre', 'clave', 'accion']);

        $permiso = Permiso::create($data);

        return response()->json(['estatus' => true, 'id' => $permiso->id]);
    }

    public function updatePermiso(Request $request, $permiso_id)
    { 
        $data = $request->only(['nombre', 'clave', 'accion']);

        $permiso = Permiso::find($permiso_id);

        if (!$permiso) {
            return response()->json(['estatus' => false]);
        }

        $permiso->update($data);

        return response()->json(['estatus' => true]);
    }

    public function deletePermiso($permiso_id)
    {
        $permiso = Permiso::find($permiso_id);

        if (!$permiso) {
            return response()->json(['estatus' => false]);
        }

        $permiso->delete();

        return response()->json(['estatus' => true]);
    }
    
}
