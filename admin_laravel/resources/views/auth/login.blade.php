<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - SAGE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

<div class="container-fluid vh-100 d-flex align-items-center justify-content-center fondo">

    <div class="login-card p-4">

        <div class="text-center mb-4">
            <img src="{{ asset('img/logo_sage.png') }}" class="logo mb-2" alt="Logo SAGE">
        </div>

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label text-light">Correo:</label>
                <input type="email" name="email" class="form-control input-custom" placeholder="ejemplo@correo.com" value="{{ old('email') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-light">Contraseña:</label>
                <input type="password" name="password" class="form-control input-custom" placeholder="********" required>
            </div>

            <div class="form-check mb-3 text-light">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Recordarme</label>
            </div>

            <div class="d-grid">
                <button class="btn btn-login" type="submit">Iniciar sesión</button>
            </div>

        </form>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>