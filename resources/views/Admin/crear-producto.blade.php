@extends('tienda.layouts.app')

@section('content')
<div class="max-w-md mx-auto p-6 bg-white rounded-lg shadow-md">
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Crear nuevo producto</h1>

    <!-- Alerta sutil para éxito -->
    @if(session('success'))
    <div class="mb-4 p-3 text-sm text-gray-700 bg-gray-100 border-l-4 border-gray-500">
        ✓ {{ session('success') }}
    </div>
    @endif

    <!-- Mostrar errores de validación -->
    @if($errors->any())
    <div class="mb-4 p-3 text-sm text-red-700 bg-red-50 border-l-4 border-red-500">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.guardar-producto') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <!-- Campo Nombre -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
            <input type="text" name="nombre" 
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
                   value="{{ old('nombre') }}"
                   required>
        </div>

        <!-- Campo Precio -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Precio</label>
            <input type="number" name="precio" step="0.01"
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500 focus:border-gray-500"
                   value="{{ old('precio') }}"
                   required>
        </div>

        <!-- Campo Descripción -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
            <textarea name="descripcion" rows="3"
                      class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500 focus:border-gray-500">{{ old('descripcion') }}</textarea>
        </div>

        <!-- Campo Imagen -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Foto del producto</label>
            <div class="flex items-center justify-center w-full">
                <label class="flex flex-col w-full border-2 border-gray-300 border-dashed rounded-lg cursor-pointer hover:bg-gray-50">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6 px-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="mb-2 text-sm text-gray-500">Seleccionar archivo</p>
                        <p id="file-name" class="text-xs text-gray-500">Ningún archivo seleccionado</p>
                    </div>
                    <input type="file" name="imagen" class="hidden" accept="image/*" onchange="updateFileName(this)">
                </label>
            </div>
        </div>

        <!-- Botón de Guardar -->
        <div class="pt-2">
            <button type="submit" 
                    class="w-full bg-black hover:bg-gray-800 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Guardar
            </button>
        </div>
    </form>
</div>

<style>
    /* Estilos para el input file */
    input[type="file"] {
        display: none;
    }
    
    /* Estilo cuando se selecciona archivo */
    .file-selected {
        color: #000;
        font-weight: 500;
    }
    
    /* Transición suave para el hover del botón */
    button {
        transition: background-color 0.2s ease;
    }
    
    /* Estilo para el símbolo de check en la alerta */
    .alert-check {
        display: inline-block;
        margin-right: 0.5rem;
    }
</style>

<script>
    // Mostrar nombre del archivo seleccionado
    function updateFileName(input) {
        const fileName = input.files[0]?.name || 'Ningún archivo seleccionado';
        const fileDisplay = document.getElementById('file-name');
        fileDisplay.textContent = fileName;
        
        if (fileName !== 'Ningún archivo seleccionado') {
            fileDisplay.classList.add('file-selected');
        } else {
            fileDisplay.classList.remove('file-selected');
        }
    }
</script>
@endsection