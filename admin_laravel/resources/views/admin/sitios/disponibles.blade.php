@extends('layouts.app')

@section('content')
<div class="map-container">
    <h1 class="dashboard-title">MAPA DE ESPACIOS</h1>

    <div class="filters-row">
        <select id="select-espacio" class="neon-input filter-select">
            @foreach($espacios as $esp)
                <option value="{{ $esp['id_espacio'] }}" data-apertura="{{ $esp['horario_apertura'] }}" data-cierre="{{ $esp['horario_cierre'] }}">
                    {{ $esp['nombre'] }} ({{ $esp['horario_apertura'] }} - {{ $esp['horario_cierre'] }})
                </option>
            @endforeach
        </select>
        <input type="date" id="fecha-select" class="neon-input filter-date" value="{{ $fecha_hoy }}">
    </div>

    <div class="map-content">
        <div class="neon-box map-grid">
            <div class="pizarron">PIZARRÓN / FRENTE</div>
            <div id="lab-grid" class="lab-grid">
                <div class="loading">Cargando equipos...</div>
            </div>
        </div>
        <div class="neon-box legend">
            <h3>Estado</h3>
            <div class="legend-item"><span class="legend-color available-color"></span> Disponible</div>
            <div class="legend-item"><span class="legend-color occupied-color"></span> Ocupado</div>
            <div class="legend-item"><span class="legend-color selected-color"></span> Seleccionado</div>
        </div>
    </div>
</div>

