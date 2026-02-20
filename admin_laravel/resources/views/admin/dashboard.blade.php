@extends('layouts.app')

@section('content')
    <h1 class="title-outline" style="font-size: 2rem; margin-bottom: 40px;">PANEL DE CONTROL</h1>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; max-width: 900px; margin: 0 auto;">
        
        <a href="{{ route('sitios.disponibles') }}" class="neon-box" style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 160px; text-decoration: none; color: white;">
            <span style="font-size: 2.5rem; margin-bottom: 10px;">📅</span>
            <span style="font-size: 1.2rem; font-weight: 600; text-transform: uppercase;">Agendar / Disponibles</span>
        </a>

        <a href="{{ route('sitios.ocupados') }}" class="neon-box" style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 160px; text-decoration: none; color: white;">
            <span style="font-size: 2.5rem; margin-bottom: 10px;">📌</span>
            <span style="font-size: 1.2rem; font-weight: 600; text-transform: uppercase;">Sitios Ocupados</span>
        </a>

        <a href="{{ route('gestion.index') }}" class="neon-box" style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 160px; text-decoration: none; color: white;">
            <span style="font-size: 2.5rem; margin-bottom: 10px;">👥</span>
            <span style="font-size: 1.2rem; font-weight: 600; text-transform: uppercase;">Gestión General</span>
        </a>

        <a href="{{ route('sitios.configuracion') }}" class="neon-box" style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 160px; text-decoration: none; color: white;">
            <span style="font-size: 2.5rem; margin-bottom: 10px;">⚙️</span>
            <span style="font-size: 1.2rem; font-weight: 600; text-transform: uppercase;">Inhabilitar Sitios</span>
        </a>

        <a href="{{ route('estadisticas.index') }}" class="neon-box" style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 160px; text-decoration: none; color: white;">
            <span style="font-size: 2.5rem; margin-bottom: 10px;">📊</span>
            <span style="font-size: 1.2rem; font-weight: 600; text-transform: uppercase;">Estadísticas</span>
        </a>

    </div>
@endsection