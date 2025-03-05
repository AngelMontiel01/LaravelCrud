<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ClienteController extends Controller
{
    public function clientesView()
    {
        if (auth()->check()) {
            // Si el usuario está autenticado, obtenemos el rol
            $rol = auth()->user()->idRol;
        } else {
            // Si no está autenticado, puedes asignar un valor por defecto
            $rol = null; // o un valor predeterminado que prefieras
        }
   
        return view('clientes', compact('rol'));
    }
    
    public function index()
    {
        $clientes = DB::select("EXEC ObtenerClientes");

        return response()->json(['data' => $clientes]); // 🔹 Agregamos la clave "data"
    }

    public function store(Request $request)
    {
        try {
            // Ejecutar el procedimiento almacenado
            $resultado = DB::select("EXEC InsertarCliente ?, ?, ?, ?, ?, ?,?", [
                $request->nombre,
                $request->apellido1,
                $request->apellido2,
                $request->ciudad_id,
                $request->categoria_id,
                auth()->user()->id,
                0 // Este valor será sobrescrito por el procedimiento
            ]);
    
            // 🔹 Ahora sí tenemos un resultado que podemos validar
            if (!empty($resultado) && isset($resultado[0]->exito)) {
                if ($resultado[0]->exito == 1) {
                    return response()->json(['message' => 'Cliente creado con éxito', 'success' => true]);
                } else {
                    return response()->json(['message' => 'Error en la base de datos', 'success' => false]);
                }
            }
    
            return response()->json(['message' => 'No se recibió respuesta del procedimiento', 'success' => false]);
    
        } catch (\Exception $e) {
            \Log::error('Error en store(): ' . $e->getMessage());
    
            return response()->json([
                'message' => 'Error al crear Cliente',
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    

    public function edit($id)
    {
        try {
            // Ejecutar el procedimiento almacenado para obtener el pedido
            $resultado = DB::select("EXEC ObtenerClientesId ?", [$id]);
    
            // Verificar si se devolvieron resultados
            if (!empty($resultado)) {
                return response()->json(['data' => $resultado[0], 'success' => true]);
            } else {
                return response()->json(['message' => 'No se encontró el Cliente', 'success' => false]);
            }
        } catch (\Exception $e) {
            \Log::error('Error en edit(): ' . $e->getMessage());
            return response()->json(['message' => 'Error al obtener pedido', 'success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    
    public function update(Request $request, $id)
    {
        try {
            // Ejecutar el procedimiento almacenado para actualizar el pedido
            $resultado = DB::select("EXEC ActualizarCliente ?, ?, ?, ?, ?, ?,?,?", [
                $id,
                $request->nombre,
                $request->apellido1,
                $request->apellido2,
                $request->ciudad_id,
                $request->categoria_id,
                auth()->user()->id,
                0
            ]);
    
            // Verificar si se devolvió el valor de exito
            if (!empty($resultado) && isset($resultado[0]->exito)) {
                if ($resultado[0]->exito == 1) {
                    return response()->json(['message' => 'Cliente actualizado con éxito', 'success' => true]);
                } else {
                    return response()->json(['message' => 'No se encontró el cliente', 'success' => false]);
                }
            }
    
            return response()->json(['message' => 'Error en la actualización', 'success' => false]);
    
        } catch (\Exception $e) {
            \Log::error('Error en update(): ' . $e->getMessage());
    
            return response()->json([
                'message' => 'Error al actualizar cliente',
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    

    public function destroy($id)
    {
        try {
            // Llamar al procedimiento almacenado y capturar el resultado
            $resultado = DB::select("EXEC EliminarCliente ?, ?", [$id, 0]);
    
            // Verificar si se devolvió un valor
            if (!empty($resultado) && isset($resultado[0]->exito)) {
                if ($resultado[0]->exito == 1) {
                    return response()->json(['message' => 'Cliente eliminado con éxito', 'success' => true]);
                } else {
                    return response()->json(['message' => 'No se encontró el cliente', 'success' => false]);
                }
            }
    
            return response()->json(['message' => 'Error en la eliminación', 'success' => false]);
    
        } catch (\Exception $e) {
            \Log::error('Error en destroy(): ' . $e->getMessage());
    
            return response()->json([
                'message' => 'Error al eliminar cliente',
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    

    
}
