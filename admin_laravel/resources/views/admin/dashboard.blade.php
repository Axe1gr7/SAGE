@extends('layouts.app')

@section('content')
    <h1 class="dashboard-title">PANEL DE CONTROL</h1>

    <div class="dashboard-grid">
        <a href="{{ route('sitios.disponibles') }}" class="neon-box dashboard-card">
            <i class="bi bi-calendar-plus card-icon"></i>
            <span class="card-label">Agendar / Disponibles</span>
        </a>

        <a href="{{ route('sitios.ocupados') }}" class="neon-box dashboard-card">
            <i class="bi bi-pin-map-fill card-icon"></i>
            <span class="card-label">Sitios Ocupados</span>
        </a>

        <a href="{{ route('gestion.index') }}" class="neon-box dashboard-card">
            <i class="bi bi-people card-icon"></i>
            <span class="card-label">Gestión General</span>
        </a>

        <a href="{{ route('sitios.configuracion') }}" class="neon-box dashboard-card">
            <i class="bi bi-gear card-icon"></i>
            <span class="card-label">Inhabilitar Sitios</span>
        </a>

        <a href="{{ route('estadisticas.index') }}" class="neon-box dashboard-card">
            <i class="bi bi-bar-chart-steps card-icon"></i>
            <span class="card-label">Estadísticas</span>
        </a>
    </div>
@endsection