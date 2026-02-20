@extends('layouts.app')

@section('content')
    <div>
        <h1 class="title-outline" style="-webkit-text-stroke: 1.5px #f97316;">INHABILITAR ESPACIOS</h1>
        <p style="text-align: center; color: #94a3b8; margin-bottom: 30px;">Selecciona el hardware o sala que presenta desperfectos o dejará de existir.</p>
        
        <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
            <select class="neon-box" style="padding: 10px; color: white; border: 1px solid #f97316; outline: none;">
                <option>Laboratorio A1</option>
                <option>Laboratorio A2</option>
            </select>
        </div>

        <div class="neon-box" style="padding: 2px; overflow: hidden; border-color: #f97316; box-shadow: 0 0 10px rgba(249, 115, 22, 0.2);">
            <table class="neon-table">
                <thead>
                    <tr>
                        <th>Identificador</th>
                        <th>Tipo</th>
                        <th>Estado Actual</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>PC-01</td>
                        <td>Computadora</td>
                        <td><span style="color: #4ade80;">Operativo</span></td>
                        <td><button onclick="abrirModalInhabilitar('PC-01')" style="background: #f97316; color: white; font-weight: bold; border: none; padding: 8px 20px; border-radius: 8px; cursor: pointer;">Inhabilitar</button></td>
                    </tr>
                    <tr>
                        <td>PC-02</td>
                        <td>Computadora</td>
                        <td><span style="color: #ef4444;">En Mantenimiento</span></td>
                        <td><button style="background: #4ade80; color: #0b101a; font-weight: bold; border: none; padding: 8px 20px; border-radius: 8px; cursor: pointer;">Habilitar</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="inhabilitarModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 2000; justify-content: center; align-items: center;">
        <div class="neon-box" style="width: 400px; padding: 30px; text-align: center; border-color: #f97316;">
            <h2 style="color: #f97316; margin-top: 0;">Inhabilitar Equipo</h2>
            <p id="equipoInfo" style="color: white; font-weight: bold;"></p>
            
            <form action="#" method="POST" style="text-align: left;">
                <label style="color: #94a3b8; font-size: 0.9rem;">Motivo (Ej: Pantalla rota, No enciende):</label>
                <textarea required rows="4" class="neon-box" style="width: 100%; padding: 10px; margin-top: 10px; margin-bottom: 20px; background: #1a2333; color: white; resize: none; outline: none; box-sizing: border-box;"></textarea>
                
                <div style="display: flex; justify-content: space-between;">
                    <button type="button" onclick="cerrarModalInhabilitar()" style="background: transparent; color: white; border: 1px solid white; padding: 10px 20px; border-radius: 8px; cursor: pointer;">Cancelar</button>
                    <button type="submit" style="background: #f97316; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; cursor: pointer;">Confirmar Baja</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModalInhabilitar(equipo) {
            document.getElementById('equipoInfo').innerText = "Equipo seleccionado: " + equipo;
            document.getElementById('inhabilitarModal').style.display = 'flex';
        }
        function cerrarModalInhabilitar() {
            document.getElementById('inhabilitarModal').style.display = 'none';
        }
    </script>
@endsection