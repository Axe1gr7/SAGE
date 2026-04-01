<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EspaciosController;
use App\Http\Controllers\EquiposController;
use App\Http\Controllers\ClasesController;
use App\Http\Controllers\EventosController;
use App\Http\Controllers\ReservasController;
use App\Http\Controllers\EstadisticasController;
use App\Http\Controllers\GestionController;

// ========== PÚBLICAS ==========
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ========== PROTEGIDAS CON MIDDLEWARE ==========
Route::middleware(['admin.auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Espacios
    Route::resource('espacios', EspaciosController::class)->except(['show']);
    Route::get('/api/espacios/{id}/equipos', [EspaciosController::class, 'equipos'])->name('api.espacios.equipos');

    // Equipos
    Route::get('/api/equipos', [EquiposController::class, 'index'])->name('api.equipos.index');
    Route::put('/api/equipos/{id}/estado', [EquiposController::class, 'updateEstado'])->name('api.equipos.updateEstado');

    // Clases
    Route::resource('clases', ClasesController::class)->except(['show']);

    // Eventos
    Route::resource('eventos', EventosController::class)->except(['show']);

    // Reservas
    Route::get('/reservas', [ReservasController::class, 'index'])->name('reservas.index');
    Route::post('/reservas', [ReservasController::class, 'store'])->name('reservas.store');
    Route::post('/reservas/{id}/cancelar', [ReservasController::class, 'cancelar'])->name('reservas.cancelar');
    Route::get('/api/ocupacion', [ReservasController::class, 'ocupacion'])->name('api.ocupacion');          // Mapa
    Route::get('/api/equipos/{id}/modulos', [EquiposController::class, 'modulos'])->name('api.equipos.modulos'); // Módulos disponibles

    // Estadísticas
    Route::get('/api/estadisticas/espacios-mas-reservados', [EstadisticasController::class, 'espaciosMasReservados']);
    Route::get('/api/estadisticas/horarios-demanda', [EstadisticasController::class, 'horariosDemanda']);

    // ==========================================
    // VISTAS PRINCIPALES (con datos dinámicos)
    // ==========================================
    Route::get('/sitios/disponibles', [EspaciosController::class, 'disponibles'])->name('sitios.disponibles');
    Route::get('/sitios/ocupados', [EspaciosController::class, 'ocupados'])->name('sitios.ocupados');
    Route::view('/sitios/activos', 'admin.sitios.activos')->name('sitios.activos');
    Route::view('/sitios/configuracion', 'admin.sitios.configuracion')->name('sitios.configuracion');
    Route::view('/estadisticas', 'admin.estadisticas.index')->name('estadisticas.index');
    Route::view('/gestion', 'admin.gestion.index')->name('gestion.index');   // Vista estática (los datos se cargan vía AJAX)

    // Registro de clase
    Route::get('/registro-clase', [ClasesController::class, 'create'])->name('public.clase.crear');
    Route::post('/registro-clase', [ClasesController::class, 'store'])->name('public.clase.store');

    // ==========================================
    // API PROXY PARA GESTIÓN GENERAL (CRUD)
    // ==========================================
    Route::get('/api-gestion/{entidad}', [GestionController::class, 'index']);        // Listar todos
    Route::get('/api-gestion/{entidad}/{id}', [GestionController::class, 'show']);    // Ver uno
    Route::post('/api-gestion/{entidad}', [GestionController::class, 'store']);       // Crear
    Route::put('/api-gestion/{entidad}/{id}', [GestionController::class, 'update']);  // Actualizar
    Route::delete('/api-gestion/{entidad}/{id}', [GestionController::class, 'destroy']); // Eliminar
}); 

// ========== PROTEGIDAS CON MIDDLEWARE ==========
Route::middleware(['admin.auth'])->group(function () {
    // ... otras rutas ...

    // Estadísticas (AJAX)
    Route::get('/api/estadisticas/espacios-mas-reservados', [EstadisticasController::class, 'espaciosMasReservados']);
    Route::get('/api/estadisticas/horarios-demanda', [EstadisticasController::class, 'horariosDemanda']);

    // Vista de estadísticas
    Route::view('/estadisticas', 'admin.estadisticas.index')->name('estadisticas.index');

    // ... resto de rutas ...
});