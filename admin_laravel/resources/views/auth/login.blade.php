<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - SAGE</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body style="display: flex; justify-content: center; align-items: center; height: 100vh;">

    <div class="neon-box" style="width: 380px; padding: 50px 40px; text-align: center;">
        <div class="neon-box" style="width: 80px; height: 80px; margin: 0 auto 30px auto; display: flex; align-items: center; justify-content: center; background-color: #0b101a;">
            <div class="logo-wrapper"> 
                <img src="{{ asset('img/logo_sadge.png') }}" alt="Logo" class="logo-img">
            </div>
        </div>

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div style="text-align: left; margin-bottom: 20px;">
                <label style="color: #94a3b8; font-size: 0.9rem;">Correo:</label>
                <input type="email" name="email" placeholder="123046426@upq.edu.mx" required style="width: 100%; padding: 12px; margin-top: 5px; background: #1a2333; border: 1px solid #334155; border-radius: 8px; color: white; box-sizing: border-box; outline: none;">
            </div>

            <div style="text-align: left; margin-bottom: 40px;">
                <label style="color: #94a3b8; font-size: 0.9rem;">Contraseña:</label>
                <input type="password" name="password" placeholder="••••••••••••" required style="width: 100%; padding: 12px; margin-top: 5px; background: #1a2333; border: 1px solid #334155; border-radius: 8px; color: white; box-sizing: border-box; outline: none;">
            </div>

            <button type="submit" style="background-color: #dbeafe; color: #1e3a8a; border: none; padding: 12px 40px; border-radius: 30px; font-weight: 800; cursor: pointer; font-size: 1rem; width: 80%; transition: background 0.3s;">
                Iniciar sesión
            </button>
        </form>
    </div>

</body>
</html>