<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nova Veste</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
        <div class="container">
           <a href="{{ route('inicio') }}" class="btn fw-bold px-4 rounded-pill text-white inicio-btn">
    <i class="bi bi-house-door-fill me-1"></i> Inicio
</a>

<style>
.inicio-btn {
    background-color: #000000; /* Negro */
    transition: background-color 0.3s ease, transform 0.3s ease;
    transform: scale(1);
}

.inicio-btn:hover {
    background-color: #6c757d; /* Gris */
    transform: scale(1.2); /* Aumenta tamaño */
}
</style>



            <div class="d-flex align-items-center">
                @auth
                    {{-- Mostrar links si es admin --}}
                    @if (auth()->user()->is_admin)
                        <a href="{{ route('admin.pedidos') }}" class="btn btn-outline-dark btn-sm me-2">
                            <i class="bi bi-box-seam"></i> Pedidos
                        </a>
                            <a href="{{ route('admin.crear-producto') }}" class="btn btn-outline-primary btn-sm me-2">
                            <i class="bi bi-plus-circle"></i> Agregar producto
                            </a>
                    @endif

                    <a href="{{ route('logout') }}" class="btn btn-outline-danger btn-sm"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Cerrar sesión
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm me-2">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-success btn-sm">Registrarse</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Contenido --}}
    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
