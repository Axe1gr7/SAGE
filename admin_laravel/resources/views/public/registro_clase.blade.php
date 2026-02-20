<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAGE - Registro de Clases</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"> <style>
        body { background-color: var(--bg-dark); color: var(--text-main); font-family: 'Inter', sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .public-container { background-color: var(--panel-bg); border: 1px solid var(--neon-blue); border-radius: 12px; box-shadow: var(--neon-glow); width: 100%; max-width: 600px; padding: 30px; }
        .neon-input { width: 100%; padding: 12px; background: rgba(11, 16, 26, 0.6); color: white; border: 1px solid rgba(123, 162, 248, 0.4); border-radius: 8px; outline: none; margin-bottom: 15px; margin-top: 5px; box-sizing: border-box; }
        .neon-input:focus { border-color: var(--neon-blue); box-shadow: 0 0 8px rgba(123, 162, 248, 0.4); }
        .btn-action-green { width: 100%; background-color: #4ade80; color: #000; border: none; padding: 12px; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 1rem; transition: 0.3s; margin-top: 15px; }
        .btn-action-green:hover { box-shadow: 0 0 15px rgba(74, 222, 128, 0.6); }
    </style>
</head>
<body>

    <div class="public-container">
        <h1 class="title-outline" style="font-size: 2rem; margin-bottom: 10px;">REGISTRO DE CLASE</h1>
        <p style="text-align: center; color: #94a3b8; margin-bottom: 25px;">Completa el formulario para solicitar la apertura de una clase.</p>

        <form action="#" method="POST">
            <label style="color: var(--neon-blue); font-size: 0.85rem;">Nombre de la Clase</label>
            <input type="text" name="nombre" class="neon-input" required>
            
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 150px;"><label style="color: var(--neon-blue); font-size: 0.85rem;">Materia</label><input type="text" name="materia" class="neon-input" required></div>
                <div style="flex: 1; min-width: 150px;"><label style="color: var(--neon-blue); font-size: 0.85rem;">Grupo</label><input type="text" name="grupo" class="neon-input" required></div>
            </div>
            
            <label style="color: var(--neon-blue); font-size: 0.85rem;">Nombre del Docente</label>
            <input type="text" name="docente" class="neon-input" required>
            
            <label style="color: var(--neon-blue); font-size: 0.85rem;">Correo del Docente</label>
            <input type="email" name="correo_docente" class="neon-input" required>
            
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 200px;">
                    <label style="color: var(--neon-blue); font-size: 0.85rem;">Fecha y Hora de Inicio</label>
                    <input type="datetime-local" name="fecha_inicio" class="neon-input" required>
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <label style="color: var(--neon-blue); font-size: 0.85rem;">Fecha y Hora de Fin</label>
                    <input type="datetime-local" name="fecha_fin" class="neon-input" required>
                </div>
            </div>

            <button type="submit" class="btn-action-green">ENVIAR SOLICITUD DE CLASE</button>
        </form>
    </div>

</body>
</html>