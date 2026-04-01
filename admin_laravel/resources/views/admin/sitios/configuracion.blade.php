@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-3">
        <h1 class="title-outline dashboard-title">INHABILITAR ESPACIOS</h1>
        <p class="text-center text-secondary mb-4">Selecciona el hardware o sala que presenta desperfectos o dejará de existir.</p>

        <div class="d-flex justify-content-between mb-4">
            <select id="select-espacio" class="neon-input filter-select" style="width: auto; min-width: 250px;">
                @foreach($espacios as $esp)
                    <option value="{{ $esp['id_espacio'] }}">{{ $esp['nombre'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="neon-box p-0 overflow-auto">
            <table class="neon-table w-100" id="tabla-equipos">
                <thead>
                    <tr>
                        <th>Identificador</th>
                        <th>Tipo</th>
                        <th>Estado Actual</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Los equipos se cargarán dinámicamente con JavaScript -->
                    <tr><td colspan="4" class="text-center">Cargando equipos...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal de inhabilitar (Bootstrap) -->
    <div class="modal fade" id="inhabilitarModal" tabindex="-1" aria-labelledby="inhabilitarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content sage-modal">
                <div class="modal-header">
                    <h5 class="modal-title">Inhabilitar Equipo</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p id="equipoInfo" class="text-center fw-bold"></p>
                    <form id="formInhabilitar">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Motivo (Ej: Pantalla rota, No enciende):</label>
                            <textarea id="motivo" rows="4" class="neon-input" required></textarea>
                        </div>
                        <div class="d-flex justify-content-between gap-2">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn-action-yellow">Confirmar Baja</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    let equipoActualId = null;
    let inhabilitarModal;

    document.addEventListener('DOMContentLoaded', () => {
        inhabilitarModal = new bootstrap.Modal(document.getElementById('inhabilitarModal'));
        document.getElementById('select-espacio').addEventListener('change', cargarEquipos);
        cargarEquipos();
    });

    async function cargarEquipos() {
        const espacioId = document.getElementById('select-espacio').value;
        const tablaBody = document.querySelector('#tabla-equipos tbody');
        tablaBody.innerHTML = '<tr><td colspan="4" class="text-center">Cargando equipos...</td></tr>';

        try {
            const response = await fetch(`/api/equipos?espacio_id=${espacioId}`);
            const equipos = await response.json();

            if (!equipos.length) {
                tablaBody.innerHTML = '<tr><td colspan="4" class="text-center">No hay equipos en este espacio.</td></tr>';
                return;
            }

            let html = '';
            equipos.forEach(eq => {
                const estado = eq.estado_operativo === 'operativo' ? 'Operativo' : 'En Mantenimiento';
                const estadoClass = eq.estado_operativo === 'operativo' ? 'text-success' : 'text-danger';
                const accion = eq.estado_operativo === 'operativo'
                    ? `<button class="btn-action-red" onclick="inhabilitarEquipo(${eq.id_equipo}, '${eq.nombre_equipo}')">Inhabilitar</button>`
                    : `<button class="btn-action-green" onclick="habilitarEquipo(${eq.id_equipo}, '${eq.nombre_equipo}')">Habilitar</button>`;
                html += `<tr>
                            <td>${eq.nombre_equipo}</td>
                            <td>${eq.tipo_equipo}</td>
                            <td><span class="${estadoClass}">${estado}</span></td>
                            <td class="acciones-cell">${accion}</td>
                         </tr>`;
            });
            tablaBody.innerHTML = html;
        } catch (error) {
            console.error('Error cargando equipos:', error);
            tablaBody.innerHTML = '<tr><td colspan="4" class="text-center">Error al cargar equipos</td></tr>';
        }
    }

    function inhabilitarEquipo(idEquipo, nombreEquipo) {
        equipoActualId = idEquipo;
        document.getElementById('equipoInfo').innerText = "Equipo seleccionado: " + nombreEquipo;
        inhabilitarModal.show();
    }

    function habilitarEquipo(idEquipo, nombreEquipo) {
        if (confirm(`¿Estás seguro de habilitar el equipo ${nombreEquipo}?`)) {
            fetch(`/api/equipos/${idEquipo}/estado`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ estado_operativo: 'operativo' })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error al habilitar: ' + data.message);
                } else {
                    cargarEquipos();  // Recargar la tabla
                }
            })
            .catch(error => console.error('Error:', error));
        }
    }

    document.getElementById('formInhabilitar').addEventListener('submit', async (e) => {
        e.preventDefault();
        const motivo = document.getElementById('motivo').value;
        if (!motivo) {
            alert('Debes escribir un motivo.');
            return;
        }

        try {
            const response = await fetch(`/api/equipos/${equipoActualId}/estado`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ estado_operativo: 'mantenimiento', motivo: motivo })
            });
            const data = await response.json();
            if (data.error) {
                alert('Error al inhabilitar: ' + data.message);
            } else {
                inhabilitarModal.hide();
                cargarEquipos();
                document.getElementById('motivo').value = '';
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error de conexión con el servidor.');
        }
    });
</script>
@endsection