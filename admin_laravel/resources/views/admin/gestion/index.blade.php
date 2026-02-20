@extends('layouts.app')

@section('content')
    <style>
        /* Estilos extra para botones en la tabla y modal de eliminar */
        .btn-action-red {
            background-color: #f87171; color: #000; border: none; padding: 5px 15px;
            border-radius: 20px; font-weight: bold; cursor: pointer; transition: 0.3s; font-size: 0.85rem;
        }
        .btn-action-red:hover { box-shadow: 0 0 10px rgba(248, 113, 113, 0.6); }
        .acciones-cell { display: flex; gap: 10px; justify-content: center; }
    </style>

    <div style="padding: 20px; max-width: 1200px; margin: 0 auto;">
        <h1 class="title-outline">GESTIÓN GENERAL</h1>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <div class="capsule-menu" id="menu-tabs" style="margin-bottom: 0;">
                <button onclick="changeTab('admin')" class="capsule-btn active" id="btn-admin">Administradores</button>
                <button onclick="changeTab('estudiantes')" class="capsule-btn inactive" id="btn-estudiantes">Estudiantes</button>
                <button onclick="changeTab('clases')" class="capsule-btn inactive" id="btn-clases">Clases</button>
                <button onclick="changeTab('eventos')" class="capsule-btn inactive" id="btn-eventos">Eventos</button>
            </div>
            
            <button class="btn-action-yellow" onclick="abrirModalAgregar()" style="padding: 10px 25px; font-size: 1rem;">+ AGREGAR REGISTRO</button>
        </div>

        <div class="neon-box" style="padding: 0; overflow: hidden; overflow-x: auto;">
            <table class="neon-table">
                <thead id="tabla-head"></thead>
                <tbody id="tabla-body"></tbody>
            </table>
        </div>
    </div>

    <div id="modalFormulario" class="modal-overlay">
        <div class="modal-content">
            <h2 id="modal-titulo" style="color: var(--neon-blue); text-align: center; margin-top: 0;">NUEVO REGISTRO</h2>
            
            <form id="formGeneral" action="#" method="POST">
                @csrf
                <input type="hidden" name="_method" id="form_method" value="POST">
                <input type="hidden" name="id" id="registro_id">

                <div id="form-fields"></div>
                
                <div style="display: flex; justify-content: space-between; margin-top: 30px;">
                    <button type="button" class="btn-outline" style="border-color: #f87171; color: #f87171;" onclick="cerrarModal()">CANCELAR</button>
                    <button type="submit" class="btn-action-green" id="btn-guardar">GUARDAR DATOS</button>
                </div>
            </form>
        </div>
    </div>

    <div id="modalEliminar" class="modal-overlay">
        <div class="modal-content" style="border-color: #f87171; box-shadow: 0 0 15px rgba(248, 113, 113, 0.4); text-align: center; width: 400px;">
            <h2 style="color: #f87171; margin-top: 0;">⚠ ELIMINAR REGISTRO</h2>
            <p style="color: var(--text-main);">¿Estás seguro de que deseas eliminar este registro? Esta acción no se puede deshacer.</p>
            
            <form id="formEliminar" action="#" method="POST">
                @csrf
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="id_eliminar" id="id_eliminar">
                
                <div style="display: flex; justify-content: center; gap: 20px; margin-top: 30px;">
                    <button type="button" class="btn-outline" onclick="cerrarModalEliminar()">CANCELAR</button>
                    <button type="submit" class="btn-action-red" style="padding: 10px 20px; font-size: 1rem;">SÍ, ELIMINAR</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentTab = 'admin';

        // 1. Plantillas de formularios sincronizadas con tu BD Final
        const formTemplates = {
            'admin': `
                <label style="color: var(--neon-blue); font-size: 0.85rem;">Nombre Completo</label>
                <input type="text" name="nombre_completo" class="neon-input" required>
                
                <label style="color: var(--neon-blue); font-size: 0.85rem;">Puesto</label>
                <input type="text" name="puesto" class="neon-input">
                
                <label style="color: var(--neon-blue); font-size: 0.85rem;">Correo Electrónico (Único)</label>
                <input type="email" name="correo" class="neon-input" required>
                
                <label style="color: var(--neon-blue); font-size: 0.85rem;">Contraseña (Dejar en blanco para no cambiar al editar)</label>
                <input type="password" name="contrasena" class="neon-input">
            `,
            'estudiantes': `
                <label style="color: var(--neon-blue); font-size: 0.85rem;">Nombre Completo</label>
                <input type="text" name="nombre_completo" class="neon-input" required>
                
                <div style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <label style="color: var(--neon-blue); font-size: 0.85rem;">Matrícula (Única)</label>
                        <input type="text" name="matricula" class="neon-input" required>
                    </div>
                    <div style="flex: 1;">
                        <label style="color: var(--neon-blue); font-size: 0.85rem;">Carrera</label>
                        <select name="carrera" class="neon-input" required>
                            <option value="">Selecciona...</option>
                            <option value="sistemas">Sistemas</option>
                            <option value="mecatronica">Mecatrónica</option>
                            <option value="ingenieria de datos">Ingeniería de Datos</option>
                            <option value="automotriz">Automotriz</option>
                            <option value="negocios internacionales">Negocios Internacionales</option>
                            <option value="administracion ">Administración</option>
                            <option value="manufactura">Manufactura</option>
                        </select>
                    </div>
                </div>
                
                <label style="color: var(--neon-blue); font-size: 0.85rem;">Correo Institucional (Único)</label>
                <input type="email" name="correo" class="neon-input" required>
                
                <label style="color: var(--neon-blue); font-size: 0.85rem;">Contraseña</label>
                <input type="password" name="contrasena" class="neon-input">
            `,
            'clases': `
                <label style="color: var(--neon-blue); font-size: 0.85rem;">Nombre de la Clase</label>
                <input type="text" name="nombre" class="neon-input" required>
                
                <div style="display: flex; gap: 10px;">
                    <div style="flex: 1;"><label style="color: var(--neon-blue); font-size: 0.85rem;">Materia</label><input type="text" name="materia" class="neon-input" required></div>
                    <div style="flex: 1;"><label style="color: var(--neon-blue); font-size: 0.85rem;">Grupo</label><input type="text" name="grupo" class="neon-input" required></div>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <div style="flex: 1;"><label style="color: var(--neon-blue); font-size: 0.85rem;">Docente</label><input type="text" name="docente" class="neon-input" required></div>
                    <div style="flex: 1;"><label style="color: var(--neon-blue); font-size: 0.85rem;">Correo Docente</label><input type="email" name="correo_docente" class="neon-input"></div>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <label style="color: var(--neon-blue); font-size: 0.85rem;">Fecha/Hora Inicio</label>
                        <input type="datetime-local" name="fecha_inicio" class="neon-input" required>
                    </div>
                    <div style="flex: 1;">
                        <label style="color: var(--neon-blue); font-size: 0.85rem;">Fecha/Hora Fin</label>
                        <input type="datetime-local" name="fecha_fin" class="neon-input" required>
                    </div>
                </div>
            `,
            'eventos': `
                <label style="color: var(--neon-blue); font-size: 0.85rem;">Nombre del Evento</label>
                <input type="text" name="nombre" class="neon-input" required>
                
                <label style="color: var(--neon-blue); font-size: 0.85rem;">Descripción</label>
                <textarea name="descripcion" class="neon-input" rows="2"></textarea>
                
                <div style="display: flex; gap: 10px;">
                    <div style="flex: 1;"><label style="color: var(--neon-blue); font-size: 0.85rem;">Inicio</label><input type="datetime-local" name="fecha_inicio" class="neon-input"></div>
                    <div style="flex: 1;"><label style="color: var(--neon-blue); font-size: 0.85rem;">Fin</label><input type="datetime-local" name="fecha_fin" class="neon-input"></div>
                </div>
            `
        };

        // 2. Base de datos simulada (Mock)
        const dbMock = {
            'admin': [
                { id: 1, nombre_completo: 'Axel V.', puesto: 'Director de TI', correo: 'axel@upq.mx', contrasena: '' }
            ],
            'estudiantes': [
                { id: 1, nombre_completo: 'Ana López M.', matricula: '1220432', carrera: 'sistemas', correo: 'ana@upq.mx', contrasena: '' }
            ],
            'clases': [
                { id: 1, nombre: 'Base de Datos Avanzada', materia: 'Bases de Datos', grupo: 'S101', docente: 'Roberto M.', correo_docente: 'roberto@upq.mx', fecha_inicio: '2026-03-25T10:00', fecha_fin: '2026-03-25T12:00' }
            ],
            'eventos': [
                { id: 1, nombre: 'Hackathon 2026', descripcion: 'Evento anual de código', fecha_inicio: '2026-03-25T08:00', fecha_fin: '2026-03-26T18:00' }
            ]
        };

        // 3. Configuración de columnas para la tabla (Keys apuntan a las columnas SQL)
        const tableConfigs = {
            'admin': { headers: ['Nombre Completo', 'Puesto', 'Correo', 'Acciones'], keys: ['nombre_completo', 'puesto', 'correo'] },
            'estudiantes': { headers: ['Matrícula', 'Nombre Completo', 'Carrera', 'Correo', 'Acciones'], keys: ['matricula', 'nombre_completo', 'carrera', 'correo'] },
            'clases': { headers: ['Nombre', 'Materia', 'Docente', 'Inicio', 'Fin', 'Acciones'], keys: ['nombre', 'materia', 'docente', 'fecha_inicio', 'fecha_fin'] },
            'eventos': { headers: ['Evento', 'Inicio', 'Fin', 'Acciones'], keys: ['nombre', 'fecha_inicio', 'fecha_fin'] }
        };

        function changeTab(tabName) {
            currentTab = tabName;
            ['admin', 'estudiantes', 'clases', 'eventos'].forEach(id => {
                document.getElementById('btn-' + id).className = (id === tabName) ? 'capsule-btn active' : 'capsule-btn inactive';
            });
            renderTable();
        }

        function renderTable() {
            const config = tableConfigs[currentTab];
            const data = dbMock[currentTab];
            
            document.getElementById('tabla-head').innerHTML = '<tr>' + config.headers.map(h => `<th>${h}</th>`).join('') + '</tr>';
            
            let htmlBody = '';
            data.forEach(row => {
                htmlBody += '<tr>';
                config.keys.forEach(key => { htmlBody += `<td style="text-transform: capitalize;">${row[key]}</td>`; });
                
                htmlBody += `
                    <td>
                        <div class="acciones-cell">
                            <button class="btn-action-yellow" onclick="abrirModalEditar(${row.id})">Editar</button>
                            <button class="btn-action-red" onclick="abrirModalEliminar(${row.id})">Eliminar</button>
                        </div>
                    </td>
                </tr>`;
            });
            document.getElementById('tabla-body').innerHTML = htmlBody;
        }

        function abrirModalAgregar() {
            document.getElementById('modal-titulo').innerText = 'NUEVO REGISTRO';
            document.getElementById('form_method').value = 'POST';
            document.getElementById('registro_id').value = '';
            document.getElementById('btn-guardar').innerText = 'GUARDAR DATOS';
            document.getElementById('form-fields').innerHTML = formTemplates[currentTab];
            document.getElementById('modalFormulario').style.display = 'flex';
        }

        function abrirModalEditar(id) {
            document.getElementById('modal-titulo').innerText = 'EDITAR REGISTRO';
            document.getElementById('form_method').value = 'PUT';
            document.getElementById('registro_id').value = id;
            document.getElementById('btn-guardar').innerText = 'ACTUALIZAR DATOS';
            document.getElementById('form-fields').innerHTML = formTemplates[currentTab];
            
            // Llenar datos
            const rowData = dbMock[currentTab].find(item => item.id === id);
            for (const key in rowData) {
                const input = document.querySelector(`[name="${key}"]`);
                if (input) input.value = rowData[key];
            }
            document.getElementById('modalFormulario').style.display = 'flex';
        }

        function abrirModalEliminar(id) {
            document.getElementById('id_eliminar').value = id;
            document.getElementById('modalEliminar').style.display = 'flex';
        }

        function cerrarModal() { document.getElementById('modalFormulario').style.display = 'none'; }
        function cerrarModalEliminar() { document.getElementById('modalEliminar').style.display = 'none'; }

        window.onload = function() { changeTab(currentTab); };
    </script>
@endsection