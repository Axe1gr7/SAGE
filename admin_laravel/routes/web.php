<?php

use Illuminate\Support\Facades\Route;

// 1. LOGIN
Route::get('/', function () { return view('auth.login'); })->name('login');
Route::post('/login', function () { return redirect()->route('admin.dashboard'); })->name('login.post');

// 2. DASHBOARD
Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('admin.dashboard');

// 3. MÓDULO: SITIOS
Route::get('/sitios/disponibles', function () { return view('admin.sitios.disponibles'); })->name('sitios.disponibles');
Route::get('/sitios/ocupados', function () { return view('admin.sitios.ocupados'); })->name('sitios.ocupados');
Route::get('/sitios/activos', function () { return view('admin.sitios.activos'); })->name('sitios.activos');
Route::get('/sitios/configuracion', function () { return view('admin.sitios.configuracion'); })->name('sitios.configuracion');

// 4. MÓDULO: ESTADÍSTICAS
Route::get('/estadisticas', function () { return view('admin.estadisticas.index'); })->name('estadisticas.index');

// 5. MÓDULO: GESTIÓN GENERAL (Usuarios, Clases, Eventos)
// Nota: Veo que esta carpeta no está en tu captura, pero dejo la ruta lista para cuando la crees.
Route::get('/gestion', function () { return view('admin.gestion.index'); })->name('gestion.index'); 

// 1. Ruta para MOSTRAR el formulario a los profesores
Route::get('/registro-clase', function () {
    return view('public.registro_clase');
})->name('public.clase.crear');