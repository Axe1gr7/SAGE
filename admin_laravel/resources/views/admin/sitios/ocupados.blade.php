@extends('layouts.app')

@section('content')
    <div>
        <h1 class="title-outline">ESPACIOS OCUPADOS</h1>
        
        <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
            <select class="neon-box" style="padding: 10px; color: white; border: 1px solid var(--neon-blue); outline: none;">
                <option>Laboratorio A1</option>
                <option>Laboratorio A2</option>
            </select>
            <input type="date" class="neon-box" style="padding: 10px; color: white; border: 1px solid var(--neon-blue); outline: none; color-scheme: dark;">
        </div>

        <div class="neon-box" style="padding: 2px; overflow: hidden;">
            <table class="neon-table">
                <thead>
                    <tr>
                        <th>Horario</th>
                        <th>Espacio / PC</th>
                        <th>Ocupado por</th>
                        <th>Tipo</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>10:00 - 12:00</td>
                        <td>PC-04</td>
                        <td>Juan Pérez (Estudiante)</td>
                        <td>Individual</td>
                        <td><button onclick="abrirModal('Juan Pérez', 'PC-04')" style="background: #ef4444; color: white; font-weight: bold; border: none; padding: 8px 15px; border-radius: 8px; cursor: pointer;">Cancelar</button></td>
                    </tr>
                    <tr>
                        <td>12:00 - 14:00</td>
                        <td>Sala Completa</td>
                        <td>Ing. Software (Clase)</td>
                        <td>Clase</td>
                        <td><button onclick="abrirModal('Clase Ing. Software', 'Sala Completa')" style="background: #ef4444; color: white; font-weight: bold; border: none; padding: 8px 15px; border-radius: 8px; cursor: pointer;">Cancelar</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div id="cancelModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 2000; justify-content: center; align-items: center;">
        <div class="neon-box" style="width: 400px; padding: 30px; text-align: center; border-color: #ef4444; box-shadow: 0 0 15px rgba(239, 68, 68, 0.4);">
            <h2 style="color: #ef4444; margin-top: 0;">Cancelar Reserva</h2>
            <p id="modalInfo" style="color: #94a3b8; font-size: 0.9rem; margin-bottom: 20px;"></p>
            
            <form action="#" method="POST" style="text-align: left;">
                <label style="color: white; font-size: 0.9rem;">Motivo de cancelación:</label>
                <textarea required rows="4" style="width: 100%; padding: 10px; margin-top: 10px; margin-bottom: 20px; background: #1a2333; border: 1px solid #334155; border-radius: 8px; color: white; resize: none;"></textarea>
                
                <div style="display: flex; justify-content: space-between;">
                    <button type="button" onclick="cerrarModal()" style="background: transparent; color: white; border: 1px solid white; padding: 10px 20px; border-radius: 8px; cursor: pointer;">Volver</button>
                    <button type="submit" style="background: #ef4444; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; cursor: pointer;">Confirmar Cancelación</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModal(usuario, espacio) {
            document.getElementById('modalInfo').innerText = "Cancelando reserva de: " + usuario + " en " + espacio;
            document.getElementById('cancelModal').style.display = 'flex';
        }
        function cerrarModal() {
            document.getElementById('cancelModal').style.display = 'none';
        }
    </script>
@endsection