<!-- Modal de reserva (Bootstrap) -->
<div class="modal fade" id="modalReserva" tabindex="-1" aria-labelledby="modalReservaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content sage-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="modalReservaLabel">Confirmar Reserva</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p>Equipo seleccionado: <strong id="equipoSelec" class="reserva-equipo"></strong></p>
                <p id="info-horario" class="reserva-horario"></p>

                <form id="formReserva">
                    @csrf
                    <input type="hidden" name="id_espacio" id="id_espacio_input">
                    <input type="hidden" name="id_equipo" id="id_equipo_input">

                    <div class="mb-3">
                        <label class="form-label">Tipo de Reserva:</label>
                        <select name="tipo_reserva" id="tipo_reserva" class="sage-input" required>
                            <option value="">Selecciona...</option>
                            <option value="estudiante">Individual (Estudiante)</option>
                            <option value="clase">Clase Programada</option>
                            <option value="evento">Evento Especial</option>
                        </select>
                    </div>

                    <div id="contenedor-referencia" class="mb-3" style="display: none;">
                        <label id="label-referencia" class="form-label"></label>
                        <select name="id_referencia" id="select-referencia" class="sage-input" required></select>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Fecha/Hora Inicio:</label>
                            <input type="datetime-local" name="fecha_hora_inicio" id="fecha_hora_inicio" class="sage-input" required>
                        </div>
                        <div class="col">
                            <label class="form-label">Fecha/Hora Fin:</label>
                            <input type="datetime-local" name="fecha_hora_fin" id="fecha_hora_fin" class="sage-input" required>
                        </div>
                    </div>

                    <!-- Opciones para clases recurrentes -->
                    <div id="recurrencia-container" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Tipo de recurrencia:</label>
                            <select name="recurrencia" id="recurrencia" class="sage-input">
                                <option value="una_vez">Una sola vez</option>
                                <option value="semanal">Semanal (16 semanas)</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between gap-2">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">Agendar Equipo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Datos iniciales pasados desde Laravel
    const espaciosData = @json($espacios);
    const equiposData = @json($equipos);
    const fechaHoy = '{{ $fecha_hoy }}';

    console.log('Equipos data:', equiposData); // Debug

    let ocupacionReal = {};
    let modalReserva;
    let ultimoAsiento = null;

    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded');
        modalReserva = new bootstrap.Modal(document.getElementById('modalReserva'));
        const fechaSelect = document.getElementById('fecha-select');
        const selectEspacio = document.getElementById('select-espacio');
        if (fechaSelect) fechaSelect.value = fechaHoy;
        if (fechaSelect) fechaSelect.addEventListener('change', actualizarTodo);
        if (selectEspacio) selectEspacio.addEventListener('change', actualizarTodo);
        const tipoReserva = document.getElementById('tipo_reserva');
        if (tipoReserva) tipoReserva.addEventListener('change', cambiarTipoReserva);
        actualizarTodo();
    });

    async function cargarOcupacion(fecha) {
        try {
            const response = await fetch(`/api/ocupacion?fecha=${fecha}`);
            if (!response.ok) throw new Error('HTTP error ' + response.status);
            const data = await response.json();
            ocupacionReal = data.ocupacion || {};
            console.log('Ocupación cargada:', ocupacionReal);
        } catch (error) {
            console.error('Error al cargar ocupación:', error);
            ocupacionReal = {};
        }
    }

    function dibujarEquipos(espacioId) {
        console.log('Dibujando equipos para espacio:', espacioId);
        const equiposEspacio = equiposData.filter(eq => eq.id_espacio === espacioId);
        const grid = document.getElementById('lab-grid');
        if (!grid) return;

        if (!equiposEspacio.length) {
            grid.innerHTML = '<p style="color: white; text-align: center;">No hay equipos registrados en este espacio.</p>';
            return;
        }

        let html = '';
        equiposEspacio.forEach(eq => {
            const estaOcupado = ocupacionReal[eq.id_equipo] && ocupacionReal[eq.id_equipo].length > 0;
            const claseEstado = estaOcupado ? 'occupied' : 'available';
            html += `<div class="pc-node ${claseEstado}" data-equipo-id="${eq.id_equipo}" data-equipo-nombre="${eq.nombre_equipo}" onclick="seleccionarEquipo(this, ${eq.id_equipo}, '${eq.nombre_equipo}')">
                        ${eq.nombre_equipo}
                    </div>`;
        });
        grid.innerHTML = html;
    }

    function seleccionarEquipo(elemento, idEquipo, nombreEquipo) {
        if (elemento.classList.contains('occupied')) {
            alert('Este equipo ya tiene reservas para la fecha seleccionada.');
            return;
        }
        if (ultimoAsiento) ultimoAsiento.classList.remove('selected');
        elemento.classList.add('selected');
        ultimoAsiento = elemento;

        document.getElementById('equipoSelec').innerText = nombreEquipo;
        document.getElementById('id_espacio_input').value = parseInt(document.getElementById('select-espacio').value);
        document.getElementById('id_equipo_input').value = idEquipo;

        // Cargar módulos disponibles para el equipo en la fecha seleccionada
        cargarModulosDisponibles(idEquipo, document.getElementById('fecha-select').value);
        modalReserva.show();
    }

    async function cargarModulosDisponibles(equipoId, fecha) {
        const combo = document.getElementById('select-horario');
        if (!combo) return;
        combo.innerHTML = '<option value="">Cargando módulos...</option>';
        combo.disabled = true;
        try {
            const response = await fetch(`/api/equipos/${equipoId}/modulos?fecha=${fecha}`);
            if (!response.ok) throw new Error('HTTP error ' + response.status);
            const data = await response.json();
            if (!data.modulos || data.modulos.length === 0) {
                combo.innerHTML = '<option value="">No hay módulos disponibles</option>';
                combo.disabled = true;
                return;
            }
            combo.innerHTML = '';
            data.modulos.forEach(mod => {
                const option = document.createElement('option');
                option.value = `${mod.hora_inicio}-${mod.hora_fin}`;
                option.textContent = `${mod.nombre} (${mod.hora_inicio} - ${mod.hora_fin})`;
                combo.appendChild(option);
            });
            combo.disabled = false;
        } catch (error) {
            console.error('Error al cargar módulos:', error);
            combo.innerHTML = '<option value="">Error al cargar módulos</option>';
            combo.disabled = true;
        }
    }

    async function actualizarTodo() {
        const fecha = document.getElementById('fecha-select').value;
        const espacioId = parseInt(document.getElementById('select-espacio').value);
        if (!fecha || isNaN(espacioId)) {
            console.error('Fecha o espacio inválidos');
            return;
        }
        await cargarOcupacion(fecha);
        dibujarEquipos(espacioId);
        // Mostrar información de horarios del espacio
        const select = document.getElementById('select-espacio');
        const apertura = select.options[select.selectedIndex].dataset.apertura;
        const cierre = select.options[select.selectedIndex].dataset.cierre;
        const info = document.getElementById('info-horario');
        if (info) {
            info.innerText = `Horarios del sitio: ${apertura} a ${cierre} hrs. (Fecha: ${fecha})`;
        }
    }

    async function cambiarTipoReserva() {
        const tipo = document.getElementById('tipo_reserva').value;
        const contenedor = document.getElementById('contenedor-referencia');
        const selectReferencia = document.getElementById('select-referencia');
        const label = document.getElementById('label-referencia');
        const recurrenciaContainer = document.getElementById('recurrencia-container');

        if (!tipo) {
            if (contenedor) contenedor.style.display = 'none';
            if (recurrenciaContainer) recurrenciaContainer.style.display = 'none';
            return;
        }

        if (contenedor) contenedor.style.display = 'block';
        label.innerText = `Selecciona ${tipo === 'estudiante' ? 'al estudiante' : (tipo === 'clase' ? 'la clase' : 'el evento')}:`;

        let url = '';
        if (tipo === 'estudiante') {
            url = '/api-gestion/estudiantes';
        } else if (tipo === 'clase') {
            url = '/api-gestion/clases';
        } else if (tipo === 'evento') {
            url = '/api-gestion/eventos';
        }

        try {
            const response = await fetch(url);
            const data = await response.json();
            selectReferencia.innerHTML = '<option value="">Selecciona...</option>';
            data.forEach(item => {
                let texto = '';
                if (tipo === 'estudiante') {
                    texto = `${item.matricula} - ${item.nombre_completo}`;
                } else if (tipo === 'clase') {
                    texto = `${item.nombre} (${item.materia})`;
                } else {
                    texto = item.nombre;
                }
                const idKey = tipo === 'estudiante' ? 'id_estudiante' : (tipo === 'clase' ? 'id_clase' : 'id_evento');
                selectReferencia.innerHTML += `<option value="${item[idKey]}">${texto}</option>`;
            });
        } catch (error) {
            console.error('Error al cargar opciones:', error);
        }

        // Mostrar opciones de recurrencia solo para clases
        if (tipo === 'clase') {
            if (recurrenciaContainer) recurrenciaContainer.style.display = 'block';
        } else {
            if (recurrenciaContainer) recurrenciaContainer.style.display = 'none';
        }
    }

    // Manejo del envío del formulario
    document.getElementById('formReserva').addEventListener('submit', async (e) => {
        e.preventDefault();

        const tipo = document.getElementById('tipo_reserva').value;
        const idReferencia = document.getElementById('select-referencia').value;
        const espacioId = document.getElementById('id_espacio_input').value;
        const equipoId = document.getElementById('id_equipo_input').value;
        const fechaInicio = document.getElementById('fecha_hora_inicio').value;
        const fechaFin = document.getElementById('fecha_hora_fin').value;
        const recurrencia = document.getElementById('recurrencia')?.value || 'una_vez';

        if (!tipo || !idReferencia) {
            alert('Debes seleccionar el tipo de reserva y el beneficiario.');
            return;
        }

        const payload = {
            id_espacio: espacioId,
            id_equipo: equipoId,
            tipo_reserva: tipo,
            id_referencia: idReferencia,
            fecha_hora_inicio: fechaInicio,
            fecha_hora_fin: fechaFin,
            recurrencia: recurrencia
        };

        try {
            const response = await fetch('/reservas', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();
            if (result.error) {
                alert('Error: ' + result.message);
            } else {
                alert('Reserva creada exitosamente');
                modalReserva.hide();
                actualizarTodo(); // Recargar mapa
            }
        } catch (error) {
            alert('Error al conectar con el servidor');
        }
    });
</script>
@endsection