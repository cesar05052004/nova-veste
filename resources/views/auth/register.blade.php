@extends('tienda.layouts.app')

@section('content')
<div class="container">
    <h1>Crear cuenta en Nova Veste</h1>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <label for="name">Nombre:</label>
            <input type="text" id="name" name="name" required>
        </div><br>

        <div>
            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" required>
        </div><br>

        <div>
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
        </div><br>

        <div>
            <label for="password_confirmation">Confirmar contraseña:</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div><br>

        <button type="submit">Registrarse</button>

        <p class="mt-3">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}">Inicia sesión aquí</a>
        </p>
    </form>
</div>
@endsection
