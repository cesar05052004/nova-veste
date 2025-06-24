@extends('tienda.layouts.app')

@section('content')
<div class="container-fluid px-0">
    <!-- Hero Banner -->
    <div class="hero-banner bg-light py-5 mb-5">
        <div class="container text-center">
            <img src="{{ asset('img/logo.png') }}" alt="Nova Veste" class="mb-3 logo-sombreado">
            <p class="lead text-muted">Descubre nuestra nueva colección</p>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="container mb-5">
        <div class="row gx-3 gy-4">
            @forelse($productos as $producto)
                <div class="col-md-4 col-6">
                    <div class="product-card">
                        <div class="product-image-container ratio ratio-1x1">
                            @if($producto->imagen)
                                <img src="{{ asset('storage/' . $producto->imagen) }}" class="product-image img-cover" alt="{{ $producto->nombre }}">
                            @else
                                <img src="{{ asset('img/logo2.png') }}" class="product-image img-cover" alt="Producto">
                            @endif
                            <div class="product-actions">
                                <form action="{{ route('pedir.producto') }}" method="GET">
                                    <input type="hidden" name="producto" value="{{ $producto->nombre }}">
                                    <input type="hidden" name="precio" value="{{ $producto->precio }}">
                                    <button type="submit" class="btn btn-dark btn-sm rounded-0">COMPRAR</button>
                                </form>
                            </div>
                        </div>
                        <div class="product-info p-2">
                            <h3 class="product-name mb-1">{{ $producto->nombre }}</h3>
                            <p class="product-description text-muted small mb-1">{{ Str::limit($producto->descripcion, 50) }}</p>
                            <p class="product-price fw-bold">${{ number_format($producto->precio, 0, ',', '.') }}</p>

                            @auth
                                @if(Auth::user()->is_admin)
                                    <div class="d-grid gap-1 mt-2">
                                        <a href="{{ route('admin.editar-producto', $producto->id) }}" class="btn btn-outline-primary btn-sm">Editar</a>
                                        <form action="{{ route('admin.eliminar-producto', $producto->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este producto?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm mt-1">Eliminar</button>
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center py-5">No hay productos disponibles en este momento.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- WhatsApp Section -->
    <div class="whatsapp-section bg-light py-5">
        <div class="container text-center">
            <h2 class="mb-3 fs-4">¿NECESITAS AYUDA?</h2>
            <p class="mb-4 small">Contáctanos para cualquier consulta</p>
            <a href="https://wa.me/573101234567" target="_blank" class="btn btn-dark rounded-0 px-4">
                <i class="bi bi-whatsapp me-2"></i>WhatsApp
            </a>
        </div>
    </div>
</div>

<footer class="bg-dark text-white text-center py-4 mt-5">
    <div class="container">
        <small>
            &copy; {{ date('Y') }} Nova Veste | Diseñado por Cesar Galvis. Todos los derechos reservados.
            <br>
            Contacto: 
            <a href="gmailto:cesargalvisesquivel@gmail.com" class="text-white text-decoration-underline">cesargalvisesquivel@gmail.com</a> |
        </small>
    </div>
</footer>


@endsection

<style>

     .logo-sombreado {
    max-height: 200px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

    .hero-banner {
        border-bottom: 1px solid #eee;
    }

    .product-card {
        position: relative;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-5px);
    }

    .product-image-container {
        position: relative;
        overflow: hidden;
        background: #f8f8f8;
    }

    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .img-cover {
        object-fit: cover;
    }

    .product-card:hover .product-image {
        transform: scale(1.03);
    }

    .product-actions {
        position: absolute;
        bottom: 10px;
        left: 0;
        right: 0;
        text-align: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .product-card:hover .product-actions {
        opacity: 1;
    }

    .product-info {
        background: white;
    }

    .product-name {
        font-size: 0.9rem;
        font-weight: 500;
        text-transform: uppercase;
    }

    .product-price {
        font-size: 0.9rem;
    }

    .whatsapp-section {
        border-top: 1px solid #eee;
    }

    .ratio-1x1 {
        aspect-ratio: 1 / 1;
    }
</style>
