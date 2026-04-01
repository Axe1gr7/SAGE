@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-3">
        <h1 class="dashboard-title mb-4">GESTIÓN GENERAL</h1>

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div class="capsule-menu" id="menu-tabs">
                <button onclick="changeTab('administradores')" class="capsule-btn active" id="btn-administradores">Administradores</button>
                <button onclick="changeTab('estudiantes')" class="capsule-btn" id="btn-estudiantes">Estudiantes</button>
                <button onclick="changeTab('clases')" class="capsule-btn" id="btn-clases">Clases</button>
                <button onclick="changeTab('eventos')" class="capsule-btn" id="btn-eventos">Eventos</button>
            </div>
            <button class="btn-action-yellow" onclick="abrirModalAgregar()">+ AGREGAR REGISTRO</button>
        </div>

        <div class="neon-box p-0 overflow-auto">
            <table class="neon-table w-100">
                <thead id="tabla-head"></thead>
                <tbody id="tabla-body">
                    <tr><td colspan="10" class="text-center">Cargando...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal para crear/editar -->
    <div class="modal fade" id="modalFormulario" tabindex="-1" aria-labelledby="modalFormularioLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content sage-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFormularioLabel">NUEVO REGISTRO</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="formGeneral">
                        @csrf
                        <input type="hidden" name="_method" id="form_method" value="POST">
                        <input type="hidden" name="id" id="registro_id">
                        <div id="form-fields"></div>
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">CANCELAR</button>
                            <button type="submit" class="btn-action-green" id="btn-guardar">GUARDAR DATOS</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para eliminar -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content sage-modal" style="border-color: #f87171; box-shadow: 0 0 15px rgba(248, 113, 113, 0.4);">
                <div class="modal-header">
                    <h5 class="modal-title" style="color: #f87171;">⚠ ELIMINAR REGISTRO</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body text-center">
                    <p>¿Estás seguro de que deseas eliminar este registro? Esta acción no se puede deshacer.</p>
                    <form id="formEliminar">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="id_eliminar" id="id_eliminar">
                        <div class="d-flex justify-content-center gap-3 mt-3">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">CANCELAR</button>
                            <button type="submit" class="btn-action-red">SÍ, ELIMINAR</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    let currentTab = 'administradores';
    let modalForm, modalDel;

    // Plantillas de formularios (coinciden con los campos de FastAPI)
    const formTemplates = {
        'administradores': `
            <div class="mb-3">
                <label class="form-label">Nombre Completo</label>
                <input type="text" name="nombre_completo" class="neon-input" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Puesto</label>
                <input type="text" name="puesto" class="neon-input">
            </div>
            <div class="mb-3">
                <label class="form-label">Correo Electrónico</label>
                <input type="email" name="correo" class="neon-input" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña (dejar en blanco para no cambiar en edición)</label>
                <input type="password" name="contrasena" class="neon-input">
            </div>
        `,
        'estudiantes': `
            <div class="mb-3">
                <label class="form-label">Nombre Completo</label>
                <input type="text" name="nombre_completo" class="neon-input" required>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Matrícula</label>
                    <input type="text" name="matricula" class="neon-input" required>
                </div>
                <div class="col">
                    <label class="form-label">Carrera</label>
                    <select name="carrera" class="neon-input" required>
                        <option value="">Selecciona...</option>
                        <option value="sistemas">Sistemas</option>
                        <option value="mecatronica">Mecatrónica</option>
                        <option value="ingenieria de datos">Ingeniería de Datos</option>
                        <option value="automotriz">Automotriz</option>
                        <option value="negocios internacionales">Negocios Internacionales</option>
                        <option value="administracion">Administración</option>
                        <option value="manufactura">Manufactura</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Correo Institucional</label>
                <input type="email" name="correo" class="neon-input" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña (dejar en blanco para no cambiar en edición)</label>
                <input type="password" name="contrasena" class="neon-input">
            </div>
        `,
        'clases': `
            <div class="mb-3">
                <label class="form-label">Nombre de la Clase</label>
                <input type="text" name="nombre" class="neon-input" required>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Materia</label>
                    <input type="text" name="materia" class="neon-input" required>
                </div>
                <div class="col">
                    <label class="form-label">Grupo</label>
                    <input type="text" name="grupo" class="neon-input" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Docente</label>
                    <input type="text" name="docente" class="neon-input" required>
                </div>
                <div class="col">
                    <label class="form-label">Correo Docente</label>
                    <input type="email" name="correo_docente" class="neon-input">
                </div>
            </div>
        `,
        'eventos': `
            <div class="mb-3">
                <label class="form-label">Nombre del Evento</label>
                <input type="text" name="nombre" class="neon-input" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="neon-input" rows="2"></textarea>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Fecha Inicio</label>
                    <input type="datetime-local" name="fecha_inicio" class="neon-input">
                </div>
                <div class="col">
                    <label class="form-label">Fecha Fin</label>
                    <input type="datetime-local" name="fecha_fin" class="neon-input">
                </div>
            </div>
        `
    };

    // Configuración de columnas y claves de ID según los modelos de FastAPI
    const tableConfigs = {
        'administradores': { 
            headers: ['Nombre Completo', 'Puesto', 'Correo', 'Acciones'], 
            keys: ['nombre_completo', 'puesto', 'correo'],
            idKey: 'id_administrador'
        },
        'estudiantes': { 
            headers: ['Matrícula', 'Nombre Completo', 'Carrera', 'Correo', 'Acciones'], 
            keys: ['matricula', 'nombre_completo', 'carrera', 'correo'],
            idKey: 'id_estudiante'
        },
        'clases': { 
            headers: ['Clase', 'Materia', 'Docente', 'Correo Docente', 'Acciones'], 
            keys: ['nombre', 'materia', 'docente', 'correo_docente'],
            idKey: 'id_clase'
        },
        'eventos': { 
            headers: ['Evento', 'Descripción', 'Inicio', 'Fin', 'Acciones'], 
            keys: ['nombre', 'descripcion', 'fecha_inicio', 'fecha_fin'],
            idKey: 'id_evento'
        }
    };

    // Obtener la URL base para las peticiones (usando rutas de Laravel que redirigen a FastAPI)
    function getBaseUrl() {
        return `/api-gestion/${currentTab}`;
    }

    async function cargarDatos() {
        const tbody = document.getElementById('tabla-body');
        tbody.innerHTML = '<td><td colspan="10" class="text-center">Cargando...</td></tr>';
        try {
            const response = await fetch(getBaseUrl());
            const data = await response.json();
            const items = Array.isArray(data) ? data : (data.data || []);
            renderTabla(items);
        } catch (error) {
            tbody.innerHTML = '<tr><td colspan="10" class="text-center">Error al cargar datos</td></tr>';
        }
    }

    function renderTabla(items) {
        const config = tableConfigs[currentTab];
        const thead = document.getElementById('tabla-head');
        thead.innerHTML = '<tr>' + config.headers.map(h => `<th>${h}</th>`).join('') + '</tr>';

        let htmlBody = '';
        if (items.length === 0) {
            htmlBody = '<tr><td colspan="10" class="text-center">No hay registros</td></tr>';
        } else {
            items.forEach(item => {
                const id = item[config.idKey];
                htmlBody += '<tr>';
                config.keys.forEach(key => {
                    let value = item[key] || '';
                    if (key === 'fecha_inicio' || key === 'fecha_fin') {
                        value = value ? new Date(value).toLocaleString() : 'N/A';
                    }
                    htmlBody += `<td>${value}</td>`;
                });
                htmlBody += `
                    <td class="acciones-cell">
                        <button class="btn-action-yellow" onclick="abrirModalEditar(${id})">Editar</button>
                        <button class="btn-action-red" onclick="abrirModalEliminar(${id})">Eliminar</button>
                    </td>
                </tr>`;
            });
        }
        document.getElementById('tabla-body').innerHTML = htmlBody;
    }

    function changeTab(tab) {
        currentTab = tab;
        ['administradores', 'estudiantes', 'clases', 'eventos'].forEach(id => {
            const btn = document.getElementById('btn-' + id);
            if (btn) {
                if (id === tab) btn.classList.add('active');
                else btn.classList.remove('active');
            }
        });
        cargarDatos();
    }

    function abrirModalAgregar() {
        document.getElementById('modalFormularioLabel').innerText = 'NUEVO REGISTRO';
        document.getElementById('form_method').value = 'POST';
        document.getElementById('registro_id').value = '';
        document.getElementById('btn-guardar').innerText = 'GUARDAR DATOS';
        document.getElementById('form-fields').innerHTML = formTemplates[currentTab];
        modalForm.show();
    }

    async function abrirModalEditar(id) {
        try {
            const response = await fetch(`${getBaseUrl()}/${id}`);
            const data = await response.json();
            document.getElementById('modalFormularioLabel').innerText = 'EDITAR REGISTRO';
            document.getElementById('form_method').value = 'PUT';
            document.getElementById('registro_id').value = id;
            document.getElementById('btn-guardar').innerText = 'ACTUALIZAR DATOS';
            document.getElementById('form-fields').innerHTML = formTemplates[currentTab];
            setTimeout(() => {
                const form = document.getElementById('formGeneral');
                for (const key in data) {
                    const input = form.querySelector(`[name="${key}"]`);
                    if (input) {
                        if ((key === 'fecha_inicio' || key === 'fecha_fin') && data[key]) {
                            input.value = data[key].substring(0, 16);
                        } else {
                            input.value = data[key];
                        }
                    }
                }
            }, 50);
            modalForm.show();
        } catch (error) {
            alert('Error al cargar los datos del registro');
        }
    }

    function abrirModalEliminar(id) {
        document.getElementById('id_eliminar').value = id;
        modalDel.show();
    }

    // Envío del formulario de creación/edición
    document.getElementById('formGeneral').addEventListener('submit', async (e) => {
        e.preventDefault();
        const method = document.getElementById('form_method').value;
        const id = document.getElementById('registro_id').value;
        const url = id ? `${getBaseUrl()}/${id}` : getBaseUrl();
        const formData = new FormData(e.target);
        const payload = {};
        formData.forEach((value, key) => {
            if (key !== '_method' && key !== '_token') payload[key] = value;
        });
        if (payload.contrasena === '') delete payload.contrasena;

        try {
            const response = await fetch(url, {
                method: method === 'POST' ? 'POST' : 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(payload)
            });
            const result = await response.json();
            if (result.error) throw new Error(result.message);
            modalForm.hide();
            cargarDatos();
            alert('Operación completada con éxito');
        } catch (error) {
            alert('Error: ' + error.message);
        }
    });

    // Envío del formulario de eliminación
    document.getElementById('formEliminar').addEventListener('submit', async (e) => {
        e.preventDefault();
        const id = document.getElementById('id_eliminar').value;
        const url = `${getBaseUrl()}/${id}`;
        try {
            const response = await fetch(url, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            });
            const result = await response.json();
            modalDel.hide();
            cargarDatos();
            alert('Registro eliminado correctamente');
        } catch (error) {
            alert('Error al eliminar el registro');
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        modalForm = new bootstrap.Modal(document.getElementById('modalFormulario'));
        modalDel = new bootstrap.Modal(document.getElementById('modalEliminar'));
        changeTab('administradores');
    });
</script>
@endsection