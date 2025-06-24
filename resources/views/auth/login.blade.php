@extends('tienda.layouts.app')

@section('content')

<div class="container">
    <h1>Iniciar Sesión</h1>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <label for="email">Correo electrónico:</label><br>
            <input type="email" id="email" name="email" required>
        </div><br>

        <div>
            <label for="password">Contraseña:</label><br>
            <input type="password" id="password" name="password" required>
        </div><br>

        <button type="submit">Entrar</button>
    </form>

    <p class="mt-3">
        ¿No tienes cuenta?
        <a href="{{ route('register') }}">Regístrate aquí</a>
    </p>
</div>
@endsection
