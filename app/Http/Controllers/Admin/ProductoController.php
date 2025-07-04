<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Producto;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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
        $publicId = null;

        try {
            if ($request->hasFile('imagen')) {
                $uploadedFile = $request->file('imagen');

                $uploadedResponse = Cloudinary::upload($uploadedFile->getRealPath(), [
                    'folder' => 'nova_veste'
                ]);

                $rutaImagen = $uploadedResponse->getSecurePath();
                $publicId = $uploadedResponse->getPublicId();
            }
        } catch (\Exception $e) {
            return back()->withErrors(['imagen' => 'Error al subir la imagen: ' . $e->getMessage()]);
        }

        Producto::create([
            'nombre' => $request->nombre,
            'precio' => $request->precio,
            'descripcion' => $request->descripcion,
            'imagen' => $rutaImagen,
            'public_id' => $publicId, // <-- necesitas una columna en la tabla
        ]);

        return redirect()->route('tienda.inicio')->with('success', 'Producto creado correctamente.');
    }

    public function eliminar($id)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Acceso denegado');
        }

        $producto = Producto::findOrFail($id);

        try {
            if ($producto->public_id) {
                Cloudinary::destroy($producto->public_id);
            }
        } catch (\Exception $e) {
            // Puedes registrar el error si quieres
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
            // Elimina la imagen anterior en Cloudinary
            if ($producto->public_id) {
                try {
                    Cloudinary::destroy($producto->public_id);
                } catch (\Exception $e) {
                    // opcional: registrar error
                }
            }

            // Subir nueva imagen a Cloudinary
            $uploadedFile = $request->file('imagen');
            $uploadedResponse = Cloudinary::upload($uploadedFile->getRealPath(), [
                'folder' => 'nova_veste'
            ]);

            $producto->imagen = $uploadedResponse->getSecurePath();
            $producto->public_id = $uploadedResponse->getPublicId();
        }

        $producto->nombre = $request->nombre;
        $producto->precio = $request->precio;
        $producto->descripcion = $request->descripcion;
        $producto->save();

        return redirect()->route('inicio')->with('success', 'Producto actualizado.');
    }
}
