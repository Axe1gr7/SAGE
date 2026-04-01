@extends('layouts.app')

@section('content')
<div>
    <h1 class="title-outline">SITIOS ACTIVOS</h1>
    
    <div class="capsule-menu">
        <a href="#" class="neon-box capsule-btn" data-tipo="todos">TODOS</a>
        <a href="#" class="neon-box capsule-btn" data-tipo="Laboratorio">LABORATORIO</a>
        <a href="#" class="neon-box capsule-btn" data-tipo="Sala">SALAS</a>
        <a href="#" class="neon-box capsule-btn" data-tipo="Taller">TALLERES</a>
        <a href="#" class="neon-box capsule-btn" data-tipo="Servicios">BIBLIOTECA</a>
        <a href="#" class="neon-box capsule-btn" data-tipo="Sala">AUDITORIOS</a>
    </div>

    <div class="neon-box" style="padding: 2px; overflow: hidden;">
        <table class="neon-table" id="tabla-reservas">
            <thead>
                 <tr>
                    <th>Id</th>
                    <th>Ubicación</th>
                    <th>Descripción</th>
                    <th>Usuario</th>
                    <th>Fecha/hora</th>
                 </tr>
            </thead>
            <tbody>
                <tr><td colspan="5" class="text-center">Cargando reservas...</td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let currentTipo = 'todos';

    async function cargarReservas(tipo = 'todos') {
        const tbody = document.querySelector('#tabla-reservas tbody');
        tbody.innerHTML = '<tr><td colspan="5" class="text-center">Cargando...</td></tr>';
        
        try {
            const response = await fetch(`/api/reservas/activas?tipo=${tipo}`);
            const reservas = await response.json();
            
            if (!reservas.length) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">No hay reservas activas.</td></tr>';
                return;
            }
            
            let html = '';
            reservas.forEach(r => {
                const fechaHora = new Date(r.fecha_hora_inicio).toLocaleString();
                // Determinar usuario (estudiante, clase o evento)
                let usuario = '';
                if (r.tipo_reserva === 'estudiante' && r.estudiante_beneficiario) {
                    usuario = r.estudiante_beneficiario.nombre_completo || r.estudiante_beneficiario.id;
                } else if (r.tipo_reserva === 'clase' && r.clase_beneficiario) {
                    usuario = `Clase: ${r.clase_beneficiario.nombre}`;
                } else if (r.tipo_reserva === 'evento' && r.evento_beneficiario) {
                    usuario = `Evento: ${r.evento_beneficiario.nombre}`;
                } else {
                    usuario = 'Sin especificar';
                }
                
                html += `<tr>
                            <td>${r.id_reserva}</td>
                            <td>${r.espacio ? r.espacio.nombre : 'N/A'}</td>
                            <td>${r.tipo_reserva === 'estudiante' ? 'Reserva individual' : (r.tipo_reserva === 'clase' ? 'Clase' : 'Evento')}</td>
                            <td>${usuario}</td>
                            <td>${fechaHora}</td>
                         </tr>`;
            });
            tbody.innerHTML = html;
        } catch (error) {
            console.error('Error al cargar reservas:', error);
            tbody.innerHTML = '<tr><td colspan="5" class="text-center">Error al cargar datos</td></tr>';
        }
    }
    
    document.querySelectorAll('.capsule-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const tipo = btn.getAttribute('data-tipo');
            currentTipo = tipo;
            cargarReservas(tipo);
            // Remover clase activa de todos y agregar al actual
            document.querySelectorAll('.capsule-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        });
    });
    
    // Cargar inicialmente
    document.addEventListener('DOMContentLoaded', () => {
        cargarReservas('todos');
    });
</script>
@endsection