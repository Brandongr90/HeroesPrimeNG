<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Producto;

class ProductoController extends Controller
{
    public function getProductos(Request $request)
    {
        $valores = $request->input();
        $productos = [];
        $total = 0;

        if (isset($valores['first'])) {
            $sort = isset($valores['sortOrder']) && $valores['sortOrder'] == 1 ? 'asc' : 'desc';
            $sortField = isset($valores['sortField']) ? $valores['sortField'] : 'nombre';
            $condicion = [];
            
            if (!empty($valores['globalFilter'])) {
                $filtro =  '%' . $valores['globalFilter'] . '%';
                $condicion = function ($query) use ($filtro) {
                    $query->where('nombre', 'like', $filtro)
                        ->orWhere('categoria', 'like', $filtro)
                        ->orWhere('genero', 'like', $filtro)
                        ->orWhere('descripcion', 'like', $filtro);
                };
            }
            $productos = Producto::where($condicion)
                ->orderBy($sortField, $sort)
                ->offset($valores['first'])
                ->limit($valores['rows'])
                ->get()
                ->toArray();

            $total = Producto::where($condicion)->count();
        }
        return response()->json(['data' => $productos, 'count' => $total, 'parametros' => $valores]);
    }

    public function getProductoById($productoId)
    {
        $producto = Producto::find($productoId);

        $array = $producto ? [
            'id' => (int)$producto->id,
            'nombre' => $producto->nombre,
            'url' => $producto->url,
            'categoria' => $producto->categoria,
            'genero' => $producto->genero,
            'descripcion' => $producto->descripcion,
            'created_at' => $producto->created_at
        ] : [];

        return response()->json(['data' => $array]);
    }

    public function insertProducto(Request $request)
    {
        $data = $request->only(['nombre', 'genero', 'descripcion', 'url', 'categoria']);

        $producto = Producto::create($data);

        return response()->json(['estatus' => true, 'id' => $producto->id]);
    }

    public function updateProducto(Request $request, $producto_id)
    {
        $data = $request->only(['nombre', 'genero', 'descripcion', 'url', 'categoria']);

        $producto = Producto::find($producto_id);

        if (!$producto) {
            return response()->json(['estatus' => false]);
        }

        $producto->update($data);

        return response()->json(['estatus' => true]);
    }

    public function deleteProducto($producto_id)
    {
        $producto = Producto::find($producto_id);

        if (!$producto) {
            return response()->json(['estatus' => false]);
        }

        $producto->delete();

        return response()->json(['estatus' => true]);
    }
}
