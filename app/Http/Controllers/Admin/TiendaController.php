<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Http\Controllers\Controller;


class TiendaController extends Controller
{
    public function inicio()
    {
        $productos = Producto::latest()->get();
        return view('tienda.inicio', compact('productos'));
    }
}

