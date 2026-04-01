<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAGE Admin - @yield('title', 'Panel de Control')</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Tu CSS personalizado -->
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

    <!-- Offcanvas (menú lateral) -->
    <div class="offcanvas offcanvas-start sage-offcanvas" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="sidebarOffcanvasLabel">SAGE</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Cerrar"></button>
        </div>
        <div class="offcanvas-body">

            <!-- Sección: ADMIN -->
            <div class="sidebar-section">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
                    <i class="bi bi-house-door me-2"></i> INICIO
                </a>
                <a href="{{ route('sitios.disponibles') }}" class="sidebar-link">
                    <i class="bi bi-calendar-plus me-2"></i> AGENDAR SITIOS
                </a>
                <a href="{{ route('sitios.ocupados') }}" class="sidebar-link">
                    <i class="bi bi-pin-map-fill me-2"></i> SITIOS OCUPADOS
                </a>
                <a href="{{ route('sitios.configuracion') }}" class="sidebar-link">
                    <i class="bi bi-gear me-2"></i> CONFIGURACIÓN
                </a>
                <a href="{{ route('gestion.index') }}" class="sidebar-link">
                    <i class="bi bi-people me-2"></i> GESTIÓN GENERAL
                </a>
                <a href="{{ route('estadisticas.index') }}" class="sidebar-link">
                    <i class="bi bi-bar-chart-steps me-2"></i> ESTADÍSTICAS
                </a>
            </div>

        </div>
    </div>

    <!-- Barra de navegación superior -->
    <nav class="navbar-sage">
        <div class="navbar-toggle">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
                <span class="navbar-toggler-icon"></span>
            </button>
            <strong class="sage-logo">SAGE</strong>
        </div>
        <div class="navbar-right">
            <span class="portal-badge">SISTEMA EN LÍNEA</span>
            <button class="theme-toggle" id="themeToggle" aria-label="Cambiar tema">
                <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
            </button>
            <div class="avatar-icon" data-bs-toggle="modal" data-bs-target="#modalPerfil">
                <span>🎓</span>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <main>
        @yield('content')
    </main>

    <!-- Modal de perfil (solo visualización + cerrar sesión) -->
    <div class="modal fade" id="modalPerfil" tabindex="-1" aria-labelledby="modalPerfilLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content sage-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPerfilLabel">Mi Perfil</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <div class="avatar-icon mx-auto" style="width: 64px; height: 64px; font-size: 2rem;">
                            <span>🎓</span>
                        </div>
                    </div>
                    <div class="profile-info">
                        <p><strong>Matrícula:</strong> 1220432</p>
                        <p><strong>Nombre:</strong> Administrador SAGE</p>
                        <p><strong>Correo:</strong> admin@universidad.edu.mx</p>
                    </div>
                    <hr class="sidebar-divider">
                    <div class="d-grid gap-2">
                        <a href="{{ route('login') }}" class="btn btn-danger">
                            <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS y scripts adicionales -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Cambio de tema (claro/oscuro)
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');

        function applyTheme(theme) {
            if (theme === 'light') {
                document.body.classList.add('light-mode');
                themeIcon.classList.remove('bi-moon-stars-fill');
                themeIcon.classList.add('bi-sun-fill');
            } else {
                document.body.classList.remove('light-mode');
                themeIcon.classList.remove('bi-sun-fill');
                themeIcon.classList.add('bi-moon-stars-fill');
            }
            localStorage.setItem('theme', theme);
        }

        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            applyTheme(savedTheme);
        } else {
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            applyTheme(prefersDark ? 'dark' : 'light');
        }

        themeToggle.addEventListener('click', () => {
            const isLight = document.body.classList.contains('light-mode');
            applyTheme(isLight ? 'dark' : 'light');
        });
    </script>
    @yield('scripts')
</body>
</html>