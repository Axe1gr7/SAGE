<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAGE Admin - Neón</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

    <div id="miSidebar" class="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-item">🏠 INICIO</a>
        <hr style="border: 1px solid rgba(0, 243, 255, 0.3); margin: 15px 20px;">
        
        <a href="{{ route('sitios.disponibles') }}" class="sidebar-item">🟩 AGENDAR SITIOS</a>
        <a href="{{ route('sitios.ocupados') }}" class="sidebar-item">🟥 SITIOS OCUPADOS</a>
        <a href="{{ route('sitios.configuracion') }}" class="sidebar-item">⚙️ CONFIGURACIÓN</a>
        <a href="{{ route('gestion.index') }}" class="sidebar-item">👥 GESTIÓN GENERAL</a>
        <a href="{{ route('estadisticas.index') }}" class="sidebar-item">📊 ESTADÍSTICAS</a>
        
        <a href="{{ route('login') }}" class="sidebar-item" style="margin-top: 50px; color: var(--neon-red); text-shadow: 0 0 8px var(--neon-red);">🚪 CERRAR SESIÓN</a>
    </div>

    <nav style="display: flex; justify-content: space-between; align-items: center; padding: 15px 30px; background: rgba(3, 6, 13, 0.8); backdrop-filter: blur(10px); border-bottom: 1px solid var(--neon-cyan); box-shadow: 0 2px 15px rgba(0, 243, 255, 0.2);">
        <div onclick="toggleSidebar()" style="cursor: pointer; display: flex; align-items: center; gap: 15px;">
            <span style="color: var(--neon-cyan); font-size: 1.5rem; text-shadow: 0 0 10px var(--neon-cyan);">☰</span>
            <strong style="color: var(--neon-green); font-family: 'Orbitron', sans-serif; font-size: 1.6rem; letter-spacing: 3px; text-shadow: 0 0 10px var(--neon-green);">SAGE</strong>
        </div>
        
        <div style="display: flex; align-items: center; gap: 25px;">
            <span style="font-family: 'Orbitron', sans-serif; font-size: 0.9rem; color: var(--neon-cyan);">SISTEMA EN LÍNEA</span>
            <div style="width: 35px; height: 35px; border-radius: 50%; background-color: var(--bg-dark); border: 2px solid var(--neon-cyan); box-shadow: 0 0 10px var(--neon-cyan);"></div>
        </div>
    </nav>

    <main style="padding: 40px 20px; max-width: 1200px; margin: 0 auto;">
        @yield('content')
    </main>

    <script>
        function toggleSidebar() {
            document.getElementById('miSidebar').classList.toggle('active');
        }
    </script>
</body>
</html>