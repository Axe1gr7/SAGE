@extends('layouts.app')

@section('content')
<div class="ocupados-container">
    <h1 class="dashboard-title">ESPACIOS OCUPADOS</h1>

    <div class="filters-row ocupados-filters">
        <select id="espacio-select" class="neon-input filter-select">
            @foreach($espacios as $esp)
                <option value="{{ $esp['id_espacio'] }}">{{ $esp['nombre'] }}</option>
            @endforeach
        </select>
        <input type="date" id="fecha-select" class="neon-input filter-date" value="{{ $fecha_hoy }}">
    </div>

    <div class="neon-box tabla-container">
        <table class="neon-table">
            <thead>
                    <th>Horario</th>
                    <th>Espacio / PC</th>
                    <th>Ocupado por</th>
                    <th>Tipo</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody id="tabla-reservas">
                <tr><td colspan="5" class="text-center">Cargando reservas...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal de cancelación (Bootstrap) -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content sage-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Cancelar Reserva</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p id="modalInfo" class="mb-3"></p>
                <form id="formCancelar">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Motivo de cancelación:</label>
                        <textarea name="motivo" id="motivo" rows="4" class="neon-input" required></textarea>
                    </div>
                    <div class="d-flex justify-content-between gap-2">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Volver</button>
                        <button type="submit" class="btn btn-danger">Confirmar Cancelación</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let modalCancel;
    let reservaActualId = null;

    document.addEventListener('DOMContentLoaded', function() {
        modalCancel = new bootstrap.Modal(document.getElementById('cancelModal'));
        document.getElementById('espacio-select').addEventListener('change', cargarReservas);
        document.getElementById('fecha-select').addEventListener('change', cargarReservas);
        cargarReservas(); // Carga inicial
    });

    async function cargarReservas() {
        const espacioId = document.getElementById('espacio-select').value;
        const fecha = document.getElementById('fecha-select').value;
        const tbody = document.getElementById('tabla-reservas');
        tbody.innerHTML = '<tr><td colspan="5" class="text-center">Cargando reservas...</td></tr>';

        try {
            const response = await fetch(`/reservas?espacio_id=${espacioId}&fecha=${fecha}`);
            const reservas = await response.json();
            if (!reservas.length) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">No hay reservas activas en este espacio en la fecha seleccionada.</td></tr>';
                return;
            }
            let html = '';
            reservas.forEach(res => {
                const inicio = new Date(res.fecha_hora_inicio);
                const fin = new Date(res.fecha_hora_fin);
                const horario = `${inicio.toLocaleTimeString()} - ${fin.toLocaleTimeString()}`;
                const espacioNombre = res.espacio?.nombre || 'N/A';
                const equipoNombre = res.id_equipo ? `PC-${res.id_equipo}` : 'Sala completa';
                let ocupante = '';
                if (res.tipo_reserva === 'estudiante' && res.estudiante_beneficiario) {
                    ocupante = `${res.estudiante_beneficiario.nombre_completo} (Estudiante)`;
                } else if (res.tipo_reserva === 'clase' && res.clase_beneficiario) {
                    ocupante = `${res.clase_beneficiario.nombre} (Clase)`;
                } else if (res.tipo_reserva === 'evento' && res.evento_beneficiario) {
                    ocupante = `${res.evento_beneficiario.nombre} (Evento)`;
                } else {
                    ocupante = 'No especificado';
                }
                const tipo = res.tipo_reserva === 'estudiante' ? 'Individual' : (res.tipo_reserva === 'clase' ? 'Clase' : 'Evento');
                html += `<tr>
                            <td>${horario}</td>
                            <td>${equipoNombre} (${espacioNombre})</td>
                            <td>${ocupante}</td>
                            <td>${tipo}</td>
                            <td>
                                <button class="btn-cancelar" onclick="abrirModalCancelacion(${res.id_reserva}, '${ocupante.replace(/'/g, "\\'")}', '${equipoNombre}')">Cancelar</button>
                            </td>
                         </tr>`;
            });
            tbody.innerHTML = html;
        } catch (error) {
            console.error('Error cargando reservas:', error);
            tbody.innerHTML = '<tr><td colspan="5" class="text-center">Error al cargar las reservas</td></tr>';
        }
    }

    function abrirModalCancelacion(idReserva, usuario, espacio) {
        reservaActualId = idReserva;
        document.getElementById('modalInfo').innerText = `Cancelando reserva de: ${usuario} en ${espacio}`;
        modalCancel.show();
    }

    document.getElementById('formCancelar').addEventListener('submit', async (e) => {
        e.preventDefault();
        const motivo = document.getElementById('motivo').value;
        if (!motivo) {
            alert('Debes escribir un motivo.');
            return;
        }
        try {
            const response = await fetch(`/reservas/${reservaActualId}/cancelar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ motivo })
            });
            const data = await response.json();
            if (data.error) {
                alert('Error al cancelar: ' + data.message);
            } else {
                modalCancel.hide();
                cargarReservas(); // recargar tabla
                document.getElementById('motivo').value = '';
            }
        } catch (error) {
            alert('Error de conexión');
        }
    });
</script>
@endsection