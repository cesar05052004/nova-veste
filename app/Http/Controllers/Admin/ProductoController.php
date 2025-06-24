<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Producto;

class ProductoController extends Controller
{
    public function crear()
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Acceso denegado');
        }

        return view('admin.crear-producto');
    }

    public function guardar(Request $request)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Acceso denegado');
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $rutaImagen = null;

        if ($request->hasFile('imagen')) {
            $rutaImagen = $request->file('imagen')->store('productos', 'public');
        }

        Producto::create([
            'nombre' => $request->nombre,
            'precio' => $request->precio,
            'descripcion' => $request->descripcion,
            'imagen' => $rutaImagen,
        ]);

        return redirect()->back()->with('success', 'Producto creado correctamente.');
    }


public function eliminar($id)
{
    if (!Auth::check() || !Auth::user()->is_admin) {
        abort(403, 'Acceso denegado');
    }

    $producto = Producto::findOrFail($id);

    // Eliminar imagen si existe
    if ($producto->imagen && \Storage::disk('public')->exists($producto->imagen)) {
        \Storage::disk('public')->delete($producto->imagen);
    }

    $producto->delete();

    return redirect()->back()->with('success', 'Producto eliminado correctamente.');
}

public function editar($id)
{
    if (!Auth::check() || !Auth::user()->is_admin) {
        abort(403, 'Acceso denegado');
    }

    $producto = Producto::findOrFail($id);
    return view('admin.editar-producto', compact('producto'));
}

public function actualizar(Request $request, $id)
{
    if (!Auth::check() || !Auth::user()->is_admin) {
        abort(403, 'Acceso denegado');
    }

    $producto = Producto::findOrFail($id);

    $request->validate([
        'nombre' => 'required|string|max:255',
        'precio' => 'required|numeric',
        'descripcion' => 'nullable|string',
        'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    if ($request->hasFile('imagen')) {
        // Eliminar imagen anterior
        if ($producto->imagen && \Storage::disk('public')->exists($producto->imagen)) {
            \Storage::disk('public')->delete($producto->imagen);
        }

        // Subir nueva imagen
        $producto->imagen = $request->file('imagen')->store('productos', 'public');
    }

    $producto->nombre = $request->nombre;
    $producto->precio = $request->precio;
    $producto->descripcion = $request->descripcion;
    $producto->save();

    return redirect()->route('inicio')->with('success', 'Producto actualizado.');
}

}