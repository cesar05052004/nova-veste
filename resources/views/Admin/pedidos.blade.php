@extends('tienda.layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Lista de Pedidos</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($pedidos->isEmpty())
        <div class="alert alert-info">
            No hay pedidos registrados.
        </div>
    @else
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pedidos as $pedido)
                    <tr class="{{ $pedido->completado ? 'table-success' : '' }}">
                        <td>{{ $pedido->id }}</td>
                        <td>{{ $pedido->user->name }}<br><small>{{ $pedido->user->email }}</small></td>
                        <td>{{ $pedido->producto }}</td>
                        <td>{{ $pedido->cantidad }}</td>
                        <td>{{ $pedido->direccion }}</td>
                        <td>{{ $pedido->telefono }}</td>
                        <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($pedido->completado)
                                <span class="badge bg-success">Completado</span>
                            @else
                                <span class="badge bg-warning text-dark">Pendiente</span>
                            @endif
                        </td>
                        <td>
                            @if(!$pedido->completado)
                            <form action="{{ route('pedidos.completar', $pedido->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm btn-success">
                                    <i class="bi bi-check-circle"></i> Marcar como listo
                                </button>
                            </form>
                            @else
                            <form action="{{ route('pedidos.reabrir', $pedido->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm btn-secondary">
                                    <i class="bi bi-arrow-counterclockwise"></i> Reabrir
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<style>
    .badge {
        font-size: 0.85em;
        padding: 0.35em 0.65em;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
</style>
@endsection
