@extends('tienda.layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Confirmación de Pedido</h1>

    {{-- Mensaje de éxito --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Resumen del pedido --}}
    @if(request('producto') && request('precio'))
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-primary">Producto: {{ request('producto') }}</h5>
                <p class="card-text fs-5">Precio: ${{ number_format(request('precio'), 0, ',', '.') }}</p>
                <p class="text-muted">Precio total se calculará según cantidad</p>
            </div>
        </div>
    @endif

    {{-- Formulario de confirmación --}}
    <form action="{{ route('pedir.producto.post') }}" method="POST" class="needs-validation" novalidate>
        @csrf
        <input type="hidden" name="producto" value="{{ request('producto') }}">
        <input type="hidden" name="precio" value="{{ request('precio') }}">

        <div class="row g-3">
            <div class="col-md-6 mb-3">
                <label for="cantidad" class="form-label">Cantidad</label>
                <input type="number" name="cantidad" id="cantidad" 
                       class="form-control" 
                       value="1" min="1" required
                       onchange="calcularTotal()">
                <div class="invalid-feedback">
                    Por favor ingrese una cantidad válida
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label for="telefono" class="form-label">Teléfono de contacto</label>
                <input type="tel" name="telefono" id="telefono" 
                       class="form-control" 
                       placeholder="Ej: 3001234567" 
                       pattern="[0-9]{10}" 
                       required>
                <div class="invalid-feedback">
                    Por favor ingrese un número de 10 dígitos
                </div>
                <small class="text-muted">Formato: 10 dígitos sin espacios ni caracteres especiales</small>
            </div>

            <div class="col-12 mb-3">
                <label for="direccion" class="form-label">Dirección de entrega</label>
                <textarea name="direccion" id="direccion" 
                          class="form-control" 
                          rows="3" required></textarea>
                <div class="invalid-feedback">
                    Por favor ingrese su dirección completa
                </div>
            </div>

            <div class="col-12 mb-4">
                <div class="card bg-light">
                    <div class="card-body">
                        <h5 class="card-title">Total estimado</h5>
                        <p class="card-text fs-4" id="total">${{ number_format(request('precio'), 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-lg w-100">
                    Confirmar pedido
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    // Calcular total al cambiar cantidad
    function calcularTotal() {
        const precio = parseFloat("{{ request('precio') }}");
        const cantidad = parseInt(document.getElementById('cantidad').value);
        const total = precio * cantidad;
        document.getElementById('total').textContent = '$' + total.toLocaleString('es-CO');
    }

    // Validación de formulario
    (function() {
        'use strict'
        
        const forms = document.querySelectorAll('.needs-validation')
        
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>

<style>
    .form-control:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
    }
    .card {
        border-radius: 0.5rem;
    }
</style>
@endsection