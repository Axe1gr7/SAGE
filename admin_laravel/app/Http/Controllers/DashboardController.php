<?php

namespace App\Http\Controllers;

use App\Services\ApiClient;

class DashboardController extends Controller
{
    public function index(ApiClient $apiClient)
    {
        // Puedes obtener datos para el dashboard si lo deseas
        return view('admin.dashboard');
    }
}