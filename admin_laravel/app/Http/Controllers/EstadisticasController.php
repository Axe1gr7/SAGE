<?php

namespace App\Http\Controllers;

use App\Services\ApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EstadisticasController extends Controller
{
    protected $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * Obtener los espacios más reservados (para el gráfico de barras).
     */
    public function espaciosMasReservados()
    {
        // Llamar a FastAPI (endpoint que debe devolver [{"nombre": "...", "total": 123}, ...])
        $data = $this->apiClient->get('/estadisticas/espacios-mas-reservados');
        
        // Manejo de errores
        if (isset($data['error'])) {
            Log::error('Error en espaciosMasReservados: ' . $data['message']);
            return response()->json([], 500);
        }

        return response()->json($data);
    }

    /**
     * Obtener los horarios más demandados (para el gráfico de pastel).
     */
    public function horariosDemanda()
    {
        // Llamar a FastAPI (endpoint que debe devolver [{"hora": "07:00-08:40", "total": 45}, ...])
        $data = $this->apiClient->get('/estadisticas/horarios-demanda');
        
        if (isset($data['error'])) {
            Log::error('Error en horariosDemanda: ' . $data['message']);
            return response()->json([], 500);
        }

        return response()->json($data);
    }
}